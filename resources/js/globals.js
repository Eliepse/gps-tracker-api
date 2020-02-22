import Leaflet from 'leaflet';

export const Map = Leaflet.map('map', {
        attributionControl: false,
        zoomControl: false
    })
    .setView([48.86, 2.34], 20);

export const Global = {
    accuracyLimitMax: 100,
    followLocation: true,
    drawOnMap: false,
    trace: [],
    traceLine: Leaflet.polyline([]),
    cursor: Leaflet.circle(Map.getCenter(), {
        radius: 2,
        stroke: false
    }),
    lastTS: Date.now()
};