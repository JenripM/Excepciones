@extends('layout.plantilla')

@section('contenido')

<!-- Botón para activar el modal -->


<table class="table">
    <thead>
        <tr>
            <th scope="col">Nombre de la tabla</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultado as $fila)
            <tr>
                <td>{{ $fila['TABLE_NAME'] }}</td>
                <td>
                    <a href="{{ route('columnas', ['tabla' => $fila['TABLE_NAME']]) }}" class="btn btn-primary" >
                        Mostrar columnas
                    </a>
                    
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
