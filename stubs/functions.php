<?php
function connect_to_db() {
    $host = "localhost:8889"; // replace with your host name
    $user = "root"; // replace with your database username
    $password = "root"; // replace with your database password
    $dbname = "Assignment_2"; // replace with your database name

    // create connection
    $conn = mysqli_connect($host, $user, $password, $dbname);

    // check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

