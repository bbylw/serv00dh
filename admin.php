<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'db_connect.php';

// 只保留一次 get_base_url() 函数的定义
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . "://" . $host;
}

$base_url = get_base_url();

// 添加链接
if (isset($_POST['add'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $icon = $_POST['icon'];
    $stmt = $conn->prepare("INSERT INTO links (category_id, title, url, icon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $category_id, $title, $url, $icon);
    $stmt->execute();
}

// 删除链接
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM links WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// 修改链接
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $icon = $_POST['icon'];
    $stmt = $conn->prepare("UPDATE links SET category_id = ?, title = ?, url = ?, icon = ? WHERE id = ?");
    $stmt->bind_param("isssi", $category_id, $title, $url, $icon, $id);
    $stmt->execute();
}

// 获取所有分类
$categories = $conn->query("SELECT * FROM categories ORDER BY id")->fetch_all(MYSQLI_ASSOC);

// 获取选定分类的链接
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
if ($selected_category) {
    $stmt = $conn->prepare("SELECT links.*, categories.name as category_name FROM links JOIN categories ON links.category_id = categories.id WHERE categories.slug = ?");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $links = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $links = $conn->query("SELECT links.*, categories.name as category_name FROM links JOIN categories ON links.category_id = categories.id ORDER BY categories.id, links.id")->fetch_all(MYSQLI_ASSOC);
}

// 添加修改密码的处理逻辑
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 验证当前密码
    $stmt = $conn->prepare("SELECT password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['admin_username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_password, $_SESSION['admin_username']);
            $stmt->execute();
            $password_message = "密码已成功更新。";
        } else {
            $password_error = "新密码和确认密码不匹配。";
        }
    } else {
        $password_error = "当前密码不正确。";
    }
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebNav Hub 管理</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; }
        input[type="text"], select { width: 100%; padding: 8px; margin-bottom: 10px; }
        input[type="submit"] { background: #333; color: #fff; padding: 10px 15px; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #555; }
        .category-filter { margin-bottom: 20px; }
        .admin-actions { margin-top: 20px; }
        .admin-actions a { 
            display: inline-block; 
            margin-right: 10px; 
            padding: 10px 15px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .admin-actions a:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>WebNav Hub 管理</h1>
        
        <!-- 添加管理操作区域 -->
        <div class="admin-actions">
            <a href="clean_database.php">清理数据库</a>
            <a href="logout.php">退出登录</a>
        </div>

        <h2>添加新链接</h2>
        <form method="post">
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="title" placeholder="标题" required>
            <input type="text" name="url" placeholder="URL" required>
            <input type="text" name="icon" placeholder="图标类名" required>
            <input type="submit" name="add" value="添加链接">
        </form>

        <h2>现有链接</h2>
        <div class="category-filter">
            <form method="get">
                <select name="category" onchange="this.form.submit()">
                    <option value="">所有分类</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['slug'] ?>" <?= $selected_category === $category['slug'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <table>
            <tr>
                <th>分类</th>
                <th>标题</th>
                <th>URL</th>
                <th>图标</th>
                <th>操作</th>
            </tr>
            <?php foreach ($links as $link): ?>
                <tr>
                    <td><?= $link['category_name'] ?></td>
                    <td><?= $link['title'] ?></td>
                    <td><a href="<?= $base_url ?>/redirect.php?url=<?= urlencode($link['url']) ?>" target="_blank"><?= htmlspecialchars($link['url']) ?></a></td>
                    <td><?= $link['icon'] ?></td>
                    <td>
                        <a href="?delete=<?= $link['id'] ?>&category=<?= $selected_category ?>" onclick="return confirm('确定要删除吗？')">删除</a>
                        |
                        <a href="#" onclick="editLink(<?= htmlspecialchars(json_encode($link)) ?>)">编辑</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <p><a href="<?php echo $base_url; ?>/logout.php">登出</a></p>

    <script>
    function editLink(link) {
        var form = document.createElement('form');
        form.method = 'post';
        form.innerHTML = `
            <input type="hidden" name="id" value="${link.id}">
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"${link.category_id == <?= $category['id'] ?> ? ' selected' : ''}><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="title" value="${link.title}" required>
            <input type="text" name="url" value="${link.url}" required>
            <input type="text" name="icon" value="${link.icon}" required>
            <input type="submit" name="edit" value="保存修改">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</body>
</html>