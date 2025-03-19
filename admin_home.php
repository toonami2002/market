<?php
session_start();
include('db_connect.php'); // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้งานเป็นแอดมิน
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $market_name = $_POST['market_name'];
    $market_description = $_POST['market_description'];
    $image = $_FILES['market_image']['name'];

    // กำหนดโฟลเดอร์สำหรับเก็บไฟล์
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // ตรวจสอบการอัปโหลดไฟล์
    if (move_uploaded_file($_FILES['market_image']['tmp_name'], $target_file)) {
        // เก็บข้อมูลในฐานข้อมูล
        $sql = "UPDATE market_info SET market_name = ?, market_description = ?, market_image = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $market_name, $market_description, $image);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>ข้อมูลตลาดถูกอัปเดตเรียบร้อยแล้ว</p>";
        } else {
            echo "<p style='color: red;'>เกิดข้อผิดพลาดในการอัปเดตข้อมูล</p>";
        }
    } else {
        echo "<p style='color: red;'>เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลตลาด</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
<nav class="navbar">
    <ul>
        <li><a href="admin_dashboard.php">จัดการข้อมูลตลาด</a></li>
        <li><a href="logout.php">ออกจากระบบ</a></li>
    </ul>
</nav>

<div class="container">
    <h2>จัดการข้อมูลตลาด</h2>

    <!-- ฟอร์มสำหรับอัปเดตข้อมูล -->
    <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
        <label for="market_name">ชื่อของตลาด:</label>
        <input type="text" name="market_name" required>

        <label for="market_description">คำอธิบายตลาด:</label>
        <textarea name="market_description" rows="4" required></textarea>

        <label for="market_image">อัปโหลดรูปภาพตลาด:</label>
        <input type="file" name="market_image" required>

        <button type="submit">อัปเดตข้อมูล</button>
    </form>

    <h3>ข้อมูลที่อัปเดตล่าสุด:</h3>
    <?php
    // ดึงข้อมูลตลาดล่าสุดจากฐานข้อมูล
    $sql = "SELECT * FROM market_info ORDER BY updated_at DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h4>" . $row['market_name'] . "</h4>";
        echo "<p>" . $row['market_description'] . "</p>";
        echo "<img src='uploads/" . $row['market_image'] . "' alt='Market Image' width='300'>";
    } else {
        echo "<p>ยังไม่มีข้อมูลตลาด</p>";
    }
    ?>
</div>
</body>
</html>
