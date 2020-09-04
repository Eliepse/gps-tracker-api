<template>
	<div id="map"></div>
</template>

<script>
	import Leaflet from 'leaflet';

	export default {
		props: {
			user_id: {
				type: Number,
				required: true
			},
			track_id: {
				type: Number,
				required: false
			}
		},
		data() {
			return {
				map: undefined
			}
		},
		mounted() {
			this.map = Leaflet.map('map', {
				attributionControl: false,
				zoomControl: true
			}).setView([48.865, 2.342], 12);

			Leaflet.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
				maxZoom: 20,
				id: 'eliepse/ck709otyo3hzd1inst8d3v08w',
				accessToken: 'pk.eyJ1IjoiZWxpZXBzZSIsImEiOiJjazR4em50MTEwNXgzM2tsanNwMmVram1nIn0.Bl7VfVdRNZBDN7ALsQZrwA'
			}).addTo(this.map);

			if (this.track_id) {
				this.$store.dispatch("loadTrack", {id:this.track_id, user_id:this.user_id})
					.then(() => {
						let line = Leaflet.polyline([], {
							opacity: .5,
							weight: 2,
							color: "#333333",
						});
						const track = this.$store.state.tracks[this.track_id];
						line.setLatLngs(track.locations.reduce((acc, location) => [...acc, location], []));
						line.addTo(this.map);
						//this.drawTracks(Object.values(this.$store.state.tracks))
						echo.channel(`App.Track.${this.track_id}`)
							.listen("LocationsStoredEvent", (data) => {
								this.$store.commit("addLocations", data.locations)
								data.locations.forEach(location => {
									line.addLatLng([location.latitude, location.longitude])
								})
							})
					})
			} else {
				this.$store.dispatch("loadUserTracks", this.user_id)
					.then(() => {
						this.drawTracks(Object.values(this.$store.state.tracks))
					})
			}
		},
		methods: {
			drawTracks(tracks) {
				let pCount = 0;
				tracks.forEach((track) => {
				  pCount += track.locations.length;
					let line = Leaflet.polyline([], {
						opacity: .5,
						weight: 2,
						color: "#333333",
					});
					line.setLatLngs(track.locations.reduce((acc, location) => [...acc, location], []));
					line.addTo(this.map);
				})
				console.info(`${pCount} points has been drawn.`)
			}
		}
	}
</script>

<style scoped>

</style>