@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">
            
        <div class="container">
            <h1>Registrar</h1>
            <br><br>
            <form method="POST" action="{{route('user.store')}}">
                @csrf

                <div class="form-group row">
                    <div class="col-3">
                        <label for="name" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required autocomplete="off">
                            @error('name')
                                @foreach($errors->get('name') as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endforeach
                            @enderror
                    
                    </div>
    
                    <div class="col-6" style="margin-bottom: 30px">
                        <label style="margin-right:10px">Rol</label>
                        <select class="form-control selectpicker" name="idRol" id="idRol" required>
                            <option value="" selected disabled>Seleccione una opcion</option>
                            @foreach ($rol as $item)
                                <option value="{{$item->idRol}}"> {{ $item->descripcion }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una opción</div>
                    </div>

                    <div class="col-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                            @error('password')
                                @foreach($errors->get('password') as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endforeach
                            @enderror
                    </div>
                </div>

                <button class="btn btn-primary" id="registrar">
                    <i class="fa fa-save"></i>Grabar
                </button>

                <a href="{{route('cancelar.user')}}" class="btn btn-danger">
                    <i class="fas fa-ban"></i>Cancelar</a>
            </form>
        </div>

    </div>


    <script src="/js/rol.js"></script>


@endsection