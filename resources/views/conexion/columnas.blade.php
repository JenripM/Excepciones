@extends('layout.plantilla')

@section('contenido')

<!-- Botón para activar el modal -->


<table class="table">
    <thead>
        <tr>
            <th scope="col">Nombre de la columna</th>
            <th scope="col">Is Null</th>
            <th scope="col">Tipo de dato</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultado as $fila)
        <tr>
            <td>{{ $fila['COLUMN_NAME'] }}</td>
            <td>{{ $fila['IS_NULLABLE'] }}</td>
            <td>{{ $fila['DATA_TYPE'] }}</td>
            <!-- Agrega más columnas si necesitas mostrar más información -->
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
