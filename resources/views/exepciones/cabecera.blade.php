@extends('layout.plantilla')

@section('contenido')

<div>
    <div>
        <select id="combo1">
    @foreach ($resultado as $fila)
            
    <option value="{{ $fila['TABLE_NAME'] }}">{{ $fila['TABLE_NAME'] }}</option>
                
        @endforeach
</select>

<div >
<divi id="rdbutton">

</div>
</div>
    </div>
    
<div>
    <div>
      <select id="combo2">
    <!-- El contenido de combo2 se actualizará dinámicamente -->
    
    </select>  
    </div>
    <div id="rdButton3">

    </div>
    <div>
        <button class="evalua">Evaluar</button>
    </div>

</div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#combo1').change(function() {
            var valorSeleccionado = $(this).val();
            console.log("valor: "+valorSeleccionado);
            $.ajax({
                type: 'GET',
                url: '/mostrarComboCabecera2/' + valorSeleccionado,
                success: function(data) {
                    var rdbutton  = data.rdButton;
                    console.log("combo2: "+combo2);
                    var combo2 = data.cmb2;
                    console.log("rdbutton: "+rdbutton);

                    $('#combo2').html(combo2);
                    $('#rdbutton').html( rdbutton);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#combo2').change(function() {
            var valorSeleccionado = $(this).val();
            console.log("valor: "+valorSeleccionado);
            $.ajax({
                type: 'GET',
                url: '/mostrarComboDetalle2/' + valorSeleccionado,
                success: function(data) {
                    $('#rdButton3').html(data);
                 
                }
            });
        });
    });
 
    $('.evalua').click(function() {
        var TablacabeceraComboBox = $('#combo1').val();
        var TabladetalleComboBox = $('#combo2').val();
        var ColumnardButtonCabecera = $("input[name='grupoCABECERA']:checked").val();
        var ColumnardButtobDetalle= $("input[name='grupoDETALLE']:checked").val();
        console.log("valores: "+ TablacabeceraComboBox +" "+TabladetalleComboBox+ " " +ColumnardButtonCabecera+" "+ColumnardButtobDetalle )
            $.ajax({
                type: 'GET',
                url: '/evaluaCabecera/',
                success: function(data) {
                    $('#tablaSecuencial').html(data); 
                    console.log("Valor data: " + data); // Corrección aquí
                }
            });
        });
 
</script>


@endsection