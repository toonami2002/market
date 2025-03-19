<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// เมื่อส่งฟอร์ม
if (isset($_POST['submit'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // SQL สำหรับเพิ่มคำถามและคำตอบ
    $sql = "INSERT INTO faqs (question, answer) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $question, $answer);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มคำถามสำเร็จ!'); window.location='add_faq.php';</script>";
    } else {
        $message = "เกิดข้อผิดพลาดในการเพิ่มคำถาม!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มคำถามและคำตอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-4">
        <h2 class="text-center">เพิ่มคำถามและคำตอบ</h2>

        <?php if (isset($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label for="question" class="form-label">คำถาม</label>
                <input type="text" class="form-control" id="question" name="question" required>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">คำตอบ</label>
                <textarea class="form-control" id="answer" name="answer" rows="4" required></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">เพิ่มคำถาม</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
