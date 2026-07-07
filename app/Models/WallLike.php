<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WallLike extends Model
{
    public $timestamps = false;
    protected $fillable = ['id_wall', 'id_user'];
    protected $table = 'walllikes';
}
