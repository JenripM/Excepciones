@extends('layout.plantilla')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-4">Reporte de Excepciones Secuenciales</h1>
                <p id="fecha-hora" class="mb-4"></p>
                <p><strong>Generado por:</strong>{{$user->name}}</p>
                <p><strong>Nombre base de datos:</strong> {{$conexion->nombreBase}}</p>
                <p><strong>Gestor de base de datos:</strong> {{$conexion->tipoConexion}}</p>
                <p>Este reporte muestra las excepciones secuenciales que se han encontrado.</p>
                <hr>
            </div>
            <div class="card-body">
                @foreach ($excepcion as $itemexcepcion)
                <div class="mb-4 border p-3">
                    <h4>Excepcion {{ $loop->iteration }}</h4>
                    <h5>Fecha y hora de identificación de la excepción: <span style="font-weight:normal;">{{ $itemexcepcion->fecha }}</span></h5>
                    <p><strong>Tabla:</strong> {{ $itemexcepcion->tabla }}</p>
                    <p><strong>Columna:</strong> {{ $itemexcepcion->columna }}</p>
                    <p><strong>Detalle:</strong></p>
                    <div class="alert alert-danger" role="alert">
                        {!! $itemexcepcion->detalle !!}
                    </div>
                </div>
                @endforeach
                @if($excepcion->isEmpty())
                <div class="alert alert-info" role="alert">
                    No hay excepciones de secuencialidad disponibles.
                </div>
                @endif
            </div>
            <h6>Descripción:</h6>
        <ul>
            <li>
                <p>
                    Las excepciónes señaladas son posibles debido a una eliminación del registro o ingreso incorrecto de este. 
                </p>
            </li>
            <li>
                <p>
                    Se recomienda implementar una validación rigurosa de los datos antes de realizar operaciones de inserción o eliminación en la base de datos.
                </p>
            </li>
            <li>
                <p>
                    El impacto de esta excepción puede comprometer la integridad de los datos en la base de datos, lo que lleva a inconsistencias y errores.
                </p>
            </li>
        </ul>
        </div>
    </div>
</div>

<script>
    // Obtener fecha y hora actual
    var fechaHora = new Date();

    // Formatear la fecha y hora según el formato deseado
    var opcionesFechaHora = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    var fechaHoraFormateada = fechaHora.toLocaleDateString('es-PE', opcionesFechaHora);

    // Mostrar la fecha y hora en el elemento con el ID 'fecha-hora'
    document.getElementById('fecha-hora').innerText = "Fecha y hora: " + fechaHoraFormateada;
</script>
@endsection
