<?php
include 'db_connect.php'; // Include your database connection file

// Fetch packages from the database
$sql = "SELECT * FROM Package"; // Adjust the table name as per your database
$result = $conn->query($sql);

// Fetch foreign key values
$destinations = $conn->query("SELECT destinationID, destinationCity FROM Destination");
$itineraries = $conn->query("SELECT itineraryID, itineraryDay FROM Itinerary");
$payments = $conn->query("SELECT paymentID, paymentType FROM Payment");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
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
            flex-direction: column;
            gap: 20px;
        }
        .form-container, .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            padding: 10px 15px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #575757;
        }
        .input-field {
            padding: 10px;
            width: max-content;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 5px 0;
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
    <a href="Payments.php">Payments</a>
</div>

<div class="container">
    <div class="form-container">
        <h2>Package Overview</h2>
        <button class="button" onclick="addRow()">Add Package</button>
        <button class="button" onclick="findPackage()">Find ID</button>
    </div>

    <div class="table-container">
        <h2>Package List</h2>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Package ID</th>
                    <th>Package Name</th>
                    <th>Destination ID</th>
                    <th>Transport</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration (Days)</th>
                    <th>Itinerary ID</th>
                    <th>Itinerary Day</th>
                    <th>Accommodation</th>
                    <th>Payment ID</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["packageID"] . "</td>";
                    echo "<td>" . $row["packageName"] . "</td>";
                    echo "<td>" . $row["destinationID"] . "</td>";
                    echo "<td>" . $row["packageTransport"] . "</td>";
                    echo "<td>" . $row["packageSDate"] . "</td>";
                    echo "<td>" . $row["packageEDate"] . "</td>";
                    echo "<td>" . $row["packageTDays"] . "</td>";
                    echo "<td>" . $row["itineraryID"] . "</td>";
                    echo "<td>" . $row["itineraryDay"] . "</td>";
                    echo "<td>" . $row["packageAccommodation"] . "</td>";
                    echo "<td>" . $row["paymentID"] . "</td>";
                    echo "<td>" . $row["packagePrice"] . "</td>";
                    echo "<td>
                            <button onclick='editRow(this)'>Edit</button>
                            <button onclick='removeRow(this)'>Remove</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No packages found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function addRow() {
        const table = document.getElementById('dataTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        const idCell = newRow.insertCell(0);
        const nameCell = newRow.insertCell(1);
        const destinationCell = newRow.insertCell(2);
        const transportCell = newRow.insertCell(3);
        const startDateCell = newRow.insertCell(4);
        const endDateCell = newRow.insertCell(5);
        const durationCell = newRow.insertCell(6);
        const itineraryIDCell = newRow.insertCell(7);
        const itineraryDayCell = newRow.insertCell(8);
        const accommodationCell = newRow.insertCell(9);
        const paymentIDCell = newRow.insertCell(10);
        const priceCell = newRow.insertCell(11);
        const actionCell = newRow.insertCell(12);

        // Make cells editable
        idCell.contentEditable = true;
        nameCell.contentEditable = true;
        destinationCell.contentEditable = true; // Make editable
        transportCell.contentEditable = true;
        startDateCell.contentEditable = true;
        endDateCell.contentEditable = true;
        durationCell.contentEditable = true;
        accommodationCell.contentEditable = true;
        itineraryDayCell.contentEditable = true;
        priceCell.contentEditable = true;

        // Create input fields for foreign keys
        destinationCell.innerHTML = `<input type="text" class="input-field" placeholder="Destination ID">`;
        itineraryIDCell.innerHTML = `<input type="text" class="input-field" placeholder="Itinerary ID">`;
        paymentIDCell.innerHTML = `<input type="text" class="input-field" placeholder="Payment ID">`;

        actionCell.innerHTML = `
            <button onclick="saveRow(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
        `;
    }

    function saveRow(button) {
    const row = button.parentElement.parentElement;
    const idCell = row.cells[0];
    const nameCell = row.cells[1];
    const destinationCell = row.cells[2].querySelector('select'); // For dropdown/select
    const transportCell = row.cells[3];
    const startDateCell = row.cells[4];
    const endDateCell = row.cells[5];
    const durationCell = row.cells[6];
    const itineraryIDCell = row.cells[7].querySelector('select');
    const itineraryDayCell = row.cells[8];
    const accommodationCell = row.cells[9];
    const paymentIDCell = row.cells[10].querySelector('select');
    const priceCell = row.cells[11];

    // Use the existing table value if no select dropdown is available
    const data = {
        packageID: idCell.textContent.trim(),
        packageName: nameCell.textContent.trim(),
        destinationID: destinationCell ? destinationCell.value : row.cells[2].textContent.trim(),
        packageTransport: transportCell.textContent.trim(),
        packageSDate: startDateCell.textContent.trim(),
        packageEDate: endDateCell.textContent.trim(),
        packageTDays: durationCell.textContent.trim(),
        itineraryID: itineraryIDCell ? itineraryIDCell.value : row.cells[7].textContent.trim(),
        itineraryDay: itineraryDayCell.textContent.trim(),
        packageAccommodation: accommodationCell.textContent.trim(),
        paymentID: paymentIDCell ? paymentIDCell.value : row.cells[10].textContent.trim(),
        packagePrice: priceCell.textContent.trim(),
    };

    console.log('Data being sent to server:', JSON.stringify(data)); // Debug log

    fetch('add_package.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Package updated successfully!');
            } else {
                console.error('Server error:', data.error);
                alert('An error occurred: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Failed to process response:', error);
            alert('An unexpected error occurred. Please try again.');
        });
}


    function editRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const nameCell = row.cells[1];
        const destinationCell = row.cells[2];
        const transportCell = row.cells[3];
        const startDateCell = row.cells[4];
        const endDateCell = row.cells[5];
        const durationCell = row.cells[6];
        const itineraryIDCell = row.cells[7];
        const itineraryDayCell = row.cells[8];
        const accommodationCell = row.cells[9];
        const paymentIDCell = row.cells[10];
        const priceCell = row.cells[11];

        // Make cells editable
        idCell.contentEditable = true;
        nameCell.contentEditable = true;
        destinationCell.contentEditable = true; // Make editable
        transportCell.contentEditable = true;
        startDateCell.contentEditable = true;
        endDateCell.contentEditable = true;
        durationCell.contentEditable = true;
        itineraryIDCell.contentEditable = true;
        itineraryDayCell.contentEditable = true;
        accommodationCell.contentEditable = true;
        paymentIDCell.contentEditable = true;
        priceCell.contentEditable = true;

        // Change the Edit button to Save
        button.textContent = 'Save';
        button.setAttribute('onclick', 'saveRow(this)');
    }

    function removeRow(button) {
        const row = button.parentElement.parentElement;
        const packageID = row.cells[0].textContent.trim();

        if (confirm('Are you sure you want to delete this package?')) {
            fetch('delete_package.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ packageID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Package removed successfully!');
                    row.parentNode.removeChild(row); // Remove row from DOM
                } else {
                    alert('Error removing package: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function findPackage() {
        const idToFind = prompt("Enter Package ID to find:");
        const table = document.getElementById('dataTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        let found = false;

        // Clear previous highlights
        for (let i = 0; i < rows.length; i++) {
            rows[i].style.backgroundColor = '';
        }

        // Search for the ID
        for (let i = 0; i < rows.length; i++) {
            const idCell = rows[i].cells[0];
            if (idCell.textContent.trim() === idToFind) {
                rows[i].style.backgroundColor = '#d1e7dd'; // Highlight found row
                found = true;
                break;
            }
        }

        if (!found) {
            alert("Package ID not found.");
        }
    }
</script>

</body>
</html>
