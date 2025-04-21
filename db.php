<?php
// تنظیمات دیتابیس
$host = 'localhost'; // نام میزبان (localhost برای محیط لوکال)
$dbname = 'user_management'; // نام دیتابیس
$username = 'root'; // نام کاربری دیتابیس
$password = ''; // رمز عبور دیتابیس

try {
    // ایجاد اتصال به دیتابیس با استفاده از PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // تنظیم خطای PDO برای نشان دادن استثناها
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // پیامی برای نمایش در صورت موفقیت اتصال
   
} catch (PDOException $e) {
    // در صورتی که اتصال به دیتابیس برقرار نشود، خطا را چاپ می‌کنیم
    echo "اتصال به دیتابیس برقرار نشد: " . $e->getMessage();
}
?>