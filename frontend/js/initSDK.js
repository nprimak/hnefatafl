

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries
window.onload = function() {
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const config = {
    apiKey: "AIzaSyAwXy67n2nVrAgqpvemCHzQ0QWzZ9T1gtM",
    authDomain: "hnefatafl-f3b92.firebaseapp.com",
    projectId: "hnefatafl-f3b92",
    storageBucket: "hnefatafl-f3b92.appspot.com",
    messagingSenderId: "986841964034",
    appId: "1:986841964034:web:4f08298884c1ce4387911b",
    measurementId: "G-Q4C8CBBBTP"
  };

// Initialize Firebase
  firebase.initializeApp(config);

  var provider = new firebase.auth.FacebookAuthProvider();

  console.log("provider", provider);

  let user = "";

  firebase.auth().getRedirectResult().then(function(result) {
    user = result;
    console.log("user", user);
  })


  const facebookButton = document.querySelector('#facebook');

  facebookButton.onclick = () => {

    if (user.user === null) {
      firebase
      .auth()
      .signInWithPopup(provider)
      .then((result) => {
        /** @type {firebase.auth.OAuthCredential} */
        var credential = result.credential;
  
        // The signed-in user info.
        user = result.user;

        console.log("user", user);
  
        // This gives you a Facebook Access Token. You can use it to access the Facebook API.
        var accessToken = credential.accessToken;
  
        // ...
      })
      .catch((error) => {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
  
        // ...
      });
    }
  }


}
