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


 module.exports = { createBoardArray }