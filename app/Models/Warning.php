<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    public $timestamps = false;
    protected $table = 'warnings';
    protected $fillable = ['id_unit', 'title', 'body', 'photos'];
    protected $hidden = ['created_at'];
}
