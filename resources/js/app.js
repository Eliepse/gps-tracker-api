import Vue from "vue";
import Axios from 'axios';
import mapAll from './components/mapAllComponent';
import store from './store';

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = Axios;

new Vue({
	el: '#app',
	components: {mapAll},
	store
});
