<!-- resources/views/conexion/error.blade.php -->

@extends('layout.plantilla')

@section('contenido')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">¡Ha ocurrido un error!</h4>
                <p>{{ $mensaje }}</p>
                <hr>
                <p class="mb-0">Por favor, intentar de nuevo.</p>
            </div>
        </div>
    </div>
</div>
@endsection
