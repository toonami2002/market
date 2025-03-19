<?php
session_start();

include('db_connect.php');

if ($_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// กำหนดค่าตัวแปรค้นหา
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// คิวรีข้อมูลการจองพร้อมข้อมูลโซน, user_id และสถานะการชำระเงิน
$sql = "SELECT bookings.id, bookings.user_id, bookings.name, bookings.shop_name, bookings.phone_number, bookings.booking_date, 
               zone_number.number AS zone_number, zone.zone AS zone_name, payments.status AS payment_status, zone_number.price AS price
        FROM bookings
        JOIN zone_number ON bookings.zone_number_id = zone_number.id
        JOIN zone ON zone_number.zone = zone.id
        LEFT JOIN payments ON bookings.id = payments.booking_id";  // เพิ่มการเข้าร่วมกับ payments เพื่อดึงข้อมูลสถานะการชำระเงิน

if (!empty($search)) {
    $sql .= " WHERE bookings.shop_name LIKE '%$search%' OR zone_number.number LIKE '%$search%'";
}


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการจองตลาด</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
<?php include('navbar.php'); ?>


    <div class="container mt-5">
        <div class="text-center">
            <h2 class="mb-4">จัดการรายการจองตลาด</h2>
        </div>


        <!-- ฟอร์มค้นหา -->
        <!-- ฟอร์มค้นหา -->
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อร้านค้า หรือ เลขโซน" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>


        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>รหัสจอง</th>
                    <th>user_id</th>
                    <th>ชื่อผู้จอง</th>
                    <th>ชื่อร้านค้า</th>
                    <th>เบอร์โทร</th>
                    <th>วันที่จอง</th>
                    <th>โซน</th>
                    <th>หมายเลขพื้นที่</th>
                    <th>ราคาที่ต้องชำระ</th>
                    <th>สถานะการชำระเงิน</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";  // แสดง user_id
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['shop_name'] . "</td>";
                        echo "<td>" . $row['phone_number'] . "</td>";
                        echo "<td>" . $row['booking_date'] . "</td>";
                        echo "<td>" . $row['zone_name'] . "</td>";
                        echo "<td>" . $row['zone_number'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";

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

                        echo "<td>";
                        echo "<a href='cancel_booking.php?id=" . $row['id'] . "' class='btn btn-danger'>ยกเลิกจอง</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>ไม่มีข้อมูลการจอง</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</body>

</html>

<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>