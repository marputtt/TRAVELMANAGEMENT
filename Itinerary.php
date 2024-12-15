<?php
include 'db_connect.php'; // Include your database connection file
// Fetch itineraries from the database
$sql = "SELECT * FROM Itinerary"; // Adjust the table name as per your database
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itinerary Dashboard</title>
    
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
        <h2>Itinerary</h2>
        <button class="button" onclick="addRow()">Add Itinerary</button>
        <button class="button" onclick="findItinerary()">Find ID</button>
    </div>

    <div class="table-container">
        <h2>Itinerary List</h2>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Itinerary ID</th>
                    <th>Day</th>
                    <th>Activity</th>
                    <th>Transport</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["itineraryID"] . "</td>";
                    echo "<td>" . $row["itineraryDay"] . "</td>";
                    echo "<td>" . $row["itineraryActivity"] . "</td>";
                    echo "<td>" . $row["itineraryTransport"] . "</td>";
                    echo "<td>
                            <button onclick='editRow(this)'>Edit</button>
                            <button onclick='removeRow(this)'>Remove</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No itineraries found</td></tr>";
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
        const dayCell = newRow.insertCell(1);
        const activityCell = newRow.insertCell(2);
        const transportCell = newRow.insertCell(3);
        const actionCell = newRow.insertCell(4);

        // Make cells editable
        idCell.contentEditable = true;
        dayCell.contentEditable = true;
        activityCell.contentEditable = true;
        transportCell.contentEditable = true;

        idCell.textContent = ''; // Start with empty cells
        dayCell.textContent = '';
        activityCell.textContent = '';
        transportCell.textContent = '';

        actionCell.innerHTML = `
            <button onclick="saveRow(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
        `;
    }

    function saveRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const dayCell = row.cells[1];
        const activityCell = row.cells[2];
        const transportCell = row.cells[3];

        // Check if the cells are filled
        if (idCell.textContent.trim() === '' || dayCell.textContent.trim() === '' || 
            activityCell.textContent.trim() === '' || transportCell.textContent.trim() === '') {
            alert('Please fill in all fields before saving.');
            return;
        }

        // AJAX request to save data
        fetch('add_itinerary.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                itineraryID: idCell.textContent.trim(),
                itineraryDay: dayCell.textContent.trim(),
                itineraryActivity: activityCell.textContent.trim(),
                itineraryTransport: transportCell.textContent.trim(),
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Itinerary added successfully!');
                location.reload(); // Reload the page to see the new itinerary
            } else {
                alert('Error adding itinerary: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('An error occurred while adding the itinerary. Please check the console for details.');
        });
    }

    function editRow(button) {
        const row = button.parentElement.parentElement;
        const idCell = row.cells[0];
        const dayCell = row.cells[1];
        const activityCell = row.cells[2];
        const transportCell = row.cells[3];

        // Make cells editable
        idCell.contentEditable = true;
        dayCell.contentEditable = true;
        activityCell.contentEditable = true;
        transportCell.contentEditable = true;

        // Change the Edit button to Save
        button.textContent = 'Save';
        button.setAttribute('onclick', 'saveRow(this)');
    }

    function removeRow(button) {
        const row = button.parentElement.parentElement;
        const itineraryID = row.cells[0].textContent.trim();
        const itineraryDay = row.cells[1].textContent.trim();

        if (confirm('Are you sure you want to delete this itinerary?')) {
            fetch('delete_itinerary.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ itineraryID, itineraryDay })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Itinerary removed successfully!');
                    row.parentNode.removeChild(row); // Remove row from DOM
                } else {
                    alert('Error removing itinerary: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function findItinerary() {
        const idToFind = prompt("Enter Itinerary ID to find:");
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
            alert("Itinerary ID not found.");
        }
    }
</script>

</body>
</html>
