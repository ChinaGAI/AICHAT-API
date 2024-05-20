<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChatClientIdLog extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'chat_client_id_log';
    protected $fillable=['user_id','client_id','expiration_time','created_at'];
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
