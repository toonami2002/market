<?php
session_start();

include('db_connect.php');

// กำหนดค่าตัวแปรค้นหา
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// คิวรีข้อมูลการจองพร้อมข้อมูลโซนและข้อมูลสถานะการชำระเงิน
$sql = "SELECT bookings.id, bookings.name, bookings.shop_name, bookings.phone_number, bookings.booking_date, 
               payments.status AS payment_status,
               zone_number.number AS zone_number, zone.zone AS zone_name
        FROM bookings
        JOIN zone_number ON bookings.zone_number_id = zone_number.id
        JOIN zone ON zone_number.zone = zone.id
        LEFT JOIN payments ON bookings.id = payments.booking_id";  // เชื่อมกับตาราง payments

if (!empty($search)) {
    $sql .= " WHERE bookings.shop_name LIKE '%$search%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านค้าต่างๆ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
<?php include('navbar.php');?>

    <div class="container mt-5">
        <h2 class="mb-4">รายชื่อร้านค้าทั้งหมดที่จองไว้</h2>
        <!-- ฟอร์มค้นหา -->
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อร้านค้า" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ชื่อร้านค้า</th>
                    <th>โซน</th>
                    <th>หมายเลขพื้นที่</th>
                    <th>วันที่จอง</th>
                    <!-- <th>สถานะการจอง</th> -->
                    <th>สถานะการชำระเงิน</th> <!-- เพิ่มคอลัมน์สำหรับสถานะการชำระเงิน -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['shop_name'] . "</td>";
                        echo "<td>" . $row['zone_name'] . "</td>";
                        echo "<td>" . $row['zone_number'] . "</td>";
                        echo "<td>" . $row['booking_date'] . "</td>";
                        // แสดงสถานะการชำระเงิน
                        echo "<td>";
                        if ($row['payment_status'] == "approved") {
                            echo "<span class='text-success'>อนุมัติแล้ว</span>";
                        } elseif ($row['payment_status'] == "rejected") {
                            echo "<span class='text-danger'>ถูกปฏิเสธ</span>";
                        } elseif ($row['payment_status'] == "pending") {
                            echo "<span class='text-warning'>รอตรวจสอบ</span>";
                        } else {
                            echo "<span class='text-secondary'>ยังไม่ได้ชำระ</span>";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>ไม่พบข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
   
    <?php include('footer.php'); ?>
    
</body>

</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>
