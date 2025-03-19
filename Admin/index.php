<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แอดมิน Dashboard</title>
    <link rel="stylesheet" href="../style/index_admin.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid text-white text-center mt-4">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p>คุณสามารถจัดการระบบที่นี่</p>
    </div>

    <div class="container my-5">
        <div class="row g-4">
            <!-- Card เมนู -->
            <?php
            $menu_items = [
                ["Manage users", "manage_users.php", "fa-users"],
                ["Upload Banner", "add_banner.php", "fa-image"],
                ["Edit Banner", "manage_banners.php", "fa-images"],
                ["Manage market bookings", "manage_booking.php", "fa-store"],
                ["Add a market panel", "add_zone_number.php", "fa-plus-circle"],
                ["Edit the market panel", "edit_zone_price.php", "fa-edit"],
                ["Check payment", "admin_payments.php", "fa-money-check-alt"],
                ["Check payment completion", "check_payments.php", "fa-receipt"],
                ["Add admin", "add_admin.php", "fa-user-plus"],
                ["Manage reviews", "admin_reviews.php", "fa-comments"],
                ["Add FAQ", "add_faq.php", "fa-question-circle"],
                ["Manage FAQs", "manage_faq.php", "fa-solid fa-clipboard-question"],
                ["Question from User", "admin_questions.php", "fa-solid fa-person-circle-question"],
            ];
            foreach ($menu_items as $item) {
                echo '
                <div class="col-md-4">
                    <a href="'.$item[1].'" class="text-decoration-none">
                        <div class="card text-center shadow-lg">
                            <div class="card-body">
                                <i class="fas '.$item[2].' fa-3x text-primary"></i>
                                <h5 class="card-title mt-3">'.$item[0].'</h5>
                            </div>
                        </div>
                    </a>
                </div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
