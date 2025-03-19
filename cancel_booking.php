<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // ถ้ายังไม่ล็อกอินให้เปลี่ยนเส้นทางไปหน้า login
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_POST['booking_id'];

// ตรวจสอบว่าผู้ใช้เป็นเจ้าของการจองนี้หรือไม่
$sql_check_booking = "SELECT * FROM bookings WHERE id = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check_booking);
$stmt_check->bind_param("ii", $booking_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // ดึงข้อมูล zone_id จาก bookings
    $booking = $result_check->fetch_assoc();
    $zone_id = $booking['zone_number_id'];  // zone_number_id คือ zone_id ของแผงที่จอง

    // ตรวจสอบว่าไม่มีการชำระเงินในตาราง payments
    $sql_check_payment = "SELECT * FROM payments WHERE booking_id = ?";
    $stmt_check_payment = $conn->prepare($sql_check_payment);
    $stmt_check_payment->bind_param("i", $booking_id);
    $stmt_check_payment->execute();
    $result_payment = $stmt_check_payment->get_result();

    if ($result_payment->num_rows == 0) { // ไม่มีข้อมูลในตาราง payments
        // อัปเดตสถานะในตาราง zone_number เป็น 0 (ว่าง)
        $sql_update_zone = "UPDATE zone_number SET status = 0 WHERE id = ?";
        $stmt_update_zone = $conn->prepare($sql_update_zone);
        $stmt_update_zone->bind_param("i", $zone_id);
        $stmt_update_zone->execute();

        // ลบการจองออกจากตาราง bookings
        $sql_delete_booking = "DELETE FROM bookings WHERE id = ?";
        $stmt_delete_booking = $conn->prepare($sql_delete_booking);
        $stmt_delete_booking->bind_param("i", $booking_id);
        $stmt_delete_booking->execute();

        // ถ้าลบสำเร็จ
        if ($stmt_delete_booking->affected_rows > 0) {
            echo "<script>alert('การจองของคุณถูกยกเลิกเรียบร้อยแล้ว'); window.location.href = 'my_bookings.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการยกเลิกการจอง'); window.location.href = 'my_bookings.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่สามารถยกเลิกการจองได้กรุณาติดต่อแอดมินได้ที่หน้าคำถาม'); window.location.href = 'my_bookings.php';</script>";
    }
} else {
    echo "<script>alert('ไม่พบการจองนี้'); window.location.href = 'my_bookings.php';</script>";
}
?>
