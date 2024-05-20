<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Models\ChatRoleTag;
use Illuminate\Http\Request;

class ChatRoleTagController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/25 11:29
     */
    public function role_tag_list(request $request)
    {
        $list = ChatRoleTag::orderby('sort_num','desc')->get();
        return response()->json(['code'=>200,'data'=>$list]);
    }

}
