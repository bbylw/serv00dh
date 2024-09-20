<?php
session_start();
require_once 'db_config.php';

// 检查管理员是否已登录
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("访问被拒绝。请先<a href='login.php'>登录管理员账号</a>。");
}

// 添加CSRF保护
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("无效的请求。");
    }
}

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 删除重复链接
    $sql = "DELETE l1 FROM links l1
            INNER JOIN links l2 
            WHERE l1.url = l2.url AND l1.id > l2.id";
    if ($conn->query($sql) === TRUE) {
        $affected_rows = $conn->affected_rows;
        $messages[] = "已删除 {$affected_rows} 个重复链接。";
    } else {
        $messages[] = "错误: " . $conn->error;
    }

    // 检查索引是否存在
    $result = $conn->query("SHOW INDEX FROM links WHERE Key_name = 'idx_url'");
    if ($result->num_rows == 0) {
        // 如果索引不存在，则创建
        $sql = "CREATE UNIQUE INDEX idx_url ON links (url)";
        if ($conn->query($sql) === TRUE) {
            $messages[] = "URL唯一索引已添加。";
        } else {
            $messages[] = "错误: " . $conn->error;
        }
    } else {
        $messages[] = "URL唯一索引已存在。";
    }

    $messages[] = "数据库清理完成。";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库清理 - WebNav Hub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
        }

        .message {
            background: #f4f4f4;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        form {
            margin-top: 20px;
        }

        input[type="submit"] {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>数据库清理</h1>
        <?php foreach ($messages as $message): ?>
            <div class="message <?php echo strpos($message, '错误') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="submit" value="执行数据库清理">
        </form>

        <p><a href="admin.php">返回管理页面</a></p>
    </div>
</body>

</html>