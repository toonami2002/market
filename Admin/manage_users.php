<?php
session_start();
include('db_connect.php'); // เชื่อมต่อกับฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// ลบผู้ใช้
// ลบข้อมูลในตาราง bookings ที่เกี่ยวข้องกับ user ก่อน
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // ลบข้อมูลในตาราง bookings ที่เกี่ยวข้องกับ user
    $sql = "DELETE FROM bookings WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // ลบข้อมูลในตาราง users
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // รีเฟรชหน้าและส่งข้อความลบสำเร็จ
    header("Location: manage_users.php?status=deleted");
    exit();
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        // เช็คว่ามี query string 'status=deleted' หรือไม่
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted') { ?>
            alert('ลบผู้ใช้สำเร็จแล้ว');
            // ลบค่าจาก URL เพื่อไม่ให้มีการแสดง alert ซ้ำ
            history.replaceState(null, null, 'manage_users.php'); // แก้ไข URL โดยไม่รีเฟรชหน้า
        <?php } ?>
    </script>
</head>

<body>
    <?php include('navbar.php'); ?>


    <div class="container mt-4">
        <div class="text-center">
            <h2>จัดการข้อมูลผู้ใช้</h2>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อผู้ใช้</th>
                    <th>เบอร์โทร</th>
                    <th>อีเมล</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <img src="../imgPf/<?php echo ($row['profile_pic'] ? $row['profile_pic'] : 'default.png'); ?>" alt="Profile Picture" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <?php echo $row['username']; ?>
                        </td>

                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">แก้ไข</a>
                            <a href="manage_users.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('คุณต้องการลบผู้ใช้คนนี้หรือไม่?')" class="btn btn-danger">ลบ</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
</body>

</html>