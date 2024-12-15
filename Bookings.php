<?php
include 'db_connect.php'; // Include your database connection file

// Fetch bookings from the database
$sql = "SELECT * FROM Booking"; // Adjust the table name as per your database
$result = $conn->query($sql);

// Fetch foreign key values
$agents = $conn->query("SELECT agentID, agentName FROM Agent");
$customers = $conn->query("SELECT customerID, customerFName FROM Customer");
$packages = $conn->query("SELECT packageID, packageName FROM Package");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="tvm.css">

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
    <div class="form-container">
        <h2>Booking </h2>
        <button class="button" onclick="addRow()">Add Booking</button>
        <button class="button" onclick="findBooking()">Find ID</button>
    </div>

    <div class="table-container">
        <h2>Booking List</h2>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Agent ID</th>
                    <th>Customer ID</th>
                    <th>Package ID</th>
                    <th>Booked Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["bookingID"] . "</td>";
                    echo "<td>" . $row["agentID"] . "</td>";
                    echo "<td>" . $row["customerID"] . "</td>";
                    echo "<td>" . $row["packageID"] . "</td>";
                    echo "<td>" . $row["bookedDate"] . "</td>";
                    echo "<td>
                            <button onclick='editRow(this)'>Edit</button>
                            <button onclick='removeRow(this)'>Remove</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No bookings found</td></tr>";
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
        const agentCell = newRow.insertCell(1);
        const customerCell = newRow.insertCell(2);
        const packageCell = newRow.insertCell(3);
        const bookedDateCell = newRow.insertCell(4);
        const actionCell = newRow.insertCell(5);

        // Make cells editable
        idCell.contentEditable = true;
        agentCell.contentEditable = true;
        customerCell.contentEditable = true;
        packageCell.contentEditable = true;
        bookedDateCell.contentEditable = true;

        idCell.textContent = ''; // Start with empty cells
        agentCell.textContent = '';
        customerCell.textContent = '';
        packageCell.textContent = '';
        bookedDateCell.textContent = '';

        actionCell.innerHTML = `
            <button onclick="saveRow(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
        `;
    }

    function saveRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const agentCell = row.cells[1];
        const customerCell = row.cells[2];
        const packageCell = row.cells[3];
        const bookedDateCell = row.cells[4];

        // Check if the cells are filled
        if (idCell.textContent.trim() === '' || agentCell.textContent.trim() === '' || 
            customerCell.textContent.trim() === '' || packageCell.textContent.trim() === '' || 
            bookedDateCell.textContent.trim() === '') {
            alert('Please fill in all fields before saving.');
            return;
        }

        const data = {
            bookingID: idCell.textContent.trim(),
            agentID: agentCell.textContent.trim(),
            customerID: customerCell.textContent.trim(),
            packageID: packageCell.textContent.trim(),
            bookedDate: bookedDateCell.textContent.trim(),
        };

        fetch('add_booking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking added successfully!');
                // Optionally refresh the page or update the table
            } else {
                alert('Error adding booking: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please try again.');
        });

        // Make cells non-editable
        idCell.contentEditable = false;
        agentCell.contentEditable = false;
        customerCell.contentEditable = false;
        packageCell.contentEditable = false;
        bookedDateCell.contentEditable = false;

        // Change the Save button to Edit
        button.textContent = 'Edit';
        button.setAttribute('onclick', 'editRow(this)');
    }

    function editRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const agentCell = row.cells[1];
        const customerCell = row.cells[2];
        const packageCell = row.cells[3];
        const bookedDateCell = row.cells[4];

        // Make cells editable
        idCell.contentEditable = true;
        agentCell.contentEditable = true;
        customerCell.contentEditable = true;
        packageCell.contentEditable = true;
        bookedDateCell.contentEditable = true;

        // Change the Edit button to Save
        button.textContent = 'Save';
        button.setAttribute('onclick', 'saveRow(this)');
    }

    function removeRow(button) {
        const row = button.parentElement.parentElement;
        const bookingID = row.cells[0].textContent.trim();

        if (confirm('Are you sure you want to delete this booking?')) {
            fetch('delete_booking.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ bookingID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking removed successfully!');
                    row.parentNode.removeChild(row); // Remove row from DOM
                } else {
                    alert('Error removing booking: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function findBooking() {
        const idToFind = prompt("Enter Booking ID to find:");
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
            alert("Booking ID not found.");
        }
    }
</script>

</body>
</html>
