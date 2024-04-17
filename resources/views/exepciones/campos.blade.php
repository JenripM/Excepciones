@extends('layout.plantilla')

@section('contenido')

<div>
    <select id="combo1">
    @foreach ($resultado as $fila)
            
    <option value="{{ $fila['TABLE_NAME'] }}">{{ $fila['TABLE_NAME'] }}</option>
                
        @endforeach
</select>
</div>
<div>
<!-- Combo box 2 -->
<table style="border-style: solid 1px black">
    <thead>
        <tr> 
          <th scope="col">Columna</th>
          <th scope="col">Null?</th>
          <th scope="col">Valores aceptados</th>
          <th scope="col">Dato</th>
        </tr>
      </thead>
      <tbody id="tablaConParametro">
      </tbody>
</table>
</div>
<div>
    <table >
        <tr class="tablaCAMPO">
        </tr>
    </table>
</div>

<!-- Combo box 1 -->




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#combo1').change(function() {
            var valorSeleccionado = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/mostrarColumnaParametro/' + valorSeleccionado,
                success: function(data) {
                    $('#tablaConParametro').html(data);
                }
            });
        });
        
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
    });
 
 </script>
 





@endsection