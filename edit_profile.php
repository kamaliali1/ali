<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if (!preg_match("/^[\x{0600}-\x{06FF}\s]+$/u", $name)) {
        $error ="نام باید فقط شامل حروف فارسی باشد.";
        
     }
     else

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "ایمیل وارد شده معتبر نیست.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $user_id
        ]);
        
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['success'] = "ویرایش با موفقیت انجام شد.";
        header("Location: profile.php");
        exit;
    }
}

// گرفتن اطلاعات فعلی کاربر
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ویرایش پروفایل</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>ویرایش پروفایل</h2>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label>نام:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <label>ایمیل:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <button type="submit">ذخیره تغییرات</button>
    </form>

    <p><a href="profile.php">بازگشت به پروفایل</a></p>
</body>
</html>