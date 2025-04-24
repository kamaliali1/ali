<?php
session_start();
// اتصال به دیتابیس
include('db.php');

function validateEmail($email) {
    // بررسی فرمت اولیه ایمیل
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;  // ایمیل معتبر نیست
    }

    // بررسی اینکه آیا بعد از آخرین نقطه (.) چیزی وجود دارد
    $domain = substr(strrchr($email, "@"), 1); // استخراج دامنه ایمیل
    if (strpos($domain, '.') === false) {
        return false;  // دامنه باید حداقل یک نقطه داشته باشد
    }

    // بررسی تعداد نقاط در دامنه (حداقل یک نقطه)
    $domainParts = explode('.', $domain);
    if (count($domainParts) < 2) {
        return false;  // دامنه معتبر نیست
    }

    // بررسی اینکه آیا بخش دامنه پس از آخرین نقطه فقط حروف باشد
    $lastPart = end($domainParts);
    if (!preg_match('/^[a-zA-Z]+$/', $lastPart)) {
        return false;  // پسوند دامنه باید حروف باشد
    }

    return true;  // ایمیل معتبر است
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت اطلاعات از فرم
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'user';  // نقش پیش‌فرض کاربر
    
    
        // اعتبارسنجی نام (فقط حروف فارسی مجاز هستند)
        if (!preg_match("/^[\x{0600}-\x{06FF}\s]+$/u", $name)) {
           echo "نام باید فقط شامل حروف فارسی باشد.";
           
        }
        else
    
        // ادامه کد ویرایش پروفایل...
    
    // بررسی ایمیل
    if (!validateEmail($email)) {
        echo "ایمیل وارد شده صحیح نیست.";
    } else {
        // بررسی یکتایی ایمیل در دیتابیس
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "این ایمیل قبلاً ثبت شده است.";
        } else {
            // هش کردن پسورد
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ذخیره‌سازی اطلاعات در دیتابیس
            $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            // نمایش پیام موفقیت
            $_SESSION['message']="ثبت نام شما با موفقیت انجام شد.";
            // هدایت به صفحه لاگین
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فرم ثبت‌نام</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>فرم ثبت‌نام</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="نام کامل" required>
            <input type="email" name="email" placeholder="ایمیل" required>
            <input type="password" name="password" placeholder="رمز عبور" required>
            <button type="submit">ثبت نام</button>
        </form>
        <p>قبلاً ثبت‌نام کرده‌اید؟ <a href="login.php">وارد شوید</a></p>
    </div>
</body>
</html>