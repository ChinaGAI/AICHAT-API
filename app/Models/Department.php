<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'admin_department';
    public $timestamps = false;
    protected $fillable=['name','created_at','parent_id','leader'];
    protected $primaryKey = 'id';
    public function getchildren() {
        return $this->hasMany(get_class($this), 'parent_id' ,'id');
    }


    public function children() {
        return $this->getchildren()->with( 'children' );
    }
}
