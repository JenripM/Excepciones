@extends('layout.plantilla')

@section('contenido')
<div class="container-fluid">
    <div class="row">
<div class="col-md-8">
    
    <label for="">Condición 1: Seleccionar columnas con tipo de dato númericos y/o algunas excepciones de tipo de dato cadena de caracteres los cuales cumplan con la condición inferior ↓</label>

    <div class="form-check">
         <label style="margin-right: 35px" class="form-check-label" for="miCheckbox">
            Si desea evaluar registros con este formato: Bol1, Bol2, Bol3 o RR1, RR2, RR3 u otro similar. Por favor marcar el recuadro 
        </label > <input class="form-check-input" type="checkbox" value="" id="miCheckbox">
    </div>

    <div class="container mt-4 text-center">
        <label for="">Tabla</label>
        <select id="comboTablas" class="form-select-sm w-50 fs-5">
            <option disabled selected>...</option>
            @foreach ($resultado as $fila)
                <option value="{{ $fila['TABLE_NAME'] }}">{{ $fila['TABLE_NAME'] }}</option>
            @endforeach
        </select>
    </div>
    
    
    <div class="container containerColumnas mt-4 text-center" style="display: none">
        
        <label class="containerColumnas" style="display: none">Columna</label>
        <select id="comboColumnas" class="form-select-sm w-50 fs-5">
            
             
        </select>
    </div>
    
    
    <div class="container contenidoColumna mt-4 text-center">
        
    </div>
    <div class="container mt-4 text-center">
        <div class="btn-toolbar" role="toolbar">
     <div class="btn-group  me-2 justify-content-center" role="group"id="btnSecuencial" style="display: none">
        <button id="botonSecuencial" class="btn  btn-primary">Evaluar</button>
    </div> 
    <div class="btn-group justify-content-center" role="group"id="guardaConsulta__1" class="btnGuardarConsulta" style="display: none">
        <button id="guardaConsulta__1" class="btn  btn-primary">Guardar Consulta</button>
    </div>
     </div>
    </div>
    <div class="container resultadoSecuencial mt-4 text-center">
        
    </div>
</div>
<div class="col-md-4 consultas__1">
    
    <div class="table-responsive " style="height: 500px;">
        <h1>CONSULTAS</h1>
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Tabla</th>
                <th>Columna</th>
                <th>Formato distinto</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>

        @if ($consulta__1Encontrada->isEmpty()) 
            <tr>
                <td colspan="2">No hay consultas registradas</td>
                        </tr>
        @else 
          
            @foreach ($consulta__1Encontrada as $consulta) 
                <tr>
                                <td class="tabnom"> {{$consulta->tablaNombre }}</td>
                                <td class="colnom">{{$consulta->columnaNombre }}</td>
                                <td class="colnom">{{$consulta->formatoEspecial }}</td>
                                <td> <button class="btn btn-primary seleccion">Seleccionar</button> </td>
                            </tr>
            
            @endforeach
        

        @endif
    </tbody>
        </table>
        </div>



</div>
</div>
</div>



<div id="customAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
    <strong>¡Error!</strong> <span id="alertMessage"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
    }, 5000);
}
</script>
<script>
    $(document).ready(function() {
        $('#comboTablas').change(function() {
            var valorSeleccionado = $(this).val();
            console.log("valor: " + valorSeleccionado);
            $.ajax({
                type: 'GET',
                url: '/secuenciasTablaShowColumn/' + valorSeleccionado,
                success: function(data) {
                    console.log("valor: " + data);
                    $('#comboColumnas').html(data);
                    $('.containerColumnas').show();
                    $('.resultadoSecuencial').hide();
                    $('.contenidoColumna').hide();
                    $('#btnSecuencial').hide();
                   
                }
            });
        });

        $('#comboColumnas').change(function() {
            var columnaSeleccionada = $(this).val();
            var tablaSeleccionada = $('#comboTablas').val();
            console.log("Valor seleccionado: " + columnaSeleccionada + " " +tablaSeleccionada); 
            $.ajax({
                type: 'GET',
                url: '/showContenidoColumn/' + tablaSeleccionada + '/'+columnaSeleccionada,
                success: function(data) {
                    console.log("data: " + data);
                    $('.contenidoColumna').html(data); 
                    $('.contenidoColumna').show();
                    $('#btnSecuencial').show();
                    $('.resultadoSecuencial').hide();
                    $('#guardaConsulta__1').show();
                }
            });
        });


        $('#botonSecuencial').click(function() {
            var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
            console.log("d-" + sel);
            $.ajax({
                type: 'GET',
                url: '/evaluaSecuencialEXP1/'+sel,
                success: function(data) {
                    console.log('d: '+data);
                    if (data == 'ERROR') {
                            showAlert("Por favor, seleccione una columna con tipo de dato valida int, bigint, smallint, decimal, numeric, float, real, tinyint y/o excepciones que indica en Condicion 1.");
                    }else{
                    $('.resultadoSecuencial').html(data);
                    $('.resultadoSecuencial').show(); 
                }
                }
            });
        });
 

        $('#guardaConsulta__1').click(function() {
            var tabla = $('#comboTablas').val();
            var columna = $('#comboColumnas').val();
            var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
            $.ajax({
                type: 'GET',
                url: '/guardarConsulta__1/'+tabla+'/'+columna+'/'+sel,
                success: function(data) {
                    console.log("data: "+data)
                    console.log('d: '+data);
                    $('.consultas__1').html(data);
                }
            });
        });
        function actualizarDatos() {
            // Realiza una solicitud AJAX para obtener los datos actualizados
            $.ajax({
                type: 'GET',
                url: 'mostrarConsulta__1', // Reemplaza esto con la ruta de tu controlador que devuelve los datos
                success: function(data) {
                    // Actualiza el contenido de un elemento HTML con los datos recibidos
                    $('.consultas__1').html(data);
                }
            });
        }
            //setInterval(actualizarDatos, 1000);

            $(document).on('click', '.seleccion', function() {
                var fila = $(this).closest('tr');
                var tablaNombre = fila.find('td:eq(0)').text().trim(); 
                var columnaNombre = fila.find('td:eq(1)').text().trim(); 
                var check = fila.find('td:eq(2)').text().trim();
                console.log("Tabla: " + tablaNombre);
                console.log("Columna: " + columnaNombre);
                var tablatabla = document.getElementById("comboTablas");
                tablatabla.value= tablaNombre;
                if (check == 'YES') {
                    var tablatabla = document.getElementById("miCheckbox");
                    tablatabla.checked = true
                }
                

                 $.ajax({
                 type: 'GET',
                 url: '/secuenciasTablaShowColumn/' + tablaNombre,
                 success: function(data) {
                     console.log("valor: " + data);
                     $('#comboColumnas').html(data);
                     var tablatabla = document.getElementById("comboColumnas");
                    tablatabla.value= columnaNombre; 
                     $('.containerColumnas').show();
                     $('.resultadoSecuencial').hide();
                     $('.contenidoColumna').hide();
                     $('#btnSecuencial').hide();
                 }
                });
                $.ajax({
                type: 'GET',
                url: '/showContenidoColumn/' + tablaNombre + '/'+columnaNombre,
                success: function(data) {
                    console.log("data: " + data);
                    $('.contenidoColumna').html(data); 
                    $('.contenidoColumna').show();
                    $('#btnSecuencial').show();
                    $('.resultadoSecuencial').hide();
                    $('#guardaConsulta__1').show();
                }
            });
                                   
            });

    });
 </script> 
 
@endsection