<?php
/**
 * @var \App\User $user
 * @var \App\Track $track
 */
?>
<html lang="fr" class="text-gray-900 antialiased leading-tight">
<head>
	<meta charset="UTF-8">
	<title>Statistiques de {{ $user->name }}</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link href="{{ mix("css/app.css") }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('/css/map.css') }}">
</head>
<body class="min-h-screen bg-gray-100">

<div class="container p-8 m-auto">
	<h1 class="text-4xl text-center mb-12">Statistiques de <span class="text-indigo-500">{{ $user->name }}</span></h1>
	<div class="flex flex-row mt-4 mb-4 text-center justify-center">
		<div class="flex flex-col mx-4">
			<span class="text-2xl mb-2">{{ $tracks_count }}</span>
			<span class="text-xs uppercase">trajets</span>
		</div>
		<div class="flex flex-col mx-4">
			<span class="text-2xl mb-2">{{ number_format(round($total_distance / 1_000), 0, ',', ' ') }}<small> km</small></span>
			<span class="text-xs uppercase">parcourus</span>
		</div>
	</div>

	<div class="text-center mt-10 mb-8">
		<button class="bg-gray-300 hover:bg-gray-500 text-teal-900 font-bold py-2 px-4 rounded">
			<a href="{{ action(\App\Http\Controllers\Dashboard\MapController::class, [$user]) }}">
				Carte
			</a>
		</button>
	</div>

	<h2 class="text-center text-2xl text-blue-500 mb-8 mt-8">Dernières semaines</h2>
	<div class="flex flex-row mt-4 mb-4 text-center justify-center">
		@foreach($weekly as $timestamp => $week)
			<div class="flex flex-col mx-4">
				<span class="text-2xl mb-2">{{ round($week / 1_000) }} <small>km</small></span>
				<span class="text-xs uppercase">{{ optional(\Carbon\Carbon::createFromTimestamp($timestamp))->format("d M Y") }}</span>
			</div>
		@endforeach
	</div>
	<div>

		<h2 class="text-center text-2xl text-blue-500 mb-8 mt-12">Derniers parcours</h2>
		<table class="table-auto border-collapse w-full">
			<thead>
			<tr>
				<th class="px-4 py-2">Date</th>
				<th class="px-4 py-2">Distance</th>
				<th class="px-4 py-2">Durée</th>
			</tr>
			</thead>
			<tbody>
			@foreach($past_tracks->take(-15) as $track)
				<tr>
					<td class="border px-4 py-2">
						<a href="{{ route("map", [$user, $track]) }}">{{ $track->created_at->format("d M, H:i") }}</a>
					</td>
					<td class="border px-4 py-2">
						{{ number_format(round($track->distance / 1_000, 1), 1, ',', ' ') }}<small>km</small>
					</td>
					<td class="border px-4 py-2">
						@if($track->getDuration()->hours)
							{{ $track->getDuration()->hours }} h
						@endif
						{{ $track->getDuration()->minutes }} min {{ $track->getDuration()->seconds }} s
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

</body>
</html>