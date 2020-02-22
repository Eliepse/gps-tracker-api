import { DevUI } from 'dev-ui';
import { Global } from './globals';
import { enableTracking, disableTracking } from './gps';
import { updateMap } from './updateGPS'
import 'axios'
import Axios from 'axios';

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//Axios.defaults.headers.common['X-CSRF-TOKEN'] = window.document.querySelector('meta[name=csrf-token]').getAttribute('content');

DevUI.add(DevUI.LabelText('lastPoint', 'lastPoint ', '/'));
DevUI.add(DevUI.LabelText('accuracy', 'Accuracy  ', '/'));
DevUI.add(DevUI.LabelText('traceCount', 'traceCount', '0'));
DevUI.add(DevUI.LabelText('accuracyLimitMax', 'accuracyLimiter', Global.accuracyLimitMax + ' m'));
DevUI.add(DevUI.Space('settingsSpace'));
DevUI.add(DevUI.Button('clear', 'Clear trace')).listen(() => {
	Global.trace = [];
	DevUI.get('traceCount').text(Global.trace.length);
	updateMap();
});
DevUI.add(DevUI.Switch('follow', 'Follow location', Global.followLocation))
	.listenOn(() => {
		Global.followLocation = true;
	})
	.listenOff(() => {
		Global.followLocation = false;
	});
DevUI.addOn('BR', DevUI.Switch('tracking', 'Tracking', false))
	.listenOn(enableTracking)
	.listenOff(disableTracking);
DevUI.addOn('BR', DevUI.Space('actionsSpace'));
DevUI.addOn('BR', DevUI.Switch('draw', 'Draw', false))
	.listenOn(() => {
		Global.drawOnMap = true;
	})
	.listenOff(() => {
		Global.drawOnMap = false;
	});
DevUI.addOn('BR', DevUI.Space('actionsSpace'));
DevUI.addOn('BR', DevUI.Button('send', 'Send to server'))
	.listen(() => {
		// Axios.post('https://sweatdb.agency-ye.com/api/tracks', {nodes:Global.trace})
		Axios.post('/tracks', { nodes: Global.trace }, {
				'headers': {
					//'Accept': 'application/json',
					//'Authorization': 'Bearer J43AZMpP0rG2UeGKPLCU7Jns3PtB1FYLaq1OlLq45hPoFmX8GOla77bvIkFW58L02NuCmPhXX5Q3hztk'
				}
			})
			.then((r) => {
				 alert('Success ! - ' + r.data.url);
			})
			.catch((e) => {
				 alert('Error ! - ' + e.message);
			});
	});