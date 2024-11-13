/*
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

*/
/*
// Using the Firebase Admin SDK
const admin = require('firebase-admin');
admin.initializeApp({
  credential: admin.credential.cert('path/to/serviceAccountKey.json')
});

const auth = admin.auth();

// Create a new user with the "admin" claim
auth.createUser({
  email: 'newadmin@example.com',
  password: 'password123',
  displayName: 'New Admin User',
  disabled: false,
  customClaims: {
    admin: true
  }
})
.then(userRecord => {
  console.log('Successfully created new user:', userRecord.uid);
})
.catch(error => {
  console.log('Error creating new user:', error);
});
*/