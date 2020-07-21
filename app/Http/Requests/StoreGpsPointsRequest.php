<?php

namespace App\Http\Requests;

use App\App;
use Illuminate\Foundation\Http\FormRequest;

class StoreGpsPointsRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return is_a($this->user(), App::class);
	}


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'points' => 'required|array|min:1',
			'points.*.longitude' => 'required|numeric|between:-180,180',
			'points.*.latitude' => 'required|numeric|between:-90,90',
			'points.*.altitude' => 'sometimes|nullable|numeric',
			'points.*.accuracy' => 'required|numeric',
			'points.*.time' => 'required|numeric',
		];
	}
}
