<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class VerifyEmailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user' => 'required|exists:users,id',
            'hash' => 'required|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = User::find($this->user);
            if (!hash_equals($this->hash, sha1($user->getEmailForVerification()))) {
                $validator->errors()->add('hash', 'The provided hash does not match the user\'s email hash.');
            }
        });
    }
}