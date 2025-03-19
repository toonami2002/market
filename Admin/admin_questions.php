<?php
session_start();

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// เชื่อมต่อกับฐานข้อมูล
include('db_connect.php');

// ดึงข้อมูลคำถามที่ยังไม่ได้รับคำตอบจากฐานข้อมูล
$sql = "SELECT q.id, q.question, q.user_id, q.status, u.username as username 
        FROM questions q 
        JOIN users u ON q.user_id = u.id
        WHERE q.status = 'pending'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตอบคำถาม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../style/admin_qt.css">

</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-4">
        <div class="text-center">
            <h2 class="mb-4">คำถามที่รอการตอบ</h2>

        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='question-card'>";
                echo "<h5>คำถามจาก " . $row['username'] . "</h5>";
                echo "<p><strong>คำถาม:</strong> " . $row['question'] . "</p>";

                // ฟอร์มตอบคำถาม
                echo "<form action='answer_question.php' method='POST'>";
                echo "<input type='hidden' name='question_id' value='" . $row['id'] . "'>";
                echo "<div class='mb-3'>";
                echo "<label for='answer' class='form-label'>คำตอบ:</label>";
                echo "<textarea name='answer' class='form-control' rows='4' required></textarea>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-success'>บันทึกคำตอบ</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-questions'>ยังไม่มีคำถามที่รอการตอบ</p>";
        }
        ?>
    </div>
</body>

</html>