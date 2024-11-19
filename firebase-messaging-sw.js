importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging-compat.js');

// Configurações do Firebase
const firebaseConfig = {
    apiKey: "AIzaSyD_C3FeIx-ZVr37LuxhXwBsR3xe93Et4iM",
    authDomain: "testzurich-34905.firebaseapp.com",
    projectId: "testzurich-34905",
    storageBucket: "testzurich-34905.firebasestorage.app",
    messagingSenderId: "796976736672",
    appId: "1:796976736672:web:bde687280d1d45a8f45cc5"
  };

// Inicializar o Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('Mensagem recebida em segundo plano:', payload);
    const { title, body, icon } = payload.notification;
    self.registration.showNotification(title, { body, icon });
});
