@extends('layout.plantilla')

@section('contenido')
<div class="container mt-4 text-center">
    <label class="containerColumnas">Tabla</label>
    <select id="comboCampoTablas" class="form-select-sm w-50 fs-5">
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


<div class="btn-group mt-4 mx-auto muestra" style="display: none; margin-top: 15px" role="group" aria-label="Basic radio toggle button group">
    
    <div class='container mt-4 text-center w-51 fs-4'id="rdbuttonCabecera">
        {{-- <input type="radio" class="btn-check" name="grupoParam" value="1" id="Null" autocomplete="off" >
        <label class="btn btn-outline-primary" for="Null">Not Null</label> --}}

        <input type='radio'style="display: none" class="btn-check primero" id='option1' name='options' value='1' autocomplete="off">
        <label for='option1' style="display: none" class="btn btn-outline-primary primero">Not NULL</label>

        <input type='radio' style="display: none" class="btn-check segundo" id='option2' name='options' value='2' autocomplete="off">
        <label for='option2' style="display: none" class="btn btn-outline-primary segundo">Parámetros aceptados</label>
{{-- 
    <input type='radio' class="btn-check option3" id='option3' name='options' value='3' autocomplete="off">
        <label for='option3'  class="btn btn-outline-primary">Caracteres aceptados</label>  --}}

        <input type='radio' style="display: none" class="btn-check cuarto" id='option4' name='options' value='4' autocomplete="off">
        <label for='option4' style="display: none" class="btn btn-outline-primary cuarto">Rango Números</label>

        <input type='radio' style="display: none" class="btn-check quinto" id='option5' name='options' value='5' autocomplete="off">
        <label for='option5'style="display: none"  class="btn btn-outline-primary quinto">Rango fechas</label>

        <input type='radio' style="display: none" class="btn-check sexto" id='option6' name='options' value='6' autocomplete="off">
        <label for='option6' style="display: none" class="btn btn-outline-primary sexto">Opción 6</label>
</div>
</div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="custom-div">
          <!-- Contenido de tu div -->
          <div class="notnull" style="display: none">
            <label>Los datos a evaluar no deben ser NULL</label>
            <button class="btn btn-primary evalua1 mt-2">Evaluar</button>
          </div>
        </div>
      </div>
    </div>
</div>



<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="custom-div">
          <!-- Contenido de tu div -->
          <div class="param2" style="display: none">
            <label>Valores permitidos: </label>
            <input class="valores">
            <button class="btn btn-primary evalua2 mt-2">Evaluar</button>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="custom-div">
          <!-- Contenido de tu div -->
          <div class="param3" style="display: none">
            <label>Caracteres permitidos: </label>
            <input class="valoresDATO">
            <button class="btn btn-primary evalua3 mt-2">Evaluar</button>
          </div>
        </div>
      </div>
    </div>
</div>


<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="custom-div">
          <!-- Contenido de tu div -->
          <div class="param4" style="display: none">
            <label>Valor mínimo: </label>
            <input type="number" class="valormin">
            <label>Valor máximo: </label>
            <input type="number" class="valormax">
            <button class="btn btn-primary evalua4 mt-2">Evaluar</button>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="custom-div">
          <!-- Contenido de tu div -->
          <div class="param5" style="display: none">
            <label>Fecha mínimo: </label>
            <input type="date" class="fechamin"> <br>
            <label>Fecha máximo: </label>
            <input type="date" class="fechamax">
            <button class="btn btn-primary evalua5 mt-2">Evaluar</button>
          </div>
        </div>
      </div>
    </div>
</div>



<div class="containerResultado">

</div>
  







<div class="container IntegridaCampo mt-4 text-center" style="display: none">
    <!-- Combo box 2 -->
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Columna</th>
                <th scope="col">Null?</th>
                <th scope="col">Valores aceptados</th>
                <th scope="col">Dato</th>
                <th scope="col">Detalle</th>
            </tr>
        </thead>
        <tbody id="parametrosIntegridaCampo">
            <!-- Aquí se mostrarán los datos de la tabla -->
        </tbody>
    </table>
</div>

<div class="container mt-4 tablaContentNULLOP" style="display: none;">
    <div class="row">
        <div class="col">
            <label class="text-center">NUll?</label>
        </div>
    </div>
</div>

<div class="container tablaContentNULLOP table-responsive" style="height: 100px; display: none;">
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Registro</th>
                <th scope="col">ID</th>
            </tr>
        </thead>
        <tbody class="tablaCAMPO1">
            <!-- Aquí van las filas de la tabla -->
        </tbody>
    </table>
</div>


<div class="container mt-4 tablaContentCAMPOS table-responsive" style="display: none;">
    <div class="row">
        <div class="col">
            <label class="text-center">Valores aceptados</label>
        </div>
    </div>
</div>
<div class="container tablaContentTIPODATO table-responsive" style="height: 100px; display: none;">
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Registro</th>
                <th scope="col">ID</th>
            </tr>
        </thead>
        <tbody class="tablaCAMPO2">
            <!-- Aquí van las filas de la tabla -->
        </tbody>
    </table>
