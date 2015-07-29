<?php session_start() ; ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php
    $player_needed = false;
    $game_data = find_all_games();
    while($game = mysqli_fetch_assoc($game_data)) {
         if($game_id != $game["game_id"]){
                $game_id = $game["game_id"];
                $player_needed = true;
        } else{
          $player_needed = false; 
         }
    }
    if($player_needed == true){
        //header("refresh: 1;");
    }
?>
<?php 
        $game_id = $_SESSION["game_id"]; 
        $id = $_SESSION["id"];
        $turn = "black";
        if($id == null || $game_id == null){
            redirect_to("../../home.html");   
        }
?>
<?php 
     $game_exists = fetch_game($game_id);
     if($game_exists === null){
         global $connection;
         
         $query = "INSERT INTO game (";
         $query .= " game_id, move1_row, move1_column, move2_row, move2_column, black_turn ";
         $query .= ") VALUES (";
         $query .= " {$game_id}, null, null, null, null, 1 ";
         $query .= ")";
         
         $data= mysqli_query($connection, $query);
         confirm_query($data); 
     }

?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Play Hnefatafl Online">
        <meta name="keywords" content="Hnefatafl, Tafl, Viking, Ancient, Board Game, Kings, Table, Scandinavian, Chess, Games Like Chess, Games Like Checkers">
        <meta name="author" content="Nadya Primak">
         <link rel="icon" href="../../images/favicon2.ico" type="image/gif" sizes="16x16"> 
    <title>HNEFATAFL</title>
        <link href='http://fonts.googleapis.com/css?family=IM+Fell+English' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../../css/game.css">
        <!--[if lte IE 8]><script type="text/javascript" src="../../js/excanvas.js"></script><![endif]-->
         <!--[if lt IE 9]>
    <script src="../../js/html5shiv/html5shiv.min.js"></script>
<![endif]-->
    </head>
<body>
    <div id="container">
       
        <div id="canvas-container">
            <div id="topbar">
                <img id="topleft" class="trim" src="../../images/trim2.jpg">
                <header><h1><a href="delete_game_id.php" onclick="return confirm('Leaving this page will cause you to forfeit the game. Are you sure?')">the game of hnefatafl</a></h1>
                </header>
                <img id="topright" class="trim" src="../../images/trim3.jpg">
            </div>
            <div id="player1" class="sidebar">
                <h2>Player 1</h2> 
                <div class="avatar">
                    <img src="../../images/avatars/<?php 
                        $avatar = get_avatar_by_gameid($game_id, 1);
                        if($avatar){
                            echo $avatar;
                        } else{
                            echo "noplayer";  
                        }
                     ?>.png" id="p1avatar">
                    <p class="nickname"><?php 
                        $nickname = find_nickname_by_gameid($game_id, 1);
                        if($nickname){
                            echo $nickname;
                        } else{
                            echo "waiting...";
                        }
                        ?></p>
                </div>
                <p class="timer">Black</p>
                <div class="update"></div>
                <form action="delete_game_id.php" method="post">
                <input class="playagain" type="submit" name="submit" value="Play Again?">
            </div>
            <canvas id="stage" width="600" height="600">Your browser does not support HTML5 Canvas Element. </canvas>
            <div id="player2" class="sidebar">
                <h2>Player 2</h2>
                <div class="avatar">
                    <img src="../../images/avatars/<?php 
                        $avatar = get_avatar_by_gameid($game_id, 0);
                        if($avatar){
                            echo $avatar;
                        } else{
                            echo "noplayer"; 
                        }
                     ?>.png" id="p2avatar">
                    <p class="nickname"><?php 
                        $nickname = find_nickname_by_gameid($game_id, 0);
                        if($nickname){
                            echo $nickname;
                        } else{
                            echo "waiting...";
                        }
                        ?></p>
                </div>
                <p class="timer">White</p>
                <div class="update"></div>
                <form action="delete_game_id.php" method="post">
                <input class="playagain" type="submit" name="submit" value="Play Again?">
            </div>
        </div>
           <div id="bottombar">
                <img id="bottomleft" class="trim" src="../../images/trim5.jpg">
                <footer>~<a href="delete_game_id.php?pageto=rules">Rules</a>~<a href="delete_game_id.php?pageto=about">About</a>~</footer>
                <img id="bottomright" class="trim" src="../../images/trim4.jpg">
        </div>
        <img id="wood" width="600" height="600" src="../../images/background.jpg" style="visibility:hidden">
    </div>
    <table id="hidden-table"></table><p id="black_turn"></p><p id="player_presence" style="visibility:hidden"></p>
            
    
</body>
        
        
<?php $nickname = find_nickname_by_id($id); ?>
    <?php include("game_mechanics.php"); ?>
    
 

</html>