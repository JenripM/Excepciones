@extends('layout.plantilla')
@section('contenido')
<div class="container ">
    <div class="row">
    <div class="col-md-6">
    <div class="row justify-content-center">
        <label style="margin-left: 10px;" for="miCheckbox">Si desea realizar una evaluación de relación CLAVE PRIMARIA y CLAVE FORÁNEA marcar el recuadro, caso contrario omitir este mensaje.</label>
        <input style="margin-left: 800px;" class="form-check-input" type="checkbox" value="" id="miCheckbox">
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
            
            <div class="text-center">
                <label >Tabla cabecera</label>
                <select id="comboCampoTablas" class="form-select-sm w-100 fs-5">
                    <option disabled selected>...</option>
                    @foreach ($resultado as $fila)
                        <option value="{{ $fila['TABLE_NAME'] }}">{{ $fila['TABLE_NAME'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
            <div class="btn-group"  role="group" aria-label="Basic radio toggle button group">
                <label id="rdbuttonCabeceralabel" style="display: none">Columnas cabecera</label>
                <div class='container mt-4 'id="rdbuttonCabecera">
            </div>
            </div>
            </div>
        </div>
        <div class="col-md-6 mb-4 tablasDetalle" style="display: none">
            <div class="text-center">
                <label >Tabla detalle</label>
                <select id="comboTablasDETALLE" class="form-select-sm w-100 fs-5">
                   
                </select>
            </div>
            <div class="col-md-12 tablasDetalleRDB"  style="display: none">
                <div class="btn-group"  role="group" aria-label="Basic radio toggle button group">
                    <label >Columnas detalle</label>
                    <div class='container mt-4 'id="rdbuttonDetalle">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="container evalua mt-4" style="display: none">
            <div class="text-center">
                <!-- Utiliza las clases de Bootstrap para centrar el contenido -->
                <button class="btn evaluando btn-primary">Evaluar</button>
            </div>
            <div class="btn-group justify-content-center" role="group"id="guardaConsulta__3" class="btnGuardarConsulta" style="display: none">
                <button id="guardaConsulta__3" class="btn  btn-primary">Guardar Consulta</button>
            </div>
        </div>
        <div class="container tablaCabeDeta table-responsive" style="height: 300px; margin-top: 15px">
            {{-- <table class="table table-striped tablaContentCabede" style="display: none">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Registro</th>
                        <th scope="col">ID</th>
                    </tr>
                </thead>
                <tbody class="tablaCabeDeta">
                   
                </tbody>
            </table> --}}
        </div>
    </div><div id="customAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
    <strong>¡Error!</strong> <span id="alertMessage"></span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
</div>



<div class="col-md-6 consultas__3">
    <div class="table-responsive-x table-sm" style="height: 500px;">
        <h1>CONSULTAS</h1>
        <table class="table table-striped">
        <thead>
            <tr>
                <th>Tabla Cabecera</th>
                <th>Tabla Detalle</th>
                <th>Columna Cabecera</th>
                <th>Columna Detalle</th>
                <th>Formato distinto</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>

         @if ($consulta__3Encontrada->isEmpty()) 
            <tr>
                <td colspan="2">No hay consultas registradas</td>
                        </tr>
        @else 
          
            @foreach ($consulta__3Encontrada as $consulta) 
                <tr>
                                <td class="tabnom"> {{$consulta->tablaCabecera }}</td>
                                <td class="colnom">{{$consulta->tablaDetalle }}</td>
                                <td class="colnom">{{$consulta->columnaCabecera }}</td>
                                <td class="colnom">{{$consulta->columnaDetalle }}</td>
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



<script>
    function showAlert(message) {
    var alertMessage = document.getElementById("alertMessage");
    alertMessage.innerText = message;
    
    var customAlert = document.getElementById("customAlert");
    customAlert.style.display = "block";
    setTimeout(function() {
        customAlert.style.display = "none";
    }, 7000);
}
</script> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
      $(document).on('click', '.seleccion', function() {
                var fila = $(this).closest('tr');
                var tablaCabecera = fila.find('td:eq(0)').text().trim(); 
                var tablaDetalle = fila.find('td:eq(1)').text().trim(); 
                var columnaCabecera = fila.find('td:eq(2)').text().trim(); 
                var columnaDetalle = fila.find('td:eq(3)').text().trim(); 
                var check = fila.find('td:eq(4)').text().trim();
                //console.log("Tabla: " + tablaNombre);
                //console.log("Columna: " + columnaNombre);comboTablasDETALLE
                var tablatabla = document.getElementById("comboCampoTablas");
                tablatabla.value= tablaCabecera;
                
                

                if (check == 'YES') {
                    var tablatabla = document.getElementById("miCheckbox");
                    tablatabla.checked = true
                }
                 $.ajax({type: 'GET',
                url: '/cabeShowCmbSel/' + tablaCabecera + '/'+ check,
                success: function(data) {
                    if ( data.tabla2Select == 0) {
                        showAlert(data.radioButton);
                        $('#rdbuttonCabecera').hide();
                        $('#rdbuttonCabeceralabel').hide();
                        $('.tablasDetalle').hide();
                    }else{
                    var radioButton  = data.radioButton;
                    console.log("combo2: "+radioButton);
                    var tabla2Select = data.tabla2Select;
                    //console.log("rdbutton: "+tabla2Select);
                    
                    $('.tablasDetalle').show();
                    $('#comboTablasDETALLE').html(tabla2Select);
                    $('#rdbuttonCabecera').html( radioButton);
                    $('#rdbuttonCabecera').show();
                    $('#rdbuttonCabeceralabel').show();
                    var tablatabla2 = document.getElementById("comboTablasDETALLE");
                    tablatabla2.value= tablaDetalle;
                    var valorSeleccionado = columnaCabecera;

// Obtener todos los radio buttons con la clase 'btn-check' y el nombre 'grupoCABECERA'
var radioButtons = document.querySelectorAll('input[name="grupoCABECERA"]');

// Iterar sobre cada radio button
radioButtons.forEach(function(radioButton) {
    // Verificar si el valor del radio button coincide con el valor deseado
    if (radioButton.value === valorSeleccionado) {
        // Marcar el radio button como seleccionado
        radioButton.checked = true;
    }
});
                    }
                }
                });
                $.ajax({
                type: 'GET',
                url: '/detaRdbShow/' + tablaDetalle+'/'+tablaCabecera+'/'+check,
                success: function(data) {
                    console.log(data);
                    $('#rdbuttonDetalle').html(data);
                    $('.tablasDetalleRDB').show();
                    $('.evalua').show();
                    $('#guardaConsulta__3').show();
                    var valorSeleccionado = columnaDetalle;

// Obtener todos los radio buttons con la clase 'btn-check' y el nombre 'grupoCABECERA'
var radioButtons = document.querySelectorAll('input[name="grupoDETALLE"]');

// Iterar sobre cada radio button
radioButtons.forEach(function(radioButton) {
    // Verificar si el valor del radio button coincide con el valor deseado
    if (radioButton.value === valorSeleccionado) {
        // Marcar el radio button como seleccionado
        radioButton.checked = true;
    }
});
                }
            });
                                   
            });









    $('#guardaConsulta__3').click(function() {
        var TablacabeceraComboBox = $('#comboCampoTablas').val();
        var TabladetalleComboBox = $('#comboTablasDETALLE').val();
        var ColumnardButtonCabecera = $("input[name='grupoCABECERA']:checked").val();
        var ColumnardButtobDetalle= $("input[name='grupoDETALLE']:checked").val();
        var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
        console.log("da: "+TablacabeceraComboBox+TabladetalleComboBox+ColumnardButtonCabecera+ColumnardButtobDetalle);
            $.ajax({
                type: 'GET',
                url: '/guardarConsulta__3/'+TablacabeceraComboBox+'/'+TabladetalleComboBox+'/'+ColumnardButtonCabecera+'/'+ColumnardButtobDetalle+'/'+sel,
                success: function(data) {
                    console.log("data: "+data)
                    console.log('d: '+data);
                    $('.consultas__3').html(data);
                }
            });
        });
        function actualizarDatos() {
            // Realiza una solicitud AJAX para obtener los datos actualizados
            $.ajax({
                type: 'GET',
                url: 'mostrarConsulta__3', // Reemplaza esto con la ruta de tu controlador que devuelve los datos
                success: function(data) {
                    // Actualiza el contenido de un elemento HTML con los datos recibidos
                    $('.consultas__3').html(data);
                }
            });
        }









    $(document).ready(function() {
        $('#comboCampoTablas').change(function() {
            var tabla = $(this).val();
            var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
            console.log("valor: "+ tabla );
            $.ajax({
                type: 'GET',
                url: '/cabeShowCmbSel/' + tabla + '/'+ sel,
                success: function(data) {
                    if ( data.tabla2Select == 0) {
                        showAlert(data.radioButton);
                        $('#rdbuttonCabecera').hide();
                        $('#rdbuttonCabeceralabel').hide();
                        $('.tablasDetalle').hide();
                    }else{
                    var radioButton  = data.radioButton;
                    console.log("combo2: "+radioButton);
                    var tabla2Select = data.tabla2Select;
                    console.log("rdbutton: "+tabla2Select);
                    $('.tablasDetalle').show();
                    $('#comboTablasDETALLE').html(tabla2Select);
                    $('#rdbuttonCabecera').html( radioButton);
                    $('#rdbuttonCabecera').show();
                    $('#rdbuttonCabeceralabel').show();
                    }
                }
            });
        });
    });

    $(document).ready(function() {
        $('#comboTablasDETALLE').change(function() {
            var valorSeleccionado = $(this).val();
            var tablaCabecera = $('#comboCampoTablas').val();
            var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
            console.log("valor: "+valorSeleccionado+tablaCabecera+sel);
            $.ajax({
                type: 'GET',
                url: '/detaRdbShow/' + valorSeleccionado+'/'+tablaCabecera+'/'+sel,
                success: function(data) {
                    $('#rdbuttonDetalle').html(data);
                    $('.tablasDetalleRDB').show();
                    $('.evalua').show();
                    $('#guardaConsulta__3').show();
                }
            });
        });
    });

    $('.evaluando').click(function() {
        var TablacabeceraComboBox = $('#comboCampoTablas').val();
        var TabladetalleComboBox = $('#comboTablasDETALLE').val();
        var ColumnardButtonCabecera = $("input[name='grupoCABECERA']:checked").val();
        var ColumnardButtobDetalle= $("input[name='grupoDETALLE']:checked").val();
        var sel = $('#miCheckbox').is(':checked') ? 'YES' : 'NO';
        console.log("valores: "+ TablacabeceraComboBox +" "+TabladetalleComboBox+ " " +ColumnardButtonCabecera+" "+ColumnardButtobDetalle+ " "+sel )
          if (ColumnardButtonCabecera && ColumnardButtobDetalle) {
        $.ajax({
                type: 'GET',
                url: '/evaluaCabeDetalle/' + TablacabeceraComboBox +"/" +TabladetalleComboBox + "/"+ColumnardButtonCabecera + "/"+ColumnardButtobDetalle + '/'+sel,
                success: function(data) {
                    console.log("data: "+ data);
                    if (data.startsWith('ERROR2')) {
                    showAlert(data);
                    }
                    if (data == 'ERROR1') {
                        showAlert('Por favor, seleccionar columnas de tabla con coincidencias en tipo de dato.');
                    }else if(data != 'ERROR1' && data.indexOf('ERROR2') !== 0) {
                    $('.tablaCabeDeta').html(data); 
                    $('.tablaContentCabede').show();
                    //console.log("Valor data: " + data);
                    // Corrección aquí
                    }
                }
                }); }  
                    else{showAlert('Por favor, seleccione una columna de la tabla.');}
});
 
</script>


@endsection