<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserLikeRole extends Model
{
    use HasFactory;
    protected $table = 'home_user_like_role';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable=['role_id','created_at','delete_at','sort_num','state','user_id'];

    protected $keyType = 'string';
    public $incrementing = false;


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function role()
    {
        return $this->hasOne('App\Models\ChatRole','id','role_id');
    }
}
