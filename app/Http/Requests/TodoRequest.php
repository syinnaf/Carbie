<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:activity_categories,id',
            'details' => 'required|array',
            'date' => 'required|date',
            'status' => 'sometimes|in:pending,done',
        ];
    }
}