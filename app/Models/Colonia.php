<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    protected $table = 'colonia';
    protected $primaryKey = 'colo_id_colonia';
    public $timestamps = false;
    protected $fillable = ['colo_nombre'];
}