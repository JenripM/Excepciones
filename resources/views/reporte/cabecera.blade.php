@extends('layout.plantilla')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-4">Reporte de Excepciones Cabecera-Detalle</h1>
                <p id="fecha-hora" class="mb-4"></p>
                <p><strong>Generado por:</strong> {{$user->name}}</p>
                <p><strong>Nombre base de datos:</strong> {{$conexion->nombreBase}}</p>
                <p><strong>Gestor de base de datos:</strong> {{$conexion->tipoConexion}}</p>
                <p>Este reporte muestra las excepciones de cabecera-detalle que se han encontrado.</p>
                <hr>
            </div>
            <div class="card-body">
                @foreach ($excepcion as $index => $itemexcepcion)
                <div class="mb-4 border p-3">
                    <h4>Excepción {{ $loop->iteration }}</h4>
                    <h5>Fecha y hora de identificación de la excepción: <span style="font-weight:normal;">{{ $itemexcepcion->fecha }}</span></h5>
                    <p><strong>Tabla Cabecera:</strong> {{ $tablaCabecera[$index] }}</p>
                    <p><strong>Columna Cabecera:</strong> {{ $ColumnaCabecera[$index] }}</p>
                    <p><strong>Tabla Detalle:</strong> {{ $tablaDetalle[$index] }}</p>
                    <p><strong>Columna Detalle:</strong> {{ $ColumnaDetalle[$index] }}</p>
                    <p><strong>Detalle:</strong></p>
                    <div class="alert alert-danger" role="alert">
                        {!! $itemexcepcion->detalle !!}
                    </div>
                </div>
                @endforeach
                @if($excepcion->isEmpty())
                <div class="alert alert-info" role="alert">
                    No hay excepciones de cabecera-detalle disponibles.
                </div>
                @endif
            </div>
            <h6>Descripción:</h6>
<ul>
    <li>
        <p>
            Las excepciones señaladas indican que existe al menos un registro en la tabla de detalle que no tiene un correspondiente en la tabla de cabecera. Esto puede ocurrir debido a una eliminación incorrecta de registros o a la inserción de datos no válidos.
        </p>
    </li>
    <li>
        <p>
            Se recomienda revisar los procesos de inserción y eliminación de datos para garantizar que se mantenga la integridad referencial entre la tabla de cabecera y la de detalle. Esto puede implicar la implementación de restricciones de clave externa o la validación rigurosa de los datos antes de realizar operaciones en la base de datos.
        </p>
    </li>
    <li>
        <p>
            Estas excepciones pueden tener un impacto significativo en la integridad de los datos en la base de datos, lo que puede conducir a inconsistencias y errores en la aplicación. Es importante abordar estas excepciones de manera proactiva para mantener la integridad y la consistencia de los datos.
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
