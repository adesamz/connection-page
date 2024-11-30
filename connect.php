<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create a database connection
$con = new mysqli('localhost', 'root', '', 'campus');

// Check if the connection was successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// If the form is submitted, handle the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $frist = $_POST['frist'];
    $last = $_POST['last'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];

    // Check if the username or email already exists
    $checkQuery = $con->prepare("SELECT * FROM `forms` WHERE username = ? OR email = ?");
    $checkQuery->bind_param("ss", $username, $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists. Please use a different one.";
    } else {
        // Insert the new data into the database
        $sql = "INSERT INTO `forms` (username, email, password, first, last, phone, gender, birthday)
                VALUES ('$username', '$email', '$password', '$frist', '$last', '$phone', '$gender', '$birthday')";

        if ($con->query($sql) === TRUE) {
            // Redirect to sign-in page after successful registration
            header("Location: sign in.php");
            exit(); // Stop further script execution after the redirect
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    // Close the prepared statement and the connection
    $checkQuery->close();
    $con->close();
}
?>
