<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin_login.html");
    exit;
}

$servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $sql = "UPDATE records SET status='已通过' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "申请已通过!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    } elseif ($action == 'reject') {
        $sql = "UPDATE records SET status='已驳回' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "申请已驳回!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM records WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "申请已删除!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM records";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>回响 - 申请管理</title>
    <link href="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/tailwindcss/2.2.19/tailwind.min.css" type="text/css" rel="stylesheet" />
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <!-- 页面顶部标题 -->
    <div class="bg-indigo-600 text-white p-4 text-center">
        <h1 class="text-3xl font-semibold">回响 - 申请管理</h1>
    </div>

    <!-- 导航栏 -->
    <nav class="bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
                <a href="admin_dashboard.php" class="text-indigo-600 hover:text-indigo-800 px-3 py-2">申请管理</a>
                <a href="admin_reports.php" class="text-indigo-600 hover:text-indigo-800 px-3 py-2">举报管理</a>
            </div>
            <a href="https://blogecho.zeimg.top" class="text-red-600 hover:text-red-800 px-3 py-2">退出登录</a>
        </div>
    </nav>

    <!-- 主体内容 -->
    <div class="max-w-7xl mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
        <!-- 已审核和未审核分开显示 -->
        <div class="mb-4">
            <h2 class="text-2xl font-semibold text-indigo-600">已审核申请</h2>
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-4">
                <?php
                $sql_approved = "SELECT * FROM records WHERE status='已通过'";
                $result_approved = $conn->query($sql_approved);
                if ($result_approved->num_rows > 0) {
                    while ($row = $result_approved->fetch_assoc()) {
                        echo "<div class='bg-white shadow-lg rounded-lg p-4'>";
                        echo "<p class='font-bold text-indigo-600 text-lg'>" . htmlspecialchars($row['site_name'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-700 text-sm'>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>" . htmlspecialchars($row['site_description'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>RSS链接: <a href='" . htmlspecialchars($row['rssurl'], ENT_QUOTES, 'UTF-8') . "' class='text-blue-600 hover:text-blue-800'>" . htmlspecialchars($row['rssurl'], ENT_QUOTES, 'UTF-8') . "</a></p>";
                        echo "<div class='mt-2 flex justify-between items-center'>
                                <span class='text-green-600 text-sm'>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</span>
                                <a href='admin_dashboard.php?action=delete&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' onclick=\"return confirm('确定删除该申请吗？');\" class='text-red-600 hover:text-red-800 text-sm'>删除</a>
                              </div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='col-span-3 text-center text-gray-500'>暂无已审核的申请</p>";
                }
                ?>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-yellow-600">未审核申请</h2>
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mt-4">
                <?php
                $sql_pending = "SELECT * FROM records WHERE status='待审核'";
                $result_pending = $conn->query($sql_pending);
                if ($result_pending->num_rows > 0) {
                    while ($row = $result_pending->fetch_assoc()) {
                        echo "<div class='bg-white shadow-lg rounded-lg p-4'>";
                        echo "<p class='font-bold text-yellow-600 text-lg'>" . htmlspecialchars($row['site_name'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-700 text-sm'>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>" . htmlspecialchars($row['site_description'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p class='text-gray-500 text-xs'>RSS链接: <a href='" . htmlspecialchars($row['rssurl'], ENT_QUOTES, 'UTF-8') . "' class='text-blue-600 hover:text-blue-800'>" . htmlspecialchars($row['rssurl'], ENT_QUOTES, 'UTF-8') . "</a></p>";
                        echo "<div class='mt-2 flex justify-between items-center'>
                                <span class='text-yellow-600 text-sm'>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</span>
                                <div class='text-sm'>
                                    <a href='admin_dashboard.php?action=approve&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='text-green-600 hover:text-green-800'>同意</a>
                                    <a href='admin_dashboard.php?action=reject&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='text-yellow-600 hover:text-yellow-800'>驳回</a>
                                    <a href='admin_dashboard.php?action=delete&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' onclick=\"return confirm('确定删除该申请吗？');\" class='text-red-600 hover:text-red-800'>删除</a>
                                </div>
                              </div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='col-span-3 text-center text-gray-500'>暂无未审核的申请</p>";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
