<?php
session_start();
session_destroy();

function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . "://" . $host;
}

$base_url = get_base_url();

header('Location: ' . $base_url . '/login.php');
exit;
?>