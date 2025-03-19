<?php
session_start(); // เริ่มต้น session
session_unset(); // ลบข้อมูลใน session
session_destroy(); // ทำลาย session
header('Location: login.php'); // เปลี่ยนเส้นทางไปยังหน้า login.php
exit();
?>
