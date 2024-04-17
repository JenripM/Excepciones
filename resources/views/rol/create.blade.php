@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">
            
        <div class="container">
            <h1>Registrar</h1>
            <br><br>
            <form method="POST" action="{{route('rol.store')}}">
                @csrf

                <div class="form-group row">

                    <div class="col-4" style="margin-bottom: 30px">
                        <label for="" class="form-label">Descripcion del rol</label>
                        <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"  value="{{ old('descripcion') }}" required>
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
                                            <input class="form-check-input" type="checkbox" value="" id="bd" name="bd">
                                            <label class="form-check-label" for="bd">Base de Datos</label>
                                        </div> 
                                    </th>
                                    <th colspan="3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones" name="excepciones">
                                            <label class="form-check-label" for="excepciones">Excepciones</label>
                                        </div> 
                                    </th>
                                    <th colspan="3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes" name="reportes">
                                            <label class="form-check-label" for="reportes">Reportes de Excepciones</label>
                                        </div> 
                                    </th>
                                    <th colspan="2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="privilegios" name="privilegios">
                                            <label class="form-check-label" for="privilegios">Privilegios</label>
                                        </div> 
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="tablas" name="tablas">
                                            <label class="form-check-label" for="tablas">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="vista_sql" name="vista_sql">
                                            <label class="form-check-label" for="vista_sql">Instrucciones SQL</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_s" name="excepciones_s">
                                            <label class="form-check-label" for="excepciones_s">Secuenciales</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_c" name="excepciones_c">
                                            <label class="form-check-label" for="excepciones_c">Campo</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="excepciones_i" name="excepciones_i">
                                            <label class="form-check-label" for="excepciones_i">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_s" name="reportes_s">
                                            <label class="form-check-label" for="reportes_s">Secuenciales</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_c" name="reportes_c">
                                            <label class="form-check-label" for="reportes_c">Campo</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="reportes_i" name="reportes_i">
                                            <label class="form-check-label" for="reportes_i">Tablas</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="roles" name="roles">
                                            <label class="form-check-label" for="roles">Roles</label>
                                        </div>    
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="usuarios" name="usuarios">
                                            <label class="form-check-label" for="usuarios">Usuarios</label>
                                        </div>    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>



                <button class="btn btn-primary" id="registrar" disabled>
                    <i class="fa fa-save"></i>Grabar
                </button>

                <a href="{{route('cancelar.rol')}}" class="btn btn-danger">
                    <i class="fas fa-ban"></i>Cancelar</a>
            </form>
        </div>

    </div>


    <script src="/js/rol.js"></script>


@endsection