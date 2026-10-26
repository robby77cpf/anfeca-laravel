<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alcaldia extends Model
{
    protected $table = 'alcaldia';
    protected $primaryKey = 'alca_id_alcaldia';
    public $timestamps = false;
    protected $fillable = ['alca_nombre'];
}