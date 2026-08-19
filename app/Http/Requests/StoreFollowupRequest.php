<?php

namespace App\Http\Requests;

use App\Enums\FollowupStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFollowupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'followup_date' => [
                'required',
                'date',
                'after_or_equal:now',
            ],

            'notes' => [
                'required',
                'string',
            ],

            'status' => [
                'sometimes',
                Rule::enum(FollowupStatus::class),
            ],
        ];
    }
}
