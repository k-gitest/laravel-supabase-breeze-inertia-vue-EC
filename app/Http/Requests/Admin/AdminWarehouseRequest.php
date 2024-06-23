<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminWarehouseRequest extends FormRequest
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
        $warehouseId = $this->id;

        $rules = [
            "name" => ["required","string","max:255"],
            "location" => ["required","string","max:255"], 
        ];

        if($this->method() === 'POST'){
            $rules['name'][] = Rule::unique('warehouses', 'name');
        }
        elseif($this->method() === 'PUT'){
            $rules['name'][] = Rule::unique('warehouses', 'name')->ignore($warehouseId);
        }

        return $rules;
    }
}
