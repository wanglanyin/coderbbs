<?php

namespace App\Http\Requests\Api;

//use Illuminate\Foundation\Http\FormRequest;

class AuthorizationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string'
        ];
        if ($this->social_type == 'weixin' && !$this->code) {
            $rules['openid']  = 'required|string';
        }

        return $rules;
    }
}
