<?php session_start() ?>
<?php require_once("../private/db_connection.php"); ?>
<?php require_once("../private/functions.php"); ?>
<?php
    $game_id = $_SESSION["game_id"];
    $game_data = fetch_game($game_id);

    $oldx = $game_data["move1_row"];
    $oldy = $game_data["move1_column"];
    $x = $game_data["move2_row"];
    $y = $game_data["move2_column"];

    echo "<tr>
            <th>OldX</th>
            <th>OldY</th>
            <th>X</th>
            <th>Y</th>
            </tr>";

    echo "<tr>
         <td>" . $oldx . "</td>
         <td>" . $oldy. "</td>;
         <td>" . $x . "</td>
         <td>" . $y . "</td>
        </tr>";
    


?>