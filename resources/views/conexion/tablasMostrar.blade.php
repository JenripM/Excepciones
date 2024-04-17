@extends('layout.plantilla')

@section('contenido')

<!-- Botón para activar el modal -->
<div class="container mt-4 " >
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Nombre de la tabla</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultado as $fila)
            <tr>
                <td class="text-center">{{ $fila['TABLE_NAME'] }}</td>
                <td class="text-center">
                    <a data-tabla="{{ $fila['TABLE_NAME'] }}" class="mostrarModal btn  btn-primary">Mostrar detalle</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table><div id="modalshow">
    
</div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('.mostrarModal').click(function(){ 
    var tablaSeleccionada = $(this).data('tabla');
    //console.log("tabla: "+tablaSeleccionada);
    $.ajax({
      type:'GET',
      url:'/mostrarModal/' + tablaSeleccionada,
      success:function(data) {
        console.log("data: "+data)
               $('#modalshow').html(data);
               $('#tablaModal').modal('show');
      }
    });
  });

  

</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-QEoIPmFq/PGVzRVzoDxmqJ48c8yNjTpzTFJCYiVhPme5H0uLwXuBoBI9K+QUhpMZf" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6/0omzNO6zJRvRrVr2msiz0f+roo+i8fkXfJKO" crossorigin="anonymous"></script>

@endsection
