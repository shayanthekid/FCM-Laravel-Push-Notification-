/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
  apiKey: "AIzaSyCustYLjU5ChZEWViBIUbjds3BA9N48tFM",
  authDomain: "push-notification-65342.firebaseapp.com",
  projectId: "push-notification-65342",
  storageBucket: "push-notification-65342.appspot.com",
  messagingSenderId: "289181547379",
  appId: "1:289181547379:web:d4860b80ceb6e30475a306",
  measurementId: "G-K7VBV52PT7"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log(
    "[firebase-messaging-sw.js] Received background message ",
    payload,
  );
  /* Customize notification here */
  const notificationTitle = "Background Message Title";
  const notificationOptions = {
    body: "Background Message body.",
    icon: '/firebase-logo.png',
    click_action: "http://127.0.0.1:8000/home"
  };

  return self.registration.showNotification(
    notificationTitle,
    notificationOptions,
  );
});
self.addEventListener("push", (payload) => {
  console.log(payload);
  let response = payload.data && payload.data.text();
  let title = JSON.parse(response).notification.title;
  let body = JSON.parse(response).notification.body;
  let icon = JSON.parse(response).notification.image;

  payload.waitUntil(
      self.registration.showNotification(title, { body, icon, image, data: { url: JSON.parse(response).data.url } })
  )
});

self.addEventListener('notificationclick', function(payload) {
  payload.notification.close();
  payload.waitUntil(
      clients.openWindow(payload.notification.data.url)
  );
}); 