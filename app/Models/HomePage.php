<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;
    protected $table = 'page';
    public $timestamps = false;
    protected $fillable=['alias','desc','content','time','title'];
    protected $primaryKey = 'id';
}
