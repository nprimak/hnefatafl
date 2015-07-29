<script>    
    
    // GLOBAL VARIABLES
    
    // CONSTANTS
    
        const stage = document.getElementById("stage");
        const squaresize = stage.width/11; //size of the squares on the board
        const rect = stage.getBoundingClientRect();
        const padding = 27; //padding around canvas
        const piecesize = squaresize/2.4; //the radius of the checkers
        const ctx = stage.getContext("2d");
    
   // OTHER GLOBAL VARIABLES
    
        var firstload = true;
        var boardArr = []; //array holding all current locations of pieces
        var pieceselected = false; //whether a piece is currently selected or not
        var currentpiece; //coordinates of piece which is currentl selected
        var round = 1;
        var turn = "black";
        var gameover = false;
        var player_needed = true;
        var timeout;
        var gametimer;
        var same_turn;
        var player_presence;

 //////// BEGIN FUNCTIONS FOR DRAWING THE BOARD  \\\\\\\\\\\\\\\\\
        
        function drawSquares(){   // drawing the background and grid of the board
            ctx.lineWidth=1;
            ctx.strokeStyle = 'black';
            ctx.fillStyle = "#8B4726";
           ctx.globalAlpha=0.8;
           var img = document.getElementById("wood");
            ctx.drawImage(img, 0, 0);
            ctx.fillRect(0,0,stage.width,stage.height);
            ctx.globalAlpha=1;
            for(i = 0; i <= 10 ; i++){
                for(j=0; j <=10 ; j++) {
                    ctx.rect(0+j*squaresize,0+i*squaresize, squaresize, squaresize);
                    if( (j==0 && i == 0) || (j==10 && i ==0) || (j==0 && i==10) || (j==10 && i ==10)){
                        ctx.fillStyle = '#6f3823';
                        ctx.fillRect(0+j*squaresize,0+i*squaresize, squaresize, squaresize);
                        ctx.beginPath();
                        ctx.moveTo(0+j*squaresize,0+i*squaresize);
                        ctx.lineTo(0+j*squaresize+squaresize,0+i*squaresize + squaresize);
                        ctx.moveTo(0+j*squaresize+squaresize, 0+i*squaresize);
                        ctx.lineTo(0+j*squaresize,0+i*squaresize+squaresize);
                        ctx.stroke();
                    }
                    if( j == 5 && i == 5){
                        ctx.fillStyle = '#6f3823';
                        ctx.fillRect(0+j*squaresize,0+i*squaresize, squaresize, squaresize);
                       
                    }
                    ctx.stroke();
                }
            }
            if(firstload== true){
                createBoardArray();
                firstload = false;
            }
        }
        
        function createBoardArray(){  // making array which stores location of all pieces on board
           // 0 = blank square | 1 =  white piece | 2 = black piece | 3 = king | 4 = corner pieces
            var row1  = [4,0,0,1,1,1,1,1,0,0,4]; 
            var row2  = [0,0,0,0,0,1,0,0,0,0,0];
            var row3  = [0,0,0,0,0,0,0,0,0,0,0];
            var row4  = [1,0,0,0,0,2,0,0,0,0,1];
            var row5  = [1,0,0,0,2,2,2,0,0,0,1];
            var row6  = [1,1,0,2,2,3,2,2,0,1,1];
            var row7  = [1,0,0,0,2,2,2,0,0,0,1];
            var row8  = [1,0,0,0,0,2,0,0,0,0,1];
            var row9  = [0,0,0,0,0,0,0,0,0,0,0];
            var row10  = [0,0,0,0,0,1,0,0,0,0,0];
            var row11  = [4,0,0,1,1,1,1,1,0,0,4]; 
            boardArr.push(row1,row2,row3,row4,row5,row6,row7,row8,row9,row10,row11);
            drawPieces();
            
        }
    
    
        
        function drawPieces(){  //drawing the pieces to the board based on where they are in boardArr 
            postAnnouncement();
             for(i = 0; i <= 10 ; i++){
                for(j=0; j <=10 ; j++) {
                    if(boardArr[i][j] == 1) {
                            //ctx.arc(x, y, radius, startAngle, endAngle, anticlockwise);
                            ctx.beginPath();
                           ctx.arc(0+j*squaresize+padding,0 +i*squaresize+padding, piecesize, 0, 2*Math.PI);
                            ctx.fillStyle = "#000000";
                        ctx.fill();
                            
                    }
                    if(boardArr[i][j] == 2 || boardArr[i][j] == 3 ) {
                        ctx.beginPath();
                            //ctx.arc(x, y, radius, startAngle, endAngle, anticlockwise);
                           ctx.arc(0+j*squaresize+padding,0 +i*squaresize+padding, piecesize, 0, 2*Math.PI);
                        ctx.fillStyle = "#FFFFFF";
                        ctx.fill();
                         
                    }
                    if(boardArr[i][j] == 3) {
                        ctx.beginPath();
                        ctx.moveTo(0+j*squaresize+squaresize/2,0+i*squaresize+9);
                        ctx.lineTo(0+j*squaresize+squaresize/2,0+i*squaresize + squaresize-9);
                        ctx.lineWidth=7;
                        ctx.moveTo(0+j*squaresize+9, 0+i*squaresize+squaresize/2);
                        ctx.lineTo(0+j*squaresize+squaresize-9,0+i*squaresize+squaresize/2);
                        ctx.lineWidth=7;
                        ctx.strokeStyle = 'red';
                        ctx.stroke();
                        
                    }

                }
             }
        }
    
    
    ///////////////// END BOARD DRAWING FUNCTONS \\\\\\\\\\\\\
    
 
