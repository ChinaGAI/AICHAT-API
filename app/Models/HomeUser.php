<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Services\ConfigService;


class HomeUser extends Model
{
    use HasFactory;
    protected $table = 'home_user';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $hidden=['password'];

    protected $fillable=['phone','password','role_id','class','nickname','created_at','updated_at','avatar','email','username','first_ip','last_ip','id'];

    public function list_data($where,$page,$page_size)
    {
        $data = $this->where($where)
            ->paginate($page_size,['*'],'page',$page)
            ->toArray();
        return $data;
    }

    /**
     * @param $data
     * @param $ip
     * @return array
     * @author:阿文
     * @date:2024/3/24 16:58
     */
    public static   function insert($data,$ip){

        $insert = [];
        foreach ($data as $key=>$value){
            $insert[$key] =$value;
        }
        $string = empty($data['phone'])?$data['username']:$data['phone'];
        $insert['nickname'] = '用户'.substr_replace($string, str_repeat('*', 4), 3, 4);
        $insert['avatar'] = ConfigService::getConfigValue('file_url').'/avatars/'.rand(1,14).'.png';
        $insert['id']=Str::uuid();
        $insert['first_ip'] = $ip;
        $insert['last_ip'] = $ip;
        $insert['created_at'] = now();
        $insert['updated_at'] = now();
        self::create($insert);

        return $insert;
    }

    /** 用户密码登陆
     * @param $usernameOrEmail
     * @return mixed
     * @author:阿文
     * @date:2024/3/9 15:26
     */
    public static function existsByUsernameOrEmail($usernameOrEmail)
    {
        return self::where(function ($query) use ($usernameOrEmail) {
            $query->where('username', $usernameOrEmail)
                ->orWhere('email', $usernameOrEmail);
        })->first();
    }



}
