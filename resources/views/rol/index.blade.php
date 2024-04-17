@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">

        <h1 style="text-align: center; background-color: aqua">GESTIÓN DE ROLES DE USUARIO</h1>
        <a href="{{route('rol.create')}}" class="btn btn-primary" style="margin-right: 30px">
            <i class="fa fa-plus"></i>Nuevo Registro
        </a>
    
      <div class="table-responsive" style="margin-top: 10px">
            <table class="table table-bordered">
            <thead>
            <tr>
                <th>Código</th>
                <th>Descripcion</th>
                <th>Funciones</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
                @if(count($rol)<=0)
                <tr>
                    <td colspan="4"><h4>No hay registros</h4></td>
                </tr>
                @else
                @foreach ($rol as $item)
                    <tr>
                        <td>{{$item->idRol}}</td>
                        <td>{{$item->descripcion}}</td>
                        <td>
                            @if ($item->tablas == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">Tablas</label>

                            @if ($item->vista_sql == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">I. SQL</label>

                            @if ($item->excepciones_s == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">E.S.</label>
                            

                            @if ($item->excepciones_c == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">E.C.</label>



                            @if ($item->excepciones_i == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">E.T.</label>


                            @if ($item->reportes_s == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">R.S</label>



                            @if ($item->reportes_c == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">R.C.</label>


                            @if ($item->reportes_i == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">R.T.</label>


                            @if ($item->roles == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">Roles</label>


                            @if ($item->usuarios == '1')
                                <img src="{{asset('/img/check-circle-fill.svg')}}" alt="">
                            @else
                                <img src="{{asset('/img/x-circle-fill.svg')}}" alt="">
                            @endif
                            <label for="">Usuarios</label>
                        </td>
                        <td>
                            <a href="{{route('rol.edit',$item->idRol)}}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>Editar</a>
                            <a href="{{route('rol.confirmar',$item->idRol)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Eliminar</a>
                        </td>
                    </tr>
                @endforeach
                @endif
            </tbody>
            </table>
        </div>

    
        
    </div>

@endsection