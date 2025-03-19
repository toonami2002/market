<?php
session_start();

include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $banner_id = $_POST['banner_id'];
    $status = $_POST['status']; // รับค่า 1 (แสดง) หรือ 0 (ซ่อน)

    $sql = "UPDATE banners SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $banner_id);
    $stmt->execute();
    $stmt->close();

    // กลับไปหน้าจัดการแบนเนอร์
    header("Location: manage_banners.php");
    exit();
}
?>
