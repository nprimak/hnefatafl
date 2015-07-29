<?php session_start() ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php 
    $game_id = $_SESSION["game_id"];
    $OldX = $_GET["OldX"];
    $OldY = $_GET["OldY"];
    $X = $_GET["X"];
    $Y = $_GET["Y"];

    global $connection;
    
    $query = "UPDATE game SET ";  
    $query .= "move1_row = {$OldX}, "; 
    $query .= "move1_column = {$OldY}, ";
    $query .= "move2_row = {$X}, "; 
    $query .= "move2_column = {$Y} ";
    $query .= "WHERE game_id = {$game_id} ";  
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);

?>