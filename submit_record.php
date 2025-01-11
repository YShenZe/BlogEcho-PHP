<?php
$servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 从表单获取数据并转义
    $site_name = $conn->real_escape_string($_POST['site_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $url = $conn->real_escape_string($_POST['url']);
    $rssurl = $conn->real_escape_string($_POST['rssurl']);
    $site_description = $conn->real_escape_string($_POST['site_description']);
    $status = '待审核';  // 初始状态

    // 使用预处理语句
    $stmt = $conn->prepare("INSERT INTO records (site_name, email, url, rssurl, site_description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $site_name, $email, $url, $rssurl, $site_description, $status);

    // 执行语句并检查是否成功
    if ($stmt->execute()) {
        echo "申请提交成功!";
    } else {
        echo "提交失败: " . $stmt->error;
    }

    // 关闭语句
    $stmt->close();
}

// 关闭连接
$conn->close();
?>
