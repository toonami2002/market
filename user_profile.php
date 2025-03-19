<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// เชื่อมต่อฐานข้อมูล
include('db_connect.php');

// ดึงข้อมูลผู้ใช้จากฐานข้อมูลตาม user_id ที่เก็บใน session
$user_id = $_SESSION['user_id']; // user_id ที่เก็บใน session
$sql = "SELECT id, username, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "ไม่พบข้อมูลผู้ใช้!";
    exit();
}

// อัปเดตข้อมูลผู้ใช้
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $profile_pic = 'default.png'; // กำหนดเป็น default.png หากไม่สามารถอัปโหลดได้

        // ตรวจสอบว่าไฟล์ที่อัปโหลดเป็นประเภทที่อนุญาต
        if (in_array($file_extension, $allowed_extensions)) {
            $upload_dir = 'imgPf/';
            $profile_pic = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $profile_pic;

            // อัปโหลดไฟล์
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_path)) {
                // อัปเดตรูปภาพในฐานข้อมูล
                $update_sql = "UPDATE users SET username = ?, email = ?, phone = ?, profile_pic = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ssssi", $username, $email, $phone, $profile_pic, $user_id);
            
                if ($update_stmt->execute()) {
                    // อัปเดตค่ารูปโปรไฟล์ใน session
                    $_SESSION['profile_pic'] = 'imgPf/' . $profile_pic;
                    echo "ข้อมูลของคุณได้รับการอัปเดตสำเร็จ!";
                    header("Location: user_profile.php"); // กลับไปที่หน้าโปรไฟล์
                    exit();
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล!";
                }
            }
            
        } else {
            echo "ไฟล์ที่อัปโหลดไม่รองรับ กรุณาเลือกไฟล์ .jpg, .jpeg หรือ .png";
        }
    } else {
        // หากไม่อัปโหลดรูปภาพ ให้ไม่เปลี่ยนแปลงรูปภาพเดิม
        $update_sql = "UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $username, $email, $phone, $user_id);
    }

    if ($update_stmt->execute()) {
        echo "ข้อมูลของคุณได้รับการอัปเดตสำเร็จ!";
        header("Location: user_profile.php"); // กลับไปที่หน้าโปรไฟล์
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล!";
    }
}


// เปลี่ยนรหัสผ่าน
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบรหัสผ่านที่กรอกมา
    if ($new_password == $confirm_password) {
        // ตรวจสอบรหัสผ่านปัจจุบัน
        $sql_check = "SELECT password FROM users WHERE id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row = $result_check->fetch_assoc();

        // เปรียบเทียบรหัสผ่านปัจจุบัน
        if (password_verify($current_password, $row['password'])) {
            // อัปเดตรหัสผ่านใหม่
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_sql = "UPDATE users SET password = ? WHERE id = ?";
            $update_password_stmt = $conn->prepare($update_password_sql);
            $update_password_stmt->bind_param("si", $new_password_hashed, $user_id);

            if ($update_password_stmt->execute()) {
                echo "รหัสผ่านของคุณได้รับการเปลี่ยนแปลงสำเร็จ!";
                header("Location: user_profile.php"); // กลับไปที่หน้าโปรไฟล์
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน!";
            }
        } else {
            echo "รหัสผ่านปัจจุบันไม่ถูกต้อง!";
        }
    } else {
        echo "รหัสผ่านใหม่ไม่ตรงกัน!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-4">
        <h1>โปรไฟล์ของฉัน</h1>
        <form action="user_profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล์</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="profile_pic" class="form-label">แก้ไขรูปโปรไฟล์</label>
                <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                <small class="text-muted">รูปโปรไฟล์ (JPEG, PNG, JPG เท่านั้น)</small>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>


        <h2 class="mt-5">เปลี่ยนรหัสผ่าน</h2>
        <form action="user_profile.php" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-success">เปลี่ยนรหัสผ่าน</button>
        </form>
    </div>
    <?php include('footer.php'); ?>

</body>

</html>