<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeUserToken extends Model
{
    use HasFactory;
    protected $table = 'home_user_token';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable=['token','user_id','expiration_time','state','created_at','ip'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    public function user()
    {
        return $this->hasOne('App\Models\HomeUser','id','user_id');
    }
    public  static function insert($phoneOrEmail,$ip,$user_id)
    {
        $token = md5(md5(now().$phoneOrEmail));
        $data = [
            'token'=>$token,
            'user_id'=>$user_id,
            'created_at'=>now(),
            'expiration_time'=>now()->addMonths(1),
            'ip'=>$ip
        ];
        self::where('user_id',$user_id)->where('expiration_time','<',now())->update(['state'=>0]);
        self::create($data);
        return  $token;
    }
}
