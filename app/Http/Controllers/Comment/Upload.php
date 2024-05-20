<?php

namespace App\Http\Controllers\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\ConfigService;

class Upload
{

    /**
     * @param Request $request
     * @return array
     * @author:阿文
     * @date:2024/2/6 16:42
     */
    public function upload_img(request $request)
    {

        $config = config('fileupload');
        $up_img = $request->file('file');
        //获取当前文件名
        $img_name = $up_img->getClientOriginalName();
        //获取上传文件的后缀对其进行校验
        $fileextension = $up_img->getClientOriginalExtension();
        if(strpos($config['need_img_type'], $fileextension) == false) {
            return (['code'=>500,'msg'=>'文件格式不支持,请选择以下格式'.$config['need_img_type']]);
        }
        $filesize  = $up_img->getSize()/1024/1024;
        //大小校验
        if($filesize>$config['max_size']){
            return (['code'=>500,'msg'=>'文件过大,请上传小于'.$config['max_size'].'M的文件']);
        }
        //检测文件是否合法 则创建  不合法则返回文件错误
        if ($up_img->isValid()){
            $upload_path = public_path('upload/imgs/').date('Ymd');
            if(!File::isDirectory($upload_path)){
                File::makeDirectory($upload_path);
            }
            $upload_path.='/';
            $new_name = date('His').'_'.time().'.'.$fileextension;
            $up_img->move($upload_path,$new_name);
            //考虑到多域名多战存全域名路径
            $domain = ConfigService::getConfigValue('file_url');
            $domain_path = $domain.'/upload/imgs/'.date('Ymd').'/'.$new_name;

            return (['code'=>200,'data'=>['url'=>$domain_path]]);
        }else{
            return (['code'=>500,'msg'=>'非法文件']);
        }

    }


}
