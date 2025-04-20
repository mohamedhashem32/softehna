<?php
session_start(); // بدء الجلسة

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

// استعلام لجلب البيانات من قاعدة البيانات
$sql = "SELECT * FROM form_submissions ORDER BY reg_date DESC"; // جلب البيانات حسب تاريخ التسجيل
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض بيانات النماذج</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            width: 90%;
            margin: 20px auto;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #4CAF50;
            color: white;
        }

        .message.error {
            background-color: #f44336;
            color: white;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>عرض بيانات النماذج</h1>

<?php
// التحقق من وجود نتائج في الجلسة لعرض الرسائل
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    // تحديد تنسيق الرسالة بناءً على نوع الرسالة
    echo "<div class='message $message_type'>$message</div>";

    // مسح الرسالة بعد عرضها
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<?php
// التحقق إذا كانت هناك نتائج
if ($result->num_rows > 0) {
    // عرض البيانات في جدول
    echo "<table>";
    echo "<thead><tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Country</th>
            <th>Service</th>
            <th>Message</th>
            <th>Submission Date</th>
        </tr></thead>";
    echo "<tbody>";
    // عرض كل سجل
    while ($row = $result->fetch_assoc()) {
        // استخدام htmlspecialchars لتجنب XSS
        echo "<tr>
                <td>" . htmlspecialchars($row["id"]) . "</td>
                <td>" . htmlspecialchars($row["name"]) . "</td>
                <td>" . htmlspecialchars($row["email"]) . "</td>
                <td>" . htmlspecialchars($row["phone"]) . "</td>
                <td>" . htmlspecialchars($row["country"]) . "</td>
                <td>" . htmlspecialchars($row["service"]) . "</td>
                <td>" . htmlspecialchars($row["message"]) . "</td>
                <td>" . htmlspecialchars($row["reg_date"]) . "</td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p style='text-align: center;'>لا توجد بيانات لعرضها.</p>";
}

$conn->close();
?>

</body>
</html>
