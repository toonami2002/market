<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $banner_id = $_GET['id'];

    // ดึงข้อมูลแบนเนอร์จากฐานข้อมูล
    $sql = "SELECT * FROM banners WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $banner_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $banner_image = $row['banner_image'];

        // ลบไฟล์รูปภาพในโฟลเดอร์ img/
        $image_path = '../img/' . $banner_image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // ลบแบนเนอร์จากฐานข้อมูล
        $delete_sql = "DELETE FROM banners WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $banner_id);
        $delete_stmt->execute();

        echo "<script>alert('ลบแบนเนอร์สำเร็จ!'); window.location.href='manage_banners.php';</script>";
    } else {
        echo "<script>alert('ไม่พบแบนเนอร์ที่ต้องการลบ'); window.location.href='manage_banners.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
