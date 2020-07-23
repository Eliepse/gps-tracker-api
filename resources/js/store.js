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
			try {
				const response = await axios.get(`/api/tracks/${id}`);
				await ctx.commit("addTracks", [response.data]);
			} catch (err) {
				console.error(err);
			}
		},
		loadUserTracks: async function (ctx, user_id) {
			try {
				const response = await axios.get(`/api/apps/${user_id}/tracks`);
				await ctx.commit("addTracks", response.data);
			} catch (err) {
				console.error(err);
			}
		}
	}
});