<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MessgeCheckRequest extends FormRequest
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
    public function rules()
    {
        return [
            'model_id' => 'required',
            'client_id' => 'required',
            'content' => 'required',
            'use_context' => 'required|in:0,1'
        ];
    }
    public function messages()
    {
        return [
            'model_id.required' => 'model数据异常',
            'client_id.required' => 'client数据异常',
            'content.required' => 'content数据异常',
            'use_context.required' => 'user_context数据异常',
            'use_context.in' => 'user_context数据异常',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw  new HttpResponseException(response()->json([
            'code'=>500,
            'message' => $validator->errors()->first(),
        ]));
    }
}
