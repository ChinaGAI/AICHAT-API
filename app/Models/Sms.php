<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'home_sms';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable=['expiration_time','created_at','code','scene','ip','user_id','phone'];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static   function insert($ip,$phone,$scene,$code,$user_id){
        $cacheKey = "sms_limit_{$phone}";
        $lastHourSmsCount = Cache::get($cacheKey, 0);
        if ($lastHourSmsCount >= 5) {
            return ['state'=>false,'msg'=>'每个小时最多接受5条短信'];
        }
        $record = new self();
        $record->ip = $ip;
        $record->phone = $phone;
        $record->scene = $scene;
        $record->code = $code;
        $record->user_id = $user_id;
        $record->created_at = now();
        $record->expiration_time = now()->addMinutes(30);
        $record->save();
        Cache::put($cacheKey, $lastHourSmsCount + 1, now()->addHour());
        return ['state'=>true];
    }
    public static function isValidCode($phone, $code,$ip)
    {
        // 查询 30 分钟内的验证码记录
        $smsRecord = self::where('phone', $phone)
            ->where('code', $code)
            ->where('ip',$ip)
            ->where('expiration_time', '>', now())
            ->first();

        // 如果找到了记录，返回 true
        return $smsRecord !== null;
    }
}
