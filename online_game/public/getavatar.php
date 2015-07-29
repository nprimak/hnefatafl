<?php session_start() ; ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php

    $playernum = $_GET["playernum"];
    $game_id = $_SESSION["game_id"];

   $avatar = (int) get_avatar_by_gameid($game_id, $playernum);

if($avatar != 0){
    echo $avatar;
}
if($avatar == 0){
    echo "noplayer";
}


?>