@extends('layout.plantilla')

@section('contenido')
<div id="customAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
    <strong>¡Error!</strong> <span id="alertMessage"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Resultado de la consulta SQL. <label for="">Mensaje: "Por favor, ten en cuenta que solo se permite realizar consultas SELECT en esta aplicación. No está permitido realizar operaciones de modificación, como INSERT, UPDATE o DELETE.</label> </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="consulta" class="form-label">Consulta SQL</label>
                        <textarea class="form-control" id="consulta" name="consulta" rows="3" required></textarea>
                    </div>
                    <button class="btn btn-primary" id="btnEjecutarConsulta">Ejecutar Consulta</button>
                    <hr>
                    <div class="table-responsive">
                        <div class="aqui" style="display: none">
                            <!-- Aquí se mostrará la tabla -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function showAlert(message) {
    var alertMessage = document.getElementById("alertMessage");
    alertMessage.innerText = message;
    
    var customAlert = document.getElementById("customAlert");
    customAlert.style.display = "block";
    setTimeout(function() {
        customAlert.style.display = "none";
    }, 2000);
}
</script>


<script>
    document.getElementById("btnEjecutarConsulta").addEventListener("click", function() {
    var consultaInput = document.getElementById("consulta");
    var consulta = consultaInput.value.trim().toUpperCase(); // Convertir a mayúsculas para evitar sensibilidad a mayúsculas y minúsculas
    
    if (consulta === "") {
        showAlert("Por favor, ingresa una consulta SQL.");
        return;
    }
    
    var palabrasProhibidas = ["INSERT", "DELETE", "UPDATE"];
    var prohibido = false;
    
    // Verificar si la consulta contiene alguna palabra prohibida
    for (var i = 0; i < palabrasProhibidas.length; i++) {
        var palabra = palabrasProhibidas[i];
        if (consulta.indexOf(palabra) !== -1) {
            showAlert("No se permite realizar la acción '" + palabra + "'. Solo se permite consultar datos empleando SELECT.");
            prohibido = true;
            break;
        }
    }
    
    if (!prohibido) {
        // Aquí puedes continuar con tu lógica para ejecutar la consulta SQL
        ejecutarConsultaAjax();
    }
});

function ejecutarConsultaAjax() {
    var sqlconsulta = $('#consulta').val();
    $.ajax({
        type:'GET',
        url:'/sqlDinamico2/' + sqlconsulta,
        success:function(data) {
            $('.aqui').html(data);
            $('.aqui').show(); // Muestra el contenido
        }
    });
}

</script>

@endsection