</div>
  

<div class="container mt-4 tablaContentTIPODATO" style="display: none;">
    <div class="row">
        <div class="col">
            <label class="text-center">Dato</label>
        </div>
    </div>
</div>

<div class="container tablaContentTIPODATO table-responsive" style="height: 100px; display: none;">
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Registro</th>
                <th scope="col">ID</th>
            </tr>
        </thead>
        <tbody class="tablaCAMPO3">
            <!-- Aquí van las filas de la tabla -->
        </tbody>
    </table>
</div>


{{-- <div>
    <table >
        <tr class="tablaCAMPO">
        </tr>
    </table>
</div> --}}

<!-- Combo box 1 --> 




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    $(document).ready(function() {
        // $('#comboCampoTablas').change(function() {
        //     var tablaSeleccionada = $(this).val();
        //     console.log("valor : "+tablaSeleccionada)
        //     $.ajax({
        //         type: 'GET',
        //         url: '/mostrarParametrosIntegridadCampo/' + tablaSeleccionada,
        //         success: function(data) {
        //             console.log("valor : "+data)
        //             $('#parametrosIntegridaCampo').html(data);
        //             $('.IntegridaCampo').show();
        //         }
        //     });
        // });
    $('.evalua1').click(function(){
        var tabla = $('#comboCampoTablas').val();
        var columna = $('#comboColumnas').val();
        console.log("datp: "+columna+" "+tabla)
        $.ajax({
            type:'GET',
            url: '/parametroNULL/'+tabla+'/'+columna,
            success:function(data){
                $('.containerResultado').html(data);
            }
        });
    });

    $('.evalua2').click(function(){
        var tabla = $('#comboCampoTablas').val();
        var columna = $('#comboColumnas').val();
        var valores = $('.valores').val();
        console.log("datp: "+columna+" "+tabla+" "+valores)
        $.ajax({
            type:'GET',
            url: '/parametrovalores/'+tabla+'/'+columna+'/'+valores,
            success:function(data){
                $('.containerResultado').html(data);
            }
        });
    });

    $('.evalua3').click(function(){
        var tabla = $('#comboCampoTablas').val();
        var columna = $('#comboColumnas').val();
        var valores = $('.valoresDATO').val();
        console.log("datp: "+columna+" "+tabla+" "+valores)
        $.ajax({
            type:'GET',
            url: '/parametroTipoDato/'+tabla+'/'+columna+'/'+valores,
            success:function(data){
                $('.containerResultado').html(data);
            }
        });
    });

    $('.evalua4').click(function(){
        var tabla = $('#comboCampoTablas').val();
        var columna = $('#comboColumnas').val();
        var valormin = $('.valormin').val();
        var valormax = $('.valormax').val();
       
        $.ajax({
            type:'GET',
            url: '/parametroRango/'+tabla+'/'+columna+'/'+valormin+'/'+valormax,
            success:function(data){
                $('.containerResultado').html(data);
            }
        });
    });

    $('.evalua5').click(function(){
        var tabla = $('#comboCampoTablas').val();
        var columna = $('#comboColumnas').val();
        var valormin = $('.fechamin').val();
        var valormax = $('.fechamax').val();
        $.ajax({
            type:'GET',
            url: '/parametroRangoFecha/'+tabla+'/'+columna+'/'+valormin+'/'+valormax,
            success:function(data){
                $('.containerResultado').html(data);
            }
        });
    });


    
    $('#comboCampoTablas').change(function() {
            var valorSeleccionado = $(this).val();
            console.log("valor: " + valorSeleccionado);
            $.ajax({
                type: 'GET',
                url: '/secuenciasTablaShowColumnParam/' + valorSeleccionado,
                success: function(data) {
                    console.log("valor: " + data);
                    $('#comboColumnas').html(data);
                    $('.containerColumnas').show();
                    
                }
            });
        });

       
         $('input[name="options"]').change(function(){
         if ($(this).is(':checked')) {
             var valorSeleccionado = $(this).val();
             console.log("Valor RADIO seleccionado: " + valorSeleccionado);
             if (valorSeleccionado == 1) {
                  $('.notnull').show();
                  $('.param2').hide();
                  $('.param3').show();
                  $('.param4').hide();
                  $('.param5').hide();
             }else if(valorSeleccionado == 2){
                 $('.notnull').hide();
                 $('.param3').hide();
                 $('.param2').show();
                 $('.param4').hide();
                 $('.param5').hide();
                 $('.notnull').hide();
             }else if(valorSeleccionado == 3){
                 $('.notnull').hide();
                 $('.param2').hide();
                 $('.param4').hide();
                 $('.param3').show();
                 $('.param5').hide();
             }else if(valorSeleccionado == 4){
                 $('.notnull').hide();
                 $('.param2').hide();
                 $('.param3').hide();
                 $('.param4').show();
                 $('.param5').hide();
             }else if(valorSeleccionado == 5){
                 $('.notnull').hide();
                 $('.param2').hide();
                 $('.param3').hide();
                 $('.param4').hide();
                 $('.param5').show();
             }
           
         }
     });
    
        $('#comboColumnas').change(function() {
            var tabla = $('#comboCampoTablas').val();
            var valorSeleccionado = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/obtieneTipoDatoParam/' +tabla+'/'+ valorSeleccionado,
                success: function(data) {
                    $('.muestra').show();
                    console.log('d:' + data);
                    if (data =='int' || data =='double' || data =='float'|| data =='bigint'|| data =='smallint'|| data =='tinyint'|| data =='decimal'|| data =='numeric'|| data =='real'|| data =='bit') {
                        $('.primero').show();
                        $('.cuarto').show();
                        $('.segundo').show();
                        $('.quinto').hide();
                        //$('.option3').show();
                    }else if(data =='char' || data =='varchar' || data =='nvarchar'|| data =='text'){
                        $('.primero').show();
                        $('.segundo').show();
                        $('.cuarto').hide();
                        $('.quinto').hide();
                       // $('.option3').show();
                    }else if(data =='datetime' || data =='date' || data =='datetime2'|| data =='time'|| data =='datetimeoffset'){
                        $('.primero').show();
                        $('.quinto').show();
                        $('.segundo').hide();
                        $('.cuarto').hide();
                       // $('.option3').show();
                    }
                }
            });
            // $('.muestra').show();
        });


    $(document).on('click', '.btnExcepcionCampo', function() {
            var tablaSeleccionada = $('#comboCampoTablas').val();
            var fila = $(this).closest('tr'); 
            var columna = fila.find('.nombreColumna').attr('value');
            var nullOp = fila.find('.miCheckbox').is(':checked') ? 'YES' : 'NONL';
            var camposColumn = fila.find('.checkParametro').is(':checked') ? fila.find('.camposColumn').val() : 'NOPR';
            var tipoDato = fila.find('.tipoDato').val();
            camposColumn = camposColumn.trim() === '' ? ' ' : camposColumn;
            console.log("Valor de la columna seleccionada: " +tablaSeleccionada+"-"+ columna + " - " + nullOp + " - " + camposColumn + " - " + tipoDato); 
            $.ajax({
                type: 'GET',
                url: '/evaluaIntegridadCampos/' + tablaSeleccionada +"/"+ columna + '/'+ nullOp +'/'+ camposColumn +'/'+ tipoDato,
                success: function(data) {
                    var tablaContentNULLOP = data.tablaContentNULLOP;
                    var tablaContentCAMPOS = data.tablaContentCAMPOS;
                    var tablaContentTIPODATO = data.tablaContentTIPODATO;
                    console.log("1: "+tablaContentNULLOP+"2: "+ tablaContentCAMPOS+"3: "+ tablaContentTIPODATO);
                    $('.tablaContentNULLOP').show();
                    $('.tablaContentCAMPOS').show();
                    $('.tablaContentTIPODATO').show();
                    $('.tablaCAMPO1').html(tablaContentNULLOP);
                    $('.tablaCAMPO2').html(tablaContentCAMPOS);
                    $('.tablaCAMPO3').html(tablaContentTIPODATO);
                }
            });
    });
 
        $('#btnSecuencial').click(function() {
            $.ajax({
                type: 'GET',
                url: '/evaluaSecuencial/',
                success: function(data) {
                    $('#tablaSecuencial').html(data); 
                    console.log("Valor data: " + data); // Corrección aquí
                }
            });
        });

      /*  
        $(document).on('click', '.btnExcepcionCampo', function() {
            var fila = $(this).closest('tr'); // Obtener la fila más cercana al botón
            var columna = fila.find('.nombreColumna').attr('value');

    // Verificar si el checkbox 'miCheckbox' está marcado
    var nullOp = fila.find('.miCheckbox').is(':checked') ? 'YES' : 'NONL';

    // Verificar si el checkbox 'checkParametro' está marcado
    var camposColumn = fila.find('.checkParametro').is(':checked') ? fila.find('.camposColumn').val() : 'NOPR';

    var tipoDato = fila.find('.tipoDato').val();
    
    // Si el valor de camposColumn está vacío, enviar una cadena vacía
    camposColumn = camposColumn.trim() === '' ? ' ' : camposColumn;
    console.log("Valor de la columna seleccionada: " + columna + " - " + nullOp + " - " + camposColumn + " - " + tipoDato); 
    $.ajax({
        type: 'GET',
        url: '/evaluaCampos/' + columna + '/'+ nullOp +'/'+ camposColumn +'/'+ tipoDato,
        success: function(data) {
            $('.tablaCAMPO').html(data);
        }
    });
});
 
        $('#btnSecuencial').click(function() {
            $.ajax({
                type: 'GET',
                url: '/evaluaSecuencial/',
                success: function(data) {
                    $('#tablaSecuencial').html(data); 
                    console.log("Valor data: " + data); // Corrección aquí
                }
            });
        });
    
 */
});
 </script>

@endsection