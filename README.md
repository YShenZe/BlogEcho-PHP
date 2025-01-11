# BlogEcho-PHP
基于麦ICP备案系统开发的Rss综合聚集平台

## 部署后端RssAPI

将项目中的RssAPI.py文件移动到服务器上，然后新建Python项目，运行该文件同时开放5000端口。

安装依赖：
```shell
pip install mysql-connector-python beautifulsoup4 requests Flask lxml
```
运行项目：
```shell
python RssAPI.py
```
内网API地址为http://127.0.0.1:5000/api

## 部署站点项目
新建PHP站点，在数据库执行
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
注意，上面的“admin”和“admin123”是后期登录管理后台的用户名和密码。

然后把所有文件移动到网站目录，给所有PHP文件上写好数据库信息即可访问了。