@extends('layout.plantilla')


@section('contenido')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">
<form action="{{route('conexionSQL')}}" method="post">
    @csrf
<div class="mb-3">
    <label for="Host" class="form-label">Host</label>
    <input type="text" @if($conexion) disabled @endif class="form-control" name="Host" value="@if($conexion){{$conexion->servidor}} @endif" id="Host" placeholder="Host" required>
  </div>
  <div class="mb-3">
    <label for="nombreBD" class="form-label">Nombre de la base de datos</label>
    <input @if($conexion) disabled @endif value="@if($conexion){{$conexion->nombreBase}} @endif" type="text" class="form-control" id="nombreBD"name="nombreBD" placeholder="Base de datos" required>
  </div>
  
  <div class="mb-3">
    <label for="tipoSql" class="form-label">Gestor base de datos</label>
    <select @if($conexion) disabled @endif name="tipoSql" class="form-select" id="tipoSql">
      <option disabled selected>...</option>
      <option value="sqlserver" @if($conexion && $conexion->tipoConexion == 'sqlserver') selected @endif>SQL Server</option>
      <option value="mysql" @if($conexion && $conexion->tipoConexion == 'mysql') selected @endif>MySQL</option>
  </select>
  



  <div class="mb-3 form-check" style="display: none">
    <input type="checkbox" class="form-check-input" id="autenticacionWindows" >
    <label class="form-check-label" for="autenticacionWindows">Autenticación de Windows</label>
</div>

<div class="mb-3">
  <label for="puerto" class="form-label">Puerto</label>
  <input @if($conexion) disabled @endif value="@if($conexion){{$conexion->puerto}} @endif" type="text" class="form-control" id="puerto"name="puerto" placeholder="Puerto" required>
</div>

  <div class="mb-3" id="usuarioContrasenaInputs">
  <div class="mb-3">
    <label for="Usuario" class="form-label">Usuario</label>
    <input @if($conexion) disabled @endif value="@if($conexion){{$conexion->usuario}} @endif" type="text" class="form-control" name="Usuario" id="Usuario" placeholder="Usuario">
  </div>
  <div class="mb-3">
    <label for="Contrasena" class="form-label">Contraseña</label>
    <input @if($conexion) disabled @endif value="@if($conexion){{$conexion->Contraseña}} @endif" type="password" class="form-control" name="Contrasena" id="Contrasena" placeholder="Contraseña">
  </div>
</div>

  </div>
  <button @if($conexion) disabled @endif type="submit" class="btnConectar btn btn-primary btn-block">Conectar</button>
  <button @if(!$conexion) disabled @endif class="btnDesconectar btn btn-primary btn-block">Desconectar</button>
</form>

</div>  
</div> 
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('.btnDesconectar').click(function(){
    event.preventDefault(); 
    $.ajax({
      type:'GET',
      url:'/desconexion/',
      success:function() {
                $('input[type="text"]').val('');
                $('#tipoSql').val('...');///Usuario
                $('#Usuario').val('');///Usuario
                $('#Contrasena').val('');
                $('#puerto').val('');
                $('.btnDesconectar').prop('disabled', true);
                $('.btnConectar').prop('disabled', false);
                $('#Host').prop('disabled', false);
                $('#nombreBD').prop('disabled', false);
                $('#tipoSql').prop('disabled', false);
                $('#Usuario').prop('disabled', false);
                $('#Contrasena').prop('disabled', false);
                $('#puerto').prop('disabled', false);

      }
    });
  });

  $('#tipoSql').change(function(){
    var tipoSQL = $(this).val();
    if (tipoSQL === 'sqlserver') {
            $('#autenticacionWindows').closest('.form-check').show();
        } else {
            $('#autenticacionWindows').closest('.form-check').hide();
            
        }
  });

  $('#autenticacionWindows').change(function(){
    var isChecked = $(this).is(':checked');
        if (isChecked) {
            $('#usuarioContrasenaInputs').hide();
        } else {
            $('#usuarioContrasenaInputs').show();
        }
  });

 

</script>

@endsection