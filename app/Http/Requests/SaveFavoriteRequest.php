<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class SaveFavoriteRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'gif_id' => 'required|string',
            'alias' => 'required|string'
        ];
    }
}
