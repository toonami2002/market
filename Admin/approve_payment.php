<?php
session_start();

include 'db_connect.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    // อัปเดตสถานะการชำระเงิน
    $sql = "UPDATE payments SET status='approved' WHERE id='$payment_id'";
    if (mysqli_query($conn, $sql)) {
        // อัปเดตสถานะการจอง
        $sql2 = "UPDATE bookings SET status=2 WHERE id=(SELECT booking_id FROM payments WHERE id='$payment_id')";
        mysqli_query($conn, $sql);
        echo "อนุมัติการชำระเงินเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}

header('Location: admin_payments.php'); // เปลี่ยนไปหน้า admin_payments.php
exit;
?>
