@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">
            
        <div class="container">
            <h1>Editar Usuario</h1>
            <br><br>
            <form method="POST" action="{{route('user.update',$user->id)}}">
                @method('put')
                @csrf

                <div class="form-group row">
                    <div class="col-3">
                        <label for="name" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}" disabled>                    
                    </div>
    
                    <div class="col-6" style="margin-bottom: 30px">
                        <label style="margin-right:10px">Rol</label>
                        <select class="form-control selectpicker" name="idRol" id="idRol" required>
                            @if ($user->idRol == NULL)
                                <option value="" selected disabled>Seleccione una opcion</option>
                            @endif
                            @foreach ($rol as $item)
                                @if ($user->idRol == $item->idRol)
                                    <option value="{{$item->idRol}}" selected> {{ $item->descripcion }}</option>    
                                @else
                                    <option value="{{$item->idRol}}"> {{ $item->descripcion }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una opción</div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary" id="registrar"><i class="fas fa-save"></i> Grabar</button>
                <a href="{{route('cancelar.user')}}" class="btn btn-danger">
                    <i class="fas fa-ban"></i>Cancelar</a>
            </form>
        </div>

    </div>


    <script src="/js/rol.js"></script>


@endsection