
const socket = io('http://localhost:3000');

socket.on('init', handleInit)
socket.on('gameCode', handleGameCode)
socket.on('unknownGame', handleUnknownGame)
socket.on('tooManyPlayers', handleTooManyPlayers)
socket.on('gameStart', handleBoardStart)
socket.on('boardUpdate', handleBoardUpdate)
socket.on('gameOver', handleGameOver)

const joinGameBtn = document.getElementById('join-game')
const createGameBtn = document.getElementById('create-game')
const roomCodeInput = document.getElementById('room-code')
const initialScreen = document.getElementById('center')
const startScreen = document.getElementById('start')
const roomCodeIdDisplay = document.getElementById('room-code-id')
const waitingScreen = document.getElementById('waiting')

joinGameBtn.addEventListener('click', joinGame)
createGameBtn.addEventListener('click', newGame)

function newGame() {
    socket.emit('newGame');
}

function joinGame() {
    const code = roomCodeInput.value;
    socket.emit('joinGame', code);
}

let startGameCode; 
let playerNumber;
let boardArr; //array holding all current locations of pieces
let turn;
var firstload = true;
var stage = document.getElementById("stage");
var ctx = stage.getContext("2d");
var squaresize = stage.width/11; //size of the squares on the board
var padding = 27; //padding around canvas
var piecesize = squaresize/2.4; //the radius of the checkers
var pieceselected = false; //whether a piece is currently selected or not
var currentpiece; //coordinates of piece which is currentl selected
var round = 1;
var gameover = false;

function showWaitingScreen() {
    waitingScreen.style.display = "inline"
    startScreen.style.display = "none"
}


function showGameScreen() {
    initialScreen.style.display = 'none';
    stage.style.display = 'inline';
}

function handleInit(number) {
    playerNumber = number;
}

function handleGameCode(gameCode) {
    roomCodeIdDisplay.innerText = gameCode;
    showWaitingScreen();
}

function handleUnknownGame() {
    reset()
    alert("Unknown Game Code")
}

function handleTooManyPlayers() {
    reset()
    alert("Game Already in Progress")
}

function handleBoardStart(gameData) {
    showGameScreen();
    handleBoardUpdate(gameData);
}

function handleBoardUpdate(gameData) {
    console.log("received game start")
    const data = JSON.parse(gameData);
    console.log("data", data);
    console.log("gamecode", data.roomName);
    startGameCode = data.roomName; 
    boardArr = data.boardArray;
    console.log("boardArr", boardArr);
    turn = data.turn;
    drawSquares()
    drawPieces()
}

function handleGameOver(gameOverData) {
    const data = JSON.parse(gameOverData)
    gameover = true;
    displayFinalMessage(data.winner, data.gameovermessage)
}


function drawSquares(){
    ctx.lineWidth=1;
    ctx.strokeStyle = 'black';
    ctx.fillStyle = "#8B4726";
    ctx.globalAlpha=0.8;
    var img = document.getElementById("wood");
    ctx.drawImage(img, 0, 0);
    ctx.fillRect(0,0, stage.width, stage.height);
    ctx.globalAlpha=1;
    for(i = 0; i <= 10 ; i++){
        for(j=0; j <=10 ; j++) {
            ctx.rect(0+j*squaresize,0+i*squaresize, squaresize, squaresize);
            //ctx.stroke();
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
        firstload = false;
    }
}

function drawPieces(){
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
               // ctx.strokeStyle = 'red';
                // ctx.stroke();
            //    ctx.beginPath();
                ctx.moveTo(0+j*squaresize+9, 0+i*squaresize+squaresize/2);
                ctx.lineTo(0+j*squaresize+squaresize-9,0+i*squaresize+squaresize/2);
                ctx.lineWidth=7;
                ctx.strokeStyle = 'red';
                ctx.stroke();
                
            }

        }
     }
    
    
}

