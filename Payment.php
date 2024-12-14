<?php
include 'db_connect.php'; // Include your database connection file
// Fetch payments from the database
$sql = "SELECT * FROM Payment"; // Adjust the table name as per your database
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Dashboard</title>
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
            width: calc(100% - 22px);
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
    <a href="Payment.php">Payments</a>
</div>

<div class="container">
    <div class="form-container">
        <h2>Payment Overview</h2>
        <button class="button" onclick="addRow()">Add Payment</button>
        <button class="button" onclick="findPayment()">Find ID</button>
    </div>

    <div class="table-container">
        <h2>Payment List</h2>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Payment Type</th>
                    <th>Payment Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["paymentID"] . "</td>";
                    echo "<td>" . $row["paymentType"] . "</td>";
                    echo "<td>" . $row["paymentPrice"] . "</td>";
                    echo "<td>
                            <button onclick='editRow(this)'>Edit</button>
                            <button onclick='removeRow(this)'>Remove</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No payments found</td></tr>";
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
        const typeCell = newRow.insertCell(1);
        const priceCell = newRow.insertCell(2);
        const actionCell = newRow.insertCell(3);

        // Make cells editable
        idCell.contentEditable = true;
        typeCell.contentEditable = true;
        priceCell.contentEditable = true;

        idCell.textContent = ''; // Start with empty cells
        typeCell.textContent = '';
        priceCell.textContent = '';

        actionCell.innerHTML = `
            <button onclick="saveRow(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
        `;
    }

    function saveRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const typeCell = row.cells[1];
        const priceCell = row.cells[2];

        // Check if the cells are filled
        if (idCell.textContent.trim() === '' || typeCell.textContent.trim() === '' || 
            priceCell.textContent.trim() === '') {
            alert('Please fill in all fields before saving.');
            return;
        }

        // AJAX request to save data
        fetch('add_payment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                paymentID: idCell.textContent.trim(),
                paymentType: typeCell.textContent.trim(),
                paymentPrice: priceCell.textContent.trim(),
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment added successfully!');
                location.reload(); // Reload the page to see the new payment
            } else {
                alert('Error adding payment: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('An error occurred while adding the payment. Please check the console for details.');
        });
    }

    function editRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const typeCell = row.cells[1];
        const priceCell = row.cells[2];

        // Make cells editable
        idCell.contentEditable = true;
        typeCell.contentEditable = true;
        priceCell.contentEditable = true;

        // Change the Edit button to Save
        button.textContent = 'Save';
        button.setAttribute('onclick', 'saveRow(this)');
    }

    function removeRow(button) {
        const row = button.parentElement.parentElement;
        const paymentID = row.cells[0].textContent.trim();

        if (confirm('Are you sure you want to delete this payment?')) {
            fetch('delete_payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ paymentID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment removed successfully!');
                    row.parentNode.removeChild(row); // Remove row from DOM
                } else {
                    alert('Error removing payment: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function findPayment() {
        const idToFind = prompt("Enter Payment ID to find:");
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
            alert("Payment ID not found.");
        }
    }
</script>

</body>
</html>
