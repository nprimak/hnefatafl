<?php require_once("../online_game/private/validation_functions.php"); ?>
<?php require_once("../online_game/private/functions.php"); ?>
<?php 
    if (isset($_POST["submit"])){
        
        $required_fields = array("p1nickname", "p2nickname");
        validate_presence($required_fields);
        
        $fields_with_max_lengths = array("p1nickname" => 15, "p2nickname" => 15);
        validate_max_lengths($fields_with_max_lengths);
        
       
        if(empty($errors)) {
                $p1nickname = htmlentities($_POST["p1nickname"]);
                $p2nickname = htmlentities($_POST["p2nickname"]);
                $p1avatar = $_POST["p1avatar"];
                $p2avatar = $_POST["p2avatar"];
                redirect_to("game.php?p1avatar={$p1avatar}&p1nickname={$p1nickname}&p2nickname={$p2nickname}&p2avatar={$p2avatar}");
              
        }
    }
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Play Hnefatafl Online">
        <meta name="keywords" content="Hnefatafl, Tafl, Viking, Ancient, Board Game, Kings, Table, Scandinavian, Chess, Games Like Chess, Games Like Checkers">
        <meta name="author" content="Nadya Primak">
         <link rel="icon" href="../images/favicon2.ico" type="image/gif" sizes="16x16"> 
        <title>HNEFATAFL</title>
        <link href='http://fonts.googleapis.com/css?family=IM+Fell+English' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/game.css">
         <!--[if lt IE 9]>
    <script src="../js/html5shiv/html5shiv.min.js"></script>
<![endif]-->
    </head>
    <body>
        <div id="container">
       
        <div id="canvas-container">
            <div id="topbar">
                <img id="topleft" class="trim" src="../images/trim2.jpg">
                 <header><h1><a href="../home.html">the game of hnefatafl</a></h1>
                </header>
                <img id="topright" class="trim" src="../images/trim3.jpg">
            </div>
            <div id="player1" class="sidebar">
                <h2>Player 1</h2> 
                <div class="avatar">
                    <img src="../images/avatars/noplayer.png">
                    <p><strong><?php 
                            echo "waiting...";
                        
                        ?></p></strong>
                </div>
                <p class="timer">Black</p>
                <div class="update"></div>
            </div>
        <div id="center">
            <form action="setup.php" method="post" style="padding-top:10px;">
                <h2>Player 1</h2>
            Nickname: <input type="text" name="p1nickname">
            &nbsp;&nbsp;Avatar: <select name="p1avatar">
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
               <h2> Player 2</h2>
 
              Nickname: <input type="text" name="p2nickname">
              &nbsp;&nbsp;Avatar: <select name="p2avatar">
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
    <br><br>
            <table width="350">
                <tr>
                    <td>1</td>	
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td><img src="../images/avatars/1.png"></td>	
                    <td><img src="../images/avatars/2.png"></td>		
                    <td><img src="../images/avatars/3.png"></td>
                    <td><img src="../images/avatars/4.png"></td>
                    <td><img src="../images/avatars/5.png"></td>
                </tr>
                <tr>
                    <td>6</td>	
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td><img src="../images/avatars/6.png"></td>	
                    <td><img src="../images/avatars/7.png"></td>		
                    <td><img src="../images/avatars/8.png"></td>
                    <td><img src="../images/avatars/9.png"></td>
                    <td><img src="../images/avatars/10.png"></td>
            </tr>
        </table>
             <br>
            <input class="btn" type="submit" name="submit" value="Play Game!">
    <br>
    
</form>
</div>
<div id="player2" class="sidebar">
                <h2>Player 2</h2>
                <div class="avatar">
                   <img src="../images/avatars/noplayer.png">
                    <strong> <p><?php 
                            echo "waiting...";
                        ?></p></strong>
                </div>
                <p class="timer">White</p>
                <div class="update"></div>

        </div>
          <div id="bottombar">
                <img id="bottomleft" class="trim" src="../images/trim5.jpg">
                <footer>~<a href="../rules.html">Rules</a>~<a href="../about.html">About</a>~</footer>
                <img id="bottomright" class="trim" src="../images/trim4.jpg">
        </div>
    </div>
    </body>
</html>
<?php echo form_errors($errors); ?>