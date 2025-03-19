<?php
session_start();
include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดแบนเนอร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <div class="text-center">
            <h2>อัปโหลดแบนเนอร์</h2>
        </div>
        <form action="upload_banner.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="banner_image" class="form-label">เลือกแบนเนอร์</label>
                <input type="file" class="form-control" id="banner_image" name="banner_image" required>
            </div>
            <button type="submit" class="btn btn-primary">อัปโหลด</button>
        </form>
    </div>
</body>

</html>