function selectPiece(event){
    var coord = getCursorPosition(event);  
    var x = coord[0];
    var y = coord[1];
    var xcell = Math.floor(x/squaresize) ;
    var ycell = Math.floor(y/squaresize) ;
    console.log("select piece function player number", playerNumber);
    //console.log(xlocation);
    //console.log(ylocation);
    if(pieceselected == false){
        currentpiece = [xcell,ycell];
        if(turn === "black" && (playerNumber === 1) && boardArr[ycell][xcell] == 1 ){
            highlightPath(xcell,ycell);
        }
        if(turn === "white" && playerNumber === 2){
            if(boardArr[ycell][xcell] == 2){
                highlightPath(xcell,ycell);
            }
            if(boardArr[ycell][xcell] == 3){
                highlightKingPath(xcell,ycell);
            }
        }
    }else{
        var colormatch = matchColor(x, y);
        if(colormatch) {
            movePiece(xcell,ycell,currentpiece);
        }
        else{ // if user has clicked on another piece, retrieve new coordinates
                coord = getCursorPosition(event); 
                x = coord[0];
                y = coord[1];
                xcell = Math.floor(x/squaresize) ;
                ycell = Math.floor(y/squaresize) ;
                currentpiece = [xcell,ycell];
                if(turn === "black" && (playerNumber == 1) && boardArr[ycell][xcell] == 1 ){
                    highlightPath(xcell,ycell);
                }
                if(turn === "white" && (playerNumber == 2) && (boardArr[ycell][xcell]==2 || boardArr[ycell][xcell]==3)){
                    if(boardArr[ycell][xcell] == 2){
                        highlightPath(xcell,ycell);
                    }
                    if(boardArr[ycell][xcell] == 3){
                        highlightKingPath(xcell,ycell);
                    }
                }
                //pieceselected = false; // YOU JUST CHANGED THIS 
            }
        }
}


function postAnnouncement(){
    var update;
    // 1 is black, 2 is white
    if(playerNumber == 1 && turn == "black") {
        update = document.getElementById("player1").getElementsByClassName("update")[0];
        update.innerHTML = "your turn";
    }
    if(playerNumber == 2 && turn == "black") {
         update = document.getElementById("player2").getElementsByClassName("update")[0];
        update.innerHTML = "black turn";
    }
    if(playerNumber == 1 && turn == "white") {
        update = document.getElementById("player1").getElementsByClassName("update")[0];
        update.innerHTML = "white turn";
    }
    if(playerNumber == 2 && turn == "white") {
        update = document.getElementById("player2").getElementsByClassName("update")[0];
        update.innerHTML = "your turn";
    }
    
}

function displayTurn() {
    update = document.getElementById("player1").getElementsByClassName("update")[0];
    update.innerHTML = "your turn";
    update = document.getElementById("player2").getElementsByClassName("update")[0];
    update.innerHTML = "black turn";
}


function movePiece(x,y,currentpiece){
    var oldx = currentpiece[0];
    var oldy = currentpiece[1];
    if (boardArr[oldy][oldx] == 1){
        boardArr[y][x] = 1;
        //boardArr[oldy][oldx] = 0;
    }
    if (boardArr[oldy][oldx] == 2){
        boardArr[y][x] = 2;
        //boardArr[oldy][oldx] = 0;
    }
    if (boardArr[oldy][oldx] == 3){
        boardArr[y][x] = 3;
        //boardArr[oldy][oldx] = 0;
    }
    boardArr[oldy][oldx] = 0;
    socket.emit('newTurn', JSON.stringify({boardArray: boardArr, roomName: startGameCode}));
    pieceselected = false;
    
}


function highlightPath(x,y) {
    drawSquares();
    pieceselected = true;
    var xcount = x;
    var ycount = y;
    if(xcount != 0 ) { 
        while( xcount > 0 ){ //find spots to the left of piece
            if(boardArr[y][xcount-1] != 0){
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


 function highlightKingPath(x,y) {
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


function matchColor(x,y){
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

function colorPath(x,y,size,size) {
    //ctx.lineWidth=2;
    ctx.fillStyle = "#a95e39";
    ctx.fillRect(x,y, size, size);
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function getCursorPosition(event){
   var x;
    var y;
    if (event.layerX || event.layerX == 0) { // Firefox
          x = event.layerX;
          y = event.layerY;
   } else if (ev.offsetX || ev.offsetX == 0) { // Opera
          x = event.offsetX;
          y = event.offsetY;
   }
    var coord = [x,y];
    return coord;
}


function displayFinalMessage(message,winner){
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
    ctx.strokeText("Winner is "+winner+"!", 35,350);
    ctx.fillText("Winner is "+winner+"!", 35,350);
    stage.removeEventListener("click", selectPiece);

}

function reset() {
    playerNumber = null;
    roomCodeInput.value = "";
    roomCodeIdDisplay.innerText = "";
    initialScreen.style.display = 'inline';
    waitingScreen.style.display = 'none';
    startScreen.style.dispkay = 'inline';
}


stage.addEventListener("click", selectPiece, false);
