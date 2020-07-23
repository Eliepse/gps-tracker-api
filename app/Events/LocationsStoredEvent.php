<?php

namespace App\Events;

use App\Location;
use App\Track;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationsStoredEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * @var Track
	 */
	private Track $track;

	/**
	 * @var Location[]|array
	 */
	private array $points;


	/**
	 * @param Track $track
	 * @param array|Location[] $points
	 */
	public function __construct(Track $track, array $points)
	{
		$this->track = $track;
		$this->points = $points;
	}


	/**
	 * @return Channel
	 */
	public function broadcastOn()
	{
		return new Channel('App.Track.' . $this->track->id);
	}


	/** @noinspection PhpUnused */
	public function broadcastWith(): array
	{
		return $this->points;
	}
}
