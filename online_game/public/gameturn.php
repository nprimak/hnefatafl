<?php session_start() ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php 
    $game_id = $_SESSION["game_id"];
    $turn = $_GET["turn"];
    if($turn == "black"){
        $turn = 1;   
    }
    if($turn == "white"){
        $turn = 0;   
    }

    global $connection;
    
    $query = "UPDATE game SET ";  
    $query .= "game_id = {$game_id}, ";  
    $query .= "black_turn = {$turn} ";
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);

?>