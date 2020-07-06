<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
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
            'name' =>'required',
            'surname' =>'required',
            'patronymic' =>'required',
            'employment_date' =>'required|date',
            'salary' =>'required',
            'img_url' =>'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя обязательно для заполнения',
            'surname.required' => 'Фамилия обязательна для заполнения',
            'patronymic.required' => 'Отчество обязательно для заполнения',
            'employment_date.required' => 'Дата приема наработу обязательна для заполнения',
            'employment_date.date' => 'Дата приема наработу должна быть датой',
            'salary.required'  => 'Зарплата обязательна для заполнения',
            'img.required' => 'Картинка обязательна для заполнения',
            'password.max'  => 'Пароль слишком длинный',
        ];
    }
}
