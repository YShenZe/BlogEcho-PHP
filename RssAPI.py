import mysql.connector
from mysql.connector import pooling
import requests
from flask import Flask, jsonify, request, g
from bs4 import BeautifulSoup
import concurrent.futures
import os
import logging

app = Flask(__name__)
app.config['JSON_AS_ASCII'] = True

# 设置日志级别
logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

# 配置数据库连接池
db_config = {
    'host': os.getenv('DB_HOST', 'localhost'),
    'user': os.getenv('DB_USER', '数据库用户名'),
    'password': os.getenv('DB_PASSWORD', '数据库密码'),
    'database': os.getenv('DB_NAME', '数据库名'),
    'auth_plugin': 'mysql_native_password',
}
try:
    connection_pool = pooling.MySQLConnectionPool(pool_name="mypool", pool_size=10, **db_config)
    logger.info("Database connection pool initialized successfully.")
except mysql.connector.Error as e:
    logger.error(f"Failed to initialize connection pool: {e}")
    connection_pool = None

@app.before_request
def before_request():
    logger.debug(f"Request received: {request.method} {request.path}")
    g.db_connection = None
    if connection_pool:
        try:
            g.db_connection = connection_pool.get_connection()
            logger.debug("Database connection acquired from pool.")
        except mysql.connector.Error as e:
            logger.error(f"Failed to acquire database connection: {e}")

@app.teardown_request
def teardown_request(exception=None):
    if hasattr(g, 'db_connection') and g.db_connection:
        g.db_connection.close()
        logger.debug("Database connection returned to pool.")

def get_rss_urls():
    if not g.db_connection:
        logger.error("No database connection available.")
        return []

    try:
        cursor = g.db_connection.cursor()
        cursor.execute("SELECT rssurl FROM records")
        rss_urls = [row[0] for row in cursor.fetchall()]
        cursor.close()
        logger.debug(f"Fetched {len(rss_urls)} RSS URLs from the database.")
        return rss_urls
    except mysql.connector.Error as e:
        logger.error(f"Database query failed: {e}")
        return []

def parse_rss_or_atom(feed_xml):
    try:
        soup = BeautifulSoup(feed_xml, 'lxml-xml')
        items = []

        channel = soup.find('channel')
        if channel:
            for item in channel.find_all('item'):
                data = {
                    'title': item.title.text if item.title else '',
                    'link': item.link.text if item.link else '',
                    'description': item.description.text if item.description else '',
                    'pubDate': item.pubDate.text if item.pubDate else ''
                }
                items.append(data)

        elif soup.find('feed'):
            for entry in soup.find_all('entry'):
                data = {
                    'title': entry.title.text if entry.title else '',
                    'link': entry.link['href'] if entry.link and entry.link.has_attr('href') else '',
                    'summary': entry.summary.text if entry.summary else '',
                    'updated': entry.updated.text if entry.updated else ''
                }
                items.append(data)

        if not items:
            logger.warning("Feed XML does not match RSS or Atom formats.")
        return items

    except Exception as e:
        logger.error(f"Error parsing feed XML: {e}")
        return []

def fetch_rss_data(rss_url):
    try:
        response = requests.get(rss_url, timeout=10)
        response.raise_for_status()

        if 'xml' in response.headers.get('Content-Type', ''):
            logger.debug(f"Feed data fetched from: {rss_url}")
            return parse_rss_or_atom(response.text)

        logger.warning(f"Invalid content type for URL {rss_url}: {response.headers.get('Content-Type')}")
        return None
    except requests.exceptions.RequestException as e:
        logger.error(f"Error fetching {rss_url}: {e}")
        return None

def fetch_all_rss_data(rss_urls):
    with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
        results = list(executor.map(fetch_rss_data, rss_urls))
    return [result for result in results if result]

@app.route('/api', methods=['GET'])
def get_rss_data():
    logger.debug("Fetching feed data...")

    rss_urls = get_rss_urls()
    if not rss_urls:
        logger.warning("No RSS URLs found.")
        return jsonify({'total_items': 0, 'data': []})

    rss_data = fetch_all_rss_data(rss_urls)
    logger.debug(f"Fetched and processed {len(rss_data)} feed entries.")

    flat_data = [item for sublist in rss_data for item in sublist]

    return jsonify({'total_items': len(flat_data), 'data': flat_data})

def create_app():
    return app

if __name__ == '__main__':
    if connection_pool is None:
        logger.critical("Database connection pool not initialized. Exiting.")
        exit(1)
    app.run(debug=True)