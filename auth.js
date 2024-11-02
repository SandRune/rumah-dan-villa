// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.0.0/firebase-auth.js";
import { getDatabase, ref, set } from "https://www.gstatic.com/firebasejs/9.0.0/firebase-database.js";

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
// Initialize Auth and Database
export const auth = getAuth(app);
export const database = getDatabase(app);
export { createUserWithEmailAndPassword };