@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 100px">
        <div class="container">
            <h1>Desea Eliminar el Rol?</h1><br>
            <h3>Código: {{$rol->idRol}}</h3>
            <h3>Descripción: {{$rol->descripcion}}</h3><br>
            <table class="table table-bordered">
                <thead style="background-color: white">
                    <tr>
                        <th colspan="10" style="background: black; color: white">Funciones</th>
                    </tr>
                    <tr style="text-align: center">
                        <th colspan="2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="bd" name="bd" @if($rol->tablas == 1 && $rol->vista_sql == 1) checked @endif disabled>
                                <label class="form-check-label" for="bd">Base de Datos</label>
                            </div> 
                        </th>
                        <th colspan="3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="excepciones" name="excepciones" @if($rol->excepciones_s == 1 && $rol->excepciones_c == 1  && $rol->excepciones_i == 1) checked @endif disabled>
                                <label class="form-check-label" for="excepciones">Excepciones</label>
                            </div> 
                        </th>
                        <th colspan="3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="reportes" name="reportes" @if($rol->reportes_s == 1 && $rol->reportes_c == 1  && $rol->reportes_i == 1) checked @endif disabled>
                                <label class="form-check-label" for="reportes">Reportes de Excepciones</label>
                            </div> 
                        </th>
                        <th colspan="2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="privilegios" name="privilegios" @if($rol->roles == 1 && $rol->usuarios == 1) checked @endif disabled>
                                <label class="form-check-label" for="privilegios">Privilegios</label>
                            </div> 
                        </th>
                    </tr>
                </thead>
                <tbody style="background-color: white">
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="tablas" name="tablas" @if($rol->tablas == 1) checked @endif disabled>
                                <label class="form-check-label" for="tablas">Tablas</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="vista_sql" name="vista_sql" @if($rol->vista_sql == 1) checked @endif disabled>
                                <label class="form-check-label" for="vista_sql">Instrucciones SQL</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="excepciones_s" name="excepciones_s" @if($rol->excepciones_s == 1) checked @endif disabled>
                                <label class="form-check-label" for="excepciones_s">Secuenciales</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="excepciones_c" name="excepciones_c" @if($rol->excepciones_c == 1) checked @endif disabled>
                                <label class="form-check-label" for="excepciones_c">Campo</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="excepciones_i" name="excepciones_i" @if($rol->excepciones_i == 1) checked @endif disabled>
                                <label class="form-check-label" for="excepciones_i">Tablas</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="reportes_s" name="reportes_s" @if($rol->reportes_s == 1) checked @endif disabled>
                                <label class="form-check-label" for="reportes_s">Secuenciales</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="reportes_c" name="reportes_c" @if($rol->reportes_c == 1) checked @endif disabled>
                                <label class="form-check-label" for="reportes_c">Campo</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="reportes_i" name="reportes_i" @if($rol->reportes_i == 1) checked @endif disabled>
                                <label class="form-check-label" for="reportes_i">Tablas</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="roles" name="roles" @if($rol->roles == 1) checked @endif disabled>
                                <label class="form-check-label" for="roles">Roles</label>
                            </div>    
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="usuarios" name="usuarios" @if($rol->usuarios == 1) checked @endif disabled>
                                <label class="form-check-label" for="usuarios">Usuarios</label>
                            </div>    
                        </td>
                    </tr>
                </tbody>
            </table>
    
    
            <form method="POST" action="{{route('rol.destroy',$rol->idRol)}}">
                @method('delete')
                {{-- {{ method_field('DELETE') }} --}}
                @csrf
                <button type="submit" class="btn btn-danger"><i class="fas fa-check-square"></i> SÍ</button>
                <a href="{{route('cancelar.rol')}}" class="btn btn-primary"><i class="fas fa-times-circle"></i>NO</a>
              </form>
        </div>
    </div>


@endsection