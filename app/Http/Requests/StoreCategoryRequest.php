<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500'
        ];
    }
}
