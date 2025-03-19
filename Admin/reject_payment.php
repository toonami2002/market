<?php
session_start();

// ตรวจสอบสิทธิ์ผู้ใช้
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// รวมไฟล์ที่เชื่อมต่อฐานข้อมูล
include 'db_connect.php'; // เปลี่ยนเส้นทางให้ถูกต้องตามที่ตั้งไฟล์ db_connect.php

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    // อัปเดตสถานะเป็น 'rejected'
    $sql = "UPDATE payments SET status='rejected' WHERE id='$payment_id'";
    if (mysqli_query($conn, $sql)) {
        echo "ปฏิเสธการชำระเงินเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}

header('Location: admin_payments.php'); // เปลี่ยนไปหน้า admin_payments.php
exit;
?>
