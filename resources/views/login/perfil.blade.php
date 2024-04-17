@extends('layout.plantilla')


@section('contenido')

    <div class="page-wrapper" style="margin-top: 70px">
            
        <div class="container">
            <h1>Perfil del Usuario</h1>
            <br><br>
            <form method="POST" action="{{route('user.update2',$user->id)}}">
                @method('put')
                @csrf

                <div class="form-group row">
                    <div class="col-3">
                        <label for="name" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{$user->name}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror                    
                    </div>
    
                    <div class="col-4" style="margin-bottom: 30px">
                        <label style="margin-right:10px">Rol</label>
                        <select class="form-control selectpicker" name="idRol" id="idRol" required>
                            <option value="{{$user->idRol}}" selected>{{ $user->descripcion }}</option>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una opción</div>
                    </div>

                    <div class="col-6">
                        <label for="password" class="form-label">Nueva Contraseña</label>
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

                <button type="submit" class="btn btn-primary" id="registrar"><i class="fas fa-save"></i>Actualizar</button>
            </form>
        </div>

    </div>


    <script src="/js/rol.js"></script>


@endsection