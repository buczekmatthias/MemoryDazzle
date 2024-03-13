<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        /** @var User $user */
        $user = auth()->user();

        return [
            'displayname' => 'string|nullable',
            'username' => "required|string|unique:users,username,{$user->username},username",
            'email' => "required|email|unique:users,email,{$user->email},email",
            'visibility' => 'required|string|in:private,public',
            'only_delete_avatar' => 'bool',
            'avatar' => 'file|mimetypes:image/jpeg,image/png,image/svg|max:2500|nullable'
        ];
    }
}
