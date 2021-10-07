import {API_KEY, AUTH_DOMAIN, PROJECT_ID, STORAGE_BUCKET, MESSAGING_SENDER_ID, APP_ID, MEASUREMENT_ID, DATABASE_URL} from '../config.js'
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-app.js'
    
// If you enabled Analytics in your project, add the Firebase SDK for Google Analytics
//import { analytics } from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-analytics.js'

// Add Firebase products that you want to use
import { getAuth, signInWithPopup, onAuthStateChanged, GoogleAuthProvider, FacebookAuthProvider} from 'https://www.gstatic.com/firebasejs/9.1.1/firebase-auth.js'

import { getDatabase, ref, set, get, child } from "https://www.gstatic.com/firebasejs/9.1.1/firebase-database.js"
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

console.log("db", db);

const facebookButton = document.querySelector('#facebook');

facebookButton.onclick = () => {
  facebookSignIn();
}


function facebookSignIn() {
  const provider = new FacebookAuthProvider();
  signInWithPopup(auth, provider)
  .then((result) => {
    // The signed-in user info.
    const user = result.user;

    // This gives you a Facebook Access Token. You can use it to access the Facebook API.
    const credential = FacebookAuthProvider.credentialFromResult(result);
    const accessToken = credential.accessToken;
    readUserData(user.uid).then((result) => {
      console.log("user data result", user);
      if(!result) {
        writeUserData(user.uid, user.displayName, user.email, user.photoURL);
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


//reading user data once to check if user exists in the system
function readUserData(userId) {
  const dbRef = ref(getDatabase());
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





