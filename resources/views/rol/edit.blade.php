@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">
            
        <div class="container">
            <h1>Editar Rol</h1>
            <br><br>
            <form method="POST" action="{{route('rol.update',$rol->idRol)}}">
                @method('put')
                @csrf

                <div class="form-group row">

                    <div class="col-4" style="margin-bottom: 30px">
                        <label for="" class="form-label">Descripcion del rol</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"  value="{{$rol->descripcion}}" required>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead style="background-color: white">
                                <tr>
                                    <th colspan="10" style="background: black; color: white">Funciones</th>
                                </tr>
                                <tr style="text-align: center">
                                    <th colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="bd" name="bd" @if($rol->tablas == 1 && $rol->vista_sql == 1) checked @endif>
                                            <label class="form-check-label" for="bd">Base de Datos</label>
                                        </div> 
                                    </th>
                                    <th colspan="3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones" name="excepciones" @if($rol->excepciones_s == 1 && $rol->excepciones_c == 1  && $rol->excepciones_i == 1) checked @endif>
                                            <label class="form-check-label" for="excepciones">Excepciones</label>
                                        </div> 
                                    </th>
                                    <th colspan="3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes" name="reportes" @if($rol->reportes_s == 1 && $rol->reportes_c == 1  && $rol->reportes_i == 1) checked @endif>
                                            <label class="form-check-label" for="reportes">Reportes de Excepciones</label>
                                        </div> 
                                    </th>
                                    <th colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="privilegios" name="privilegios" @if($rol->roles == 1 && $rol->usuarios == 1) checked @endif>
                                            <label class="form-check-label" for="privilegios">Privilegios</label>
                                        </div> 
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="tablas" name="tablas" @if($rol->tablas == 1) checked @endif>
                                            <label class="form-check-label" for="tablas">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="vista_sql" name="vista_sql" @if($rol->vista_sql == 1) checked @endif>
                                            <label class="form-check-label" for="vista_sql">Instrucciones SQL</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_s" name="excepciones_s" @if($rol->excepciones_s == 1) checked @endif>
                                            <label class="form-check-label" for="excepciones_s">Secuenciales</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_c" name="excepciones_c" @if($rol->excepciones_c == 1) checked @endif>
                                            <label class="form-check-label" for="excepciones_c">Campo</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_i" name="excepciones_i" @if($rol->excepciones_i == 1) checked @endif>
                                            <label class="form-check-label" for="excepciones_i">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_s" name="reportes_s" @if($rol->reportes_s == 1) checked @endif>
                                            <label class="form-check-label" for="reportes_s">Secuenciales</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_c" name="reportes_c" @if($rol->reportes_c == 1) checked @endif>
                                            <label class="form-check-label" for="reportes_c">Campo</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_i" name="reportes_i" @if($rol->reportes_i == 1) checked @endif>
                                            <label class="form-check-label" for="reportes_i">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="roles" name="roles" @if($rol->roles == 1) checked @endif>
                                            <label class="form-check-label" for="roles">Roles</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="usuarios" name="usuarios" @if($rol->usuarios == 1) checked @endif>
                                            <label class="form-check-label" for="usuarios">Usuarios</label>
                                        </div>    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>


                <button type="submit" class="btn btn-primary" id="registrar"><i class="fas fa-save"></i> Grabar</button>
                <a href="{{route('cancelar.rol')}}" class="btn btn-danger">
                    <i class="fas fa-ban"></i>Cancelar</a>
            </form>
        </div>

    </div>


    <script src="/js/rol.js"></script>

@endsection