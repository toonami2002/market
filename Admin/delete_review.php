<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (isset($_GET['id'])) {
    $review_id = $_GET['id'];

    // ลบรีวิวจากฐานข้อมูล
    $sql_delete = "DELETE FROM reviews WHERE id = '$review_id'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: admin_reviews.php"); // กลับไปยังหน้าจัดการรีวิว
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบรีวิว: " . $conn->error;
    }
} else {
    header("Location: admin_reviews.php");
    exit();
}
?>
