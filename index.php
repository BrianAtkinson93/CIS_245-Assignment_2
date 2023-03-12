<?php

// import functions.php
require_once('stubs/functions.php');

session_start();
if (isset($_SESSION['user_email'])) {
    header("home.php");
    exit();
}

// create connection
$conn = connect_to_db();

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// query the database for public statuses
$public_statuses_query = "SELECT * FROM status WHERE status_privacy = 1";
// execute query and display public statuses
$public_statuses_result = mysqli_query($conn, $public_statuses_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assignment_3</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header class="header">
    <h1>Assignment 3</h1>
    <?php
    if (isset($_SESSION['user_email'])) {
        echo '<form action="stubs/logout.php" method="post">';
        echo '<p>Welcome, ' . $_SESSION['user_email'] . '!</p>';
        echo '<input type="submit" name="logout" value="Log-Out">';
        echo '</form>';
    } else {
        echo '<form action="stubs/logging.php" method="post">';
        echo '<label for="email">Email:</label>';
        echo '<input type="email" id="email" name="email" required>';
        echo '<label for="password">Password:</label>';
        echo '<input type="password" id="password" name="password" required>';
        echo '<input type="submit" name="submit" value="Log-In">';
        echo '</form>';
    }
    ?>
</header>

<div class="content-container">
    <div class="left-column">
        <h2>Public Statuses:</h2>
        <?php
        if (mysqli_num_rows($public_statuses_result) == 0) {
            echo '<p>No public statuses to show. Please log in.</p>';
        } else {
            while ($row = mysqli_fetch_assoc($public_statuses_result)) {
                echo '<div class="status">';
                echo '<p class="status-text">' . $row['status_text'] . '</p>';
                echo '<p class="user-email">Posted By: ' . $row['user_email'] . '</p>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>

</body>

</html>
