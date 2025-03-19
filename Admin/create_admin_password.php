<?php
session_start();

include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = trim($_POST['username']);
    $admin_email = trim($_POST['email']);
    $admin_phone = trim($_POST['phone']);
    $admin_password = trim($_POST['password']);

    if (!empty($admin_username) && !empty($admin_email) && !empty($admin_phone) && !empty($admin_password)) {
        // เข้ารหัสรหัสผ่าน
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // เพิ่ม admin เข้าไปในฐานข้อมูล
        $sql = "INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $role = 'admin';
        $stmt->bind_param("sssss", $admin_username, $admin_email, $admin_phone, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ เพิ่มแอดมินสำเร็จ!');
                    window.location.href = 'manage_users.php';
                  </script>";
        } else {
            echo "<script>
                    alert('❌ เกิดข้อผิดพลาด: " . $stmt->error . "');
                    window.history.back();
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('❌ กรุณากรอกข้อมูลให้ครบถ้วน!');
                window.history.back();
              </script>";
    }
}

$conn->close();
