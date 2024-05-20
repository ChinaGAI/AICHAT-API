<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HomeOrders extends Model
{
    use HasFactory;
    protected $table = 'home_orders';
    public $timestamps = false;

    protected $fillable=['user_id','total_amount','status','created_at','updated_at','shop_id','pay_type','token'];
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
    public function user()
    {
        return $this->hasOne('App\Models\HomeUser','id','user_id');
    }
    public function shop()
    {
        return $this->hasOne('App\Models\HomeShop','id','shop_id');
    }
}
