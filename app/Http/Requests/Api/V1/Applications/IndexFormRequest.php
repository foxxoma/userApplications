<?php

namespace App\Http\Requests\Api\V1\Applications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'page' => ['integer'],
            'count' => ['integer'],
            'sortBy' => ['string'],
            'operator' => ['string']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sortValue = $this->input('sortBy');
            $operator = $this->input('operator');

            if ($sortValue && !in_array($sortValue, ['status', 'created_at'])) {
                $validator->errors()->add('sortBy', 'The sortBy value must be either status or created_at.');
            }

            if ($operator && !in_array($operator, ['asc', 'desc'])) {
                $validator->errors()->add('operator', 'The operator value must be either asc or desc.');
            }
        });
    }
}
