<?php
session_start();

include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// ดึง Zone จากฐานข้อมูล
$sql = "SELECT * FROM zone";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มแผงในตลาด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h2>เพิ่มแผงในโซนตลาด</h2>
            </div>
            <div class="card-body">
                <form action="save_zone_number.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="zone" class="form-label">เลือกโซน:</label>
                        <select name="zone" class="form-select" required>
                            <option value="">-- เลือกโซน --</option>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['zone'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">หมายเลขแผง:</label>
                        <input type="text" class="form-control" name="number" placeholder="เช่น A1 หรือ B5" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">ราคาของแผง (บาท):</label>
                        <input type="number" class="form-control" name="price" placeholder="ระบุราคา" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">สถานะ:</label>
                        <select name="status" class="form-select">
                            <option value="0">ว่าง</option>
                            <option value="1">จองแล้ว</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">อัปโหลดรูปภาพ:</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">เพิ่มแผง</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>

</html>

<?php $conn->close(); ?>