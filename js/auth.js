import { auth } from "./initFirebase";
import { signInWithEmailAndPassword } from "firebase/auth";

document.getElementById('login-form').addEventListener('submit', (event) => {
  event.preventDefault();
  
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  console.log(`${email} ${password}`)

  signInWithEmailAndPassword(auth, email, password)
    .then((userCredential) => {
      // User berhasil sign-in
      const user = userCredential.user;
      if (user != null) {

        alert('Sign-in successful!');
        console.log(user);
      } else {
        console.log("error")
      }
    })
    .catch((error) => {
      // Jika ada error
      const errorMessage = error.message;
      document.getElementById('error-message').innerText = errorMessage;
    });
    console.log("Functon done run")
});
