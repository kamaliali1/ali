<?php
// مسیرها
$headerPath = 'includes/header.php';
$footerPath = 'includes/footer.php';
$indexPath = 'public/index.php';

// محتوای header
$headerContent = <<<HTML
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>صفحه من</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <h1>خوش آمدید!</h1>
</header>
HTML;

// محتوای footer
$footerContent = <<<HTML
<footer>
    <p>تمام حقوق محفوظ است &copy; 2025</p>
</footer>
</body>
</html>
HTML;

// نوشتن فایل‌ها
file_put_contents($headerPath, $headerContent);
file_put_contents($footerPath, $footerContent);

// ویرایش index.php
if (file_exists($indexPath)) {
    $body = file_get_contents($indexPath);

    // حذف همه چی قبل از <body> و بعد از </body>
    $body = preg_replace('/.*<body>/s', '', $body);
    $body = preg_replace('/<\/body>.*/s', '', $body);

    // اضافه کردن include
    $newIndex = <<<PHP
<?php include('../includes/header.php'); ?>

$body

<?php include('../includes/footer.php'); ?>
PHP;

    file_put_contents($indexPath, $newIndex);
    echo "header.php و footer.php ساخته شد و index.php ویرایش شد.";
} else {
    echo "index.php پیدا نشد.";
}
?>