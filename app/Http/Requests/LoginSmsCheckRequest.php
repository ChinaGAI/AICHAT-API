<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginSmsCheckRequest extends FormRequest
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
            'phone' => 'required|regex:/^1[345789][0-9]{9}$/',
            'code' => 'required|digits:6', // 验证码可能不存在，如果存在必须是 6 位数字
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
            'phone.required' => '用户名/邮箱不能为空。',
            'phone.regex' => '手机号格式不对',
            'code.required' => '验证码不能为空。',
            'code.digits' => '验证码必须是 6 位数字。',
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
