
@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">

        <h1 style="text-align: center; background-color: rgb(0, 255, 106)">GESTIÓN DE USUARIOS</h1>
        <a href="{{route('user.create')}}" class="btn btn-primary" style="margin-right: 30px">
            <i class="fa fa-plus"></i>Nuevo Registro
        </a>
    
      <div class="table-responsive" style="margin-top: 10px">
            <table class="table table-bordered">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Rol de Usuario</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
                @if(count($user)<=0)
                <tr>
                    <td colspan="4"><h4>No hay registros</h4></td>
                </tr>
                @else
                @foreach ($user as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            @if ($item->idRol == NULL)
                                <label for="" style="color: red">SIN ROL ASIGNADO</label>
                            @else
                                {{$item->descripcion}}
                            @endif
                        </td>
                        <td>
                            <a href="{{route('user.edit',$item->id)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>Editar</a>
                            <a href="{{route('user.confirmar',$item->id)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Eliminar</a>
                        </td>
                    </tr>
                @endforeach
                @endif
            </tbody>
            </table>
        </div>

    
        
    </div>

@endsection