<?php
include('db_connect.php');

if (isset($_POST['submit'])) {
    $user_name = $_POST['username'];
    $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_email = $_POST['email'];
    $user_phone = $_POST['phone'];

    // ตรวจสอบว่ามี username หรือ email นี้อยู่ในระบบแล้วหรือไม่
    $check_user = "SELECT * FROM users WHERE username='$user_name' OR email='$user_email'";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        echo "<div class='alert alert-danger text-center'>ชื่อผู้ใช้หรืออีเมลล์นี้ถูกใช้งานแล้ว!</div>";
    } else {
        $profile_pic = "default.png"; // ตั้งค่ารูปเริ่มต้น

        if (!empty($_FILES['profile_pic']['name'])) {
            $target_dir = "imgPf/";  // โฟลเดอร์เก็บรูป
            $file_name = basename($_FILES["profile_pic"]["name"]);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // กำหนดชื่อไฟล์ใหม่ให้ไม่ซ้ำ
            $new_file_name = $user_name . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $new_file_name;

            // ตรวจสอบว่านามสกุลไฟล์ถูกต้อง
            $allowed_types = array("jpg", "jpeg", "png");
            if (in_array($file_ext, $allowed_types)) {
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    $profile_pic = $new_file_name;
                } else {
                    echo "<div class='alert alert-danger text-center'>อัปโหลดรูปภาพไม่สำเร็จ!</div>";
                }
            } else {
                echo "<div class='alert alert-danger text-center'>อัปโหลดเฉพาะไฟล์ JPG, JPEG, PNG เท่านั้น!</div>";
            }
        }

        // เพิ่มข้อมูลลงในฐานข้อมูล
        $sql = "INSERT INTO users (username, password, email, phone, profile_pic) 
                VALUES ('$user_name', '$user_password', '$user_email', '$user_phone', '$profile_pic')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success text-center center-alert'>สมัครสมาชิกสำเร็จ! กำลังไปยังหน้าเข้าสู่ระบบ...</div>";
            header("refresh:2; url=login.php");
        } else {
            echo "<div class='alert alert-danger text-center'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="style/register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background: linear-gradient(to right, #ffffff, #b3e0ff); position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
        <div class="container-fluid justify-content-center">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="index.php">Home</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- เพิ่มพื้นที่ด้านบนเพื่อไม่ให้เนื้อหาซ้อนกับ navbar -->
    <div style="margin-top: 70px;"></div>



    <div class="register-container">
        <h2 class="mb-3">สมัครสมาชิก</h2>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" required placeholder="ชื่อผู้ใช้">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" required placeholder="รหัสผ่าน">
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" required placeholder="อีเมล์">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="phone" required placeholder="เบอร์โทรศัพท์">
            </div>
            <!-- ช่องอัปโหลดรูป -->
            <div class="mb-3">
                <input type="file" class="form-control" name="profile_pic" accept="image/*">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">สมัครสมาชิก</button>
        </form>
        <p class="mt-3">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
    <img src="imgLogin/mk1.png" class="city-left">
    <img src="imgLogin/mk1.png" class="city-right">

</body>

</html>