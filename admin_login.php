<!--本文件是 回响 的一部分。

回响 是自由软件：你可以再分发之和/或依照由自由软件基金会发布的 GNU 通用公共许可证修改之，无论是版本 3 许可证，还是（按你的决定）任何以后版都可以。

发布 回响 是希望它能有用，但是并无保障；甚至连可销售和符合某个特定的目的都不保证。请参看 GNU 通用公共许可证，了解详情。

你应该随程序获得一份 GNU 通用公共许可证的副本。如果没有，请看 <https://www.gnu.org/licenses/>。

本程序使用了附加条款。如要获取附加条款，请看 <https://blog.BlogEcho.com/2024/07/19/85.html>，或者查看随附的 ADDITIONAL-LICENSE.txt。-->

<?php
session_start();

$servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
    
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin_users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['loggedin'] = true;
    header("Location: admin_dashboard.php");
} else {
    echo "用户名或密码错误";
}

$conn->close();
?>
