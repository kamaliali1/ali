<?php
session_start();
require 'db.php'; // فایل اتصال به دیتابیس شما

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

    // بررسی توکن در دیتابیس
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // به‌روزرسانی رمز عبور
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $user['email']]);

        // حذف توکن از جدول
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute ([$token]);

        $_SESSION['message'] = '<p style="color: green;">رمز عبور با موفقیت تغییر یافت.</p>';
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['message'] = '<p style="color: red;">توکن نامعتبر یا منقضی شده است.</p>';
        header("Location: reset_password.php?token=" . htmlspecialchars($token));
        exit;
    }
}

// حالت GET: نمایش فرم
if (!isset($_GET['token'])) {
    echo "توکن معتبر نیست.";
    exit;
}

$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>بازنشانی رمز عبور</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>بازنشانی رمز عبور</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>
    <form method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">رمز عبور جدید:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">تایید</button>
    </form>
    <a href="login.php">بازگشت به صفحه ورود</a>
</body>
</html>