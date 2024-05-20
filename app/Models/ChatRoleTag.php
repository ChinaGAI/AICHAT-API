<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatRoleTag extends Model
{
    use HasFactory;
    protected $table = 'chat_role_tag';
    public $timestamps = false;

    protected $fillable=['name','created_at','updated_at','sort_num','icon'];
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
