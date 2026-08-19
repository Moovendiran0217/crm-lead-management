<?php

namespace App\Http\Requests;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[0-9+\-\s()]{7,20}$/',
            ],

            'source' => [
                'sometimes',
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
