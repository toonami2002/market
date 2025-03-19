<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// ดึงข้อมูลแบนเนอร์ทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM banners";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการแบนเนอร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <div class="card-header text-center">
            <h2>จัดการแบนเนอร์</h2>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../img/<?php echo $row['banner_image']; ?>" class="card-img-top" alt="Banner">
                            <div class="card-body">
                                <h5 class="card-title">แบนเนอร์ ID: <?php echo $row['id']; ?></h5>

                                <!-- แสดงสถานะ -->
                                <p class="fw-bold">สถานะ: 
                                    <span class="<?php echo $row['status'] ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo $row['status'] ? 'แสดง' : 'ซ่อน'; ?>
                                    </span>
                                </p>

                                <!-- ฟอร์มแก้ไขสถานะ -->
                                <form action="update_banner_status.php" method="POST">
                                    <input type="hidden" name="banner_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="form-select mb-2" onchange="this.form.submit()">
                                        <option value="1" <?php echo $row['status'] ? 'selected' : ''; ?>>แสดง</option>
                                        <option value="0" <?php echo !$row['status'] ? 'selected' : ''; ?>>ซ่อน</option>
                                    </select>
                                </form>

                                <!-- ปุ่มแก้ไขและลบ -->
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">แก้ไข</button>
                                <a href="delete_banner.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">ลบ</a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal สำหรับการแก้ไขแบนเนอร์ -->
                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">แก้ไขแบนเนอร์</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="update_banner.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="banner_id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="banner_image" class="form-label">เลือกแบนเนอร์ใหม่</label>
                                            <input type="file" class="form-control" id="banner_image" name="banner_image" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">อัปโหลดแบนเนอร์ใหม่</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>ยังไม่มีแบนเนอร์ในระบบ</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
