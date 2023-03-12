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
    echo "<h1>Welcome, $user_email!</h1>";
    echo "<form action='logout.php' method='post'><input type='submit' value='Log out'></form>";

    // Display the status form
    echo "<h2>Add a new status:</h2>";
    echo "<form action='' method='post'>";
    echo "<label for='status_text'>Status text:</label>";
    echo "<input type='text' id='status_text' name='status_text' required>";
    echo "<br>";
    echo "<label for='status_privacy'>Private:</label>";
    echo "<input type='checkbox' id='status_privacy' name='status_privacy'>";
    echo "<br>";
    echo "<input type='submit' value='Post'>";
    echo "</form>";

    // Display the timeline
    echo "<h2>Your timeline:</h2>";
    $timeline_query = "SELECT * FROM status WHERE user_email='$user_email'";
    $timeline_result = mysqli_query($conn, $timeline_query);
    while ($row = mysqli_fetch_assoc($timeline_result)) {
        echo "<div class='status'>";
        echo "<p class='status-text'>" . $row['status_text'] . "</p>";
        echo "<p class='status-privacy'>" . ($row['status_privacy'] ? "Public" : "Private") . "</p>";
        echo "</div>";
    }

    // Close the database connection
    mysqli_close($conn);

} else {
    // User is not logged in, redirect to the login page
    header("Location: logging.php");
    exit();
}
