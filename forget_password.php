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
    $email = $_POST["email"];

    // بررسی وجود کاربر
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // تولید توکن
        $token = bin2hex(random_bytes(16));
        $created_at = date('Y-m-d H:i:s');

        // حذف توکن‌های قبلی کاربر (اختیاری)
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        // ذخیره توکن جدید
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $created_at]);

        // ساخت ایمیل
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;

            $mail->setFrom('no-reply@example.com', 'My App');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'بازیابی رمز عبور';
            $resetLink = 'http://localhost/ali/reset_password.php?token=' . $token;
            $mail->Body = 'برای بازنشانی رمز عبور، <a href="' . $resetLink . '">اینجا کلیک کنید</a>.';

            $mail->send();
            $_SESSION['message'] = '<p style="color:green;">لینک بازنشانی رمز عبور به ایمیل شما ارسال شد.</p>';
        } catch (Exception $e) {
            $_SESSION['message'] = '<p style="color:red;">خطا در ارسال ایمیل: ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
        }
    } else {
        $_SESSION['message'] = '<p style="color:red;">کاربری با این ایمیل یافت نشد.</p>';
    }

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