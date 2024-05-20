<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    use HasFactory;

    protected $table = 'admin_user_role';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable=['role_name','access_codes','access_ids','updated_at'];
}
