// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAuth } from "firebase/auth";
import { getDocs, getFirestore, doc } from "firebase/firestore";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCKxA6Mo6OTiuwynSRDF12oUHoXv9KC2Zk",
  authDomain: "rumah-dan-villa.firebaseapp.com",
  projectId: "rumah-dan-villa",
  storageBucket: "rumah-dan-villa.appspot.com",
  messagingSenderId: "1017744462449",
  appId: "1:1017744462449:web:50e3d09677e4ebb24d01d0"
};

// Initialize Firebase
export const app = initializeApp(firebaseConfig);
// Init auth
export const auth = getAuth();
// init firestore
export const firestore = getFirestore();


import { auth } from "./js/initFirebase";
import { signInWithEmailAndPassword } from "firebase/auth";

//Sign in 
/*
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



document.getElementById("signup-form").addEventListener("submit", (e) => {
    e.preventDefault(); // Mencegah halaman refresh
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    
    signUp(email, password);
});



//submit buttpm sing in
const submit = document.getElementById('submit').value;
*/


// Import Firebase Auth library di bagian atas (jika belum)
import { getAuth, createUserWithEmailAndPassword } from "firebase/auth";

// Event listener untuk tombol submit
document.getElementById("submit").addEventListener("click", function (event) {
    event.preventDefault(); // Mencegah refresh halaman

    // Ambil nilai email dan password dari input form
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Inisialisasi Firebase Auth
    const auth = getAuth();

    // Fungsi sign up Firebase
    createUserWithEmailAndPassword(auth, email, password)
        .then((userCredential) => {
            // Jika berhasil Sign Up
            const user = userCredential.user;
            alert("Creating an account...");
            
            // Redirect ke halaman index.html
            window.location.href = "index.html";
        })
        .catch((error) => {
            // Menangkap dan menampilkan error jika terjadi kesalahan
            const errorMessage = error.message;
            alert(errorMessage);
        });
});