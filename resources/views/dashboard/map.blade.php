<?php
/**
 * @var \App\App $app
 */
?>
<html lang="fr" class="text-gray-900 antialiased leading-tight">
<head>
	<meta charset="UTF-8">
	<title>Statistiques de {{ $app->name }}</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link href="{{ mix("css/app.css") }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('/css/map.css') }}">
</head>
<body class="min-h-screen bg-gray-100">

{{--<div class="container p-8 m-auto">--}}
{{--	<h1 class="text-4xl text-center mb-12">Statistiques de <span class="text-indigo-500">{{ $app->name }}</span></h1>--}}
	<div id="map"></div>
{{--</div>--}}

<script>const data = @json($data);</script>
<script src="{{ mix("js/stat-map.js") }}"></script>

</body>
</html>