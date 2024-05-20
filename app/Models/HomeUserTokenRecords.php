<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeUserTokenRecords extends Model
{
    use HasFactory;
    protected $table = 'home_user_token_records';
    public $timestamps = false;

    protected $fillable=['chat_id','user_id','amount','type','created_at','desc','balance'];
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
}
