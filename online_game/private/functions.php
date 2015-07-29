<?php 

function redirect_to($new_location) {
    header("Location: " . $new_location);
    exit;
}

function mysql_prep($string) {
    global $connection;
    
    $escaped_string = mysqli_real_escape_string($connection, $string);
    return $escaped_string;
    
}

function confirm_query($result_set){
 if(!$result_set) {
    die("Database query failed."); 
 }
}

function find_all_players_id() {
    global $connection;
    
    $query ="SELECT * ";
    $query .= "FROM player ";
   // $query .= "ORDER BY position ASC";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set); 
    return $subject_set;
}


function get_last_player_id(){ 
    global $connection;
    
    $query ="SELECT id ";
    $query .= "FROM player ";
    $query .= "ORDER BY id DESC ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set);
    if($data){
        return $data["id"];
    } else {
        return null;   
    }
    
}

function update_player_game($game, $id, $is_black){
     global $connection;
    $query = "UPDATE player SET ";  
    if($game !== null){
        $query .= "game_id = {$game}, ";  
    }
    $query .= "player1 = {$is_black} "; 
    $query .= "WHERE id = {$id}";
    
    $result = mysqli_query($connection, $query);
    confirm_query($result);
    
}

function count_players(){ 
    global $connection;
    
    $query = "SELECT count(*) as total FROM player";
    $player_count = mysqli_query($connection, $query);
    confirm_query($player_count); 
    $data = mysqli_fetch_assoc($player_count);
    if($data){
        return $data['total'];
    } else {
        return null;   
    }
    
}

function find_player_without_game($current_player){
    global $connection;
    
    $query = "SELECT id ";
    $query .= "FROM player ";
    $query .= "WHERE game_id IS NULL ";
    $query .= "AND nickname IS NOT NULL ";
    $query .= "AND id <> {$current_player} ";
    $query .= "ORDER BY id ASC ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["id"];
    } else {
        return null;   
    }
    
    
}

function find_nickname_by_gameid($id, $is_black){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT nickname ";
    $query .= "FROM player ";
    $query .= "WHERE game_id = {$id} ";
    $query .= "AND player1 = {$is_black} ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["nickname"];
    } else {
        return null;   
    }
    
}

function find_nickname_by_id($id){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT nickname ";
    $query .= "FROM player ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["nickname"];
    } else {
        return null;   
    }
    
}

function check_for_existing_nickname($nickname){
    global $connection;
    
    $query = "SELECT nickname ";
    $query .= "FROM player ";
    $query .= "WHERE nickname = '{$nickname}' ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["nickname"];
    } else {
        return null;   
    }
    
}

function get_avatar_by_gameid($id, $is_black){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT avatar ";
    $query .= "FROM player ";
    $query .= "WHERE game_id = {$id} ";
    $query .= "AND player1 = {$is_black} ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["avatar"];
    } else {
        return null;   
    }
}



function find_last_game(){
    global $connection;
    
    $query ="SELECT game_id ";
    $query .= "FROM player ";
    $query .= "WHERE game_id IS NOT NULL ";
    $query .= "ORDER BY game_id DESC ";
    $query .= "LIMIT 1";
    $player_set = mysqli_query($connection, $query);
    confirm_query($player_set); 
    $data = mysqli_fetch_assoc($player_set); 
    if($data){
        return $data["game_id"];
    } else {
        return null;   
    }
}

function find_all_games(){
    global $connection;
    
    $query ="SELECT * ";
    $query .= "FROM player ";
    $query .= "WHERE game_id IS NOT NULL ";
    $query .= "ORDER BY game_id ASC ";
    $data = mysqli_query($connection, $query);
    confirm_query($data); 
    return $data;
 
}



function is_player_black($id){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT * ";
    $query .= "FROM player ";
    $query .= "WHERE id = {$id} ";
    $query .= "AND player1 = 1 ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data); 
    if($data){
        return true;
    } else {
        return false;   
    }  
}

function is_player1_black($game_id){
    global $connection;
    
    $query = "SELECT player1 ";
    $query .= "FROM player ";
    $query .= "WHERE game_id = {$game_id} ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data); 
    if($data){
        return $data["player1"];
    } else {
        return false;   
    }  
}


function get_player_color($id, $game_id){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT player1 ";
    $query .= "FROM player ";
    $query .= "WHERE id = {$id} ";
    $query .= "AND game_id = {$game_id} ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data); 
    if($data){
        return $data["player1"];
    } else {
        return null;   
    } 
    
}


function get_id_by_game_id($game_id){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT id ";
    $query .= "FROM player ";
    $query .= "WHERE game_id = {$game_id} ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data); 
    if($data){
        return $data["id"];
    } else {
        return null;   
    }
}
  

function get_game_id_by_player_id($id){
    global $connection;
    $id = (int) $id;
    
    $query = "SELECT game_id ";
    $query .= "FROM player ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data); 
    if($data){
        return $data["game_id"];
    } else {
        return null;   
    } 
    
}



function fetch_game($game_id){
    global $connection;
    
    $query = "SELECT * ";
    $query .= "FROM game ";
    $query .= "WHERE game_id = {$game_id} ";
    $query .= "LIMIT 1";
    $data= mysqli_query($connection, $query);
    confirm_query($data); 
    $data = mysqli_fetch_assoc($data);
    if($data){
        return $data;
    } else {
        return null;   
    } 
}

function fetch_turn($game_id){
    $data = fetch_game($game_id);
    $turn = $data["black_turn"];
    return $turn;
}

function fetch_oldx($game_id){
    $data = fetch_game($game_id);
    $oldx = $data["move1_row"];
    return $oldx;
    
}

function fetch_oldy($game_id){
    $data = fetch_game($game_id);
    $oldy = $data["move1_column"];
    return $oldy;
    
}
function fetch_x($game_id){
    $data = fetch_game($game_id);
    $x = $data["move2_row"];
    return $x;
    
}

function fetch_y($game_id){
    $data = fetch_game($game_id);
    $y = $data["move2_column"];
    return $y;
}




function form_errors($errors= array()){
	$output = "";
	if (!empty($errors)) {
		$output = '<script language="javascript">';
		$output .= 'alert("Please fix the following errors:';
        $count = 1;
		foreach ($errors as $key => $error) {
			$output .= " ";
            $output .= htmlentities($error);
            $output .= ", ";
            $count++;
		}	
		$output .= '")';
		$output .= "</script>";
	} 
	return $output;
}
	

?>