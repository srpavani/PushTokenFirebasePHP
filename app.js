// Importar os módulos necessários do Firebase
import { initializeApp } from 'https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/11.0.2/firebase-messaging.js';

// Configurações do Firebase
const firebaseConfig = {
    "your firebase config aqui"
  };

// Inicializar o Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Registrar o Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/FirebasePush/firebase-messaging-sw.js')
        .then((registration) => {
            console.log('Service Worker registrado com sucesso:', registration);
        })
        .catch((error) => {
            console.error('Erro ao registrar o Service Worker:', error);
        });
}

// Solicitar permissão para notificações e obter o token FCM
document.getElementById('subscribe').addEventListener('click', async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
            // Use o Service Worker registrado explicitamente
            const registration = await navigator.serviceWorker.getRegistration('/FirebasePush/firebase-messaging-sw.js');
            if (registration) {
                const currentToken = await getToken(messaging, {
                    vapidKey: 'BCdDl_TTC9jRRx8WxqXHgnvnltJpQ4dY2X1sLYBvSrz6WO5UD2SinT-hG1aMfllUkPK9LZrLsj2qFQhbJ15UvSg',
                    serviceWorkerRegistration: registration // Especifica o SW
                });
                if (currentToken) {
                    console.log('Token FCM obtido:', currentToken);
                    // Exibir o token no HTML ou usá-lo conforme necessário
                } else {
                    console.log('Nenhum token disponível. Solicite permissão ao usuário.');
                }
            } else {
                console.error('Service Worker não encontrado.');
            }
        } else {
            console.log('Permissão de notificação não concedida.');
        }
    } catch (error) {
        console.error('Erro ao obter o token FCM:', error);
    }
});
onMessage(messaging, (payload) => {
    console.log('Mensagem recebida com o app em primeiro plano:', payload);

    const { title, body, icon } = payload.notification;

    console.log('Tentando exibir a notificação...');
    if (Notification.permission === 'granted') {
        try {
            new Notification(title, { body, icon });
            console.log('Notificação exibida com sucesso.');
        } catch (error) {
            console.error('Erro ao exibir a notificação:', error);
        }
    } else {
        console.log('Permissão para notificações não concedida ou removida.');
    }
});
