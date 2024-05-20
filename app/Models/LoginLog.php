<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;
    protected $table = 'admin_login_log';
    public $timestamps = false;
    protected $primaryKey = 'id';


}
