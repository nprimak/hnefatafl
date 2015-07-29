<?php session_start() ; ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php

    $playernum = $_GET["playernum"];
    $game_id = $_SESSION["game_id"];

    $nickname = find_nickname_by_gameid($game_id, $playernum);
    if($nickname != null){
        echo $nickname;
    }
    else{
        echo 'waiting...';
    }


?>