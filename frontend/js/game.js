import { db, user, readUserData, leaveGameRoom } from './initSDK.js'
import { checkCapture, checkGameOver } from './logic.js';
import {ref, update, onValue} from "https://www.gstatic.com/firebasejs/9.1.1/firebase-database.js"



// socket.on('init', handleInit)
// socket.on('gameStart', handleBoardStart)
// socket.on('boardUpdate', handleBoardUpdate)
// socket.on('gameOver', handleGameOver)


screen.orientation.lock('portrait')

let startGameCode; 
let playerNumber;
let boardArr; //array holding all current locations of pieces
let turn;
const stage = document.getElementById('canvas');
var ctx = stage.getContext("2d");
if( document.body.clientWidth > 320) {
    stage.width = 320;
} else {
    stage.width = document.body.clientWidth;
}
var padding = stage.width/22; //padding around canvas
stage.height = 320;
var squaresize = stage.width/11; //size of the squares on the board
var piecesize = squaresize/2.4; //the radius of the checkers
var pieceselected = false; //whether a piece is currently selected or not
var currentpiece; //coordinates of piece which is currentl selected
var gameover = false;
let boardUpdateRef;
let boardUpdateListener; 




// need to show image + name of each player 
function displayPlayerData(player2, player1) {
    const player1element = document.getElementById("player1");
    const player2element = document.getElementById("player2");
    //console.log("user", user);
    if(user.uid === player2) {
        playerNumber = 2;
        readUserData(player1).then((playerData) => {
            player1element.querySelector('h2').innerHTML = playerData.username; 
            player1element.querySelector('img').src = playerData.profile_picture;
            player2element.querySelector('h2').innerHTML = user.displayName; 
            player2element.querySelector('img').src = user.photoURL;
        })
    } else {
        playerNumber = 1;
        readUserData(player2).then((playerData) => {
            player1element.querySelector('h2').innerHTML = user.displayName; 
            player1element.querySelector('img').src = user.photoURL;
            player2element.querySelector('h2').innerHTML = playerData.username; 
            player2element.querySelector('img').src = playerData.profile_picture;
        })
    }
  
}




function listenForBoardUpdates(on = true) {
    if(on) {
       boardUpdateListener = onValue(boardUpdateRef, (snapshot) => {
            const data = snapshot.val();
            handleBoardUpdate(data, startGameCode)
         });
    } else {
       boardUpdateListener();
    }
}


const handleInit = (gameData, roomName) => {
    startGameCode = roomName; 
    boardUpdateRef = ref(db, 'rooms/' + startGameCode );
    displayPlayerData(gameData.player2, gameData.player1);
    handleBoardUpdate(gameData);
    listenForBoardUpdates();
    handleGameOver({winner: "white"});
}


const handleBoardUpdate = (gameData) => {
   // console.log("received game start", gameData)
    boardArr = gameData.board;
    //console.log("boardArr", boardArr);
    turn = gameData.turn;
    drawSquares()
    drawPieces()
}

function handleGameOver(gameOverData) {
    gameover = true;
    displayFinalMessage(gameOverData.winner)
    listenForBoardUpdates(false);
    leaveGameRoom(user.uid, startGameCode, playerNumber);
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
    for(let i = 0; i <= 10 ; i++){
        for(let j=0; j <=10 ; j++) {
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
            if(j == 5 && i == 5){
                ctx.fillStyle = '#6f3823';
                ctx.fillRect(0+j*squaresize,0+i*squaresize, squaresize, squaresize);
               
            }
            ctx.stroke();
        }
    }

}

