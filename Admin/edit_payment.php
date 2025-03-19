<?php
session_start();

include 'db_connect.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    // ดึงข้อมูลการชำระเงิน
    $sql = "SELECT * FROM payments WHERE id = '$payment_id'";
    $result = mysqli_query($conn, $sql);
    $payment = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];

    // อัปเดตสถานะการชำระเงิน
    $sql = "UPDATE payments SET status='$status' WHERE id='$payment_id'";
    if (mysqli_query($conn, $sql)) {
        header('Location: check_payments.php'); // กลับไปที่หน้าตรวจสอบการชำระเงิน
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขการชำระเงิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php include('navbar.php'); ?>


    <div class="container mt-5">
        <h2 class="mb-4 text-center">แก้ไขสถานะการชำระเงิน</h2>

        <form action="edit_payment.php?id=<?= $payment['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="status" class="form-label">สถานะการชำระเงิน</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="pending" <?= $payment['status'] == 'pending' ? 'selected' : ''; ?>>รอตรวจสอบ</option>
                    <option value="approved" <?= $payment['status'] == 'approved' ? 'selected' : ''; ?>>อนุมัติ</option>
                    <option value="rejected" <?= $payment['status'] == 'rejected' ? 'selected' : ''; ?>>ปฏิเสธ</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</body>

</html>
