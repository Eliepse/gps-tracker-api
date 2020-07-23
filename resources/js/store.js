import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

export default new Vuex.Store({
	strict: true,
	state: {
		tracks: {},
	},
	getters: {},
	mutations: {
		addTracks: (state, tracks) => tracks.forEach(track => { state.tracks[track.id] = track }),
		addLocation: (state, location) => {
			const track = state.tracks[location.track_id];
			if (!isArray(track.locations)) {
				track.locations = [];
			}
			track.locations.push(location);
		},
	},
	actions: {
		loadTrack: async function (ctx, id) {
			axios.get(`/api/tracks/${id}`)
				.then((r) => {
					ctx.commit("addTracks", [r.data])
				})
				.catch((r) => {
					console.error(r)
				});
		},
		loadUserTracks: async function (ctx, user_id) {
			axios.get(`/api/apps/${user_id}/tracks`)
				.then((r) => {
					ctx.commit("addTracks", r.data)
				})
				.catch((r) => {
					console.error(r)
				});
		}
	}
});