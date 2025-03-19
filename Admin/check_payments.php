<?php
session_start();
include 'db_connect.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// ตรวจสอบว่ามีการค้นหาหรือไม่
$search = isset($_GET['search']) ? $_GET['search'] : '';

// สร้าง SQL Query โดยใช้ LIKE เพื่อค้นหาจากชื่อร้านหรือชื่อผู้ใช้
$sql = "SELECT p.id, p.booking_id, u.username, p.payment_slip, p.status, b.name, b.shop_name, z.price
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN bookings b ON p.booking_id = b.id
        JOIN zone_number z ON b.zone_number_id = z.id
        WHERE b.shop_name LIKE '%$search%' OR u.username LIKE '%$search%'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบการชำระเงิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include('navbar.php'); ?>


    <div class="container mt-5">
        <h2 class="mb-4 text-center">รายการการชำระเงินทั้งหมด</h2>
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-center"></h5>
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="ค้นหาชื่อร้านหรือชื่อผู้ใช้" value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>รหัสจอง</th>
                        <th>ผู้ใช้</th>
                        <th>ชื่อร้าน</th>
                        <th>สลิป</th>
                        <th>ยอดที่ต้องชำระ</th>
                        <th>สถานะ</th>
                        <th>การกระทำ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['booking_id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['shop_name']); ?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentSlipModal" data-bs-payment-slip="<?= $row['payment_slip']; ?>">ดูสลิป</button>
                            </td>
                            <td>
                                <span class="badge bg-primary text-white"><?= number_format($row['price'], 2); ?> บาท</span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark">รอตรวจสอบ</span>
                                <?php elseif ($row['status'] == 'approved') : ?>
                                    <span class="badge bg-success text-white">อนุมัติ</span>
                                <?php else : ?>
                                    <span class="badge bg-danger text-white">ปฏิเสธ</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_payment.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal สำหรับแสดงสลิป -->
    <div class="modal fade" id="paymentSlipModal" tabindex="-1" aria-labelledby="paymentSlipModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSlipModalLabel">สลิปการชำระเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="paymentSlipImage" src="" alt="Payment Slip" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../script/paymentSlipModal.js"></script>


</body>

</html>