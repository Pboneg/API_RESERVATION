import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// Laravel Echo pour écouter les événements.
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'b038bf50e19df075d9d9',
    cluster: 'mt1',
    forceTLS: true,
});
