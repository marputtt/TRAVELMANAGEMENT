<?php

include 'db_connect.php'; // Include your database connection file
// Fetch customers from the database
$sql = "SELECT * FROM Customer"; // Adjust the table name as per your database
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
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
        <h2>Customer Overview</h2>
        <button class="button" onclick="addRow()">Add Customer</button>
        <button class="button" onclick="findCustomer()">Find ID</button>
    </div>

    <div class="table-container">
        <h2>Customer List</h2>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["customerID"] . "</td>";
                    echo "<td>" . $row["customerFName"] . "</td>";
                    echo "<td>" . $row["customerLName"] . "</td>";
                    echo "<td>" . $row["customerSex"] . "</td>";
                    echo "<td>" . $row["customerDOB"] . "</td>";
                    echo "<td>" . $row["customerEmail"] . "</td>";
                    echo "<td>" . $row["customerPhone"] . "</td>";
                    echo "<td>
                            <button onclick='editRow(this)'>Edit</button>
                            <button onclick='removeRow(this)'>Remove</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No customers found</td></tr>";
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
        const fNameCell = newRow.insertCell(1);
        const lNameCell = newRow.insertCell(2);
        const sexCell = newRow.insertCell(3);
        const dobCell = newRow.insertCell(4);
        const emailCell = newRow.insertCell(5);
        const phoneCell = newRow.insertCell(6);
        const actionCell = newRow.insertCell(7);

        // Make cells editable
        idCell.contentEditable = true;
        fNameCell.contentEditable = true;
        lNameCell.contentEditable = true;
        sexCell.contentEditable = true;
        dobCell.contentEditable = true;
        emailCell.contentEditable = true;
        phoneCell.contentEditable = true;

        idCell.textContent = ''; // Start with empty cells
        fNameCell.textContent = '';
        lNameCell.textContent = '';
        sexCell.textContent = '';
        dobCell.textContent = '';
        emailCell.textContent = '';
        phoneCell.textContent = '';

        actionCell.innerHTML = `
            <button onclick="saveRow(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
        `;
    }

function saveRow(button) {
    const row = button.parentElement.parentElement;
    const idCell = row.cells[0];
    const fNameCell = row.cells[1];
    const lNameCell = row.cells[2];
    const sexCell = row.cells[3];
    const dobCell = row.cells[4];
    const emailCell = row.cells[5];
    const phoneCell = row.cells[6];

    // Check if the cells are filled
    if (idCell.textContent.trim() === '' || fNameCell.textContent.trim() === '' || 
        lNameCell.textContent.trim() === '' || sexCell.textContent.trim() === '' || 
        dobCell.textContent.trim() === '' || emailCell.textContent.trim() === '' || 
        phoneCell.textContent.trim() === '') {
        alert('Please fill in all fields before saving.');
        return;
    }

    // AJAX request to save data
    fetch('add_customer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            customerID: idCell.textContent.trim(),
            customerFName: fNameCell.textContent.trim(),
            customerLName: lNameCell.textContent.trim(),
            customerSex: sexCell.textContent.trim(),
            customerDOB: dobCell.textContent.trim(),
            customerEmail: emailCell.textContent.trim(),
            customerPhone: phoneCell.textContent.trim(),
        }),
    })
    .then(response => {
        // Check if the response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Customer added successfully!');
            // Optional: Reload the page or update the table
            location.reload();
        } else {
            // Log the full error for debugging
            console.error('Server error:', data);
            alert('Error adding customer: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('An error occurred while adding the customer. Please check the console for details.');
    });
}


    function editRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const fNameCell = row.cells[1];
        const lNameCell = row.cells[2];
        const sexCell = row.cells[3];
        const dobCell = row.cells[4];
        const emailCell = row.cells[5];
        const phoneCell = row.cells[6];

        // Make cells editable
        idCell.contentEditable = true;
        fNameCell.contentEditable = true;
        lNameCell.contentEditable = true;
        sexCell.contentEditable = true;
        dobCell.contentEditable = true;
        emailCell.contentEditable = true;
        phoneCell.contentEditable = true;

        // Change the Edit button to Save
        button.textContent = 'Save';
        button.setAttribute('onclick', 'saveRow(this)');
    }

    function removeRow(button) {
    const row = button.parentElement.parentElement;
    const customerID = row.cells[0].textContent.trim();

    if (confirm('Are you sure you want to delete this customer?')) {
        fetch('delete_customer.php', { // Call the new delete_customer.php
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ customerID })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Customer removed successfully!');
                row.parentNode.removeChild(row); // Remove row from DOM
            } else {
                alert('Error removing customer: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

    function findCustomer() {
        const idToFind = prompt("Enter Customer ID to find:");
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
            alert("Customer ID not found.");
        }
    }
</script>

</body>
</html>
