const io = require('socket.io')();
const {createBoardArray, checkCapture, checkGameOver} = require('./game')
const {makeid} =  require('./utils')

const state = {}
const clientRooms = {}

io.on('connection', client => {

    client.on('newGame', handleNewGame)
    client.on('newTurn', handleTurn)
    client.on('joinGame', handleJoinGame)

    function handleNewGame() {
        let roomName = makeid(5)
        clientRooms[client.id] = roomName; 
        client.emit('gameCode', roomName);
        state[roomName] = {
            boardArray: createBoardArray(),
            turn: "black",
            roomName: ""
        }

        client.join(roomName);
        client.number = 1;
        client.emit('init', 1)
    }

    function handleJoinGame(roomName) {
        const room = io.sockets.adapter.rooms[roomName];
        
        let allUsers;
        if (room) {
            allUsers = room.sockets;
        }

        let numClients = 0;
        if (allUsers) {
            numClients = Object.keys(allUsers).length
        }

        if(numClients === 0) {
            client.emit('unknownGame');
            return;
        } else if (numClients > 1 ){
            client.emit('tooManyPlayers');
            return
        }

        clientRooms[client.id] = roomName;

        client.join(roomName);
        client.number = 2;
        client.emit('init', 2);
        state[roomName].roomName = roomName;
        emitGameStart(roomName, state[roomName]);
    }

    function handleTurn(data) {
        currentState = JSON.parse(data);
        //console.log("currentState", currentState);

        const gameoverData = checkGameOver(currentState.boardArray);
        //console.log("gameover", gameoverData);

        if(!gameoverData.gameover) {
            if(state[currentState.roomName].turn === "black"){
                state[currentState.roomName].turn = "white"
            } else {
                state[currentState.roomName].turn = "black"
            }
            state[currentState.roomName].boardArray = checkCapture(currentState.boardArray, state[currentState.roomName].turn)
            emitGameState(currentState.roomName, state[currentState.roomName])
        } else {
            emitGameOver(currentState.roomName, gameoverData)
            state[currentState.roomName] = null;
        }
    }

    function emitGameStart(roomName, state) {
        io.sockets.in(roomName)
            .emit('gameStart', JSON.stringify(state))
    }

    function emitGameState(roomName, state) {
        io.sockets.in(roomName)
            .emit('boardUpdate', JSON.stringify(state))
    }

    function emitGameOver(roomName, gameOverData) {
        io.sockets.in(roomName)
            .emit('gameOver', JSON.stringify(gameOverData))
    }
    
});

io.listen(3000)



  