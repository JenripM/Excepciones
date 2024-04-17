<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consulta1 extends Model
{ 
    use HasFactory;
    
    protected $table='consultas__1';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $fillable=['tablaNombre','columnaNombre','formatoEspecial','usuarioID','basenombre','tipoConexion','idConexion'];
}
 