///////////// SELECT AND MOVE PIECES  \\\\\\\\\\\\\\\\\\
    
        
        function selectPiece(event){ // called onclick
            var timer;
            var coord = getCursorPosition(event);  
            var x = coord[0];
            var y = coord[1];
            var xcell = Math.floor(x/squaresize) ;
            var ycell = Math.floor(y/squaresize) ;
            var players_turn = isThisPlayersTurn();
            if(players_turn == true){ 
                if(pieceselected == false){
                    // piece should be highlighted depending on whose turn it is
                    currentpiece = [xcell,ycell];
                    if(turn === "black" && boardArr[ycell][xcell] == 1 ){
                        highlightPath(xcell,ycell);
                    }
                    if(turn === "white" && (boardArr[ycell][xcell]==2 || boardArr[ycell][xcell]==3)){
                        if(boardArr[ycell][xcell] == 2){
                            highlightPath(xcell,ycell);
                        }
                        if(boardArr[ycell][xcell] == 3){
                            highlightKingPath(xcell,ycell);
                        }
                    }
                }else{ //if piece is already highlighted, see if user has clicked in a highlighted area
                    var colormatch = matchColor(x, y);
                    if(colormatch) {
                        movePiece(xcell,ycell,currentpiece); //if yes, move piece
                        updateTurnForPlayer();

                    }
                    else{ // if user has clicked on another piece, retrieve new coordinates
                        coord = getCursorPosition(event); 
                        x = coord[0];
                        y = coord[1];
                        xcell = Math.floor(x/squaresize) ;
                        ycell = Math.floor(y/squaresize) ;
                        currentpiece = [xcell,ycell];
                        if(turn === "black" && boardArr[ycell][xcell] == 1 ){
                            highlightPath(xcell,ycell);
                        }
                        if(turn === "white" && (boardArr[ycell][xcell]==2 || boardArr[ycell][xcell]==3)){
                            if(boardArr[ycell][xcell] == 2){
                                highlightPath(xcell,ycell);
                            }
                            if(boardArr[ycell][xcell] == 3){
                                highlightKingPath(xcell,ycell);
                            }
                        }
                    }
                }
            } 
            
    }
    
      function getCursorPosition(event){
            var x;
            var y;
            if (event.layerX || event.layerX == 0) { // Firefox
		          x = event.layerX;
		          y = event.layerY;
	       } else if (ev.offsetX || ev.offsetX == 0) { // Opera // Chrome / Everything Else
		          x = event.offsetX;
		          y = event.offsetY;
	       }
            var coord = [x,y];
            return coord;
        }
    
    
       function movePiece(x,y,currentpiece){
            var oldx = currentpiece[0];
            var oldy = currentpiece[1];
            updateLastMoveForBothPlayers(oldx,oldy,x,y);
            updateBoardArrayForBothPlayers(); 
        }
    
    
    
