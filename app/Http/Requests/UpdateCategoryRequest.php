<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        $categoryId = $this->route('category');
        return [
            'name' => 'required|string|min:3|max:100|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string|max:500'
        ];
    }
}
