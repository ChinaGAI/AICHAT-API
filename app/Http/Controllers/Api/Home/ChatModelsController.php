<?php

namespace App\Http\Controllers\api\Home;

use App\Http\Controllers\Controller;
use App\Models\ChatApiKey;
use Illuminate\Http\Request;

class ChatModelsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @author:阿文
     * @date:2024/3/14 17:24
     */
    public function model_list()
    {
        $list = ChatApiKey::with(['chatModels' => function ($query) {
            $query->where('enabled',1)->orderBy('sort_num','desc');
        }])->orderBy('sort_num','desc')
            ->where('enabled',1)
            ->get(['name','icon','sort_num','id']);


        return response()->json(['code'=>200,'data'=>$list]);
    }
}
