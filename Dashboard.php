
<!DOCTYPE html>
<?php

include 'db_connect.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch total bookings
$totalBookingsQuery = "SELECT COUNT(*) as total FROM Booking";
$totalBookingsResult = $conn->query($totalBookingsQuery);
$totalBookings = $totalBookingsResult ? $totalBookingsResult->fetch_assoc()['total'] : 0;

// Fetch total revenue
$totalRevenueQuery = "SELECT SUM(paymentPrice) as total FROM Payment";
$totalRevenueResult = $conn->query($totalRevenueQuery);
$totalRevenue = $totalRevenueResult ? $totalRevenueResult->fetch_assoc()['total'] : 0;

// Fetch total agents
$totalAgentsQuery = "SELECT COUNT(DISTINCT agentID) as total FROM Agent";
$totalAgentsResult = $conn->query($totalAgentsQuery);
$totalAgents = $totalAgentsResult ? $totalAgentsResult->fetch_assoc()['total'] : 0;

// Fetch total customers
$totalCustomersQuery = "SELECT COUNT(DISTINCT customerID) as total FROM Booking";
$totalCustomersResult = $conn->query($totalCustomersQuery);
$totalCustomers = $totalCustomersResult ? $totalCustomersResult->fetch_assoc()['total'] : 0;

// Fetch bookings by month
$bookingsByMonthQuery = "SELECT MONTH(bookedDate) as month, COUNT(*) as count 
                         FROM Booking 
                         GROUP BY month 
                         ORDER BY month";

$bookingsByMonth = ['labels' => [], 'values' => []];

$bookingsByMonthResult = $conn->query($bookingsByMonthQuery);
if ($bookingsByMonthResult) {
    while ($row = $bookingsByMonthResult->fetch_assoc()) {
        $bookingsByMonth['labels'][] = $row['month'];
        $bookingsByMonth['values'][] = $row['count'];
    }
} else {
    error_log("Error fetching bookings by month: " . $conn->error);
    $bookingsByMonth = ['labels' => [], 'values' => []];
}

// Debug output for bookings by month
error_log("Bookings by Month Data: " . print_r($bookingsByMonth, true));

// Fetch revenue by month
$revenueByMonthQuery = "SELECT MONTH(Booking.bookedDate) AS month, 
    SUM(Payment.paymentPrice) AS total
    FROM Booking
    JOIN Package ON Booking.packageID = Package.packageID
    JOIN Payment ON Payment.paymentID = Package.paymentID
    GROUP BY MONTH(Booking.bookedDate)
    ORDER BY month";

$revenueByMonth = ['labels' => [], 'values' => []];

$revenueByMonthResult = $conn->query($revenueByMonthQuery);
if ($revenueByMonthResult) {
    while ($row = $revenueByMonthResult->fetch_assoc()) {
        $revenueByMonth['labels'][] = $row['month'];
        $revenueByMonth['values'][] = $row['total'];
    }
} else {
    error_log("Error fetching revenue by month: " . $conn->error);
}

// Fetch top packages
$topPackagesQuery = "SELECT Package.packageName, COUNT(Booking.bookingID) as count 
                     FROM Package 
                     LEFT JOIN Booking ON Package.packageID = Booking.packageID 
                     GROUP BY Package.packageName 
                     ORDER BY count DESC 
                     LIMIT 5";
$topPackages = [
    'labels' => [],
    'values' => []
];
$topPackagesResult = $conn->query($topPackagesQuery);
if ($topPackagesResult) {
    while ($row = $topPackagesResult->fetch_assoc()) {
        $topPackages['labels'][] = $row['packageName'];
        $topPackages['values'][] = $row['count'];
    }
} else {
    error_log("Top packages query error: " . $conn->error);
}



$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/Applications/XAMPP/xamppfiles/htdocs/TRAVELMANAGEMENT/tvm.css">

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
            position: fixed;
        }
        .sidebar h2 {
            color: #fff;
             FONT-SIZE: xx-large;

        }
        .sidebar a {
            color: #fff;
    text-decoration: none;
    display: block;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    transition: background 0.3s;
    FONT-SIZE: x-large;
    text-align: center;
        }
        .sidebar a:hover {
            background: #575757;
        }
        .container {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
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
    <h2>Travel maxxing</h2>
    <a href="Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="Bookings.php"><i class="fas fa-book"></i> Bookings</a>
    <a href="Destination.php"><i class="fas fa-map-marker-alt"></i> Destination</a>
    <a href="Agents.php"><i class="fas fa-user-friends"></i> Agents</a>
    <a href="Customer.php"><i class="fas fa-users"></i> Customers</a>
    <a href="Package.php"><i class="fas fa-gift"></i> Package</a>
    <a href="Itinerary.php"><i class="fas fa-calendar-alt"></i> Itinerary</a>
    <a href="Payment.php"><i class="fas fa-credit-card"></i> Payments</a>
</div>

<div class="container">
    <div class="card">
        <h2>Overview Dashboard</h2>
        <p>Total Bookings: <strong><?php echo $totalBookings ?? '0'; ?></strong></p>
        <p>Total Revenue: <strong>Rp <?php echo isset($totalRevenue) ? number_format($totalRevenue, 0, ',', '.') : '0'; ?></strong></p>
        <p>Total Agents: <strong><?php echo $totalAgents ?? '0'; ?></strong></p>
        <p>Total Customers: <strong><?php echo $totalCustomers ?? '0'; ?></strong></p>
    </div>

    <div class="card">
        <h2>Bookings Analysis</h2>
        <canvas id="bookingsByMonth"></canvas>
    </div>

    <div class="card">
        <h2>Revenue Analysis</h2>
        <canvas id="revenueByMonth"></canvas>
    </div>

    <div class="card">
        <h2>Top Packages</h2>
        <canvas id="topPackages"></canvas>
    </div>
</div>

<script>


window.onload = function() {
    // Bookings by Month Chart
    const bookingsByMonthData = <?php echo json_encode($bookingsByMonth ?? ['labels' => [], 'values' => []]); ?>;
    console.log('Bookings by Month Data:', bookingsByMonthData);
    
    if (bookingsByMonthData.labels.length > 0 && bookingsByMonthData.values.length > 0) {
        const ctx1 = document.getElementById('bookingsByMonth').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: bookingsByMonthData.labels.map(month => `Month ${month}`),
                datasets: [{
                    label: 'Bookings by Month',
                    data: bookingsByMonthData.values,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            }
        });
    } else {
        console.error('No bookings data available');
        document.getElementById('bookingsByMonth').innerHTML = 'No data available';
    }

    // Revenue by Month Chart
    const revenueByMonthData = <?php echo json_encode($revenueByMonth); ?>;
    console.log('Revenue by Month Data:', revenueByMonthData);
    if (revenueByMonthData && Array.isArray(revenueByMonthData.labels) && Array.isArray(revenueByMonthData.values)) {
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
    } else {
        console.error("Invalid or incomplete revenue data:", revenueByMonthData);
    }

    // Top Packages Chart
    const topPackagesData = <?php echo json_encode($topPackages); ?>;
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
};

    console.log(bookingsByMonthData);
</script>


</body>
</html>
