<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRoleRequest extends FormRequest
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
            'icon'=>'required|min:4|max:100',
            'name' => 'required|min:1|max:100', // 名称必须在 1-100 位之间
            // 标签id必须36位并且在 chatroletag表存在
            'tag_id' => 'required|size:36|exists:chat_role_tag,id',
            'desc'=>'required|min:1|max:255',
            'context' => 'required|min:0|max:1000', // 上下文必须在 0-1000 位之间
            'hello_msg'=>'nullable',
            'suggestions'=>'nullable'
        ];
    }
    public function messages()
    {
        return [
            'icon.required' => 'icon不能为空。',
            'icon.min' => 'icon长度必须至少为 4 位。',
            'icon.max' => 'icon长度不能超过 100 位。',
            'name.required' => '名称不能为空。',
            'name.min' => '名称长度必须至少为 1 位。',
            'name.max' => '名称长度不能超过 100 位。',
            'tag_id.required' => '标签id不能为空。',
            'tag_id.size' => '标签id长度必须为 36 位。',
            'tag_id.exists' => '标签id不存在。',
            'desc.required' => '描述不能为空。',
            'desc.min' => '描述长度必须至少为 1 位。',
            'desc.max' => '描述长度不能超过 255 位。',
            'context.required' => 'context不能为空。',
            'context.min' => '上下文长度必须至少为 0 位。',
            'context.max' => '上下文长度不能超过 1000 位。',
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
