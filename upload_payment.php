<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $user_id = $_POST['user_id'];

    // ตรวจสอบไฟล์ที่อัปโหลด
    if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
        $target_dir = "uploads/slips/";
        $file_name = time() . "_" . basename($_FILES["payment_slip"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["payment_slip"]["tmp_name"], $target_file)) {
            // บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO payments (booking_id, user_id, payment_slip, status) 
                    VALUES ('$booking_id', '$user_id', '$file_name', 'pending') 
                    ON DUPLICATE KEY UPDATE payment_slip = '$file_name', status = 'pending'";

            if (mysqli_query($conn, $sql)) {
                header("Location: my_bookings.php");
                exit;
            } else {
                echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
            }
        } else {
            echo "อัปโหลดไฟล์ไม่สำเร็จ";
        }
    } else {
        echo "กรุณาเลือกไฟล์เพื่ออัปโหลด";
    }
}
?>
