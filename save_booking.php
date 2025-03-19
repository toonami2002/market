<?php
session_start(); // เริ่ม session
include('db_connect.php'); // เชื่อมต่อฐานข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการจอง'); window.location.href='login.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id']; // ดึง user_id ของผู้ใช้ที่ล็อกอิน
    $zone_number_id = $_POST['zone_number_id'];
    $name = trim($_POST['name']);
    $shop_name = trim($_POST['shop_name']);
    $phone_number = trim($_POST['phone_number']);

    if (!empty($zone_number_id) && !empty($name) && !empty($shop_name) && !empty($phone_number)) {
        // เพิ่มข้อมูลการจองในฐานข้อมูล พร้อมวันที่
        $sql = "INSERT INTO bookings (user_id, zone_number_id, name, shop_name, phone_number, booking_date) 
                VALUES (?, ?, ?, ?, ?, NOW())"; // เพิ่ม NOW() เพื่อบันทึกวันที่และเวลา
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $user_id, $zone_number_id, $name, $shop_name, $phone_number);

        if ($stmt->execute()) {
            // อัปเดตสถานะที่นั่งเป็น "จองแล้ว"
            $update_sql = "UPDATE zone_number SET status = 1 WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $zone_number_id);
            $update_stmt->execute();

            echo "<script>alert('จองสำเร็จ!'); window.location.href='my_bookings.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการจอง'); window.location.href='booking.php';</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.location.href='booking.php';</script>";
    }
}

?>
