<?php session_start(); ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php
    global $connection;
    $id = (int) $_SESSION["id"];
    $game_id = (int) $_SESSION["game_id"]; 
    $gameover = $_GET["gameover"];


    $query = "DELETE FROM player ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";

    $result1 = mysqli_query($connection, $query);

    $all_games = find_all_games();  //is actually finding all players with games, not all games

    $delete_game = true;
    if($all_games !== null){
        while($game = mysqli_fetch_assoc($all_games)){
            if($game["game_id"] == $game_id){  //if there is a player with this game id still existing, don't delete the game yet
                   $delete_game = false;
            }
           
        }
    }

    if($delete_game == true ){
        $query = "DELETE FROM game ";
        $query .= "WHERE game_id = {$game_id} ";
        $query .= "LIMIT 1";

        $result2 = mysqli_query($connection, $query);
        
    }
    
    $_SESSION["id"] = null;
    $_SESSION["game_id"] = null;

    $pageto = $_GET["pageto"];

    mysqli_close($connection);

    if($pageto == "rules"){
         redirect_to("../../rules.html");
    }

    if($pageto == "about"){
        redirect_to("../../about.html");
    }
    else{
        redirect_to("../../home.html");
    }

   

?>