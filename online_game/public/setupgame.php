<?php session_start(); ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/validation_functions.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php
    $player_id = (int) $_SESSION["id"];
    $game_id = null;
    $p2_black = null;
    $p1_black = null;
    $player2_needed = false;
    $game_data = find_all_games();
    if($game_data !== null){
        while($game = mysqli_fetch_assoc($game_data)) {
            if($game_id != $game["game_id"]){  //make sure game id doesn't already exist
                $game_id = $game["game_id"];
                $player2_needed = true;
            } else{
                $player2_needed = false;
            }
        
        }
    }

    //IF THERE IS A GAME_ID WITH ONLY ONE PLAYER, ADD PLAYER TO THAT GAME_ID
    if($player2_needed === true){
         $p1_black = is_player1_black($game_id);
         if($p1_black == 0){
                $p2_black = 1; 
            //p1 is not black
        }
          if($p1_black == 1){
                $p2_black = 0;   
             // p1 is black
          }
        update_player_game($game_id, $player_id, $p2_black);
        
        $_SESSION["game_id"] = $game_id;
        redirect_to('game.php');
    }
    if($player2_needed === false){
            // IF THERE IS NO GAME ID WITH ONLY 1 PLAYER, THEN CREATE A NEW GAME ID
            $p1_black = mt_rand(0,1);
            $game_id = find_last_game();
            if($game_id === null){
                $game_id = 1;
                update_player_game($game_id, $player_id, $p1_black);
            }
            else{
                $game_id++;
                update_player_game($game_id, $player_id, $p1_black);
            }
            $_SESSION["game_id"] = $game_id;
            redirect_to('game.php');
    
        
        //GAME ID MUST BE DEFINED WHEN ONE PLAYER JOINS
            
    }
        

?>