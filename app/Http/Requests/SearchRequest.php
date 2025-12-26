<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PriceRange;
use App\Enums\SortOption;
use Illuminate\Validation\Rules\Enum;

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
        /*
        $value = $this->warehouse_check;

        if ($value === 'false') {
            $value = false;
        } elseif ($value === 'true') {
            $value = true;
        }
        $this->merge([
            'warehouse_check' => $value,
        ]);
        */
        $this->merge([
            'warehouse_check' => $this->boolean('warehouse_check'),
        ]);
    }
    
    public function rules(): array
    {
        /*
        $priceRanges = array_column(PriceRange::cases(), 'value');
        $sortOptions = array_column(SortOption::cases(), 'value');
        
        return [
            'sort_option' => [Rule::in($sortOptions)],
            'category_ids.*' => ['numeric', 'exists:categories,id'],
            'q' => ['nullable', 'string', 'max:255'],
            'price_range.*' => [Rule::in($priceRanges)],
            'warehouse_check' => ['boolean'],
        ];
        */
        return [
            'sort_option'     => ['nullable', new Enum(SortOption::class)],
            
            'category_ids'    => ['nullable', 'array'],
            'category_ids.*'  => ['numeric', 'exists:categories,id'],
            
            'q'               => ['nullable', 'string', 'max:255'],
            
            'price_range'     => ['nullable', 'array'],
            'price_range.*'   => ['required', new Enum(PriceRange::class)],
            
            'warehouse_check' => ['boolean'],
        ];
    }

}
