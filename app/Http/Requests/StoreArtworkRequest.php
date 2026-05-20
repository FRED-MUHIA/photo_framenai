<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtworkRequest extends FormRequest
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
        return [
            'artwork' => [
                'required',
                'file',
                'mimetypes:' . implode(',', config('shop.allowed_artwork_mimes', [])),
                'max:' . config('shop.max_artwork_size_kb', 20480),
            ],
            'product_id' => ['nullable', 'exists:products,id'],
            'order_item_id' => ['nullable', 'exists:order_items,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'artwork.mimetypes' => 'Please upload a valid PDF, PNG, JPG, WEBP or AI artwork file.',
            'artwork.max' => 'Artwork file exceeds the maximum allowed upload size.',
        ];
    }
}
