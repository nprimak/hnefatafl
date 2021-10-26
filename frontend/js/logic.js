// deleted part of if statement that was checking x and y coordinates.. not sure why that was there
function checkCapture(boardArr, turn){
    for(let i = 0; i <= 10 ; i++){
        for(let j=0; j <=10 ; j++) { 
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
    for(let i = 0; i <= 10 ; i++){
        for(let j=0; j <=10 ; j++) { 
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
    let whitecount = 0;
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


export {checkCapture, checkGameOver }