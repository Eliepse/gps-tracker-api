import Vue from "vue";
import Axios from 'axios';
import Echo from 'laravel-echo';
import mapAll from './components/mapAllComponent';
import store from './store';

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = Axios;
window.io = require('socket.io-client');
window.echo = new Echo({
	broadcaster: 'socket.io',
	host: window.location.hostname + ':6001',
	namespace: 'App.Events'
});

new Vue({
	el: '#app',
	components: {mapAll},
	store
});
