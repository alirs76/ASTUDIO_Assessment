<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'unique:users,email'],
			'password' => ['required', 'confirmed', Password::min(8)->max(32)->letters()->numbers()->symbols()->uncompromised()],
		];
	}

	public function messages()
	{
		return [
			'password.min' => 'The password must be at least 8 characters long.',
			'password.letters' => 'The password must contain at least one letter.',
			'password.numbers' => 'The password must contain at least one number.',
			'password.symbols' => 'The password must contain at least one symbol.',
			'password.uncompromised' => 'The password has been compromised. Please choose a different password.',
			'password.confirmed' => 'The password confirmation does not match.',
			'password.required' => 'The password field is required.',

			'first_name.required' => 'The first name field is required.',
			'first_name.string' => 'The first name must be a string.',

			'last_name.required' => 'The last name field is required.',
			'last_name.string' => 'The last name must be a string.',

			'email.required' => 'The email field is required.',
			'email.email' => 'The email must be a valid email address.',
			'email.unique' => 'The email has already been taken.',

		];
	}
}
