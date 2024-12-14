<?php
include 'db_connect.php';

$sql = "SELECT * FROM Agent";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        <a href="Dashboard.php">Dashboard </a>
        <a href="Bookings.php">Bookings </a>
        <a href="Destination.php">Destination </a>
        <a href="Agents.php">Agents </a>
        <a href="Customer.php">Customers </a>
        <a href="Package.php">Package </a>
        <a href="Itinerary.php">Itinerary</a>
        <a href="Payment.php">Payments</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Agent Overview</h2>
            <button class="button" onclick="addRow()">Add Agent</button>
            <button class="button" onclick="findAgent()">Find ID</button>
        </div>

        <div class="table-container">
            <h2>Agent List</h2>
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>Agent ID</th>
                        <th>Agent Name</th>
                        <th>Agent Sex</th>
                        <th>Agent DOB</th>
                        <th>Agent Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["agentID"] . "</td>";
                        echo "<td>" . $row["agentName"] . "</td>";
                        echo "<td>" . $row["agentSex"] . "</td>";
                        echo "<td>" . $row["agentDOB"] . "</td>";
                        echo "<td>" . $row["agentPhone"] . "</td>";
                        echo "<td>
                                <button onclick='editRow(this)'>Edit</button>
                                <button onclick='removeRow(this)'>Remove</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No agents found</td></tr>";
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
            const sexCell = newRow.insertCell(2);
            const dobCell = newRow.insertCell(3);
            const phoneCell = newRow.insertCell(4);
            const actionCell = newRow.insertCell(5);

            idCell.contentEditable = true;
            nameCell.contentEditable = true;
            sexCell.contentEditable = true;
            dobCell.contentEditable = true;
            phoneCell.contentEditable = true;

            actionCell.innerHTML = `
            <button onclick="saveToDatabase(this)">Save</button>
            <button onclick="removeRow(this)">Remove</button>
            `;
        }

        function saveToDatabase(button) {
    const row = button.parentElement.parentElement;
    const idCell = row.cells[0].textContent.trim();
    const nameCell = row.cells[1].textContent.trim();
    const sexCell = row.cells[2].textContent.trim();
    const dobCell = row.cells[3].textContent.trim();
    const phoneCell = row.cells[4].textContent.trim();

    // Validate fields
    if (!idCell || !nameCell || !sexCell || !dobCell || !phoneCell) {
        alert('Please fill in all fields before saving.');
        return;
    }

    // AJAX request to save data
    fetch('add_agent.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            agentID: idCell,
            agentName: nameCell,
            agentSex: sexCell,
            agentDOB: dobCell,
            agentPhone: phoneCell,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Agent added successfully!');
            // Optionally, you can add the new row to the table dynamically
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${idCell}</td>
                <td>${nameCell}</td>
                <td>${sexCell}</td>
                <td>${dobCell}</td>
                <td>${phoneCell}</td>
                <td>
                    <button onclick='editRow(this)'>Edit</button>
                    <button onclick='removeRow(this)'>Remove</button>
                </td>
            `;
            document.getElementById('dataTable').getElementsByTagName('tbody')[0].appendChild(newRow);
        } else {
            alert('Error adding agent: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}


        function saveRow(button) {
            const row = button.parentElement.parentElement;
            const id = row.cells[0].textContent.trim();
            const name = row.cells[1].textContent.trim();
            const sex = row.cells[2].textContent.trim();
            const dob = row.cells[3].textContent.trim();
            const phone = row.cells[4].textContent.trim();

            if (id === '' || name === '' || sex === '' || dob === '' || phone === '') {
                alert('All fields are required!');
                return;
            }

            // Send data to backend via AJAX
            fetch('add_agent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    agentID: id,
                    agentName: name,
                    agentSex: sex,
                    agentDOB: dob,
                    agentPhone: phone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Agent saved successfully!');
                    
                    // Make cells non-editable
                    for (let i = 0; i < 5; i++) {
                        row.cells[i].contentEditable = false;
                    }

                    button.textContent = 'Edit';
                    button.setAttribute('onclick', 'editRow(this)');
                } else {
                    alert('Error saving agent: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function editRow(button) {
            const row = button.parentElement.parentElement;
            const idCell = row.cells[0];
            const nameCell = row.cells[1];
            const sexCell = row.cells[2];
            const dobCell = row.cells[3];
            const phoneCell = row.cells[4];

            // Make cells editable
            idCell.contentEditable = true;
            nameCell.contentEditable = true;
            sexCell.contentEditable = true;
            dobCell.contentEditable = true;
            phoneCell.contentEditable = true;

            // Change the Edit button to Save
            button.textContent = 'Save';
            button.setAttribute('onclick', 'saveRow(this)');
        }

        function removeRow(button) {
    const row = button.parentElement.parentElement;
    const agentID = row.cells[0].textContent.trim();

    if (confirm('Are you sure you want to delete this agent?')) {
        fetch('delete_agent.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ agentID })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Agent removed successfully!');
                row.parentNode.removeChild(row); // Remove row from DOM
            } else {
                alert('Error removing agent: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}


        function findAgent() {
            const idToFind = prompt("Enter Agent ID to find:");
            const rows = document.querySelectorAll('#dataTable tbody tr');
            let found = false;

            // Clear previous highlights
            rows.forEach(row => row.style.backgroundColor = '');

            // Search for the ID
            rows.forEach(row => {
                const idCell = row.cells[0];
                if (idCell.textContent.trim() === idToFind) {
                    row.style.backgroundColor = '#d1e7dd'; // Highlight found row
                    found = true;
                }
            });

            if (!found) {
                alert("Agent ID not found.");
            }
        }
    </script>

</body>
</html>
