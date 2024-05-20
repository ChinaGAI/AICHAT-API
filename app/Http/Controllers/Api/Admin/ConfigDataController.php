<?php

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 主要用于返回web前端一线不需要验证的配置信息
 */
class ConfigDataController extends Controller
{

    /**
     * @return void
     * @author:阿文
     * @date:2024/1/10 18:02
     */
    public function list(request $request)
    {
        $config =  json_decode(Storage::disk('local')->get('config.json'),true);

        $data =[
            'code'=>200,
            'message'=>'',
            'data'=>$config
        ];
        return  response()->json($data);
    }



    /**
     * @param Request $request
     * @return void
     * @author:阿文
     * @date:2024/1/10 21:40
     */
    public function update(request $request)
    {
        $config = $request->input('data');
        Storage::disk('local')->put('config.json', $config);
        $return = [
            'code'=>200,
            'message'=>'修改成功',
            'data'=>[]
        ];
        return response()->json($return);
    }

}
