<?php
session_start();

include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $zone_id = intval($_POST['zone']);
    $number = trim($_POST['number']);
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : 0;
    $image_name = "";

    // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../imageZone/"; // โฟลเดอร์เก็บรูป
        $image_name = time() . "_" . basename($_FILES["image"]["name"]); // ตั้งชื่อไฟล์ใหม่
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // ตรวจสอบชนิดไฟล์ (อนุญาตเฉพาะ JPG, PNG, JPEG)
        $allowed_types = ["jpg", "jpeg", "png"];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<p style='color: red;'>❌ อัปโหลดได้เฉพาะไฟล์ JPG, JPEG, PNG เท่านั้น!</p>";
            exit;
        }

        // ย้ายไฟล์ไปยังโฟลเดอร์ uploads/
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<p style='color: red;'>❌ เกิดข้อผิดพลาดในการอัปโหลดรูป!</p>";
            exit;
        }
    }

    if (!empty($zone_id) && !empty($number) && $price >= 0) {
        // ตรวจสอบว่าหมายเลขแผงซ้ำหรือไม่
        $check_sql = "SELECT * FROM zone_number WHERE number = ? AND zone = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("si", $number, $zone_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "<p style='color: red;'>❌ หมายเลขแผงนี้มีอยู่แล้วในโซนนี้!</p>";
        } else {
            // เพิ่มข้อมูลลงฐานข้อมูล
            $sql_insert = "INSERT INTO zone_number (number, zone, price, status, image) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sidis", $number, $zone_id, $price, $status, $image_name);

            if ($stmt_insert->execute()) {
                // เพิ่มสำเร็จให้แสดง alert และเปลี่ยนหน้าไปยัง edit_zone_price.php
                echo "<script>
                    alert('เพิ่มแผงสำเร็จ!');
                    window.location.href = 'edit_zone_price.php?zone_id=" . $zone_id . "';
                </script>";
            } else {
                echo "<p style='color: red;'>❌ เกิดข้อผิดพลาด: " . $stmt_insert->error . "</p>";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    } else {
        echo "<p style='color: red;'>❌ กรุณากรอกข้อมูลให้ครบถ้วน และราคาต้องไม่น้อยกว่า 0!</p>";
    }
}

$conn->close();
?>

