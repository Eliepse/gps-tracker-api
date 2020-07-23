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
				this.$store.dispatch("loadTrack", this.track_id)
					.then(() => {
						this.drawTracks(Object.values(this.$store.state.tracks))
						//echo.channel(`App.Track.${this.track_id}`)
						//	.listen("LocationsStoredEvent", (locations) => {
						//		console.debug(locations);
						//	})
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
					let line = Leaflet.polyline([], {
						opacity: .5,
						weight: 2,
						color: "#333333",
					});
					line.setLatLngs(
						track.locations.reduce((acc, location) => {
							acc.push([location.latitude, location.longitude]);
							pCount++;
							return acc;
						}, [])
					);
					line.addTo(this.map);
				})
				console.info(`${pCount} points has been drawn.`)
			}
		}
	}
</script>

<style scoped>

</style>