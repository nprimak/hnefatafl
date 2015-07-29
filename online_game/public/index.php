<?php 

    $did_it_start = my_session_start();

    function my_session_start(){
        $sn = session_name();
      if (isset($_COOKIE[$sn])) {
          $sessid = $_COOKIE[$sn];
      } else if (isset($_GET[$sn])) {
          $sessid = $_GET[$sn];
      } else {
          return session_start();
      }

     if (!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)) {
          return false;
      }
      return session_start();
    }

    if ( !my_session_start() ) {
    session_id( uniqid() );
    session_start();
    session_regenerate_id();
    
}  ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/validation_functions.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php 
    if (isset($_POST["submit"])){
        
        $required_fields = array("nickname", "avatar");
        validate_presence($required_fields);
        
        $fields_with_max_lengths = array("nickname" => 15);
        validate_max_lengths($fields_with_max_lengths);
        
        $nickname_double = check_for_existing_nickname($_POST["nickname"]);
        
        if($nickname_double == $_POST["nickname"]){
            $errors[] = "Nickname '" . $_POST["nickname"] . "' already exists. Please choose another.";   
        }
        
       
        if(empty($errors)) {
            $nickname = mysql_prep($_POST["nickname"]);
            $avatar = (int) $_POST["avatar"];

            $query = "INSERT INTO player (";
            $query .= " game_id, nickname, avatar, player1 ";
            $query .= ") VALUES (";
            $query .= " null, '{$nickname}', {$avatar} , null ";
            $query .= ")";
            
            $result = mysqli_query($connection, $query);
            
            
            if($result) {
                $_SESSION["id"] = get_last_player_id();
                redirect_to("setupgame.php");
            } else {
                $message = "User Creation Failed." ;
            }   
        }
    } else{
        $_SESSION["id"] = null;
        
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
         <!--[if lt IE 9]>
    <script src="../../js/html5shiv/html5shiv.min.js"></script>
<![endif]-->
    </head>
    <body>
        <div id="container">
       
        <div id="canvas-container">
            <div id="topbar">
                <img id="topleft" class="trim" src="../../images/trim2.jpg">
                <header><h1><a href="../../home.html">the game of hnefatafl</a></h1>
                </header>
                <img id="topright" class="trim" src="../../images/trim3.jpg">
            </div>
            <div id="player1" class="sidebar">
                <h2>Player 1</h2> 
                <div class="avatar">
                    <img src="../../images/avatars/noplayer.png">
                    <p><strong><?php 
                            echo "waiting...";
                        
                        ?></p></strong>
                </div>
                <p class="timer">Black</p>
                <div class="update"></div>
            </div>
<div id="center">
<?php if(isset($message)) {
                $output = htmlentities($message);
                echo $output;
            } 
?>
<form action="index.php" method="post">
            Nickname: <input type="text" name="nickname">
            <br>
            <br>
            Avatar: <select name="avatar">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    </select>
            <br>
    <br>
    
            <table width="350">
                <tr>
                    <td>1</td>	
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td><img src="../../images/avatars/1.png"></td>	
                    <td><img src="../../images/avatars/2.png"></td>		
                    <td><img src="../../images/avatars/3.png"></td>
                    <td><img src="../../images/avatars/4.png"></td>
                    <td><img src="../../images/avatars/5.png"></td>
                </tr>
                <tr>
                    <td>6</td>	
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td><img src="../../images/avatars/6.png"></td>	
                    <td><img src="../../images/avatars/7.png"></td>		
                    <td><img src="../../images/avatars/8.png"></td>
                    <td><img src="../../images/avatars/9.png"></td>
                    <td><img src="../../images/avatars/10.png"></td>
            </tr>
        </table>
             <br>
            <input class="btn" type="submit" name="submit" value="Play Game!">
    <br>
    <p class="playercount">
    <?php 
            $player_count = count_players();
            $output = $player_count + 1;
            $output .= " players (including you) are currently online.";
            echo $output;
    
    ?> </p>
    
</form>
</div>
<div id="player2" class="sidebar">
                <h2>Player 2</h2>
                <div class="avatar">
                   <img src="../../images/avatars/noplayer.png">
                    <strong> <p><?php 
                            echo "waiting...";
                        ?></p></strong>
                </div>
                <p class="timer">White</p>
                <div class="update"></div>

        </div>
           <div id="bottombar">
                <img id="bottomleft" class="trim" src="../../images/trim5.jpg">
                <footer>~<a href="delete_game_id.php?pageto=rules">Rules</a>~<a href="delete_game_id.php?pageto=about">About</a>~</footer>
                <img id="bottomright" class="trim" src="../../images/trim4.jpg">
        </div>
    </div>
        <?php echo $did_it_start ?>
    </body>
</html>

<?php echo form_errors($errors); ?>
