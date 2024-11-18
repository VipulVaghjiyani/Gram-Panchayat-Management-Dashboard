<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'alpha', 'max:50'],
            'middle_name' => ['required', 'alpha', 'max:50'],
            'last_name' => ['required', 'alpha', 'max:50'],
            'mobile' => ['required', 'max:20', 'min:3', 'regex:/^[0-9\-\+\(\)]+$/'],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
