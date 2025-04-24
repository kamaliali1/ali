<?php
// شروع جلسه برای پیام‌ها
session_start();

// بارگذاری کلاس‌های PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// اتصال به دیتابیس
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // دریافت ایمیل وارد شده توسط کاربر
    $email = $_POST["email"];
    
    // بررسی وجود کاربر در دیتابیس
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // اگر کاربر وجود داشت، توکن جدید تولید می‌شود
        $token = bin2hex(random_bytes(16));  // ایجاد توکن تصادفی
        $created_at = date('Y-m-d H:i:s');  // زمان ایجاد توکن
        
        // حذف توکن‌های قبلی کاربر (اختیاری)
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        // ذخیره توکن جدید در جدول password_resets
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $created_at]);

        // ساخت ایمیل بازیابی رمز عبور
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->setFrom('no-reply@example.com', 'My App');
            $mail->addAddress($email);  // ایمیل کاربر
            $mail->isHTML(true);
            $mail->Subject = 'بازیابی رمز عبور';

            // لینک بازنشانی رمز عبور با توکن
            $resetLink = 'http://localhost/ali/reset_password.php?token=' . $token;
            $mail->Body = 'برای بازنشانی رمز عبور، <a href="' . $resetLink . '">اینجا کلیک کنید</a>.';

            // ارسال ایمیل
            $mail->send();

            // پیام موفقیت در ارسال ایمیل
            $_SESSION['message'] = "لینک بازنشانی رمز عبور به ایمیل شما ارسال شد.";
        } catch (Exception $e) {
            // در صورت بروز خطا در ارسال ایمیل
            $_SESSION['message'] = "خطا در ارسال ایمیل: " . htmlspecialchars($mail->ErrorInfo);
        }
    } else {
        // در صورتی که ایمیل وارد شده مربوط به هیچ کاربری نباشد
        $_SESSION['message'] = "کاربری با این ایمیل یافت نشد.";
    }

    // هدایت به صفحه ورود پس از ارسال ایمیل
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>فراموشی رمز عبور</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>فراموشی رمز عبور</h2>
    <?php
    // نمایش پیام‌ها در صورت وجود
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>
    <form method="post">
        <label for="email">ایمیل:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">ارسال لینک بازنشانی</button>
    </form>
    <a href="login.php">بازگشت به ورود</a>
</body>
</html>