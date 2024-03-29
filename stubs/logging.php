<?php
session_start();

// Import functions.php
require_once('functions.php');

// create connection
$conn = connect_to_db();

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // query the database to check if the user exists
    $user_query = "SELECT * FROM users WHERE user_email = '$email' AND password = '$password'";
    $user_result = mysqli_query($conn, $user_query);

    if (mysqli_num_rows($user_result) == 1) {
        // user authenticated, set session variable
        $_SESSION['user_email'] = $email;
        header("Location: home.php"); // redirect to home page
        exit();
    } else {
        // user not authenticated, display error message
        echo '<p>Invalid email or password. Please try again.</p>';
    }
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
if (isset($_SESSION['user_email'])) {
    header("Location: home.php");
    exit();
} elseif (mysqli_num_rows($public_statuses_result) == 0) {
    echo '<p>No public statuses to show. Please log in.</p>';
}
?>

<form action="logging.php" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <input type="submit" name="submit" value="Log-In">
</form>

<h2>Public Statuses:</h2>

<?php
while ($row = mysqli_fetch_assoc($public_statuses_result)) {
    echo '<div class="status">';
    echo '<p class="status-text">' . $row['status_text'] . '</p>';
    echo '<p class="user-email">Posted By: ' . $row['user_email'] . '</p>';
    echo '</div>';
}

mysqli_close($conn);
?>

</body>
</html>
