<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkshopRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workshopId = $this->route('workshop');

        return [

            'category_id' => [
                'required',
                'exists:workshop_categories,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'slug' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'workshops',
                    'slug'
                )->ignore($workshopId)
            ],

            'short_description' => [
                'nullable',
                'string'
            ],

            'full_description' => [
                'nullable',
                'string'
            ],

            'status' => [
                'nullable',
                'in:draft,published,archived'
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'is_featured' => [
                'nullable',
                'boolean'
            ],

            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'video_url' => [
                'nullable',
                'url'
            ],
        ];
    }
}
