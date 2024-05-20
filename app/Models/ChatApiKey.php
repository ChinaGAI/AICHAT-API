<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatApiKey extends Model
{
    use HasFactory;

    protected $table = 'chat_api_keys';
    public $timestamps = false;

    protected $fillable=['name','value','type','desc','api_url','enabled','use_proxy','created_at','updated_at','icon','sort_num'];
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
    public function chatModels()
    {
        return $this->hasMany(ChatModels::class, 'key_id');
    }
}
