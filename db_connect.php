<?php
// بيانات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_data";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// الحصول على بيانات النموذج مع تعقيم المدخلات لتجنب XSS
$name = htmlspecialchars(trim($_POST['name']));
$email = htmlspecialchars(trim($_POST['email']));
$phone = htmlspecialchars(trim($_POST['phone']));
$country = htmlspecialchars(trim($_POST['country']));
$service = htmlspecialchars(trim($_POST['service']));
$message = htmlspecialchars(trim($_POST['message']));

// التحقق من المدخلات
if (empty($name) || empty($email) || empty($phone) || empty($country) || empty($service) || empty($message)) {
    $_SESSION['message'] = "All fields are required.";
    $_SESSION['message_type'] = 'error';
    header("Location: /softechna/index.html");
    exit();
}

// التحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format.";
    $_SESSION['message_type'] = 'error';
    header("Location: /softechna/index.html");
    exit();
}

// استخدام Prepared Statements لتجنب SQL Injection
$stmt = $conn->prepare("INSERT INTO form_submissions (name, email, phone, country, service, message, reg_date)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())");

// ربط المتغيرات بـ Prepared Statement
$stmt->bind_param("ssssss", $name, $email, $phone, $country, $service, $message);

// تنفيذ الاستعلام
if ($stmt->execute()) {
    $_SESSION['message'] = "Your data has been submitted successfully!";
    $_SESSION['message_type'] = 'success';
    header("Location: /softechna/index.html"); // التوجيه إلى الصفحة الرئيسية
    exit();
} else {
    $_SESSION['message'] = "Error: " . $stmt->error;
    $_SESSION['message_type'] = 'error';
    header("Location: /softechna/index.html"); // التوجيه في حال حدوث خطأ
    exit();
}

// إغلاق الاتصال
$stmt->close();
$conn->close();
?>
