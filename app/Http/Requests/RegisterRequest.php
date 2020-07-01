<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' =>'required|min:6|max:40|confirmed ',
            'email' =>'required|unique:users|email',
            'name' =>'required',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'E-mail занят',
            'name.required' => 'Имя является обязательным для заполнения',
            'password.required' => 'Пароль является обязательным для заполнения',
            'password.confirmed' => 'Пароли не совподяют',
            'password.min'  => 'Пароль слишком лёгкий',
            'password.max'  => 'Пароль слишком длинный',
        ];
    }

}
