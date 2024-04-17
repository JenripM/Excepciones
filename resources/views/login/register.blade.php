<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{asset('/css/login.css')}}" rel="stylesheet">
</head>
<body class="container-fluid">
    
    <div class="row" style="height: 100vh">
        <div class="col-6" style="background-color: aquamarine">

        </div>
        <main class="col-6">
            <div class="container_full_center" style="gap: 40px;">
                <h1>Registrar usuario</h1>
                <form method="POST" style="width: 50%;" action="{{route('validar-registro')}}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombres</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required autocomplete="off">
                            @error('name')
                                @foreach($errors->get('name') as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endforeach
                            @enderror
                    
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" onkeypress="return avoidSpaces(event)" name="email" id="email" value="{{ old('email') }}" required autocomplete="off">
                            @error('email')
                                @foreach($errors->get('email') as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endforeach
                            @enderror
                    </div>
                    <div class="mb-3">
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

                    <div style="margin-top: 30px; text-align: center;">
                        <button type="submit" class="boton_verde" style="width: 100%">Registrarse</button>
                        <a href="{{route('login')}}">Atrás</a>
                    </div>
                </form>
            </div>
        </main>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function avoidSpaces(event) {
            if (event.keyCode === 32) { // Verificar si se presiona la tecla de espacio
                event.preventDefault(); // Prevenir que se escriba el espacio
                return false;
            }
            return true;
        }
    </script>
</body>
</html>