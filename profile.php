<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>پروفایل</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>پروفایل کاربر</h2>

<?php if (!empty($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

<p>نام: <?= htmlspecialchars($user['name']) ?></p>


<p>
    <a href="edit_profile.php">ویرایش پروفایل</a> 
<br>

<a href="change_password.php">تغییر رمز عبور</a></p>

    <a href="index.php">بازگشت به صفحه اصلی</a>
</p>
</body>
</html>
