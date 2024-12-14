<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- For charts -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #333;
            color: white;
            padding: 20px;
            height: 100vh;
        }
        .sidebar h2 {
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: #575757;
        }
        .container {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            flex: 1 1 30%;
            min-width: 300px;
        }
        h2 {
            margin-top: 0;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <a href="Dashboard.php">Dashboard</a>
    <a href="Bookings.php">Bookings</a>
    <a href="Destination.php">Destination</a>
    <a href="Agents.php">Agents</a>
    <a href="Customer.php">Customers</a>
    <a href="Package.php">Package</a>
    <a href="Itinerary.php">Itinerary</a>
    <a href="Payment.php">Payments</a>
</div>

<div class="container">
<div class="card">
        <h2>Overview Dashboard</h2>
        <p>Total Bookings: <strong><?php echo isset($totalBookings) ? $totalBookings : '0'; ?></strong></p>
        <p>Total Revenue: <strong>Rp <?php echo isset($totalRevenue) ? number_format($totalRevenue, 0, ',', '.') : '0'; ?></strong></p>
        <p>Total Agents: <strong><?php echo isset($totalAgents) ? $totalAgents : '0'; ?></strong></p>
        <p>Total Customers: <strong><?php echo isset($totalCustomers) ? $totalCustomers : '0'; ?></strong></p>
    </div>

    <div class="card">
        <h2>Bookings Analysis</h2>
        <canvas id="bookingsByMonth"></canvas>
        <canvas id="revenueByMonth"></canvas>
    </div>

    <div class="card">
        <h2>Package Performance</h2>
        <canvas id="topPackages"></canvas>
    </div>

    <div class="card">
        <h2>Destination Insights</h2>
        <canvas id="popularDestinations"></canvas>
    </div>
</div>

<script>
    // Sample data for charts (replace with PHP data)
    const bookingsByMonthData = <?php echo json_encode($bookingsByMonth); ?>;
    const revenueByMonthData = <?php echo json_encode($revenueByMonth); ?>;
    const topPackagesData = <?php echo json_encode($topPackages); ?>;

    // Bookings by Month Chart
    const ctx1 = document.getElementById('bookingsByMonth').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: bookingsByMonthData.labels,
            datasets: [{
                label: 'Bookings by Month',
                data: bookingsByMonthData.values,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        }
    });

    // Revenue by Month Chart
    const ctx2 = document.getElementById('revenueByMonth').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: revenueByMonthData.labels,
            datasets: [{
                label: 'Revenue by Month',
                data: revenueByMonthData.values,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });

    // Top Packages Chart
    const ctx3 = document.getElementById('topPackages').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: topPackagesData.labels,
            datasets: [{
                label: 'Top Packages',
                data: topPackagesData.values,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        }
    });
</script>

<?php
// Database connection
include 'db_connect.php';

// Fetch total bookings
$totalBookingsQuery = "SELECT COUNT(*) as total FROM Booking";
$result = $conn->query($totalBookingsQuery);
if (!$result) {
    die("Error fetching total bookings: " . $conn->error);
}
$totalBookings = $result->fetch_assoc()['total'] ?? 0;

// Fetch total revenue
$totalRevenueQuery = "SELECT SUM(Payment.paymentPrice) as total FROM Payment JOIN Booking ON Payment.paymentID = Booking.packageID";
$result = $conn->query($totalRevenueQuery);
if (!$result) {
    die("Error fetching total revenue: " . $conn->error);
}
$totalRevenue = $result->fetch_assoc()['total'] ?? 0;

// Fetch total agents
$totalAgentsQuery = "SELECT COUNT(*) as total FROM Agent";
$result = $conn->query($totalAgentsQuery);
if (!$result) {
    die("Error fetching total agents: " . $conn->error);
}
$totalAgents = $result->fetch_assoc()['total'] ?? 0;

// Fetch total customers
$totalCustomersQuery = "SELECT COUNT(DISTINCT customerID) as total FROM Booking";
$result = $conn->query($totalCustomersQuery);
if (!$result) {
    die("Error fetching total customers: " . $conn->error);
}
$totalCustomers = $result->fetch_assoc()['total'] ?? 0;

// Fetch bookings by month
$bookingsByMonthQuery = "SELECT MONTH(bookedDate) as month, COUNT(*) as count FROM Booking GROUP BY month";
$result = $conn->query($bookingsByMonthQuery);
if (!$result) {
    die("Error fetching bookings by month: " . $conn->error);
}
$bookingsByMonth = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $bookingsByMonth['labels'][] = $row['month'];
    $bookingsByMonth['values'][] = $row['count'];
}

// Fetch revenue by month
$revenueByMonthQuery = "SELECT MONTH(bookedDate) as month, SUM(Payment.paymentPrice) as total FROM Booking JOIN Payment ON Booking.packageID = Payment.paymentID GROUP BY month";
$result = $conn->query($revenueByMonthQuery);
if (!$result) {
    die("Error fetching revenue by month: " . $conn->error);
}
$revenueByMonth = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $revenueByMonth['labels'][] = $row['month'];
    $revenueByMonth['values'][] = $row['total'];
}

// Fetch top packages
$topPackagesQuery = "SELECT Package.packageName, COUNT(Booking.bookingID) as count FROM Package LEFT JOIN Booking ON Package.packageID = Booking.packageID GROUP BY Package.packageName ORDER BY count DESC LIMIT 5";
$result = $conn->query($topPackagesQuery);
if (!$result) {
    die("Error fetching top packages: " . $conn->error);
}
$topPackages = ['labels' => [], 'values' => []];
while ($row = $result->fetch_assoc()) {
    $topPackages['labels'][] = $row['packageName'];
    $topPackages['values'][] = $row['count'];
}

$conn->close();
?>

</body>
</html>
