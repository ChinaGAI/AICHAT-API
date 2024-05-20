<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserToken extends Model
{
    use HasFactory;
    protected $table = 'admin_user_token';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable=['token','user_id','expiration_time','state','created_at'];


}
