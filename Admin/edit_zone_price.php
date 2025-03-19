<?php
session_start();

include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_zone"])) {
    $zone_number_id = $_POST["delete_zone_id"];

    // ดึงชื่อไฟล์รูปภาพจากฐานข้อมูล
    $sql = "SELECT image FROM zone_number WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $zone_number_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // ลบรูปภาพออกจากโฟลเดอร์ ถ้ามีไฟล์
    if (!empty($image) && file_exists("imageZone/" . $image)) {
        unlink("../imageZone/" . $image);
    }

    // ลบข้อมูลแผงตลาดจากฐานข้อมูล
    $sql = "DELETE FROM zone_number WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $zone_number_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบแผงตลาดสำเร็จ!'); window.location='edit_zone_price.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล!');</script>";
    }

    $stmt->close();
}
// อัปเดตข้อมูลแผง
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_zone"])) {
    $zone_number_id = $_POST["zone_number_id"];
    $new_zone = $_POST["zone"];
    $new_number = $_POST["number"];
    $new_price = $_POST["price"];

    // ตรวจสอบว่ามีการอัปโหลดรูปใหม่หรือไม่
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../imageZone/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $sql = "UPDATE zone_number SET zone = ?, number = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $new_zone, $new_number, $new_price, $image_name, $zone_number_id);
        } else {
            echo "<script>alert('ไฟล์รูปภาพต้องเป็น JPG, JPEG, PNG หรือ GIF เท่านั้น!');</script>";
            exit();
        }
    } else {
        $sql = "UPDATE zone_number SET zone = ?, number = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $new_zone, $new_number, $new_price, $zone_number_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='edit_zone_price.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด!');</script>";
    }
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT zone_number.id, zone.zone AS zone_name, zone_number.zone, zone_number.number, zone_number.price, zone_number.image
        FROM zone_number
        JOIN zone ON zone_number.zone = zone.id";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE zone_number.number LIKE '%$search%'";
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลแผงตลาด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include('navbar.php'); ?>


    <div class="container mt-5">
        <div class="text-center">
            <h2 class="mb-4">แก้ไขข้อมูลแผงตลาด</h2>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-center"><a href="add_zone_number.php">🔙 เพิ่มแผง</a></h5>
            
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="ค้นหาเลขโซน (A1, B3, C10)" value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>โซน</th>
                    <th>หมายเลขแผง</th>
                    <th>ราคา (บาท)</th>
                    <th>รูปภาพ</th>
                    <th>แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row["zone_name"]; ?></td>
                        <td><?= $row["number"]; ?></td>
                        <td><?= number_format($row["price"], 2); ?> บาท</td>
                        <td>
                            <img src="../imageZone/<?= !empty($row["image"]) ? $row["image"] : 'default.jpg'; ?>"
                                alt="รูปแผง" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">แก้ไข</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id']; ?>">ลบ</button>
                        </td>
                    </tr>

                    <!-- Modal สำหรับแก้ไขข้อมูล -->
                    <div class="modal fade" id="editModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">แก้ไขแผง <?= $row["number"]; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="zone_number_id" value="<?= $row['id']; ?>">

                                        <div class="mb-3">
                                            <label>โซน:</label>
                                            <input type="text" name="zone" class="form-control" value="<?= $row['zone']; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>หมายเลขแผง:</label>
                                            <input type="text" name="number" class="form-control" value="<?= $row['number']; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>ราคาต่อแผง (บาท):</label>
                                            <input type="number" name="price" class="form-control" value="<?= $row['price']; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>อัปโหลดรูปใหม่ (ถ้ามี):</label>
                                            <input type="file" name="image" class="form-control">
                                            <small>ไฟล์ที่รองรับ: JPG, JPEG, PNG, GIF</small>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_zone" class="btn btn-success">บันทึก</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal สำหรับลบข้อมูล -->
                    <div class="modal fade" id="deleteModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ยืนยันการลบ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>คุณแน่ใจหรือไม่ว่าต้องการลบแผงหมายเลข <strong><?= $row['number']; ?></strong> นี้?</p>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST">
                                        <input type="hidden" name="delete_zone_id" value="<?= $row['id']; ?>">
                                        <button type="submit" name="delete_zone" class="btn btn-danger">ลบ</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>