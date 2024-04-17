<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conexion extends Model
{
    use HasFactory;
  
    protected $table='CONEXION';
    protected $primaryKey='IdConexion';
    public $timestamps=false;
    protected $fillable=['servidor','nombreBase','usuario', 'contraseña', 'tipoConexion','puerto', 'estado'];
}
