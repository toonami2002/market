<?php
// เชื่อมต่อกับฐานข้อมูล
include('db_connect.php');

// รับค่าจากฟอร์ม
$question_id = $_POST['question_id'];
$answer = $_POST['answer'];

// รับเวลาปัจจุบันเมื่อแอดมินตอบคำถาม
$answered_at = date('Y-m-d H:i:s');

// อัพเดทคำตอบในตาราง questions และเปลี่ยนสถานะเป็น answered พร้อมบันทึกเวลาตอบ
$sql = "UPDATE questions 
        SET answer = ?, status = 'answered', answered_at = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $answer, $answered_at, $question_id);
$stmt->execute();

// ตรวจสอบผลการอัพเดท
if ($stmt->affected_rows > 0) {
    echo "<script>alert('คำตอบของคุณถูกบันทึก'); window.location.href = 'admin_questions.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกคำตอบ'); window.location.href = 'admin_questions.php';</script>";
}
?>
