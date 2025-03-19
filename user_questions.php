<?php
session_start();
include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนส่งคำถาม'); window.location.href='login.php';</script>";

    exit();
}

$user_id = $_SESSION['user_id']; // รับ user_id ของผู้ใช้
$sql = "SELECT * FROM questions WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คำถามของฉัน</title>
    <link rel="stylesheet" href="style/modal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>

<body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
                <h3>คำถามของฉัน</h3>
                <h2 class="text-center"></h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionModal">
                    ติดต่อสอบถาม/แจ้งปัญหา
                </button>
            </div>
        <table class="table table-bordered">
            <thead class="">
                <tr>
                    <th>คำถาม</th>
                    <th>คำตอบจาก Admin</th>
                    <th>วันที่ถาม</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['question']); ?></td>
                        <td>
                            <?php echo $row['answer'] ? htmlspecialchars($row['answer']) : "<span class='text-danger'>ยังไม่มีคำตอบ</span>"; ?>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Modal สำหรับส่งคำถาม -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">ส่งคำถามของคุณ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="save_question.php" method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">คำถาม/แจ้งปัญหา</label>
                            <textarea class="form-control" id="question" name="question" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">ส่งคำถาม</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>