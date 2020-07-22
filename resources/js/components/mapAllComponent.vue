<template>
	<div id="map"></div>
</template>

<script>
	import Leaflet from 'leaflet';

	export default {
		props: {
			app_id: {
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
				axios.get(`/api/tracks/${this.track_id}`)
					.then((r) => {
						this.drawTracks([r.data])
					})
					.catch((r) => {
						console.error(r)
					})
			} else {
				axios.get(`/api/apps/${this.app_id}/tracks`)
					.then((r) => {
						this.drawTracks(r.data)
					})
					.catch((r) => {
						console.error(r)
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
						track.points.reduce((acc, point) => {
							acc.push([point.latitude, point.longitude]);
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