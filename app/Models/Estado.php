<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estado';
    protected $primaryKey = 'esta_id_estado';
    public $timestamps = false;
    protected $fillable = ['esta_nombre'];
}