<?php session_start(); ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php

    $nickname_player1 = $_GET["nickname_player1"];
    $nickname_player2 = $_GET["nickname_player2"];

    $nickname1_exists = check_for_existing_nickname($nickname_player1);
    $nickname2_exists = check_for_existing_nickname($nickname_player2);

    if($nickname1_exists != null && $nickname2_exists != null){
        echo json_encode("both players present");   
    }
    if($nickname1_exists == null){
        echo json_encode("Player 1 has left.");   
    }
    if($nickname2_exists == null){
        echo json_encode("Player 2 has left.");  
    }
    

?>