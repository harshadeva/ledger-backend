<?php

namespace App\Http\Requests;

use App\Classes\ApiCatchErrors;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TransactionRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        return ApiCatchErrors::validationError($validator);
    }

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
     * @return array
     */
    public function rules()
    {
        Log::info($this->all());
        return [
            'type' => 'required|in:INCOME,EXPENSE',
            'date' => 'required|date',
            'description' => 'required|max:255',
            'account_id' => 'required|exists:accounts,id',
            'stakeholder_id' => 'required|exists:stakeholders,id',
            'category_id' => 'required|exists:categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'reference' => 'required|max:255',
            'amount' => 'required|numeric|min:0|not_in:0',
        ];
    }
}
