<?php
session_start();

include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// รับค่า ID การจองจาก URL
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // อัปเดตสถานะโซนให้เป็น "ว่าง"
    $update_sql = "UPDATE zone_number SET status = 0 WHERE id IN (SELECT zone_number_id FROM bookings WHERE id = ?)";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // ลบการจองจากตาราง bookings
    $delete_sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // ตรวจสอบผลลัพธ์
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('ยกเลิกการจองสำเร็จ'); window.location.href='manage_booking.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการยกเลิก'); window.location.href='show_booking.php';</script>";
    }

    $stmt->close();
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
