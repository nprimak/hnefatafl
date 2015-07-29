<?php session_start() ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php 

    $game_id = $_SESSION["game_id"];
    $game_data = fetch_game($game_id);

    $turn = $game_data["black_turn"];

    echo $turn;
?>