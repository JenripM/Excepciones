<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <link href="{{asset('/css/login.css')}}" rel="stylesheet">

</head>
<body class="container-fluid">
    <div class="row" style="height: 100vh">
        <div class="col-6" style="background-color: aquamarine">

        </div>
        <main class="col-6">
            <div class="container_full_center" style="gap: 10px;">
                <h1>Inicie Sesión</h1>
                <form method="POST" style="width: 50%;" action="{{route('inicia-sesion')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"  value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$message}}</strong>
                                </span>
                            @enderror
                    </div>
        
        
                    {{-- <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="rememberCheck">
                        <label for="rememberCheck" class="form-check-label">Mantener sesión iniciada</label>
                    </div> --}}
        
                    
                    {{-- <div>
                        <p>¿No tienes cuenta? <a href="{{route('registro')}}">Regístrate</a></p>
                    </div> --}}
                    <button type="submit" class="boton_verde" style="width: 100%">Acceder</button>
                </form>
            </div>
        </main>
    </div>
    


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
    {{-- <script>
        function avoidSpaces(event) {
            if (event.keyCode === 32) { // Verificar si se presiona la tecla de espacio
                event.preventDefault(); // Prevenir que se escriba el espacio
                return false;
            }
            return true;
        }
    </script> --}}
</body>
</html>