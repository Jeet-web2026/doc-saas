<?php

namespace App\Http\Requests\General;

use App\Models\PinCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_load'     => ['required', 'in:very_good,good,ok,poor,very_poor'],
            'page_design'   => ['required', 'in:very_good,good,ok,poor,very_poor'],
            'overall'       => ['required', 'in:very_good,good,ok,poor,very_poor'],
            'feedback_type' => ['required', 'string', 'max:255'],
            'comments'      => ['nullable', 'string'],
            'pincode'       => [
                'required',
                'digits:6',
                
            ],
            'orga_uin'      => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'page_load.required'   => 'Please rate how quickly the page loaded.',
            'page_design.required' => 'Please rate the page design.',
            'overall.required'     => 'Please give an overall rating.',
            'pincode.digits'       => 'Pincode must be exactly 6 digits.',
            'pincode.exists'       => 'The given pincode is invalid.',
        ];
    }
}
