<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consulta3 extends Model
{
    use HasFactory;

    protected $table='consultas__3';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=['tablaCabecera','tablaDetalle','columnaCabecera','columnaDetalle','formatoEspecial','usuarioID','basenombre','tipoConexion','idConexion'];
} 
