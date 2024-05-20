<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUserAccess extends Model
{
    use HasFactory;
    protected $table = 'admin_user_role_access';
    public $timestamps = false;
    protected $fillable=['name','description','code','created_at','updated_at','url','parent_id'];
    protected $primaryKey = 'id';
    public function getchildren() {
        return $this->hasMany(get_class($this), 'parent_id' ,'id');
    }

    public function children() {
        return $this->getchildren()->with( 'children' );
    }

}
