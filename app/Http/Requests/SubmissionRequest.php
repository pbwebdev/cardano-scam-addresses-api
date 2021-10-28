<?php

namespace App\Http\Requests;

use App\Models\Submission;
use App\Rules\TransactionHash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmissionRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'transaction' => [
                'required',
                'string',
                Rule::unique('submissions')->ignore($this->get('transaction'), 'transaction'),
                new TransactionHash(),
            ],
            'description' => [
                'required',
                'string',
            ],
            'status'      => [
                'sometimes',
                'required',
                'string',
                Rule::in(Submission::STATUS_NAMES),
            ],
        ];
    }
}
