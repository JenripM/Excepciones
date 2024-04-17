@extends('layout.plantilla')

@section('contenido')
@php
$contador = 0;
@endphp
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4>Reporte de Excepciones de Campos</h4>
                <div class="card-header">
                  
                    <p id="fecha-hora" class="mb-4"></p>
                    <p><strong>Generado por:</strong>{{$user->name}}</p>
                    <p><strong>Nombre base de datos:</strong> {{$conexion->nombreBase}}</p>
                    <p><strong>Gestor de base de datos:</strong> {{$conexion->tipoConexion}}</p>
                    <p>Este reporte muestra las excepciones en campos que se han encontrado.</p>
                    <hr>
                </div>
            </div>
            <div class="card-body">
                @foreach ($excepcion as $index => $itemexcepcion)
                <div class="container mt-4 tablaContentTIPODATO">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Reporte: {{$itemexcepcion->tabla}}, {{$itemexcepcion->columna}}</h5>
                                </div>
                                <div>
                                    {{$itemexcepcion->detalle}}
                                </div>
                                {{-- <div class="card-body">
                                    <p class="card-text">Fecha: {{$itemexcepcion->fecha}}</p>
                                    <div class="container tablaContentNULLOP table-responsive" >
                                        <div class="container mt-4 tablaContentTIPODATO" >
                                            <div class="row">
                                                <div class="col">
                                                    <label class="text-center">Null?</label>
                                                    <label class="text-center">
                                                        @if ($PRnull[$index] == 'YES') No permitido NULL  @else {No se enviaron parámetros de evaluación} @endif        
                                                </label>
                                                </div>
                                            </div>
                                        </div>    
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">Registro</th>
                                                    <th scope="col">ID</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tablaCAMPO1">
                                                <tr>
                                                    <td>{!! $RESnull[$index] !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="container tablaContentNULLOP table-responsive" >
                                        <div class="col">
                                            <label class="text-center">CAMPO</label>
                                            <label class="text-center">
                                               
                                                    @if ($PRcampo[$index] != 'NOPR')Parametros aceptados: {{$PRcampo[$index]}} @else {No se enviaron parámetros de evaluación} @endif
                                                
                                            </label>
                                        </div>
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">Registro</th>
                                                    <th scope="col">ID</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tablaCAMPO1">
                                                <tr>
                                                    <td>{!! $REScampo[$index] !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="container tablaContentNULLOP table-responsive" >
                                        <div class="col">
                                            <label class="text-center">Dato aceptados</label>
                                            <label class="text-center">
                                               
                                                    @if ($PRdato[$index] == 'L') Tipo de datos aceptado: VARCHAR @endif @if ($PRdato[$index] == 'N') Tipo de datos aceptado: INT  @endif @if ($PRdato[$index] == 'null') {No se selecciono parámetros de evaluación} @endif
                                                
                                            </label>
                                        </div>
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col">Registro</th>
                                                    <th scope="col">ID</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tablaCAMPO1">
                                                <tr>
                                                    <td>{!! $RESdato[$index] !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($excepcion->isEmpty())
                <div class="alert alert-info" role="alert">
                    No hay excepciones de campo disponibles.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection







