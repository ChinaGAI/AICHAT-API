<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatRole extends Model
{
    use HasFactory;
    protected $table = 'chat_roles';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable=['name','context','desc','icon','enabled','sort_num','created_at','updated_at','tag_id','hello_msg','suggestions','user_id','is_delete'];

    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
    public function tag()
    {
        return $this->hasOne('App\Models\ChatRoleTag','id','role_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\HomeUser','id','user_id')  ;
    }
}
