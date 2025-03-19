<?php
session_start();
include('db_connect.php');

// // ตรวจสอบว่าแอดมินล็อกอินหรือไม่
if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php'); // หากไม่ใช่แอดมินให้กลับไปที่หน้า login
    exit();
}

// ลบคำถามคำตอบ
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // ลบคำถามคำตอบจากฐานข้อมูล
    $sql = "DELETE FROM faqs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // รีเฟรชหน้าและส่งข้อความลบสำเร็จ
    echo "<script>alert('ลบข้อมูลสำเร็จ!'); window.location='manage_faq.php';</script>";

    exit();
}

// ดึงข้อมูลคำถามและคำตอบทั้งหมด
$sql_faq = "SELECT * FROM faqs ORDER BY created_at DESC";
$faq_result = $conn->query($sql_faq);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำถามคำตอบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">จัดการคำถามคำตอบ</h2>

        <!-- แสดงคำถามคำตอบจากฐานข้อมูล -->
        <?php if ($faq_result->num_rows > 0) { ?>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th>คำถาม</th>
                        <th>คำตอบ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($faq = $faq_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $faq['question']; ?></td>
                            <td><?php echo $faq['answer']; ?></td>
                            <td>
                                <!-- ปุ่มแก้ไข -->
                                <a href="edit_faq.php?id=<?php echo $faq['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                <!-- ปุ่มลบ -->
                                <a href="manage_faq.php?delete_id=<?php echo $faq['id']; ?>" onclick="return confirm('คุณต้องการลบคำถามคำตอบนี้หรือไม่?');" class="btn btn-danger btn-sm">ลบ</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo "<p>ยังไม่มีคำถามคำตอบ</p>";
        } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
