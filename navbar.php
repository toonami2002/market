<?php
// เรียกใช้งาน session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลผู้ใช้เมื่อล็อกอิน
if (isset($_SESSION['username']) && !isset($_SESSION['profile_pic'])) {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['profile_pic'] = !empty($row['profile_pic']) ? "imgPf/" . $row['profile_pic'] : "imgPf/default.png";
    } else {
        $_SESSION['profile_pic'] = "imgPf/default.png"; // เผื่อกรณีเกิดข้อผิดพลาด
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <style>
        /* ปรับขนาดโลโก้ */
        .logo {
            height: 50px;
        }

        /* ทำให้ Navbar ดูเบาสบาย */
        .navbar {
            border-bottom: 2px solid #f8f9fa;
        }

        /* สีของเมนู */
        .nav-link {
            color: #333 !important;
            /* สีเทาเข้ม อ่านง่าย */
            font-weight: 500;
            transition: 0.3s ease-in-out;
        }

        /* เปลี่ยนสีเมื่อ hover */
        .nav-link:hover {
            color: #007bff !important;
            /* น้ำเงินอ่อน */
        }

        /* ปุ่มเข้าสู่ระบบ / ออกจากระบบ */
        .nav-item .btn {
            transition: 0.3s;
            border-radius: 20px;
        }

        /* ปรับสไตล์ปุ่ม */
        .nav-item .btn:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        /* ทำให้ Navbar อยู่ด้านบน */
        .navbar.fixed-top {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <!-- โลโก้ -->
    <a class="navbar-brand ms-3" href="index.php">
        <img src="logo/logo.png" alt="Logo" class="logo">
    </a>

    <!-- ปุ่ม Toggle สำหรับมือถือ -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- เมนู -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="Admin/index.php">Dashboard</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">หน้าหลัก</a>
                </li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php">จองที่ขาย</a></li>
            <li class="nav-item"><a class="nav-link" href="show_booking.php">ดูร้านค้าต่างๆที่จองแล้ว</a></li>
            <li class="nav-item"><a class="nav-link" href="my_bookings.php">แผงของฉัน</a></li>
            <li class="nav-item"><a class="nav-link" href="user_questions.php">คำถาม</a></li>

            <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item d-flex align-items-center">
                    <span class="nav-link text-primary">ยินดีต้อนรับ, <?php echo $_SESSION['username']; ?>!</span>
                    <img src="<?php echo $_SESSION['profile_pic']; ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger px-3" href="logout.php">ออกจากระบบ</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary px-3" href="login.php">เข้าสู่ระบบ</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

</body>

</html>