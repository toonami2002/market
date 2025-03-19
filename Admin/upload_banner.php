<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่าได้เลือกไฟล์หรือไม่
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $fileTmpPath = $_FILES['banner_image']['tmp_name'];
        $fileName = $_FILES['banner_image']['name'];
        $fileSize = $_FILES['banner_image']['size'];
        $fileType = $_FILES['banner_image']['type'];

        // กำหนดประเภทไฟล์ที่ยอมรับ
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            // สร้างชื่อไฟล์ใหม่เพื่อป้องกันการซ้ำ
            $newFileName = time() . '_' . $fileName;

            // กำหนดตำแหน่งที่จะเก็บไฟล์
            $uploadDir = '../img/';
            $uploadFilePath = $uploadDir . $newFileName;

            // อัปโหลดไฟล์
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // บันทึกข้อมูลลงในฐานข้อมูล
                $sql = "INSERT INTO banners (banner_image) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $newFileName);

                if ($stmt->execute()) {
                    echo "<script>alert('อัปโหลดแบนเนอร์สำเร็จ!'); window.location.href='add_banner.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล'); window.location.href='add_banner.php';</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์'); window.location.href='add_banner.php';</script>";
            }
        } else {
            echo "<script>alert('โปรดอัปโหลดไฟล์รูปภาพที่มีประเภท JPEG, PNG หรือ GIF'); window.location.href='add_banner.php';</script>";
        }
    } else {
        echo "<script>alert('โปรดเลือกไฟล์แบนเนอร์'); window.location.href='add_banner.php';</script>";
    }
}
?>
