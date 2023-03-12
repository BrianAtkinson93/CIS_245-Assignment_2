<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page Title</title>
    <meta name="description" content="Assignment_2">
    <meta name="keywords" content="CIS, Assignment_2, Brian, Atkinson, UFV">
    <meta name="author" content="Brian Atkinson">
    <link rel="stylesheet" href="../css/style.css">
<!--    <link rel="icon" href="path/to/favicon.ico">-->
</head>
<body>

<?php
// Start the session
session_start();

// Import functions.php
require_once('functions.php');

// Check if the user is logged in
if (isset($_SESSION['user_email'])) {
    // User is logged in, display the homepage
    $user_email = $_SESSION['user_email'];

    // Connect to Database
    $conn = connect_to_db();

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the user has submitted the status form
    if (isset($_POST['status_text'])) {
        // Get the status text and privacy setting from the form
        $status_text = $_POST['status_text'];
        $status_privacy = isset($_POST['status_privacy']) ? 0 : 1;

        // Insert the new status into the database
        $insert_query = "INSERT INTO status (user_email, status_text, status_privacy) VALUES ('$user_email', '$status_text', '$status_privacy')";
        mysqli_query($conn, $insert_query);
    }

    // Display the welcome message and logout button
    echo "<div class='header'>";
    echo "<h1>Welcome, $user_email!</h1>";
    echo "<form action='logout.php' method='post'><input type='submit' value='Log out'></form>";
    echo "</div>";

    // Display the new post form
    echo "<div class='new-post'>";
    echo "<h2>Add a new post:</h2>";
    echo "<form action='' method='post'>";
    echo "<label for='status_text'>Post text:</label>";
    echo "<input type='text' id='status_text' name='status_text' required>";
    echo "<br>";
    echo "<label for='status_privacy'>Private:</label>";
    echo "<input type='checkbox' id='status_privacy' name='status_privacy'>";
    echo "<br>";
    echo "<input type='submit' value='Post'>";
    echo "</form>";
    echo "</div>";

    // Display the timeline statuses
    echo "<div class='content-container'>";
    echo "<div class='left-column'>";
    echo "<h2>Your timeline:</h2>";
    $timeline_query = "SELECT * FROM status WHERE user_email='$user_email'";
    $timeline_result = mysqli_query($conn, $timeline_query);
    while ($row = mysqli_fetch_assoc($timeline_result)) {
        echo "<div class='status " . ($row['status_privacy'] ? "private" : "public") . "'>";
        echo "<p class='status-text'>" . $row['status_text'] . "</p>";
        echo "</div>";
    }
    echo "</div>";

    // Display the public statuses column
    echo "<div class='right-column'>";
    echo "<h2>Public statuses:</h2>";
    $public_statuses_query = "SELECT * FROM status WHERE status_privacy=1";
    $public_statuses_result = mysqli_query($conn, $public_statuses_query);
    while ($row = mysqli_fetch_assoc($public_statuses_result)) {
        echo "<div class='status public'>";
        echo "<p class='status-text'>" . $row['status_text'] . "</p>";
        echo "<p class='status-user'>" . $row['user_email'] . "</p>";
        echo "</div>";
    }
    echo "</div>";

    // Display the public statuses column
//    echo "<div class='public-statuses'>";
//    echo "<h2>Public statuses:</h2>";
//    $public_statuses_query = "SELECT * FROM status WHERE status_privacy=1";
//    $public_statuses_result = mysqli_query($conn, $public_statuses_query);
//    while ($row = mysqli_fetch_assoc($public_statuses_result)) {
//        echo "<div class='status public'>";
//        echo "<p class='status-text'>" . $row['status_text'] . "</p>";
//        echo "<p class='status-user'>" . $row['user_email'] . "</p>";
//        echo "</div>";
//    }
//    echo "</div>";

    // Close the database connection
    mysqli_close($conn);

} else {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php");
    exit();
}
?>

</body>
</html>

