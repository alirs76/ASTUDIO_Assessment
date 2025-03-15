<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('update', $this->route('user'));
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'first_name' => ['string', 'max:255'],
			'last_name' => ['string', 'max:255'],
			'email' => ['email', 'unique:users,email,'.$this->route('user')->id],
			'password' => ['confirmed', Password::min(8)->max(32)->letters()->numbers()->symbols()->uncompromised()],
		];
	}
}
