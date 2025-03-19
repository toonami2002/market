<?php
session_start(); // เริ่ม session
if (isset($_POST['submit'])) {
    include('db_connect.php'); // เชื่อมต่อฐานข้อมูล
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // ตรวจสอบว่ามี username ในฐานข้อมูลหรือไม่
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {
            // เก็บข้อมูลใน session
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_id'] = $row['id'];  // เก็บ user_id ใน session
            if ($row['role'] == 'admin') {
                header('Location: Admin/index.php'); // ไปหน้า admin
            } else {
                header('Location: index.php'); // ไปหน้า user
            }
            exit();
        } else {
            $_SESSION['error'] = "❌ รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $_SESSION['error'] = "❌ ชื่อผู้ใช้ไม่ถูกต้อง!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    

    
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

    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']); // ลบข้อความ error หลังจากแสดง
        }
        ?>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
        </form>
        <p class="mt-3 text-center">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
    </div>

    <img src="imgLogin/mk1.png" class="city-left">
    <img src="imgLogin/mk1.png" class="city-right">
</body>

</html>