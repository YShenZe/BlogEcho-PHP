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

if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['record_id'])) {
    $id = intval($_GET['id']);
    $record_id = intval($_GET['record_id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $sql_report = "UPDATE reports SET status='已通过' WHERE id='$id'";
        $sql_record = "UPDATE records SET status='确认举报' WHERE id='$record_id'";

        if ($conn->query($sql_report) === TRUE && $conn->query($sql_record) === TRUE) {
            echo "举报信息已通过!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    } elseif ($action == 'reject') {
        $sql_report = "UPDATE reports SET status='已驳回' WHERE id='$id'";
        $sql_record = "UPDATE records SET status='正常' WHERE id='$record_id'";

        if ($conn->query($sql_report) === TRUE && $conn->query($sql_record) === TRUE) {
            echo "举报信息已驳回!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM reports WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "举报信息已删除!";
        } else {
            echo "操作失败: " . $conn->error;
        }
    }
}

$sql = "SELECT reports.*, records.site_name, records.url FROM reports JOIN records ON reports.record_id = records.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>回响 - 举报管理</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <h1 class="text-3xl font-bold text-center mb-8">回响 - 举报管理</h1>

        <nav class="mb-6">
            <a href="admin_dashboard.php" class="text-blue-600 hover:text-blue-800">申请管理</a> |
            <a href="admin_reports.php" class="text-blue-600 hover:text-blue-800">举报管理</a> |
            <a href="https://blogecho.zeimg.top" class="text-blue-600 hover:text-blue-800">退出登录</a>
        </nav>

        <!-- Approved Reports -->
        <h2 class="text-xl font-semibold mb-4">已通过举报</h2>
        <div class="overflow-x-auto shadow-lg rounded-lg bg-white mb-8">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">举报ID</th>
                        <th class="px-4 py-2 text-left">网站名</th>
                        <th class="px-4 py-2 text-left">网址</th>
                        <th class="px-4 py-2 text-left">举报原因</th>
                        <th class="px-4 py-2 text-left">状态</th>
                        <th class="px-4 py-2 text-left">创建时间</th>
                        <th class="px-4 py-2 text-left">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Filter reports by status '已通过'
                    $sql_approved = "SELECT reports.*, records.site_name, records.url FROM reports JOIN records ON reports.record_id = records.id WHERE reports.status = '已通过'";
                    $result_approved = $conn->query($sql_approved);
                    if ($result_approved->num_rows > 0) {
                        while ($row = $result_approved->fetch_assoc()) {
                            echo "<tr class='border-t'>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['site_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['report_reason'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>
                                    <a href='admin_reports.php?action=reject&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "&record_id=" . htmlspecialchars($row['record_id'], ENT_QUOTES, 'UTF-8') . "' class='text-yellow-600 hover:text-yellow-800'>驳回</a> |
                                    <a href='admin_reports.php?action=delete&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='text-red-600 hover:text-red-800'>删除</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='px-4 py-2 text-center'>暂无已通过举报信息</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Rejected Reports -->
        <h2 class="text-xl font-semibold mb-4">已驳回举报</h2>
        <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">举报ID</th>
                        <th class="px-4 py-2 text-left">网站名</th>
                        <th class="px-4 py-2 text-left">网址</th>
                        <th class="px-4 py-2 text-left">举报原因</th>
                        <th class="px-4 py-2 text-left">状态</th>
                        <th class="px-4 py-2 text-left">创建时间</th>
                        <th class="px-4 py-2 text-left">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Filter reports by status '已驳回'
                    $sql_rejected = "SELECT reports.*, records.site_name, records.url FROM reports JOIN records ON reports.record_id = records.id WHERE reports.status = '已驳回'";
                    $result_rejected = $conn->query($sql_rejected);
                    if ($result_rejected->num_rows > 0) {
                        while ($row = $result_rejected->fetch_assoc()) {
                            echo "<tr class='border-t'>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['site_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['report_reason'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>" . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td class='px-4 py-2'>
                                    <a href='admin_reports.php?action=approve&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "&record_id=" . htmlspecialchars($row['record_id'], ENT_QUOTES, 'UTF-8') . "' class='text-green-600 hover:text-green-800'>通过</a> |
                                    <a href='admin_reports.php?action=delete&id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='text-red-600 hover:text-red-800'>删除</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='px-4 py-2 text-center'>暂无已驳回举报信息</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>

<?php
$conn->close();
?>
