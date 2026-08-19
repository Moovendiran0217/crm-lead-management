<?php

namespace App\Http\Requests;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]{7,20}$/',
            ],

            'source' => [
                'required',
                Rule::enum(LeadSource::class),
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'sometimes',
                Rule::enum(LeadStatus::class),
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}
