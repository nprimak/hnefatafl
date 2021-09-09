const io = require('socket.io')();
const {createBoardArray} = require('./game')


io.on('connection', client => {
    const state = {
        boardArray: createBoardArray(),
        turn: "black",
    }

    startGameTurns(client, state)
    
});

function startGameTurns(client, state) {
    const winner = gameLoop(state)

    if(!winner) {
        client.emit('gameState', JSON,stringiy(state))
    } else {
        client.emit('gameOver')
    }
}

io.listen(3000)



  