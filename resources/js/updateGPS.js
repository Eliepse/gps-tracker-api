import { DevUI } from 'dev-ui'
import { Global, Map } from './globals'
import pms from 'pretty-ms';
import Leaflet from 'leaflet';

Leaflet.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
	maxZoom: 20,
	id: 'mapbox/streets-v11',
	accessToken: 'pk.eyJ1IjoiZWxpZXBzZSIsImEiOiJjazR4em50MTEwNXgzM2tsanNwMmVram1nIn0.Bl7VfVdRNZBDN7ALsQZrwA'
}).addTo(Map);

Global.traceLine.addTo(Map);
Global.cursor.addTo(Map);

export const gpsUpdate = (geolocation) => {
	let point = {
		coords: [geolocation.coords.latitude, geolocation.coords.longitude],
		accuracy: geolocation.coords.accuracy,
		speed: geolocation.coords.speed,
		time: geolocation.timestamp,
	};

	Global.cursor.setLatLng(point.coords);
	Global.cursor.setRadius(point.accuracy.toFixed(2))

	if (Global.drawOnMap && point.accuracy <= Global.accuracyLimitMax) {
		Global.trace.push(point);
		updateMap();
	} else if (Global.followLocation) {
		Map.panTo(point.coords);
	}

	DevUI.get('traceCount').text(Global.trace.length);
	DevUI.get('lastPoint').text('(' + point.coords[0].toFixed(4) + ',' + point.coords[1].toFixed(4) + ') ' + pms(point.time - Global.lastTS) + ' ago');
	DevUI.get('accuracy').text(point.accuracy.toFixed(2) + ' m | ' + ((point.speed || 0) * 3.8).toFixed(2) + ' km/h');

	Global.lastTS = geolocation.timestamp;
}

export const updateMap = () => {
	let points = Global.trace.reduce((acc, point) => {
		acc.push(point.coords);
		return acc;
	}, []);
	Global.traceLine.setLatLngs(points);
	if (points.length > 0 && Global.followLocation) {
		Map.fitBounds(Global.traceLine.getBounds());
	}
}