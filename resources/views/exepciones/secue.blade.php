@extends('layout.plantilla')

@section('contenido')

<div>
    <select id="combo1">
    @foreach ($resultado as $fila)
            
    <option value="{{ $fila['TABLE_NAME'] }}">{{ $fila['TABLE_NAME'] }}</option>
                
        @endforeach
</select>
 
<!-- Combo box 2 -->
<select id="combo2">
    <!-- El contenido de combo2 se actualizará dinámicamente -->
    
</select>

<table id="tabla"> 
    <tr>Registro
    </tr>
</table>
</div>

<div>
    <div>
        <button id="btnSecuencial">Evaluar</button>
    </div>
    <div>
       <table id="tablaSecuencial">
        <tr>RESULTADO</tr>
    </table> 
    </div>
    
</div>
<!-- Combo box 1 -->




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#combo1').change(function() {
            var valorSeleccionado = $(this).val();

            $.ajax({
                type: 'GET',
                url: '/actualizarCombo2/' + valorSeleccionado,
                success: function(data) {
                    $('#combo2').html(data);
                }
            });
        });
        
        $('#combo2').change(function() {
            var columnaSeleccionada = $(this).val();
            console.log("Valor seleccionado: " + columnaSeleccionada); 
            $.ajax({
                type: 'GET',
                url: '/actualizarTabla/' + columnaSeleccionada,
                success: function(data) {
                    $('#tabla').html(data); // Corrección aquí
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