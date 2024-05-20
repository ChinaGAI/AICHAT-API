<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginCheckRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required|min:8|max:40', // 用户名必须在 8-40 位之间
            'password' => 'required|min:8|max:20', // 密码必须在 8-20 位之间
        ];
    }

    /**
     * @return string[]
     * @author:阿文
     * @date:2024/3/7 23:41
     */
    public function messages()
    {
        return [
            'account.required' => '用户名/邮箱不能为空。',
            'account.min' => '用户名/邮箱长度必须至少为 8 位。',
            'account.max' => '用户名/邮箱长度不能超过 40 位。',
            'password.required' => '密码不能为空。',
            'password.min' => '密码长度必须至少为 8 位。',
            'password.max' => '密码长度不能超过 20 位。',
        ];
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\JsonResponse|void
     * @author:阿文
     * @date:2024/3/7 23:58
     */
    protected function failedValidation(Validator $validator)
    {
        throw  new HttpResponseException(response()->json([
            'code'=>500,
            'message' => $validator->errors()->first(),
        ]));
    }
}
