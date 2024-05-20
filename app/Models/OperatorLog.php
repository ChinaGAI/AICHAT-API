<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorLog extends Model
{
    use HasFactory;
    protected $table = 'operator_log';
    public $timestamps = false;
    protected $fillable=['remark','user_id','ip','request_data'];

    protected $primaryKey = 'id';
    public function user()
    {
        return $this->hasOne('App\Models\AdminUser','id','user_id');
    }

}
