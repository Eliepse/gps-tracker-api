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

const dataLength = data.length;

function getColor(index) {
	switch(index) {
		case dataLength - 4: return "#495057";
		case dataLength - 3: return "#343a40";
		case dataLength - 2: return "#212529";
		case dataLength - 1: return "#fcc419";
		default: return "#868e96";
	}
}

let count = 0;

data.forEach(function (track, i) {
	let line = Leaflet.polyline([], {
		opacity: .75,
		weight: 2,
		color: getColor(i),
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