import { gpsUpdate } from "./updateGPS";
import { Global } from "./globals";

let gpsTrackerID;

export const enableTracking = () => {
    gpsTrackerID = navigator.geolocation.watchPosition(
        gpsUpdate,
        () => {}, {
            enableHighAccuracy: true
        }
    );
    Global.lastTS = Date.now();
};

export const disableTracking = () => {
    navigator.geolocation.clearWatch(gpsTrackerID);
};