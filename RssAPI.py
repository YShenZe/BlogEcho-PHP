import mysql.connector
import requests
from flask import Flask, jsonify, request, g
from bs4 import BeautifulSoup
import concurrent.futures
import os
import logging

app = Flask(__name__)

app.config['JSON_AS_ASCII'] = True

logging.basicConfig(level=logging.DEBUG)

@app.before_request
def before_request():
    app.logger.debug(f"Request received: {request.method} {request.path}")
    g.db_connection = None

@app.after_request
def after_request(response):
    app.logger.debug(f"Response: {response.status}")
    return response

@app.teardown_request
def teardown_request(exception=None):
    if hasattr(g, 'db_connection') and g.db_connection:
        g.db_connection.close()
        app.logger.debug("Database connection closed.")
        
def get_db_connection():
    try:
        connection = mysql.connector.connect(
            host=os.getenv('DB_HOST', 'localhost'),
            user=os.getenv('DB_USER', '数据库用户名'),
            password=os.getenv('DB_PASSWORD', '数据库密码'),
            database=os.getenv('DB_NAME', '数据库名'),
            use_pure=True,
            connection_timeout=10,
            auth_plugin='mysql_native_password'
        )
        app.logger.debug("Successfully connected to the database.")
        return connection
    except Exception as e:
        app.logger.error(f"Error connecting to database: {e}")
        return None

def get_rss_urls():
    connection = get_db_connection()
    if connection:
        cursor = connection.cursor()
        cursor.execute("SELECT rssurl FROM records")
        rss_urls = [row[0] for row in cursor.fetchall()]
        cursor.close()
        connection.close()
        app.logger.debug(f"Fetched {len(rss_urls)} RSS URLs from the database.")
        return rss_urls
    else:
        app.logger.error("No database connection available.")
        return []

def parse_rss_xml(rss_xml):
    try:
        soup = BeautifulSoup(rss_xml, 'lxml-xml')
        channel = soup.find('channel')

        items = []
        for item in channel.find_all('item'):
            data = {
                'title': item.title.text if item.title else '',
                'link': item.link.text if item.link else '',
                'description': item.description.text if item.description else '',
                'pubDate': item.pubDate.text if item.pubDate else ''
            }
            items.append(data)

        return items
    except Exception as e:
        app.logger.error(f"Error parsing RSS XML: {e}")
        return []

def fetch_rss_data(rss_url):
    try:
        response = requests.get(rss_url, timeout=10)
        response.raise_for_status()

        if 'xml' in response.headers['Content-Type']:
            app.logger.debug(f"RSS data fetched from: {rss_url}")
            return parse_rss_xml(response.text)

        return None
    except requests.exceptions.RequestException as e:
        app.logger.error(f"Error fetching {rss_url}: {e}")
        return None

def fetch_all_rss_data(rss_urls):
    with concurrent.futures.ThreadPoolExecutor(max_workers=10) as executor:
        return list(executor.map(fetch_rss_data, rss_urls))

@app.route('/api', methods=['GET'])
def get_rss_data():
    app.logger.debug(f"Fetching RSS data...")

    rss_urls = get_rss_urls()
    if not rss_urls:
        app.logger.warning("No RSS URLs found.")
    
    rss_data = fetch_all_rss_data(rss_urls)
    app.logger.debug(f"Fetched {len(rss_data)} RSS data entries.")

    rss_data = [data for data in rss_data if data]
    app.logger.debug(f"Filtered valid RSS data entries: {len(rss_data)}")

    return jsonify({
        'total_items': len(rss_data),
        'data': rss_data
    })

def create_app():
    return app

if __name__ == '__main__':
    app.run(debug=True)
