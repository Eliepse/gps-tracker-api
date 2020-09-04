<template>
  <div id="map"></div>
</template>

<script>
import Leaflet from 'leaflet';

const deg2RadFactor = Math.PI / 180;
const color1 = [245, 159, 0];
const color2 = [25, 113, 194];

function getNewPolyline() {
  return Leaflet.polyline([], {opacity: .5, weight: 4, color: "#333333"});
}

function distance(a, b) {
  const x = deg2RadFactor * (Math.abs(b[1]) - Math.abs(a[1])) * Math.cos(deg2RadFactor * (a[0] + b[0]) / 2);
  const y = deg2RadFactor * (a[0] - b[0]);
  return Math.sqrt(x * x + y * y) * 6378137;
}

function getSpeedColor(speed, min, max) {
  //function pickHex(color1, color2, weight) {
  const w1 = (speed - min) / max;
  const w2 = 1 - w1;
  const rgb = [
    Math.round(color1[0] * w1 + color2[0] * w2).toString(16),
    Math.round(color1[1] * w1 + color2[1] * w2).toString(16),
    Math.round(color1[2] * w1 + color2[2] * w2).toString(16),
  ];
  return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
}

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
    this.map = Leaflet.map('map', {attributionControl: false, zoomControl: true})
        .setView([48.865, 2.342], 12);

    Leaflet.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      maxZoom: 20,
      id: 'eliepse/ck709otyo3hzd1inst8d3v08w',
      accessToken: 'pk.eyJ1IjoiZWxpZXBzZSIsImEiOiJjazR4em50MTEwNXgzM2tsanNwMmVram1nIn0.Bl7VfVdRNZBDN7ALsQZrwA'
    }).addTo(this.map);

    if (this.track_id) {
      this.$store.dispatch("loadTrack", {id: this.track_id, user_id: this.user_id})
          .then((metas) => {
            const track = this.$store.state.tracks[this.track_id];
            const line = getNewPolyline();

            for (let i = 0; i < track.locations.length - 1; i++) {
              const a = track.locations[i];
              const b = track.locations[i + 1];
              const speedKmH = (distance(a, b) / (b[2] - a[2])) * 3.6;
              getNewPolyline()
                  .setLatLngs([a, b]).addTo(this.map)
                  .setStyle({
                    color: getSpeedColor(Math.min(speedKmH, 40), 0, 40),
                    opacity: 1,
                    weight: 8,
                  })
                  .addTo(this.map);
            }

            line.addTo(this.map);
            this.map.fitBounds(metas.bounds);

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
          .then((metas) => {
            this.drawTracks(Object.values(this.$store.state.tracks))
            this.map.fitBounds(metas.bounds);
          })
    }
  },
  methods: {
    drawTracks(tracks) {
      let pCount = 0;
      tracks.forEach((track) => {
        pCount += track.locations.length;
        getNewPolyline().setLatLngs(track.locations).addTo(this.map);
      })
      console.info(`${pCount} points has been drawn.`)
    }
  }
}
</script>

<style scoped>

</style>