<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    // 这里可以添加一些安全检查,例如检查URL是否在允许的域名列表中
    header("Location: " . $url);
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>