<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class excepcion extends Model
{
    use HasFactory;

    protected $table='EXCEPCION';
    protected $primaryKey='IdExcepcion';
    public $timestamps=false;
    protected $fillable=['IdConexion','tipoExcepcion','fecha','tabla', 'columna', 'detalle'];
}
