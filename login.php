<?php
// Include the connection file to connect to the database
include('include/connection.php');

// Start session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $email = $_POST['txtEmail'];
    $password = $_POST['txtpass'];

    // Query to check if the email exists in the database
    $sql = "SELECT * FROM user_login WHERE userid = ?";
    
    // Prepare and execute the query
    $stmt = sqlsrv_prepare($conn, $sql, array($email));

    if (sqlsrv_execute($stmt)) {
        // Fetch user data if the email exists
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Directly compare the entered password with the stored password
            if ($password == $row['user_password']) {
                // Password is correct, store session data
                $_SESSION['user'] = $row['userid'];
                echo "success"; // Return success message
            } else {
                echo "Invalid email or password."; // Password mismatch
            }
        } else {
            echo "Invalid email or password."; // Email not found
        }
    } else {
        echo "Error in database query."; // Query failure
    }

    // Close the database connection
    sqlsrv_close($conn);
}
?>
