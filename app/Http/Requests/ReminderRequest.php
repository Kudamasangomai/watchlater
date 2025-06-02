<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReminderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'id' => 'required|string',
            'title' => 'required|string',
            'url' => 'required|string |unique:reminders,url',// could have added uniqque in db just lazy
            'remind_at' => ['required', 'date', 'after:now'],

        ];
    }

    public function messages()
    {
        return [
            'url.unique' => 'This url has already been added.',
        ];
    }
}
