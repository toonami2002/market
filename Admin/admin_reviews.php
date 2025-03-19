<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}


// ดึงข้อมูลรีวิวทั้งหมด
$sql_reviews = "SELECT reviews.id, reviews.comment, users.username FROM reviews JOIN users ON reviews.user_id = users.id ORDER BY reviews.created_at DESC";
$reviews_result = $conn->query($sql_reviews);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการรีวิว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2 class="text-center">จัดการรีวิว</h2>
        <?php if ($reviews_result->num_rows > 0) { ?>
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th>รีวิว</th>
                        <th>ผู้ใช้</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($review = $reviews_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $review['comment']; ?></td>
                            <td><?php echo $review['username']; ?></td>
                            <td>
                                <a href="delete_review.php?id=<?php echo $review['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบรีวิวนี้หรือไม่?');">ลบ</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo "<p>ยังไม่มีรีวิว</p>";
        } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>