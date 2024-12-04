import { db, storage } from "/js/firebase-config.js";
import { ref as dbRef, set } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js";
import { ref as storageRef, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js";

// Handle Form Submission
document.getElementById("rentingForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    // Collect form data
    const location = document.getElementById("location").value;
    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const pictures = document.getElementById("propertyPictures").files;

    // Upload Images to Firebase Storage
    const pictureURLs = [];
    for (const picture of pictures) {
        const fileRef = storageRef(storage, `propertyPictures/${picture.name}`);
        const snapshot = await uploadBytes(fileRef, picture);
        const downloadURL = await getDownloadURL(snapshot.ref);
        pictureURLs.push(downloadURL);
    }

    // Save Data to Firebase Realtime Database
    const propertyId = Date.now(); // Unique ID for the property
    await set(dbRef(db, `properties/${propertyId}`), {
        location,
        title,
        description,
        pictureURLs,
    });

    alert("Property listed successfully!");
});
