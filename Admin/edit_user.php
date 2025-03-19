<?php
session_start();
include('db_connect.php');


if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบว่าได้ส่ง ID ผู้ใช้มาหรือไม่
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลผู้ใช้นี้";
        exit();
    }
} else {
    echo "ไม่มี ID ผู้ใช้";
    exit();
}

// แก้ไขข้อมูลผู้ใช้
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE users SET username = ?, phone = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $phone, $email, $role, $user_id);
    $stmt->execute();

    // ตั้งค่าข้อความสำเร็จใน session และ redirect ไปยังหน้า manage_users.php
    $_SESSION['success_message'] = "บันทึกข้อมูลผู้ใช้เรียบร้อยแล้ว";
    header('Location: manage_users.php'); // เปลี่ยนเส้นทางไปยังหน้า manage_users.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php include('navbar.php'); ?>


    <div class="container mt-4">
        <h2>แก้ไขข้อมูลผู้ใช้</h2>
        
        <!-- แสดงข้อความเมื่อบันทึกสำเร็จ -->
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<p class='alert alert-success'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']); // ลบข้อความสำเร็จหลังจากแสดงผล
        }
        ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทร</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">สิทธิ์ผู้ใช้</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</body>

</html>
