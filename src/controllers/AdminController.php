<?php
// Ensure only admins can access this page
session_start();
if ($_SESSION['role'] != 'admin') {
    echo "You do not have permission to access this page.";
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "root", "test");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch users and their roles
$query = "SELECT id, name, email, role FROM users";
$result = mysqli_query($conn, $query);

echo "<h2>Manage User Roles</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<form method='POST' action=''>";
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Change Role</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['role']}</td>
                <td>
                    <select name='role_{$row['id']}'>
                        <option value='user' " . ($row['role'] == 'user' ? 'selected' : '') . ">User</option>
                        <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                    </select>
                </td>
            </tr>";
    }

    echo "</table>";
    echo "<input type='submit' name='update_roles' value='Update Roles'>";
    echo "</form>";
}

// Update roles if the form is submitted
if (isset($_POST['update_roles'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'role_') === 0) {
            // Extract user ID from the field name
            $userId = substr($key, 5);
            $newRole = $value;

            // Update role in the database
            $updateQuery = "UPDATE users SET role = '$newRole' WHERE id = $userId";
            if (mysqli_query($conn, $updateQuery)) {
                echo "Role updated successfully for user with ID $userId.";
            } else {
                echo "Error updating role: " . mysqli_error($conn);
            }
        }
    }
}

mysqli_close($conn);
?>
