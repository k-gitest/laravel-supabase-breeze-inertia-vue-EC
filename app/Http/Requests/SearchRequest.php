<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
    protected function prepareForValidation() {
        $value = $this->warehouse_check;

        if ($value === 'false') {
            $value = false;
        } elseif ($value === 'true') {
            $value = true;
        }
        $this->merge([
            'warehouse_check' => $value,
        ]);
    }
    
    public function rules(): array
    {
        return [
            'sort_option' => ['string', 'in:newest,price_asc,price_desc,favorites_asc,favorites_desc'],
            'category_ids.*' => ['numeric'],
            'q' => ['nullable', 'string', 'max:255'],
            'price_range.*' => ['string', 'in:0-1500,1500-5000,5000-10000,10000-30000,30000-'],
            'warehouse_check' => ['boolean'],
        ];
    }
}
