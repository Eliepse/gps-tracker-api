<?php
/**
 * @var \App\User $app
 */
?>
<html lang="fr" class="text-gray-900 antialiased leading-tight">
<head>
	<meta charset="UTF-8">
	<title>Statistiques de {{ $app->name }}</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{ mix("css/app.css") }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('/css/map.css') }}">
</head>
<body class="min-h-screen bg-gray-100">

<div id="app">
	<map-all :user_id="{{ $app->id }}" @if($track) :track_id="{{ $track->id }}" @endif ></map-all>
</div>

<script src="{{ mix("js/app.js") }}"></script>
</body>
</html>