<?php
session_start();

// چک کردن اینکه کاربر لاگین کرده و نقش او مدیر است یا نه
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin' ) {
    header("Location: index.php");  // هدایت به صفحه اصلی در صورت عدم دسترسی
    exit();
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>پنل مدیریت</h1>
    <p>به پنل مدیریت خوش آمدید!</p>

    <ul>
        <li><a href="manage_users.php">مدیریت کاربران</a></li>
        <li><a href="site_settings.php">تنظیمات سایت</a></li>
    </ul>

    <a href="logout.php">خروج</a>

</body>
</html>