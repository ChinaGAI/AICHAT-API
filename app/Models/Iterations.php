<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iterations extends Model
{
    use HasFactory;
    protected $table = 'iterations';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable=['version','date','content','modules','persons','status','created_at','updated_at','title'];
}
