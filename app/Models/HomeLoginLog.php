<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeLoginLog extends Model
{
    use HasFactory;
    protected $table = 'home_login_log';
    public $timestamps = false;
    protected $fillable=['created_at','ip','type','user_id','content','state'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    //记录用户登陆
    public static function insert($ip,$type,$user_id,$content='',$state)
    {
        $data = [
            'created_at'=>now(),
            'ip'=>$ip,
            'type'=>$type,
            'user_id'=>$user_id,
            'content'=>$content,
            'state'=>$state
        ];
        self::create($data);
    }

}
