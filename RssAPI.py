import mysql.connector
from mysql.connector import pooling
import requests
from flask import Flask, jsonify, request, g
from bs4 import BeautifulSoup
import concurrent.futures
import os
import logging
from dateutil import parser  # 日期时间解析库

app = Flask(__name__)
app.config['JSON_AS_ASCII'] = False  # 确保 JSON 输出支持非 ASCII 字符

logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

# 配置数据库连接池
db_config = {
    'host': os.getenv('DB_HOST', 'localhost'),
    'user': os.getenv('DB_USER', '数据库用户名'),
    'password': os.getenv('DB_PASSWORD', '数据库密码'),
    'database': os.getenv('DB_NAME', '数据库名'),
    'charset': 'utf8mb4',  # 确保数据库连接使用 UTF-8
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
        soup = BeautifulSoup(feed_xml, 'lxml-xml')  # 解析 XML
        items = []

        channel = soup.find('channel')  # 检查 RSS 格式
        if channel:
            for item in channel.find_all('item'):
                data = {
                    'title': item.title.text if item.title else '',
                    'link': item.link.text if item.link else '',
                    'description': item.description.text if item.description else '',
                    'pubDate': item.pubDate.text if item.pubDate else ''
                }
                items.append(data)

        elif soup.find('feed'):  # 检查 Atom 格式
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

        # 设置编码
        if 'charset' in response.headers.get('Content-Type', '').lower():
            response.encoding = requests.utils.get_encoding_from_headers(response.headers)
        else:
            response.encoding = response.apparent_encoding

        logger.debug(f"Fetching feed from {rss_url}, encoding detected: {response.encoding}")

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
    return [item for sublist in results if sublist for item in sublist]  # 展平嵌套列表

def sort_rss_data_by_date(flat_data):
    def get_date(item):
        # 提取时间字段并解析为日期对象
        date_str = item.get('pubDate') or item.get('updated')  # 优先选择存在的字段
        try:
            return parser.parse(date_str) if date_str else None
        except Exception as e:
            logger.warning(f"Failed to parse date: {date_str}, error: {e}")
            return None

    # 按时间降序排序
    return sorted(flat_data, key=lambda x: get_date(x) or parser.parse('1970-01-01'), reverse=True)

@app.route('/api', methods=['GET'])
def get_rss_data():
    logger.debug("Fetching feed data...")

    rss_urls = get_rss_urls()
    if not rss_urls:
        logger.warning("No RSS URLs found.")
        return jsonify({'total_items': 0, 'data': []})

    rss_data = fetch_all_rss_data(rss_urls)
    logger.debug(f"Fetched and processed {len(rss_data)} feed entries.")

    # 按时间完全排序
    sorted_data = sort_rss_data_by_date(rss_data)

    return jsonify({'total_items': len(sorted_data), 'data': sorted_data})

def create_app():
    return app

if __name__ == '__main__':
    if connection_pool is None:
        logger.critical("Database connection pool not initialized. Exiting.")
        exit(1)
    app.run(debug=True)
