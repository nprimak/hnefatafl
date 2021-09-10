const io = require('socket.io')();
const {createBoardArray, checkCapture, checkGameOver} = require('./game')


io.on('connection', client => {
    const state = {
        boardArray: createBoardArray(),
        turn: "black",
    }

    client.emit('gameStart', JSON.stringify(state))
    client.on('newTurn', handleTurn)

    function handleTurn(data) {
        console.log("boardArr", JSON.parse(data).boardArr);
        state.boardArray = JSON.parse(data).boardArr; 


        const gameoverData = checkGameOver(state.boardArray);
        console.log("gameover", gameoverData);

        if(!gameoverData.gameover) {
            if(state.turn === "black"){
                state.turn = "white"
            } else {
                state.turn = "black"
            }
            state.boardArray = checkCapture(state.boardArray, state.turn)
            client.emit('boardUpdate', JSON.stringify(state))
        } else {
            client.emit('gameOver', JSON.stringify(gameoverData))
        }
    }
    
});

io.listen(3000)



  