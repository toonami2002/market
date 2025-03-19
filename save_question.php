<?php
session_start();
include('db_connect.php'); // เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');

    exit();
}

$user_id = $_SESSION['user_id']; // รับ user_id จาก session
$question = trim($_POST['question']); // รับคำถามจากฟอร์ม

if (empty($question)) {
    echo "กรุณากรอกคำถาม";
    exit();
}

// บันทึกลงฐานข้อมูล
$sql = "INSERT INTO questions (user_id, question) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $question);
if ($stmt->execute()) {
    echo "<script>alert('ส่งคำถามสำเร็จ!'); window.location.href='user_questions.php';</script>";
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
