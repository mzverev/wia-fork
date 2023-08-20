import Pusher from 'pusher-js';
Pusher.logToConsole = true;

const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'eu',
});

export const channel = pusher.subscribe('image-processing');
