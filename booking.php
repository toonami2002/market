<?php
session_start();
include('db_connect.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM zone_number WHERE 1";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND number LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>market stall</title>
    <link rel="stylesheet" href="style/booking.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

</head>

<body>

    <?php include('navbar.php'); ?>


    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-center"></h2>
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="ค้นหาเลขโซน (A1, B3, C10)" value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </form>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $zone_id = $row['zone'];
                    $zone_number = $row['number'];
                    $price = $row['price']; // ดึงราคาจากฐานข้อมูล
                    $status = $row['status'];
                    $image = $row['image']; // ดึงชื่อไฟล์รูปจากฐานข้อมูล
                    $status_text = ($status == 0) ? 'ว่าง' : 'จองแล้ว';
            ?>
                    <div class="col" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card h-100">
                            <!-- เพิ่มรูปภาพที่นี่ -->
                            <div class="card-header text-center">
                                <h5><?php echo "Zone " . $zone_id . " - " . $zone_number; ?></h5>
                            </div>
                            <img src="imageZone/<?php echo $image; ?>" class="card-img-top" alt="รูปโซนตลาด">

                            <div class="card-body">
                                <h6>สถานะ: <?php echo $status_text; ?></h6>
                                <h6>ราคา: <?php echo number_format($price, 2); ?> บาท</h6> <!-- แสดงราคา -->
                                <?php if ($status == 0) { ?>
                                    <?php if (isset($_SESSION['username'])): ?>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $zone_number; ?>"><i class="bi bi-cart-plus"></i>จองตอนนี้</button>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-danger">กรุณาเข้าสู่ระบบเพื่อจอง</a>
                                    <?php endif; ?>
                                <?php } else { ?>
                                    <button class="btn btn-secondary" disabled>จองแล้ว</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Modal สำหรับการจอง -->
                    <div class="modal fade" id="bookingModal<?php echo $zone_number; ?>" tabindex="-1" aria-labelledby="bookingModalLabel<?php echo $zone_number; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- หัวข้อ Modal -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingModalLabel<?php echo $zone_number; ?>">
                                        <i class="fas fa-calendar-check"></i> กรอกรายละเอียดการจอง
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- เนื้อหา Modal -->
                                <div class="modal-body">
                                    <form action="save_booking.php" method="POST">
                                        <input type="hidden" name="zone_number_id" value="<?php echo $row['id']; ?>">

                                        <div class="mb-3">
                                            <label for="name" class="form-label"><i class="fas fa-user"></i> ชื่อผู้จอง</label>
                                            <input type="text" class="form-control shadow-sm" id="name" name="name" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="shop_name" class="form-label"><i class="fas fa-store"></i> ชื่อร้าน</label>
                                            <input type="text" class="form-control shadow-sm" id="shop_name" name="shop_name" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label"><i class="fas fa-phone"></i> เบอร์ติดต่อ</label>
                                            <input type="tel" class="form-control shadow-sm" id="phone_number" name="phone_number" pattern="[0-9]{10}" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save"></i> บันทึกการจอง
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>ไม่มีข้อมูลที่นั่งในระบบ</p>";
            }
            ?>
        </div>
    </div>

    <!-- AOS Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="script/madal.js"></script>

    <?php include('footer.php'); ?>
</body>

</html>