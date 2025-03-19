<?php
session_start();
include('db_connect.php'); // เชื่อมต่อกับฐานข้อมูล

// ดึงข้อมูลตลาดล่าสุดจากฐานข้อมูล
$sql = "SELECT * FROM zone_number WHERE status = 0 ORDER BY RAND() LIMIT 3";
$result = $conn->query($sql);

// การเพิ่มรีวิว
if (isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];  // ไอดีผู้ใช้ที่ล็อกอิน
    $comment = $_POST['comment'];     // ข้อความรีวิว

    // SQL สำหรับเพิ่มรีวิว
    $sql_review = "INSERT INTO reviews (user_id, comment) VALUES ('$user_id', '$comment')";
    if ($conn->query($sql_review) === TRUE) {
        // รีเฟรชหน้าหลังจากส่งรีวิวเสร็จ
        header("Location: index.php");  // รีเฟรชหน้า index.php
        exit();  // หยุดการทำงานของโค้ดหลังจากที่รีเฟรชหน้า
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มรีวิว: " . $conn->error;
    }
}

// ดึงข้อมูลรีวิวจากฐานข้อมูล
$sql_reviews = "SELECT reviews.comment, users.username FROM reviews JOIN users ON reviews.user_id = users.id ORDER BY reviews.created_at DESC";
$reviews_result = $conn->query($sql_reviews);
?>
<?php
$sql_faq = "SELECT * FROM faqs ORDER BY created_at DESC";
$result_faq = $conn->query($sql_faq);
// $sql = "SELECT * FROM banners";
// $result = $conn->query($sql);

// ดึงข้อมูลแค่แบนเนอร์ที่มี id เท่ากับ 1, 2, 3
// $sql_banner = "SELECT * FROM banners WHERE id IN (1, 2, 3)";
// $banner_result = $conn->query($sql_banner);

$sql_banner = "SELECT * FROM banners WHERE status = 1 ORDER BY id ASC";
$banner_result = $conn->query($sql_banner);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Fastival</title>
    <link rel="stylesheet" href="style/booking.css">
    <link rel="stylesheet" href="style/modal.css">
    <link rel="stylesheet" href="style/review.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
</head>

<body>

    <?php include('navbar.php'); ?>
    <!-- banner -->

    <!-- banner -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            <?php
            $activeClass = 'active'; // กำหนดแบนเนอร์แรกเป็น active
            if ($banner_result->num_rows > 0) {
                while ($row = $banner_result->fetch_assoc()) {
                    $bannerImage = 'img/' . $row['banner_image'];
            ?>
                    <div class="carousel-item <?php echo $activeClass; ?>">
                        <img src="<?php echo $bannerImage; ?>" class="d-block w-100" alt="Banner">
                    </div>
            <?php
                    $activeClass = ''; // ตั้งค่าให้แบนเนอร์ถัดไปไม่เป็น active
                }
            } else {
                echo '<div class="carousel-item active"><p class="text-center">ไม่มีแบนเนอร์ที่แสดง</p></div>';
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
    

    <div class="container mt-5">
        <!-- Section แนะนำตลาด -->
        <section class="text-center">
            <h2>📍 ตลาดของเราคืออะไร?</h2>
            <p>ตลาดของเราเปิดให้บริการตามเทศกาล มีแผงค้าหลากหลายโซนให้เลือก</p>

            <!-- จริงๆในส่วนของผังพื้นที่นี้จะให้อยู่ใน databaseครับอันนี้ขอลองtestดูก่อน -->
            <div class="text-center mt-3">
                <h4>🗺 แผนผังตลาด</h4>
                <img src="imgAreaDiagram/AreaDG2.png" class="img-fluid shadow-lg rounded" alt="แผนผังตลาด" style="max-width: 90%;">
            </div>
        </section>
        <!-- Section รายการแผงให้จอง -->
        <section class="mt-4">
            <h3>📌 แผงที่ว่างให้จอง</h3>
            <div class="owl-carousel">
                <?php
                $sql = "SELECT * FROM zone_number WHERE status = 0 ORDER BY RAND() LIMIT 9"; // ดึงข้อมูล 9 อัน
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $zone_id = $row['zone'];
                        $zone_number = $row['number'];
                        $price = $row['price'];
                        $image = $row['image'];
                ?>
                        <div class="item">
                            <div class="card">
                                <img src="imageZone/<?php echo $image; ?>" class="card-img-top" alt="แผง <?php echo $zone_number; ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo "แผง " . $zone_number; ?></h5>
                                    <p class="card-text">ราคา <?php echo number_format($price, 2); ?> บาท</p>
                                    <a href="booking.php" class="btn btn-primary">จองตอนนี้</a>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>ไม่มีแผงว่างให้จองในขณะนี้</p>";
                }
                ?>
            </div>
        </section>
        <script src="script/carousel.js"></script>

        <!-- Section รีวิวจากลูกค้า -->
        <section class="mt-4 text-center">
            <h3>⭐ รีวิวจากลูกค้า</h3>

            <!-- ฟอร์มให้ผู้ใช้เพิ่มรีวิว -->
            <?php if (isset($_SESSION['user_id'])) { ?>
                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" placeholder="เขียนความคิดเห็นของคุณที่นี่" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="btn btn-primary">ส่งรีวิว</button>
                </form>
            <?php } else { ?>
                <p>กรุณาล็อกอินเพื่อเพิ่มรีวิว</p>
            <?php } ?>
            <!-- แสดงรีวิวจากลูกค้า -->
            <div class="mt-4">
                <?php
                if ($reviews_result->num_rows > 0) {
                    while ($review = $reviews_result->fetch_assoc()) {
                        // ตัดชื่อและแทนที่ตัวอักษรที่เหลือด้วย '*'
                        $username = $review['username'];
                        $masked_username = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
                ?>
                        <div class="review-card mb-4 p-3 border rounded shadow-sm">
                            <blockquote class="blockquote mb-0">
                                <p class="lead">“<?php echo $review['comment']; ?>”</p>
                                <footer class="blockquote-footer">
                                    โดย <strong><?php echo $masked_username; ?></strong>
                                </footer>
                            </blockquote>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>ยังไม่มีรีวิวจากลูกค้า</p>";
                }
                ?>
            </div>
        </section>
        <!-- Section FAQ -->
        <section class="mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h3>❓ คำถามที่พบบ่อย</h3>
                <h2 class="text-center"></h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionModal">
                    ติดต่อสอบถาม❓
                </button>
            </div>
            <div class="accordion" id="faqAccordion">
                <?php
                if ($result_faq->num_rows > 0) {
                    $counter = 1;
                    while ($faq = $result_faq->fetch_assoc()) {
                ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $counter; ?>">
                                    <?php echo $faq['question']; ?>
                                </button>
                            </h2>
                            <div id="faq<?php echo $counter; ?>" class="accordion-collapse collapse <?php echo ($counter == 1) ? 'show' : ''; ?>">
                                <div class="accordion-body">
                                    <?php echo $faq['answer']; ?>
                                </div>
                            </div>
                        </div>
                <?php
                        $counter++;
                    }
                } else {
                    echo "<p>ยังไม่มีคำถามที่พบบ่อย</p>";
                }
                ?>
            </div>
        </section>
    </div>

    <!-- Modal สำหรับส่งคำถาม -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">ส่งคำถามของคุณ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="save_question.php" method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">คำถาม</label>
                            <textarea class="form-control" id="question" name="question" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">ส่งคำถาม</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include('footer.php'); ?>
</body>

</html>