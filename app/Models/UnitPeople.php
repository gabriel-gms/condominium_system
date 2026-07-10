<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class UnitPeople extends Model
{
    protected $hidden = ['id_unit'];
    public $timestamps = false;
    protected $table = 'unitpeople';
    protected $fillable = ['name', 'birthdate', 'id_unit'];
}
