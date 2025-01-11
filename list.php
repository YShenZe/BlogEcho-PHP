<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回响 - 查询申请</title>

    <!-- 引入 TailwindCSS 2.2.19 CDN -->
    <link href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- 顶栏 -->
    <header class="bg-blue-600 text-white p-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <i class="fa fa-bullhorn text-xl mr-2"></i>
                <h1 class="text-2xl font-semibold">回响</h1>
            </div>

            <button class="lg:hidden text-white" id="menu-toggle">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- 侧边导航栏（从左侧展开） -->
    <div id="sidebar" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 transform -translate-x-full lg:hidden transition-transform duration-300">
        <div class="w-64 h-full bg-blue-600 text-white p-6">
            <div class="flex justify-end">
                <button id="close-menu" class="text-white">
                    <i class="fa fa-times text-2xl"></i>
                </button>
            </div>

            <nav class="mt-8 space-y-6">
                <a href="https://blogecho.zeimg.top/record_form.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-edit"></i>
                    <span>申请加入我们</span>
                </a>
                <a href="https://blogecho.zeimg.top/list.php" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-search"></i>
                    <span>查询回响号</span>
                </a>
                <a href="https://blogecho.zeimg.top/announcement.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-bullhorn"></i>
                    <span>站点公告</span>
                </a>
                <a href="https://blogecho.zeimg.top/admin_login.html" class="flex items-center space-x-3 hover:bg-yellow-500 px-4 py-2 rounded-md">
                    <i class="fa fa-shield"></i>
                    <span>管理员登录</span>
                </a>
                <a href="https://blogecho.zeimg.top/about.html" class="flex items-center space-x-3 hover:bg-green-500 px-4 py-2 rounded-md">
                    <i class="fa fa-info-circle"></i>
                    <span>关于本站点</span>
                </a>
            </nav>
        </div>
    </div>

    <div class="flex lg:flex-row flex-col">
        <!-- 侧边栏（桌面端显示） -->
        <div class="hidden lg:block w-64 bg-blue-600 text-white p-6">
            <nav class="mt-8 space-y-6">
                <a href="https://blogecho.zeimg.top/record_form.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-edit"></i>
                    <span>申请加入我们</span>
                </a>
                <a href="https://blogecho.zeimg.top/list.php" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-search"></i>
                    <span>查询回响号</span>
                </a>
                <a href="https://blogecho.zeimg.top/announcement.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-bullhorn"></i>
                    <span>站点公告</span>
                </a>
                <a href="https://blogecho.zeimg.top/admin_login.html" class="flex items-center space-x-3 hover:bg-yellow-500 px-4 py-2 rounded-md">
                    <i class="fa fa-shield"></i>
                    <span>管理员登录</span>
                </a>
                <a href="https://blogecho.zeimg.top/about.html" class="flex items-center space-x-3 hover:bg-green-500 px-4 py-2 rounded-md">
                    <i class="fa fa-info-circle"></i>
                    <span>关于本站点</span>
                </a>
            </nav>
        </div>

        <!-- 主体内容 -->
        <div class="flex-1 max-w-full lg:max-w-4xl mx-auto px-4 py-6">
            <h1 class="text-3xl font-semibold text-center mb-6 text-blue-600">回响 - 查询申请信息</h1>

            <form method="get" action="query_record.php" class="space-y-6 bg-white p-6 rounded-lg shadow-lg">
                <div>
                    <label for="id" class="block text-lg font-medium text-gray-700">网站ID:</label>
                    <input type="text" id="id" name="id" 
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex justify-between space-x-4">
                    <input type="submit" value="查询"
                        class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300">
                    <a href="https://blogecho.zeimg.top" class="w-full text-center py-2 border border-transparent text-blue-600 hover:underline rounded-md transition duration-300">
                        返回首页
                    </a>
                </div>
            </form>

            <h2 class="text-2xl font-semibold mt-8 mb-4 text-blue-600">已加入的申请</h2>

            <?php
            // 设置每页显示的记录数
            $records_per_page = 10;

            // 获取当前页码，默认为第一页
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start_from = ($page - 1) * $records_per_page;

            // 数据库连接
            $servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

            // 创建连接
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }

            // 查询已加入的记录，排除搜索条件
            $sql = "SELECT * FROM records WHERE status = '已通过' LIMIT $start_from, $records_per_page";
            $result = $conn->query($sql);

            // 显示查询结果
            if ($result->num_rows > 0) {
                echo "<div class='overflow-x-auto max-w-full'>
                        <table class='min-w-full table-auto border-collapse text-sm mt-6'>
                            <thead class='bg-blue-100'>
                                <tr>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>ID</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>网站名</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>邮箱</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>网址</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>网站描述</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>状态</th>
                                    <th class='px-6 py-3 text-left text-sm font-medium text-gray-700'>创建时间</th>
                                </tr>
                            </thead>
                            <tbody class='bg-white'>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='border-b'>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['site_name'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['site_description'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') . "</td>
                        </tr>";
                }

                echo "</tbody></table></div>";
            } else {
                echo "<p class='text-center text-gray-500 mt-6'>暂无已通过的申请。</p>";
            }

            // 获取总记录数
            $total_records_sql = "SELECT COUNT(*) FROM records WHERE status = '已通过'";
            $total_records_result = $conn->query($total_records_sql);
            $total_records_row = $total_records_result->fetch_row();
            $total_records = $total_records_row[0];

            // 计算总页数
            $total_pages = ceil($total_records / $records_per_page);

            // 分页导航
            echo "<div class='mt-6 text-center'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<span class='px-4 py-2 bg-blue-500 text-white rounded-md'>$i</span> ";
                } else {
                    echo "<a href='?page=$i' class='px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300'>$i</a> ";
                }
            }
            echo "</div>";

            $conn->close();
            ?>

            <div class="text-center text-sm text-gray-500 mt-6">
                <p>&copy; 2024 BlogEcho。</p>
            </div>
        </div>
    </div>

    <script>
        // 控制侧边菜单的展开和收起
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const closeMenu = document.getElementById('close-menu');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        });

        closeMenu.addEventListener('click', () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
        });
    </script>

</body>

</html>
