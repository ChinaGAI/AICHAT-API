<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeShop extends Model
{
    use HasFactory;
    protected $table = 'home_shops';
    public $timestamps = false;


    protected $fillable=['title','content','tokens','price','desc','enable','origin_price','created_at','updated_at'];
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
