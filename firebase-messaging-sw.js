importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging-compat.js');

// Configurações do Firebase
const firebaseConfig = {
    "your firebase config here"
  };

// Inicializar o Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('Mensagem recebida em segundo plano:', payload);
    const { title, body, icon } = payload.notification;
    self.registration.showNotification(title, { body, icon });
});
