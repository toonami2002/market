<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}


// แก้ไขคำถามคำตอบ
if (isset($_GET['id'])) {
    $faq_id = $_GET['id'];

    // ดึงข้อมูลคำถามคำตอบที่ต้องการแก้ไข
    $sql = "SELECT * FROM faqs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $faq_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $faq = $result->fetch_assoc();
}

if (isset($_POST['update_faq'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // อัปเดตข้อมูลคำถามคำตอบ
    $sql = "UPDATE faqs SET question = ?, answer = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $question, $answer, $faq_id);
    $stmt->execute();

    echo "<script>alert('อัพเดทข้อมูลสำเร็จ!'); window.location='manage_faq.php';</script>";

    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขคำถามคำตอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">แก้ไขคำถามคำตอบ</h2>

        <form action="edit_faq.php?id=<?php echo $faq['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="question" class="form-label">คำถาม</label>
                <input type="text" class="form-control" id="question" name="question" value="<?php echo $faq['question']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="answer" class="form-label">คำตอบ</label>
                <textarea class="form-control" id="answer" name="answer" rows="4" required><?php echo $faq['answer']; ?></textarea>
            </div>
            <button type="submit" name="update_faq" class="btn btn-primary">อัปเดตคำถามคำตอบ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
