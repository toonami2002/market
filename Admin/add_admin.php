<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มแอดมิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>


    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h3>เพิ่มแอดมินใหม่</h3>
                    </div>
                    <div class="card-body">
                        <form action="create_admin_password.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล (Email)</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">เบอร์โทร (Phone)</label>
                                <input type="tel" class="form-control" name="phone" pattern="[0-9]{10}" required placeholder="กรอกเบอร์โทร 10 หลัก">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน (Password)</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">เพิ่มแอดมิน</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="text-center mt-3">
                    <a href="admin_dashboard.php" class="btn btn-secondary">กลับไปหน้าหลัก</a>
                </div> -->
            </div>
        </div>
    </div>
</body>

</html>