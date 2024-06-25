<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCategoryRequest extends FormRequest
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
        $rules = [
            "name" => ["required","string","max:255"],
            "description" => ["required","string","max:255"],
        ];

        if ($this->method() === "POST")
        {
            $rules['name'][] = Rule::unique('categories', 'name');
        }
        elseif ($this->method() === "PUT")
        {
            $categoryId = $this->id;
            $rules['name'][] = Rule::unique('categories', 'name')->ignore($categoryId);
        }
        
        return $rules;
    }
}
