<?php
session_start();
include 'db_connect.php';

if ($_SESSION['role'] != 'admin') {
        header('Location: ../login.php');
        exit();
}
// ดึงข้อมูลสลิปที่รอการตรวจสอบ พร้อมกับราคาจากตาราง zone_number
$sql = "SELECT p.id, p.booking_id, u.username, p.payment_slip, p.status, z.price 
        FROM payments p 
        JOIN users u ON p.user_id = u.id 
        JOIN bookings b ON p.booking_id = b.id
        JOIN zone_number z ON b.zone_number_id = z.id
        WHERE p.status = 'pending'";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="th">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ตรวจสอบสลิปการชำระเงิน</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
                // ฟังก์ชั่นที่จะอัปเดตสถานะในตารางหลังจากกดอนุมัติหรือปฏิเสธ
                function updateStatus(paymentId, status) {
                        const row = document.getElementById('payment_' + paymentId); // แถวที่ต้องการอัปเดต
                        const statusCell = row.querySelector('.status-cell'); // เซลล์สถานะ

                        // อัปเดตสถานะในเซลล์
                        if (status === 'approved') {
                                statusCell.innerHTML = '<span class="badge bg-success text-white">อนุมัติ</span>';
                        } else if (status === 'rejected') {
                                statusCell.innerHTML = '<span class="badge bg-danger text-white">ปฏิเสธ</span>';
                        }
                }
        </script>
</head>

<body>
        <?php include('navbar.php'); ?>


        <div class="container mt-5">
                <h2 class="mb-4 text-center">รายการสลิปที่รอการตรวจสอบ</h2>
                <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-center"><a href="check_payments.php">🔙 Check all payment</a></h5>
                </div>
                <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                                <thead class="thead-dark">
                                        <tr>
                                                <th>รหัสจอง</th>
                                                <th>ผู้ใช้</th>
                                                <th>สลิป</th>
                                                <th>สถานะ</th>
                                                <th>ยอดที่ต้องชำระ</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                <tr id="payment_<?= $row['id']; ?>">
                                                        <td><?= htmlspecialchars($row['booking_id']); ?></td>
                                                        <td><?= htmlspecialchars($row['username']); ?></td>
                                                        <td>

                                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentSlipModal" data-bs-payment-slip="<?= $row['payment_slip']; ?>">ดูสลิป</button>

                                                        </td>
                                                        <td class="status-cell">
                                                                <span class="badge bg-warning text-dark">รอตรวจสอบ</span>
                                                        </td>
                                                        <td>
                                                                <span class="badge bg-primary text-white"><?= number_format($row['price'], 2); ?> บาท</span>
                                                        </td>
                                                        <td>
                                                                <a href="approve_payment.php?id=<?= $row['id']; ?>"
                                                                        class="btn btn-success btn-sm" onclick="updateStatus(<?= $row['id']; ?>, 'approved');">อนุมัติ</a>
                                                                <a href="reject_payment.php?id=<?= $row['id']; ?>"
                                                                        class="btn btn-danger btn-sm" onclick="updateStatus(<?= $row['id']; ?>, 'rejected');">ปฏิเสธ</a>
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