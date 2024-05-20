<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatModels extends Model
{
    use HasFactory;
    protected $table = 'chat_models';
    public $timestamps = false;
    protected $hidden =['updated_at'];
    protected $fillable=['key_id','name','value','sort_num','magnification','updated_at','created_at','enabled','vision'];
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
    public function key()
    {
        return $this->hasOne('App\Models\ChatApiKey','id','key_id');
    }
}
