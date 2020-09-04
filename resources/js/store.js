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
		addLocations: (state, locations) => {
			locations.forEach(location => {
				const track = state.tracks[location.track_id];
				if (!track.locations) {
					track.locations = [];
				}
				track.locations.push(location)
			})
		},
	},
	actions: {
		loadTrack: async function (ctx, {id, user_id}) {
			try {
				const response = await axios.get(`/api/users/${user_id}/tracks`, {params: {ids: [id]}});
				await ctx.commit("addTracks", response.data);
			} catch (err) {
				console.error(err);
			}
		},
		loadUserTracks: async function (ctx, user_id) {
			try {
				const response = await axios.get(`/api/users/${user_id}/tracks`);
				await ctx.commit("addTracks", response.data);
			} catch (err) {
				console.error(err);
			}
		}
	}
});