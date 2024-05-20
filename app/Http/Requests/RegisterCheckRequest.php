<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterCheckRequest extends FormRequest
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
            'username' => 'required|min:8|max:25|unique:home_user', // 用户名必须在 20-40 位之间，且在数据库中唯一
            'code' => 'nullable|digits:6', // 验证码可能不存在，如果存在必须是 6 位数字
            'email' => 'nullable|email', // 电子邮件可能不存在，如果存在必须是有效的电子邮件格式
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
            'username.required' => '用户名不能为空。',
            'username.min' => '用户名长度必须至少为 8 位。',
            'username.max' => '用户名长度不能超过 25 位。',
            'username.unique' => '用户名已存在。',
            'code.digits' => '验证码必须是 6 位数字。',
            'email.email' => '电子邮件格式不正确。',
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
