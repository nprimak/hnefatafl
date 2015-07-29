<?php
    define('DB_SERVER', 'mysql.play-hnefatafl.com');
    define('DB_USER', 'nadyaprimakcom');
    define('DB_PASS', 'Alatecra6');
    define('DB_NAME', 'hnefatafl');


    //1. Create a database connection
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    // Test if connection occurred.
    if(mysqli_connect_errno() ) {
     die("Database connection failed: " .
         mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")"
        );
    }
?>