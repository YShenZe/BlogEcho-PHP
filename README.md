# BlogEcho-PHP
基于麦ICP备案系统开发的 RSS 综合聚合平台。

## 部署后端 RssAPI

1. 将 `RssAPI.py` 文件移动到服务器上。
2. 新建 Python 虚拟环境（可选，但推荐）。
3. 安装依赖：
   ```shell
   pip install mysql-connector-python beautifulsoup4 requests Flask lxml
   ```
4. 运行 API 服务：
   ```shell
   python RssAPI.py
   ```
5. API 运行后，可通过 `http://127.0.0.1:5000/api` 访问。

## 部署站点项目

1. **创建数据库**  
   在 MySQL 执行以下 SQL 语句：
   ```sql
   CREATE DATABASE record_system;

   USE record_system;

   CREATE TABLE records (
       id INT AUTO_INCREMENT PRIMARY KEY,
       site_name VARCHAR(255) NOT NULL,
       email VARCHAR(255) NOT NULL,
       url VARCHAR(255) NOT NULL,
       rssurl VARCHAR(255) NOT NULL,
       site_description TEXT,
       status VARCHAR(50) DEFAULT 'pending',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE TABLE admin_users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL,
       password VARCHAR(255) NOT NULL
   );

   INSERT INTO admin_users (username, password) VALUES ('admin', 'admin123');

   CREATE TABLE reports (
       id INT AUTO_INCREMENT PRIMARY KEY,
       record_id INT NOT NULL,
       report_reason TEXT NOT NULL,
       status ENUM('pending', 'reviewed') DEFAULT 'pending',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (record_id) REFERENCES records(id)
   );
   ```
   **注意：** `admin` 和 `admin123` 为默认的管理员账号和密码，可在部署后修改。

2. **部署 PHP 站点**
   - 将项目文件移动到网站根目录。
   - 配置 `db.php`，填写数据库连接信息。
   - 访问站点，即可使用。

## 许可协议

本项目遵循上游，使用 **GNU GENERAL PUBLIC LICENSE (GPL)** 进行开源。