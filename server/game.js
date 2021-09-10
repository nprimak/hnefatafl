function createBoardArray(){
    boardArr = []
    // 0 = blank square | 1 =  white piece | 2 = black piece | 3 = king
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
    // row 1 - 5 are mirrored on the bottom half of board
     boardArr.push(row1,row2,row3,row4,row5,row6,row7,row8,row9,row10,row11);
     return boardArr;     
 }

 function checkCapture(boardArr, turn){
    for(i = 0; i <= 10 ; i++){
        for(j=0; j <=10 ; j++) { 
            var piece = boardArr[j][i];
            if(j > 0 && j < 10 && i > 0 && i < 10){
                if(piece == 1 && turn == "black"){
                    if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3) && (boardArr[j-1][i]==2 || boardArr[j-1][i]==3) ){
                        boardArr[j][i] = 0;   
                    }
                    if((boardArr[j][i+1]==2 || boardArr[j][i+1]==3) && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3 ) ){
                        boardArr[j][i] = 0;   
                    }
                    if(boardArr[5][5] == 0){
                        if((i== 4 || i == 6) && j == 5){
                           if(i== 6 && (boardArr[j][i+1] == 2 || boardArr[j][i+1] == 3)){
                               boardArr[j][i] = 0;  
                           }
                            if(i== 4 && (boardArr[j][i-1] == 2 || boardArr[j][i-1] == 3)){
                               boardArr[j][i] = 0;  
                           }
                        }
                        if((j== 4 || j == 6) && i == 5){
                            if(j== 6 && (boardArr[j+1][i] == 2 || boardArr[j+1][i] == 3) ){
                               boardArr[j][i] = 0;  
                           }
                             if(j== 4 && (boardArr[j-1][i] == 2 || boardArr[j-1][i] == 3)){
                               boardArr[j][i] = 0;  
                           }
                        }
                      
                    }
                    
                }
                if(piece == 2 && turn == "white"){
                    if(boardArr[j+1][i]==1 && boardArr[j-1][i]==1){
                        boardArr[j][i] = 0;   
                    }
                    if(boardArr[j][i+1]==1 && boardArr[j][i-1]==1 ){
                        boardArr[j][i] = 0;   
                    }
                    if(boardArr[5][5] == 0){
                         if((i== 4 || i == 6) && j == 5){
                             if(i== 6 && boardArr[j][i+1] == 1 ){
                                 boardArr[j][i] = 0;
                             }
                             if(i==4 && boardArr[j][i-1] == 1 ){
                                 boardArr[j][i] = 0;
                             }
                         }
                             
                         if((j== 4 || j == 6) && i == 5){
                              if(j== 6 && boardArr[j+1][i] == 1){
                                  boardArr[j][i] = 0;
                              }
                             if(j== 4 && boardArr[j-1][i] == 1){
                                    boardArr[j][i] = 0;
                             }
                         }
                    }
                }
        
            }
            if(j==0 || j == 10){
                if(piece == 1 && turn == "black"){
                    if((boardArr[j][i+1]==2 || boardArr[j][i+1]==2) && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3) ){
                        boardArr[j][i] = 0;   
                    }
                    if(boardArr[j][i+1]==4 && (boardArr[j][i-1]==2 || boardArr[j][i-1]==3) ){
                        boardArr[j][i] = 0;   
                    }
                     if((boardArr[j][i+1]==2 || boardArr[j][i-1]==3) && boardArr[j][i-1]==4 ){
                        boardArr[j][i] = 0;   
                    }
                }
                if(piece == 2 && turn == "white"){
                    if(boardArr[j][i+1]==1 && boardArr[j][i-1]==1){
                        boardArr[j][i] = 0;   
                    }
                    if(boardArr[j][i+1]==4 && boardArr[j][i-1]==1){
                        boardArr[j][i] = 0;   
                    }
                     if(boardArr[j][i+1]==1 && boardArr[j][i-1]==4 ){
                        boardArr[j][i] = 0;   
                    }
                
                }
            }
            if(i==0 || i == 10){
                if(piece == 1 && turn == "black"){
                    if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3) && ( boardArr[j-1][i]==2 || boardArr[j-1][i]==3 )){
                        boardArr[j][i] = 0; 
                    }
                    if(boardArr[j+1][i]==4 && ( boardArr[j-1][i]==2 || boardArr[j-1][i]==3) ){
                        boardArr[j][i] = 0; 
                    }
                     if((boardArr[j+1][i]==2 || boardArr[j+1][i]==3 )&& boardArr[j-1][i]==4 ){
                        boardArr[j][i] = 0; 
                    }
                }
                if(piece == 2 && turn == "white"){
                    if(boardArr[j+1][i]==1 && boardArr[j-1][i]==1 ){
                        boardArr[j][i] = 0; 
                    }
                      if(boardArr[j+1][i]==1 && boardArr[j-1][i]==4 ){
                        boardArr[j][i] = 0; 
                    }
                      if(boardArr[j+1][i]==4 && boardArr[j-1][i]==1 ){
                        boardArr[j][i] = 0; 
                    }
                }
                
            }
            
        }
    }
    return boardArr; 
}

function canKingMove(boardArr){
    //console.log("canKingMove function is running");
    for(i = 0; i <= 10 ; i++){
        for(j=0; j <=10 ; j++) { 
            if(boardArr[j][i]== 3){
                //console.log("y = " + j + " and x = " + i);
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

function checkGameOver(boardArr){
    var gameovermessage;
    var winner;
    var gameover = false;
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
                //console.log("whitecount = " +whitecount);
            }
            if(j==10 && i == 10 && whitecount==0){
               // console.log("inside whitecount = 0");
                var cankingmove = canKingMove(boardArr);
               // console.log("cankingmove = "+ cankingmove);
                if(cankingmove == false){
                    gameover = true;
                    gameovermessage = "No moves for white!";
                    winner = "black";
                }
                abort = true;
            }
        }
    }
    return { gameover: gameover, gameovermessage: gameovermessage, winner: winner}
}


 module.exports = { createBoardArray, checkCapture, checkGameOver }