function drawPieces(){
    postAnnouncement();
     for(let i = 0; i <= 10 ; i++){
        for(let j=0; j <=10 ; j++) {
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
                ctx.lineWidth=3;
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
    //console.log("select piece function player number", playerNumber);
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
        if(colormatch && pieceselected) {
            movePiece(xcell,ycell);
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
    if(turn == "black") {
        update = document.getElementById("player1").getElementsByClassName("update")[0];
        update.innerHTML = "your turn";
    }
    if(turn == "white") {
         update = document.getElementById("player2").getElementsByClassName("update")[0];
        update.innerHTML = "your turn";
    }
    
}


function movePiece(x,y){
    var oldx = pieceselected.x;
    var oldy = pieceselected.y;
    if (boardArr[oldy][oldx] == 1){
        boardArr[y][x] = 1;
        turn = "white";
    }
    if (boardArr[oldy][oldx] == 2){
        boardArr[y][x] = 2;
        turn = "black";
    }
    if (boardArr[oldy][oldx] == 3){
        boardArr[y][x] = 3;
        turn = "black";
    }
    boardArr = checkCapture(boardArr, turn);
    boardArr[oldy][oldx] = 0;
    const gameOverData = checkGameOver(boardArr);
    if(gameOverData.gameover) {
        handleGameOver(gameOverData);
    } else {
        updateBoard();
    }
    pieceselected = false;
    
}


function highlightPath(x,y) {
    drawSquares();
    pieceselected = {x, y};
    var xcount = x;
    var ycount = y;
    if(xcount != 0 ) { 
        while( xcount > 0 ){ //find spots to the left of piece
            if(boardArr[y][xcount-1] != 0){
                break; //make sure you don't highlight spots past another piece
            }
            else {
                xcount--;
                colorPath(0+xcount*squaresize,0+y*squaresize, squaresize);
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
                colorPath(0+xcount*squaresize,0+y*squaresize, squaresize);
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
                colorPath(0+x*squaresize,0+ycount*squaresize, squaresize);
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
                colorPath(0+x*squaresize,0+ycount*squaresize, squaresize);
            } 
        }
    }
    
    ctx.fillStyle = '#6f3823';
    ctx.fillRect(0+5*squaresize,0+5*squaresize, squaresize, squaresize);                   
    drawPieces();
    
}


 function highlightKingPath(x,y) {
    drawSquares();
    pieceselected = {x, y};
    var xcount = x;
    var ycount = y;
    if(xcount != 0 ) { 
        while( xcount > 0 ){ //find spots to the left of piece
            if(boardArr[y][xcount-1] != 0 && boardArr[y][xcount-1] != 4){
                break; //make sure you don't highlight spots past another piece
            }
            else {
                xcount--;
                colorPath(0+xcount*squaresize,0+y*squaresize, squaresize);
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
                colorPath(0+xcount*squaresize,0+y*squaresize, squaresize);
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
                colorPath(0+x*squaresize,0+ycount*squaresize, squaresize);
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
                colorPath(0+x*squaresize,0+ycount*squaresize, squaresize);
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

function colorPath(x,y,size) {
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


function displayFinalMessage(winner){
    ctx.fillStyle = 'rgba(0,0,0,0.5)';
    ctx.fillRect(0,0,stage.width,stage.height);
    ctx.font = "700 60pt IM Fell English";
    ctx.fillStyle = '#d0975e';
    ctx.lineWidth = 4;
    ctx.strokeStyle = '#000000';
    ctx.strokeText("Over", 60, 250);
    ctx.fillText("Over", 60, 250);
    ctx.font = "700 60pt IM Fell English";
    ctx.lineWidth = 4;
    ctx.strokeText("Game", 50,100);
    ctx.fillText("Game", 50,100);
    stage.removeEventListener("click", selectPiece);
    var update;
    if(winner === "black") {
        update = document.getElementById("player1").getElementsByClassName("update")[0];
        update.innerHTML = "winner!";
        update = document.getElementById("player2").getElementsByClassName("update")[0];
        update.innerHTML = "";
    }
    if(winner === "white") {
        update = document.getElementById("player2").getElementsByClassName("update")[0];
        update.innerHTML = "winner!";
        update = document.getElementById("player1").getElementsByClassName("update")[0];
        update.innerHTML = "";
    }

}


function updateBoard() {
    update(ref(db, 'rooms/' + startGameCode), {
        board: boardArr,
        turn: turn
    });
}


stage.addEventListener("click", selectPiece, false);

export {handleInit};
