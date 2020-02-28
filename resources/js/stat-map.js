import Axios from 'axios';
import Leaflet from 'leaflet';

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const map = Leaflet
	.map('map', {
		attributionControl: false,
		zoomControl: true
	})
	.setView([48.865, 2.342], 12);

Leaflet
	.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
		maxZoom: 20,
		id: 'eliepse/ck709otyo3hzd1inst8d3v08w',
		accessToken: 'pk.eyJ1IjoiZWxpZXBzZSIsImEiOiJjazR4em50MTEwNXgzM2tsanNwMmVram1nIn0.Bl7VfVdRNZBDN7ALsQZrwA'
	})
	.addTo(map);

/**
 * @var array data
 * */

let count = 0;

data.forEach(function (track) {
	let line = Leaflet.polyline([], {
		opacity: .5,
		weight: 3,
		color: "#8e7b2f",
	});
	line.setLatLngs(
		track.points.reduce((acc, point) => {
			acc.push([point.latitude, point.longitude]);
			count++;
			return acc;
		}, [])
	);
	line.addTo(map);
});

console.log(count + " points drawn");