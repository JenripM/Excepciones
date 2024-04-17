@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 100px">
        <div class="container">
            <h1>Desea Eliminar el Usuario?</h1><br>
            <h3>Código: {{$user->id}}</h3>
            <h3>Nombre: {{$user->name}}</h3>
            <h3>Rol: 
                @if ($user->idRol == NULL)
                    <label for="" style="color: red">SIN ROL ASIGNADO</label>
                @else
                    {{$user->descripcion}}
                @endif
            </h3><br>
 
            <form method="POST" action="{{route('user.destroy',$user->id)}}">
                @method('delete')
                {{-- {{ method_field('DELETE') }} --}}
                @csrf
                <button type="submit" class="btn btn-danger"><i class="fas fa-check-square"></i> SÍ</button>
                <a href="{{route('cancelar.user')}}" class="btn btn-primary"><i class="fas fa-times-circle"></i>NO</a>
              </form>
        </div>
    </div>


@endsection