///////////////// END SELECT AND MOVE PIECES  \\\\\\\\\\\\\\\    
    
        
///////////////  TURN FUNCTIONS  \\\\\\\\\\\\\\\\\\\
        
       function isThisPlayersTurn(){  //checking whose turn it is
            var is_player_black = <?php $json = is_player_black($id); echo json_encode($json); ?>;
            if(turn == "black" && is_player_black == true){
                        this_player_turn = true; 
            } 
            if(turn == "white" && is_player_black == false ){
                       this_player_turn = true;
            } 
            if(turn == "white" && is_player_black == true){
                        this_player_turn = false;
            } 
            if(turn == "black" && is_player_black == false ){
                        this_player_turn = false;
            }  
            return this_player_turn;    
        }    
    
    
    function postAnnouncement(){  // posting turn updates to the user
            var update;
            var black_turn = document.getElementById("black_turn").innerHTML;
            if(turn ==="white" && black_turn == 0){
                update = document.getElementById("player1").getElementsByClassName("update")[0];
                update.innerHTML = "white turn";
                update.style.color = "#A14D34";
                
            }
            if(turn==="black" && black_turn == 1){
                update = document.getElementById("player2").getElementsByClassName("update")[0];
                update.innerHTML = "black turn";
                update.style.color = "#A14D34";
                
            }
            
        }
    
    
        
 //////////// END TURN RELATED FUNCTIONS \\\\\\\\\\\\\\\\\   
 
  
    
        
    
    
    //////////////////    BEGIN AJAX REQUESTS   \\\\\\\\\\\\\\\\\\\\\\\\\\
         
        function updateLastMoveForBothPlayers(oldx,oldy,x,y){ //sending last board move coordinates to php function to load into database
             if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
            }

            else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            var PageToSendTo = "gamevars.php?";
            var OldXName = "OldX=";
            var OldYName = "OldY=";
            var XName = "X=";
            var YName = "Y=";
            var UrlToSend = PageToSendTo + OldXName + oldx + '&' + OldYName + oldy + '&' + XName + x + '&' + YName + y;
            xmlhttp.open("GET", UrlToSend, false);
            xmlhttp.send();
        }
    
    function getArrayDataFromDatabase(){  // retrieving coordinates of last moved piece from database, placing data into hidden table
            if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
            }

            else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("hidden-table").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","getarraydata.php", false);
        xmlhttp.send();
    }
        
    
    
    function updateTurnForPlayer(){ // placing turn data into database, sending to PHP
        if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        var PageToSendTo = "gameturn.php?";
        var UrlToSend = PageToSendTo + "turn=" + turn;
        xmlhttp.open("GET", UrlToSend, false);
        xmlhttp.send();
    }
    

    
    
    function getTurnDataFromDatabase(old_turn){   // retrieving whose turn it is from database, placing data into hidden div
        if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("black_turn").innerHTML = xmlhttp.responseText;
                var new_turn = document.getElementById("black_turn").innerHTML;
                if(new_turn == old_turn){
                    same_turn = true;   
                }
                if(new_turn != old_turn){
                    same_turn = false;   
                }
              
            }
        }
        xmlhttp.open("GET","getturndata.php", false);
        xmlhttp.send();   
        
    }
    
    function getMissingPlayer(){
        var nickname1 = document.getElementById("player1").getElementsByClassName("nickname")[0].innerHTML;
        var nickname2 = document.getElementById("player2").getElementsByClassName("nickname")[0].innerHTML;
        var playernum;
        if(nickname1 == 'waiting...'){
            playernum = 1;
        }
        if(nickname2 == 'waiting...'){
            playernum = 0;
            
        }
        
        getPlayerNickname(playernum);
        getPlayerAvatar(playernum);
        
        
    }
    
    function getPlayerNickname(playernum){
        var id;
        if(playernum == 1){
            id = "player1";   
        }
        if(playernum == 0){
             id = "player2";    
        }
         if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(id).getElementsByClassName("nickname")[0].innerHTML = xmlhttp.responseText;
              
            }
        }
        xmlhttp.open("GET","getnickname.php?playernum=" +playernum, false);
        xmlhttp.send(); 
        
    }
    
    
     function getPlayerAvatar(playernum){
        var id;
        if(playernum == 1){
            id = "p1avatar";   
        }
        if(playernum == 0){
             id = "p2avatar";    
        }
          if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById(id).src = "../../images/avatars/"+xmlhttp.responseText+".png";
                
              
            }
        }
        xmlhttp.open("GET","getavatar.php?playernum=" +playernum, false);
        xmlhttp.send(); 
        
    } 
    
    function getPlayerPresence(){   // making sure both players are present
        if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("player_presence").innerHTML = xmlhttp.responseText;

            }
        }
        var PageToSendTo = "check_player_presence.php?";
        var nickname1 = "nickname_player1=";
        var nicknamep1 = document.getElementById("player1").getElementsByClassName("nickname")[0].innerHTML; 
        var nickname2 = "nickname_player2=";
        var nicknamep2 = document.getElementById("player2").getElementsByClassName("nickname")[0].innerHTML;
        var UrlToSend = PageToSendTo + nickname1 + nicknamep1 + '&' + nickname2 + nicknamep2 ;
        xmlhttp.open("GET", UrlToSend , false);
        xmlhttp.send();   
        
    }
    
    function deleteUserInfoFromDatabase(){
        if (window.XMLHttpRequest){
                xmlhttp=new XMLHttpRequest();
        }

        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        var PageToSendTo = "delete_game_id.php?gameover=" + gameover;
        xmlhttp.open("GET", PageToSendTo, false);
        xmlhttp.send();
        
    }
    
    
    ///////////////////// END AJAX FUNCTIONS   \\\\\\\\\\\\\\\\\\\\
    
        
      function updateBoardArrayForBothPlayers(){  
          // function which retrieves coord data, moves last piece and calls to redraw the board, update turn
          getArrayDataFromDatabase();
          var oldx, oldy, x, y;
          var refTab = document.getElementById("hidden-table");  
          for ( var i = 0; row = refTab.rows[i]; i++ ) {
                row = refTab.rows[i];
            for ( var j = 0; col = row.cells[j]; j++ ) {
                if(col.firstChild.nodeValue !== null){
                        if(j==0){
                            oldx = parseInt(col.firstChild.nodeValue,10);
                        }
                        if(j==1){
                            oldy = parseInt(col.firstChild.nodeValue,10);
                        }
                        if(j==2){
                            x = parseInt(col.firstChild.nodeValue,10);
                        }
                        if(j==3){
                            y = parseInt(col.firstChild.nodeValue,10);
                        }
                    }
                }
          }
            if (boardArr[oldy][oldx] == 1){
                turn = "white"; 
                boardArr[y][x] = 1;
            }
            if (boardArr[oldy][oldx] == 2){
                turn = "black";
                boardArr[y][x] = 2;
            }
            if (boardArr[oldy][oldx] == 3){
                turn = "black";
                boardArr[y][x] = 3;
            }
            boardArr[oldy][oldx] = 0;
            checkCapture(x,y);
            drawSquares();
            drawPieces();
            checkGameOver();
            pieceselected = false;  
             
        } 
        
    ////////// CHECKING IF A PIECE HAS BEEN CAPTURED  & REMOVING PIECE FROM THE ARRAY \\\\\\\\\\\\\
    
    
        function checkCapture(x,y){
            for(i = 0; i <= 10 ; i++){
                for(j=0; j <=10 ; j++) { 
                    var piece = boardArr[j][i];
                    if(j > 0 && j < 10 && i > 0 && i < 10){
                        if(piece == 1 && turn == "black"){
                            if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3) && (boardArr[j-1][i]==2 || boardArr[j-1][i]==3) && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0;   
                            }
                            if((boardArr[j][i+1]==2 || boardArr[j][i+1]==3) && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3 ) && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                            if(boardArr[5][5] == 0){
                                if((i== 4 || i == 6) && j == 5){
                                   if(i== 6 && (boardArr[j][i+1] == 2 || boardArr[j][i+1] == 3) && y==j && x==i+1){
                                       boardArr[j][i] = 0;  
                                   }
                                    if(i== 4 && (boardArr[j][i-1] == 2 || boardArr[j][i-1] == 3) && y==j && x==i-1){
                                       boardArr[j][i] = 0;  
                                   }
                                }
                                if((j== 4 || j == 6) && i == 5){
                                    if(j== 6 && (boardArr[j+1][i] == 2 || boardArr[j+1][i] == 3) && y==j+1 && x==i){
                                       boardArr[j][i] = 0;  
                                   }
                                     if(j== 4 && (boardArr[j-1][i] == 2 || boardArr[j-1][i] == 3) && y==j-1 && x==i){
                                       boardArr[j][i] = 0;  
                                   }
                                }
                              
                            }
                            
                        }
                        if(piece == 2 && turn == "white"){
                            if(boardArr[j+1][i]==1 && boardArr[j-1][i]==1 && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0;   
                            }
                            if(boardArr[j][i+1]==1 && boardArr[j][i-1]==1 && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                            if(boardArr[5][5] == 0){
                                 if((i== 4 || i == 6) && j == 5){
                                     if(i== 6 && boardArr[j][i+1] == 1 && y==j && x==i+1){
                                         boardArr[j][i] = 0;
                                     }
                                     if(i==4 && boardArr[j][i-1] == 1 && y==j && x==i-1){
                                         boardArr[j][i] = 0;
                                     }
                                 }
                                     
                                 if((j== 4 || j == 6) && i == 5){
                                      if(j== 6 && boardArr[j+1][i] == 1 && y==j+1 && x==i){
                                          boardArr[j][i] = 0;
                                      }
                                     if(j== 4 && boardArr[j-1][i] == 1 && y==j-1 && x==i){
                                            boardArr[j][i] = 0;
                                     }
                                 }
                            }
                        }
                
                    }
                    if(j==0 || j == 10){
                        if(piece == 1 && turn == "black"){
                            if((boardArr[j][i+1]==2 || boardArr[j][i+1]==2) && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3) && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                            if(boardArr[j][i+1]==4 && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3) && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                             if((boardArr[j][i+1]==2 || boardArr[j][i-1]==3) && boardArr[j][i-1]==4 && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                        }
                        if(piece == 2 && turn == "white"){
                            if(boardArr[j][i+1]==1 && boardArr[j][i-1]==1 && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                            if(boardArr[j][i+1]==4 && boardArr[j][i-1]==1 && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                             if(boardArr[j][i+1]==1 && boardArr[j][i-1]==4 && (x == i+1 || x== i-1) && (y==j)){
                                boardArr[j][i] = 0;   
                            }
                        
                        }
                    }
                    if(i==0 || i == 10){
                        if(piece == 1 && turn == "black"){
                            if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3) && ( boardArr[j-1][i]==2 || boardArr[j-1][i]==3 )&& (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                            if(boardArr[j+1][i]==4 && ( boardArr[j-1][i]==2 || boardArr[j-1][i]==3) && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                             if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3 )&& boardArr[j-1][i]==4 && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                        }
                        if(piece == 2 && turn == "white"){
                            if(boardArr[j+1][i]==1 && boardArr[j-1][i]==1 && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                              if(boardArr[j+1][i]==1 && boardArr[j-1][i]==4 && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                              if(boardArr[j+1][i]==4 && boardArr[j-1][i]==1 && (y == j+1 || y== j-1) && (x==i)){
                                boardArr[j][i] = 0; 
                            }
                        }
                        
                    }
                    
                }
            }
        }
    //////////// END CHECK CAPTURE FUNCTION \\\\\\\\\
    
 
   ///////// PATH FUNCTIONS  --- HIGHLIGHT PATH OF PIECE WHEN IT IS CLICKED \\\\\\\\\\\\\
    
    
        function highlightPath(x,y) {   // figuring out where to highlight the path of each piece when it is clicked
            drawSquares();
            pieceselected = true;
            var xcount = x;
            var ycount = y;
            if(xcount != 0 ) { 
                while( xcount > 0 ){ //find spots to the left of piece
                    if(boardArr[y][xcount-1] != 0 ){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        xcount--;
                        colorPath(0+xcount*squaresize,0+y*squaresize, squaresize, squaresize);
                    }
                }
            }
            xcount = x; //reset xcount
            if(xcount != 10 ){
                while( xcount < 10) { //find spots to the right of piece
                    if(boardArr[y][xcount+1] != 0){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        xcount++;
                        colorPath(0+xcount*squaresize,0+y*squaresize, squaresize, squaresize);
                    } 
                }
            }
            if(ycount != 10) {
                while( ycount < 10){ //find spots below piece
                    if(boardArr[ycount+1][x] != 0){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        ycount++;
                        colorPath(0+x*squaresize,0+ycount*squaresize, squaresize, squaresize);
                    } 
                }
            }
            ycount = y; //reset ycount
            if(ycount != 0) {
                while( ycount > 0){ //find spots above piece
                    if(boardArr[ycount-1][x] != 0){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        ycount--;
                        colorPath(0+x*squaresize,0+ycount*squaresize, squaresize, squaresize);
                    } 
                }
            }
            
            ctx.fillStyle = '#6f3823';
            ctx.fillRect(0+5*squaresize,0+5*squaresize, squaresize, squaresize);                   
            drawPieces();
            
        }
        
      
    
         function highlightKingPath(x,y) { //king has more moves available so its a modified version of highlightPath
            drawSquares();
            pieceselected = true;
            var xcount = x;
            var ycount = y;
            if(xcount != 0 ) { 
                while( xcount > 0 ){ //find spots to the left of piece
                    if(boardArr[y][xcount-1] != 0 && boardArr[y][xcount-1] != 4){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        xcount--;
                        colorPath(0+xcount*squaresize,0+y*squaresize, squaresize, squaresize);
                    }
                }
            }
            xcount = x; //reset xcount
            if(xcount != 10 ){
                while( xcount < 10) { //find spots to the right of piece
                    if(boardArr[y][xcount+1] != 0 && boardArr[y][xcount+1] != 4){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        xcount++;
                        colorPath(0+xcount*squaresize,0+y*squaresize, squaresize, squaresize);
                    } 
                }
            }
            if(ycount != 10) {
                while( ycount < 10){ //find spots below piece
                    if(boardArr[ycount+1][x] != 0 && boardArr[ycount+1][x] != 4){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        ycount++;
                        colorPath(0+x*squaresize,0+ycount*squaresize, squaresize, squaresize);
                    } 
                }
            }
            ycount = y; //reset ycount
            if(ycount != 0) {
                while( ycount > 0){ //find spots above piece
                    if(boardArr[ycount-1][x] != 0 && boardArr[ycount-1][x] != 4){
                        break; //make sure you don't highlight spots past another piece
                    }
                    else {
                        ycount--;
                        colorPath(0+x*squaresize,0+ycount*squaresize, squaresize, squaresize);
                    } 
                }
            }
                                 
            drawPieces();
            
        }
    
    //////////////////// END PATH FUNCTIONS \\\\\\\\\\\\\\\\\\\
    
    
      ///////////// START COLOR FUNCTIONS    \\\\\\\\\\\\\\\\
        
        
    function colorPath(x,y,size,size) {   //coloring the path of the piece
            ctx.fillStyle = "#a95e39";
            ctx.fillRect(x,y, size, size);
    }
        
    
    
    function matchColor(x,y){    //checking to see if where the user clicked matches the color of the path
            var imgData=ctx.getImageData(x,y,5,5);
            var red=imgData.data[0];
            var green=imgData.data[1];
            var blue = imgData.data[2];
            var color = rgbToHex(red,green,blue);
            if( color == "#a95e39"){
                return true;
            }
            else {
                return false;
            }
        }
        
    
        function rgbToHex(r, g, b) {  //converting rgb data from canvas color data
            return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
        }
        
    
        function componentToHex(c) {
            var hex = c.toString(16);
            return hex.length == 1 ? "0" + hex : hex;
        }
    
    
    ////////////// END COLOR FUNCTIONS   \\\\\\\\\\\\\\\\
        
  
    
    
    //////// BEGIN  GAME OVER FUNCTIONS  \\\\\\\\\\\
        
        function checkGameOver(){   
            var gameovermessage;
            var winner;
            if(boardArr[0][0] == 3 || boardArr[0][10] == 3 || boardArr[10][0] == 3 || boardArr[10][10] == 3){
                gameover = true; 
                gameovermessage = "The king has escaped!";
                winner = "white";
            }
            whitecount = 0;
            var i, j, abort = false;
            for(i = 0; i <= 10 && !abort; i++){
                for(j=0; j <=10 && !abort; j++) { 
                    if(i==5 && (j == 4 || j == 6) && boardArr[j][i] == 3){
                        if(j == 4 && boardArr[j-1][i] == 1 && boardArr[j][i+1] == 1 && boardArr[j][i-1] == 1){
                            gameover = true;
                            gameovermessage = "The king is captured!";
                            winner = "black";
                        }
                        if(j == 6 && boardArr[j+1][i] == 1 && boardArr[j][i+1] == 1 && boardArr[j][i-1] == 1){
                            gameover = true;
                            gameovermessage = "The king is captured!";
                            winner = "black";   
                        }
                    }
                    if(j==5 && (i == 4 || i == 6) && boardArr[j][i] == 3){
                        if(i == 4 && boardArr[j][i-1] == 1 && boardArr[j+1][i] == 1 && boardArr[j-1][i] == 1){
                            gameover = true;
                            gameovermessage = "The king is captured!";
                            winner = "black";
                        }
                        if(i == 6 && boardArr[j][i+1] == 1 && boardArr[j+1][i] == 1 && boardArr[j-1][i] == 1){
                            gameover = true;
                            gameovermessage = "The king is captured!";
                            winner = "black";   
                        }
                    }
                    if(boardArr[j][i] == 3 && i > 0 && i <10 && j>0 && j<10){
                           if(boardArr[j+1][i] == 1 && boardArr[j-1][i]==1 && boardArr[j][i+1]==1 && boardArr[j][i-1] == 1){
                               gameover = true;
                               gameovermessage = "The king is captured!";
                               winner = "black"; 
                               
                           }
                        
                    }
                    if(boardArr[j][i]== 2){
                        whitecount++;
                    }
                    if(j==10 && i == 10 && whitecount==0){
                        var cankingmove = canKingMove();
                        if(cankingmove == false){
                            gameover = true;
                            gameovermessage = "No moves for white!";
                            winner = "black";
                        }
                        abort = true;
                    }
            }
        }
        if(gameover== true){
          displayFinalMessage(gameovermessage, winner);
             clearInterval(gametimer);
                displayPlayAgainButton();
                window.setTimeout(deleteUserInfoFromDatabase, 10000);
                         

        }
    }
    
    function checkPlayerGone(){
        if(gameover == false){
            var winner;
            var gameovermessage;
            if(player_presence == '"Player 1 has left."'){
                    winner = "white";
                    gameover = true;
                    gameovermessage = "Player 1 has left.";
            }
            if(player_presence == '"Player 2 has left."'){
                    winner = "black";
                    gameover = true;
                    gameovermessage = "Player 2 has left.";
            }
            if(gameover == true){
                displayFinalMessage(gameovermessage, winner);
                clearInterval(gametimer);
                displayPlayAgainButton();
                window.setTimeout(deleteUserInfoFromDatabase, 5000);
            }
        }
                     
    }
      
    
    function displayFinalMessage(message,winner){  // When game is over, display message saying who won, etc. 
        if( message != undefined && winner != undefined ){
            ctx.fillStyle = 'rgba(0,0,0,0.5)';
            ctx.fillRect(0,0,stage.width,stage.height);
            ctx.font = "700 40pt IM Fell English";
            ctx.fillStyle = '#d0975e';
            ctx.lineWidth = 4;
            ctx.strokeStyle = 'black';
            ctx.strokeText(message, 60, 250);
            ctx.fillText(message, 60, 250);
            ctx.font = "700 60pt IM Fell English";
            ctx.lineWidth = 4;
            if (message != "Player 1 has left." && message != "Player 2 has left."){
                ctx.strokeText("Winner is "+winner+"!", 35,350);
                ctx.fillText("Winner is "+winner+"!", 35,350);
            }
            stage.removeEventListener("click", selectPiece);
        }
        
    }
    
   function displayPlayAgainButton(){
        var P1playagain = document.getElementById("player1").getElementsByClassName("playagain")[0];
        var P2playagain = document.getElementById("player2").getElementsByClassName("playagain")[0];
        var player1name = document.getElementById("player1").getElementsByClassName("nickname")[0].innerHTML;
        var player2name = document.getElementById("player2").getElementsByClassName("nickname")[0].innerHTML;
        var nickname = <?php echo json_encode($nickname); ?>;
        if(nickname == player1name){
            P1playagain.style.visibility = "visible";
        }
        if(nickname == player2name){
            P2playagain.style.visibility = "visible";
        }
        
    }
    
    
        
        
    function canKingMove(){  // if the king cannot move and there are no other white pieces on the board, game is over
        for(i = 0; i <= 10 ; i++){
            for(j=0; j <=10 ; j++) { 
                if(boardArr[j][i]== 3){
                     if(j== 0 || j == 10){
                         if(j==0 && boardArr[j][i+1]==1 && boardArr[j][i-1]==1 && boardArr[j+1][i]==1){
                            return false;   
                         }
                         if(j==10 && boardArr[j][i+1]==1 && boardArr[j][i-1]==1 && boardArr[j-1][i]==1){
                            return false;   
                         }
                     }
                     if(i == 0 || i == 10){
                          if(i==0 && boardArr[j+1][i]==1 && boardArr[j-1][i]==1 && boardArr[j][i+1]==1){
                            return false;   
                         }
                         if(i==10 && boardArr[j+1][i]==1 && boardArr[j-1][i]==1 && boardArr[j][i-1]==1){
                            return false;   
                         }
                     }
                }
                
            }
        }
       return true;     
   }
    
    
    /////////////////////// END GAME OVER FUNCTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    
    
    
    function printCount(count, timeout, black_turn){   // printing and formatting the time countdown for the user
             var min = parseInt(count/60, 10);
             var sec = parseInt(count % 60, 10);
            if(sec == 0){
                if(count == 0 || count == 120 || count == 60){
                    timeout.innerHTML = "Time Left to Move: " +(2-min) + ":00";
                }
                else{
                    timeout.innerHTML = "Time Left to Move: " +(1-min) + ":00";
                }
            }
            if(sec > 0 && sec <= 50){
                timeout.innerHTML = "Time Left to Move: " +(1-min) + ":" + (60-sec);
            }
            if(sec > 50 && sec <= 59){
              timeout.innerHTML = "Time Left to Move: " +(1-min) + ":0" + (60-sec);
            }
            if(count > 90 && count < 120){
                timeout.style.color = "darkred";
            }
            if(count <90){
                 timeout.style.color = "#A14D34";
            }
            if(count >= 120){  // End game if timer is over 2 min 
                         var gameovermessage;
                         var winner;
                         gameover = true;
                          if(black_turn == 0){
                              gameovermessage= "Time out for white!";
                              winner = "black";
                          }
                          if(black_turn == 1){
                              gameovermessage= "Time out for black!";
                              winner = "white";

                          }
                          timeout.innerHTML = "TIME OUT";
                          displayFinalMessage(gameovermessage, winner);
                        clearInterval(gametimer);
                        displayPlayAgainButton();
                        deleteUserInfoFromDatabase(); 
                         
          }
        
    }
    
//////////////////// BEGIN ONLOAD/PARENT FUNCTION   \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    
        window.onload = function() {   
            count = 0; // keeps track of the seconds
            var timeout;
                drawSquares();
                 gametimer = setInterval(function(){   // BEGIN INTERVAL, ALL FUNCTIONS BELOW RUN EVERY SECOND
                   if(player_needed == false){
                        getPlayerPresence();   
                     } 
                    if(player_needed == true){
                         count = 0;
                        getMissingPlayer();
                        var nickname1 = document.getElementById("player1").getElementsByClassName("nickname")[0].innerHTML;
                        var nickname2 = document.getElementById("player2").getElementsByClassName("nickname")[0].innerHTML;
                        if(nickname1 != 'waiting...' && nickname2 != 'waiting...'){
                            player_needed = false;
                        }
                     } 
                     player_presence = document.getElementById("player_presence").innerHTML;
                     stage.addEventListener("click", selectPiece, false);
                     var black_turn = document.getElementById("black_turn").innerHTML;
                     getTurnDataFromDatabase(black_turn);
                     if(same_turn == true && gameover == false){
                        count++;  // keeps track of the seconds
                         checkPlayerGone();
                         if(black_turn == 0 && gameover == false){
                            timeout = document.getElementById("player2").getElementsByClassName("update")[0];
                             printCount(count,timeout,black_turn);
                         }
                        if(black_turn == 1 && gameover == false){
                            timeout = document.getElementById("player1").getElementsByClassName("update")[0]; 
                            printCount(count,timeout,black_turn);
                        }
                
                     }
                     if(same_turn == false){
                        count = 0;
                         checkPlayerGone();
                         checkGameOver();
                        timeout.innerHTML = " ";
                     }
                     var is_player_black = <?php $json = is_player_black($id); echo json_encode($json); ?>;
                     if(black_turn == 1 && is_player_black == false && count < 120 && gameover == false){
                         stage.removeEventListener("click", selectPiece, false); //turns event listener off if its not the players turn
                         updateBoardArrayForBothPlayers(); 
                         checkGameOver();
                         checkPlayerGone();
                         
                
                    }
                       
                    if(black_turn == 0 && is_player_black == true && count < 120  && gameover== false){
                          stage.removeEventListener("click", selectPiece, false); //turns event listener off if its not the players turn
                          updateBoardArrayForBothPlayers();
                        checkGameOver();
                        checkPlayerGone();
                            
                        
                      }

                 
                 }, 1000);
                
                
        };
        
    var myEvent = window.attachEvent || window.addEventListener;
    var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload'; /// make IE7, IE8 compitable

            myEvent(chkevent, function(e) { // For >=IE7, Chrome, Firefox
                var confirmationMessage = 'Leaving this page will cause you to forfeit the game. Are you sure?';  // a space
                if(player_needed == false  && gameover == false){
                    (e || window.event).returnValue = confirmationMessage;
                    return confirmationMessage;
                }
            });
    
     window.onunload = function() {
           deleteUserInfoFromDatabase();
        
         
    };
</script>  