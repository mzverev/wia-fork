import Pusher from 'pusher-js';

const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'eu',
});

export const channel = pusher.subscribe('image-processing');
