<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    use HasFactory;
    protected $table='ROL';
    protected $primaryKey='idRol';
    public $timestamps=false;
    protected $fillable=['descripcion','tablas','vista_sql','excepciones_s','excepciones_c','excepciones_i','reportes_s','reportes_c','reportes_i', 'roles','usuarios','estado'];
    
    public function users(){
        return $this->hasMany(User::class,'idRol','idRol');
    }
}
