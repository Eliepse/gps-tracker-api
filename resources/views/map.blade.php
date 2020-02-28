<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>Letter Tracking</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ mix('/css/map.css') }}">
</head>

<body>
<div id="map"></div>
<script src="{{ mix('/js/map.js') }}"></script>
</body>

</html>