<?php
session_start();

// بررسی سطح دسترسی مدیر
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include('db.php');

// حذف کاربر اگر فرم ارسال شده باشد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $user_id = intval($_POST['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    header("Location: manage_users.php?deleted=1");
    exit;
}

// واکشی لیست کاربران
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت کاربران</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>مدیریت کاربران</h2>
    <p><a href="admin_panel.php">بازگشت به پنل مدیریت</a></p>

    <?php if (isset($_GET['deleted'])): ?>
        <p style="color: green;">کاربر با موفقیت حذف شد.</p>
    <?php endif; ?>
    

    <table style="border-spacing: 20px;"  border='1'>
    
        <tr>
            <th>شناسه</th>
            <th>نام</th>
            <th>ایمیل</th>
            <th>نقش</th>
            <th>عملیات</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <!-- فرم حذف -->
                    <form action="manage_users.php" method="POST" style="display:inline;">
                        <input type="hidden" name="delete" value="<?= $user['id'] ?>">
                        <button type="submit" onclick="return confirm('آیا از حذف این کاربر مطمئن هستید؟');">حذف</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    
</body>
</html>