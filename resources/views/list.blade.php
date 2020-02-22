@extends('root')

<?php
/**
 * @var \App\GpsTrack $track
 */
?>

@section('head')
	<style>
		img {
			border: 1px solid lightblue;
		}
	</style>
@show

@section('main')
	@foreach($tracks as $track)
		<img src="{{ action(\App\Http\Controllers\GpsTrackSvgController::class, [$track]) }}"/>
	@endforeach
@endsection