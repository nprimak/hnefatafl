import {API_KEY, AUTH_DOMAIN, PROJECT_ID, STORAGE_BUCKET, MESSAGING_SENDER_ID, APP_ID, MEASUREMENT_ID, DATABASE_URL} from '../config.js'
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-app.js'
    
// If you enabled Analytics in your project, add the Firebase SDK for Google Analytics
//import { analytics } from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-analytics.js'

// Add Firebase products that you want to use
import { getAuth, signInWithPopup, onAuthStateChanged, GoogleAuthProvider, FacebookAuthProvider} from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-auth.js'

import { getDatabase, ref, set, get, child, update, query } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-database.js"
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const config = {
  apiKey: API_KEY,
  authDomain: AUTH_DOMAIN,
  projectId: PROJECT_ID,
  storageBucket: STORAGE_BUCKET,
  messagingSenderId: MESSAGING_SENDER_ID,
  appId: APP_ID,
  measurementId: MEASUREMENT_ID,
  // For databases not in the us-central1 location, databaseURL will be of the
  // form https://[databaseName].[region].firebasedatabase.app.
  // For example, https://your-database-123.europe-west1.firebasedatabase.app
  databaseURL: DATABASE_URL
};

// Initialize Firebase
const app = initializeApp(config);
const auth = getAuth(app);
const db = getDatabase();
const dbRef = ref(getDatabase());

console.log("db", db);

const facebookButton = document.querySelector('#facebook');
const waitingScreen = document.getElementById('waiting');
const startScreen = document.getElementById('start');

facebookButton.onclick = () => {
  facebookSignIn();
}

function showWaitingScreen() {
  waitingScreen.style.display = "inline"
  startScreen.style.display = "none"
}


function facebookSignIn() {
  const provider = new FacebookAuthProvider();
  signInWithPopup(auth, provider)
  .then((result) => {

    showWaitingScreen();
    // The signed-in user info.
    const user = result.user;

    // This gives you a Facebook Access Token. You can use it to access the Facebook API.
    const credential = FacebookAuthProvider.credentialFromResult(result);
    const accessToken = credential.accessToken;
    readUserData(user.uid).then((result) => {
      if(!result) {
        console.log("user", user);
        writeUserData(user.uid, user.displayName, user.email, user.photoURL);
        
      } else {
        if(result.room_id) {
          console.log("player has a room");
          // check if another player has joined their game
          readGameData(result.room_id).then((roomData) => {
            console.log(roomData);
            if(!roomData.player2) {
              //display WAITING FOR SECOND PLAYER 
              // offer to invite someone? 
            } else {
              // load the existing game
            }
          })
        } 
      
      }
    });
    
    // ...
  })
  .catch((error) => {
    // Handle Errors here.
    const errorCode = error.code;
    const errorMessage = error.message;
    // The email of the user's account used.
    const email = error.email;
    // The AuthCredential type that was used.
    const credential = FacebookAuthProvider.credentialFromError(error);

    // ...
  });

}

function displayGameLobby() {
  // need code to fetch list of gameRooms from db that don't have a player2 
  get(child(dbRef, `rooms/`)).then((snapshot) => {
    if (snapshot.exists()) {
      const rooms = snapshot.val()
      const roomKeys = Object.keys(rooms);
      const lobbyRow = document.getElementById("lobby-item");
      const lobbyTable = document.getElementById("lobby-table");
      roomKeys.forEach((key, index) => {
        console.log("index", rooms[key]);
        let lobbyRowClone = lobbyRow.content.cloneNode(true);
        readUserData(rooms[key].player1).then(user => {
          lobbyRowClone.querySelector('img').src = user.profile_picture;
          lobbyRowClone.querySelector('span').innerHTML = "Play against " + user.username; //TODO change to nickname 
          lobbyTable.appendChild(lobbyRowClone)
        })
      })
    
    } else {
      console.log("No data available");
    }
  }).catch((error) => {
    console.error(error);
  });
  // populate a table on the frontend to display gameRooms waiting for a player
  // have a "join game" button 
}


//reading user data once to check if user exists in the system
function readGameData(roomId) {
  return get(child(dbRef, `rooms/${roomId}`)).then((snapshot) => {
    if (snapshot.exists()) {
      return snapshot.val()
    } else {
      console.log("No data available");
    }
  }).catch((error) => {
    console.error(error);
  });
 }

 function readUserData(userId) {
 
  return get(child(dbRef, `users/${userId}`)).then((snapshot) => {
    if (snapshot.exists()) {
      return snapshot.val()
    } else {
      console.log("No data available");
    }
  }).catch((error) => {
    console.error(error);
  });
 }


  function writeUserData(userId, name, email, imageUrl) {
    set(ref(db, 'users/' + userId), {
      username: name,
      email: email,
      profile_picture : imageUrl
    });
  }

  function createNewGameRoom(roomId, userId) {
    update(ref(db, 'users/' + userId), {
      room_id: roomId
    })
    set(ref(db, 'rooms/' + roomId), {
      player1: userId,
      player2: null,
      board: createBoardArray(),
      turn: "white"
    });
  }

  function updateGameRoomForPlayer() {

  }

  function makeid() {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
    var charactersLength = 5; //change to affect room code length
    for( var i = 0; i < charactersLength; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength)) 
    }
    return result;
}

function createBoardArray(){
  const boardArr = [];
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

displayGameLobby()



export {db};
