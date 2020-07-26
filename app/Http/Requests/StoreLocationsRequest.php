<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocationsRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user();
	}


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'locations' => 'required|array|min:1',
			'locations.*.longitude' => 'required|numeric|between:-180,180',
			'locations.*.latitude' => 'required|numeric|between:-90,90',
			'locations.*.altitude' => 'sometimes|nullable|numeric',
			'locations.*.accuracy' => 'required|numeric',
			'locations.*.time' => 'required|numeric',
		];
	}
}
