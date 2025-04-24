<?php include('../includes/header.php'); ?>


    <h2>صفحه اصلی</h2>

    <?php if (isset($_SESSION['user'])): ?>
        <p>سلام، <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</p>
        

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <p><a href="admin_panel.php">ورود به پنل مدیریت</a></p>
        <?php endif; ?>

        <p><a href="profile.php">پروفایل من</a></p>
        <p><a href="logout.php">خروج</a></p>
    <?php else: ?>
        <p><a href="register.php">ثبت نام</a></p>
        <p><a href="login.php">ورود</a></p>
    <?php endif; ?>


<?php include('../includes/footer.php'); ?>