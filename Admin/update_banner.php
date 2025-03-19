<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $banner_id = $_POST['banner_id'];
        $fileTmpPath = $_FILES['banner_image']['tmp_name'];
        $fileName = $_FILES['banner_image']['name'];
        $fileType = $_FILES['banner_image']['type'];

        // กำหนดประเภทไฟล์ที่ยอมรับ
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            // ดึงข้อมูลแบนเนอร์เก่า
            $sql = "SELECT * FROM banners WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $banner_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $old_banner_image = $row['banner_image'];

            // ลบไฟล์แบนเนอร์เก่า
            $old_file_path = 'img/' . $old_banner_image;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }

            // สร้างชื่อไฟล์ใหม่
            $newFileName = time() . '_' . $fileName;

            // กำหนดตำแหน่งที่จะเก็บไฟล์
            $uploadDir = 'img/';
            $uploadFilePath = $uploadDir . $newFileName;

            // อัปโหลดไฟล์
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // อัปเดตข้อมูลแบนเนอร์ในฐานข้อมูล
                $update_sql = "UPDATE banners SET banner_image = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $newFileName, $banner_id);

                if ($update_stmt->execute()) {
                    echo "<script>alert('อัปเดตแบนเนอร์สำเร็จ!'); window.location.href='manage_banners.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตแบนเนอร์'); window.location.href='manage_banners.php';</script>";
                }

                $update_stmt->close();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดไฟล์'); window.location.href='manage_banners.php';</script>";
            }
        } else {
            echo "<script>alert('โปรดอัปโหลดไฟล์รูปภาพที่มีประเภท JPEG, PNG หรือ GIF'); window.location.href='manage_banners.php';</script>";
        }
    }
}
?>
