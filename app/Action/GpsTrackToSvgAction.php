<?php


namespace App\Action;


use App\GpsTrack;

class GpsTrackToSvgAction
{
	private $zoomFactor = 300000;
	private $scale;


	public function __construct($scale = 1)
	{
		$this->scale = $scale === 0 ? $this->zoomFactor : $scale * $this->zoomFactor;
	}


	public function __invoke(GpsTrack $track): string
	{
		// extract coordinates
		$coordinates = array_map(function (array $node) {
			return $node['coords'];
		}, $track->nodes);

		// check bounds
		$box = [
			'top' => $coordinates[0][0], // top
			'right' => $coordinates[0][1], // right
			'bottom' => $coordinates[0][0], // bottom
			'left' => $coordinates[0][1], // left
		];

		foreach ($coordinates as $coord) {
			$box['top'] = max($coord[0], $box['top']);
			$box['right'] = max($coord[1], $box['right']);
			$box['bottom'] = min($coord[0], $box['bottom']);
			$box['left'] = min($coord[1], $box['left']);
		}

		// normalize points
		$origin = ['x' => $box['left'], 'y' => $box['bottom']];
		$size = ['w' => $box['right'] - $origin['x'], 'h' => $box['top'] - $origin['y']];

		$coordinates = array_map(function ($coord) use ($origin, $size) {
			$x = ($coord[1] - $origin['x']);
			$y = ($coord[0] - $origin['y']);

			return [
				round($x * $this->scale, 2),
				round($y * $this->scale, 2),
			];
		}, $coordinates);

		// draw path
		$first = array_shift($coordinates);

		$path = array_reduce($coordinates, function (string $carry, $coord) {
			return $carry . " L{$coord[1]} {$coord[0]}";
		}, "M{$first[1]} {$first[0]}");

		$viewbox = 'width="' . round($size['h'] * $this->scale) . '" height="' . round($size['w'] * $this->scale) . '"';

		// return svg
		return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<svg xmlns=\"http://www.w3.org/2000/svg\" $viewbox>
					<path stroke='black' fill='none' d=\"$path\" />
				</svg>";
	}
}