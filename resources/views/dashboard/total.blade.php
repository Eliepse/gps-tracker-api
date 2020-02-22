<?php
/**
 * @var \App\App $app
 */
?>
<html lang="fr">
<head>
	<title>Statistiques de {{ $app->name }}</title>
	<style>
		html, body {
			font-family: sans-serif;
			font-size: 16px;
		}
	</style>
</head>
<body>
<h1>Statistiques de {{ $app->name }}</h1>
<ul>
	<li>{{ $totalTracks }} parcours</li>
	<li>{{ number_format($totalDistance, 3, ',', ' ') }} km parcourus</li>
</ul>

<h2>Semaines passées</h2>
<ul>
	<li>[WIP]</li>
</ul>

<h2>Précédents parcours</h2>
<ul>
	@foreach($tracksDistances as $track)
		<li>{{ $track["time"]->format("ymd") }} : {{ number_format($track["distance"], 3, ',', ' ') }}</li>
	@endforeach
</ul>

</body>
</html>