import Vue from "vue"
import mapAll from './components/mapAllComponent';

require('./bootstrap');

new Vue({
	el: '#app',
	components: {mapAll}
});
