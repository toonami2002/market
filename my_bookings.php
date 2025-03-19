<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // ถ้ายังไม่ล็อกอินให้เปลี่ยนเส้นทางไปหน้า login
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลการจองของผู้ใช้
$sql = "SELECT b.id AS booking_id, z.number, p.payment_slip, p.status AS payment_status, b.shop_name
        FROM bookings b 
        JOIN zone_number z ON b.zone_number_id = z.id 
        LEFT JOIN payments p ON b.id = p.booking_id 
        WHERE b.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจองของฉัน</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2>การจองของฉัน</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>รหัสจอง</th>
                    <th>ชื่อร้านค้า</th>
                    <th>หมายเลขแผง</th>
                    <th>สถานะการชำระเงิน</th>
                    <th>การกระทำ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['booking_id']; ?></td>
                        <td><?= $row['shop_name']; ?></td>
                        <td><?= $row['number']; ?></td>

                        <td>
                            <?php
                            if ($row['payment_status'] == "approved") {
                                echo "<span class='text-success'>อนุมัติแล้ว</span>";
                            } elseif ($row['payment_status'] == "rejected") {
                                echo "<span class='text-danger'>ถูกปฏิเสธ</span>";
                            } elseif ($row['payment_status'] == "pending") {
                                echo "<span class='text-warning'>รอตรวจสอบ</span>";
                            } else {
                                echo "<span class='text-secondary'>ยังไม่ได้ชำระ</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if (!$row['payment_slip'] || $row['payment_status'] == "rejected"): ?>
                                <!-- ปุ่มอัปโหลดสลิป -->
                                <form action="upload_payment.php" method="post" enctype="multipart/form-data" class="d-inline">
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                    <input type="file" name="payment_slip" required>
                                    <button type="submit" class="btn btn-primary btn-sm">อัปโหลดสลิป</button>
                                </form>
                            <?php else: ?>
                                <!-- ปุ่มดูสลิปใน Modal -->
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentSlipModal" data-bs-payment-slip="<?= $row['payment_slip']; ?>">ดูสลิป</button>
                            <?php endif; ?>
                            <?php if ($row['payment_slip'] == ""): ?>
                                <form action="cancel_booking.php" method="post" class="d-inline">
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">ยกเลิกการจอง</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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

    <?php include('footer.php'); ?>

    <script>
        var paymentSlipModal = document.getElementById('paymentSlipModal')
        paymentSlipModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget // ปุ่มที่เปิด Modal
            var paymentSlip = button.getAttribute('data-bs-payment-slip') // ดึงชื่อไฟล์สลิป
            var modalBody = paymentSlipModal.querySelector('.modal-body #paymentSlipImage')
            modalBody.src = 'uploads/slips/' + paymentSlip
        })
    </script>

</body>

</html>