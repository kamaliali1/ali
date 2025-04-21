<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include('db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user_id = $_SESSION['user']['id'];

    // دریافت رمز عبور فعلی از دیتابیس
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user || !password_verify($current_password, $user['password'])) {
        $message = "رمز عبور فعلی اشتباه است.";
    } elseif ($new_password !== $confirm_password) {
        $message = "رمزهای جدید با هم مطابقت ندارند.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $message = "رمز عبور با موفقیت تغییر کرد.";
        $_SESSION['success']=$message;
        header('location:profile.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>تغییر رمز عبور</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>تغییر رمز عبور</h2>
    <p><?= $message ?></p>
    <form method="post">
        <input type="password" name="current_password" placeholder="رمز فعلی" required><br>
        <input type="password" name="new_password" placeholder="رمز جدید" required><br>
        <input type="password" name="confirm_password" placeholder="تکرار رمز جدید" required><br>
        <button type="submit">تغییر رمز</button>
    </form>
    <a href="profile.php">بازگشت به پروفایل</a>
</body>
</html>