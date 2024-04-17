<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class conexionController extends Controller
{
    public $dsn = null;
    public $usuario = null;
    public $contrasena = null;
    public $tipoConexion = null;
    public $base_datos = null;
    public $tablaSeleccionada = null;
    public $arraySecuencial = array();
    //CONTROLADORES DE CONEXION
    //CONTROLADORES DE MOSTRAR

    //Recibe columna, y campos a verificar en la columna seleccionada, ya sea
    //variables permitidas, not null, tipo datos.

    public function contieneTexto($cadena) {
        return preg_match('/[a-zA-Z]/', $cadena) || (preg_match('/[a-zA-Z]/', $cadena) && preg_match('/\d/', $cadena));
    }

    public function evaluaCampos($columna, $nullOp, $camposColumn, $tipoDato, Request $request)
    {
        $dsn = $request->session()->get('dsn');
        $tablaSeleccionada = $request->session()->get('tablaSeleccionada');
        $connection = new PDO($dsn);

        //$datosCombo2 = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$opcionSeleccionada'");
        $valores = null;
        $evaluaCampos = false;
        $tablita = '';
        if ($nullOp == 'YES') {
            $contador = 0;
            $consulta1 = $connection->query("select $columna from $tablaSeleccionada");
            foreach ($consulta1 as $fila1) {
                $registro1 = $fila1[$columna];
                $contador = $contador + 1; 
                if ($registro1 == NULL) {
                    $tablita .= "<td>Hay un vacio en la columna $columna perteneciente a id= $contador</td>";//$registroNULL = $registro1;
                }
                //$registroNULL .= "$registro1<br/>";
                
            }
        }
        if ($camposColumn != 'NOPR') {
            $tablita = '';
            $tablita2= '';
            $conteo = 0;
            $valores = explode(', ', $camposColumn);
            $consulta2 = $connection->query("select $columna from $tablaSeleccionada");
            foreach ($consulta2 as $fila2) {
                $registro2 = $fila2[$columna];
                $conteo = $conteo + 1;
                if (in_array($registro2, $valores)) {
                    $tablita = "<td>No se encontraron excepciones</td> <br/>";
                } else {
                    $tablita2 .= "<td>Hay un error en el id $conteo tiene un registro no valido $registro2</td><br/>";
                } 
                
            }
        }
        if ($tipoDato == "L") {
            $cuenta = 0;
            $consulta3 = $connection->query("select $columna from $tablaSeleccionada");
            foreach($consulta3 as $fila3){
                $registro3 = $fila3[$columna];
                $cuenta = $cuenta + 1;
                if ($this->contieneTexto($registro3)) {
                    $tablita = "<td>No se encontraron excepciones</td> <br/>";
                }else{
                $tablita .="<td>Excepcion en id: $cuenta</td> <br/>";
                }
            }
        }else{
            $cuenta = 0;
            $consulta4 = $connection->query("select $columna from $tablaSeleccionada");
            foreach($consulta4 as $fila4){
                $registro4 = $fila4[$columna];
                $cuenta = $cuenta + 1;
                if (is_numeric($registro4)) {
                    $tablita = "<td>No se encontraron excepciones</td> <br/>";
                }else{
                $tablita .="<td>Excepcion en id: $cuenta</td> <br/>";
                }
            }
        
        }
            // No se encontraron excepciones
            return response()->json("<br/><td>$tablita  </td>");
        
    }
     
    public function evaluaSecuencial(Request $request)
    {

        $arraySecuencial = $request->session()->get('arraySecuencial');
        $tablita = ''; // Inicializa la variable fuera del bucle
        $excepcionesEncontradas = false; // Bandera para verificar si se encontraron excepciones

        for ($i = 0; $i < count($arraySecuencial) - 1; $i++) {
            if ($arraySecuencial[$i + 1] - $arraySecuencial[$i] != 1) {
                // Se encontró una excepción
                $excepcionesEncontradas = true;
                $tablita .= "<td>{$arraySecuencial[$i + 1]}</td>";
            }
        }

        if ($excepcionesEncontradas) {
            // Se encontraron excepciones
            return response()->json($tablita);
        } else {
            // No se encontraron excepciones
            return response()->json("<td>No se encontraron excepciones</td>");
        }
    }

    public function actualizarTabla($tabla, Request $request)
    {

        $dsn = $request->session()->get('dsn');
        $tablaSeleccionada = $request->session()->get('tablaSeleccionada');
        // Ejecutar una sentencia SQL para obtener los datos para combo2
        $connection = new PDO($dsn);
        $datosModal = null;
        $tablita = '';
        
        $datosTabla = $connection->query("select $tabla from $tablaSeleccionada");
        foreach ($datosTabla as $fila) {
            $registro = $fila[$tabla];
            $this->arraySecuencial[] = $registro;
            $tablita .= "<td>$registro</td>";
        }
        $request->session()->put('arraySecuencial', $this->arraySecuencial);
        return response()->json($tablita);
    }

    public function mostrarColumnaParametro($opcionSeleccionada, Request $request)
    {
        $this->tablaSeleccionada = $opcionSeleccionada;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        // Ejecutar una sentencia SQL para obtener los datos para combo2
        $connection = new PDO($dsn);
        $datosModal = null;
        $datosCombo2 = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$opcionSeleccionada'");
        // Generar el HTML para las opciones de combo2
        $htmlCombo2 = '';
        foreach ($datosCombo2 as $fila) {
            $nombreTabla = $fila['COLUMN_NAME'];
            $htmlCombo2 .= "<tr>
                            <td class='nombreColumna' value='$nombreTabla'>$nombreTabla</td>
                            <td>
                            <input type='checkbox' class='miCheckbox'  name='miCheckbox'>
                            <label for='miCheckbox'>NOT NULL</label>
                            </td>
                            <td>
                            <input type='checkbox' class='checkParametro' name='checkParametro' onchange='toggleInput(this)'>
                            <input type='text' disabled  name='textoParametro' class='camposColumn form-control-sm' placeholder='A,B'>
                            </td>
                            <td>
                            <select class='tipoDato'>
                            <option value='' selected disabled>...</option>
                            <option value='L'>Cadena de texto</option>
                            <option value='N'>Números</option>
                            </select>
                            </td>
                            <td>
                            <button class='btnExcepcionCampo'>
                                Analizar campos
                            </button>
                            </td>
                            </tr>
                            <script>
                            function toggleInput(checkbox) {
                                // Encuentra el campo de texto dentro de la fila específica
                                var camposColumn = checkbox.closest('tr').querySelector('.camposColumn');
                                // Cambia el estado de habilitado/deshabilitado del campo de texto
                                camposColumn.disabled = !checkbox.checked;
                            }</script>";
        }
        // Devolver el HTML en formato JSON
        return response()->json($htmlCombo2);
    }

    public function actualizarCombo2($opcionSeleccionada, Request $request)
    {
        $this->tablaSeleccionada = $opcionSeleccionada;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        // Ejecutar una sentencia SQL para obtener los datos para combo2
        $connection = new PDO($dsn);
        $datosModal = null;
        $datosCombo2 = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$opcionSeleccionada'");
        // Generar el HTML para las opciones de combo2
        $htmlCombo2 = '';
        foreach ($datosCombo2 as $fila) {
            $nombreTabla = $fila['COLUMN_NAME'];
            $htmlCombo2 .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
        }

        // Devolver el HTML en formato JSON
        return response()->json($htmlCombo2);
    }

    public function inicioConexion()
    {

        return view('conexion.index');
    }

    public function mostrarColumnas(Request $request, $tabla)
    {
        $dsn = $request->session()->get('dsn');
        $base_datos = $request->session()->get('base_datos');
        $connection = new PDO($dsn);

        $tabla2 = $tabla;
        //dd($tabla2);
        $resultado = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla2'");


        //dd($resultado);
        return view('conexion.columnas', compact('resultado'));
    }

    public function tablas(Request $request)
    {
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        if ($tipoConexion == 'sqlserver') {
            //dd($dsn);
            $connection = new PDO($dsn);
            $datosModal = null;
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('conexion.tablas', compact('resultado', 'datosModal'));
        } else {
            $base_datos = $request->session()->get('base_datos');
            $usuario = $request->session()->get('usuario');
            $contrasena = $request->session()->get('contrasena');
            $connection = new PDO($dsn, $usuario, $contrasena);
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$base_datos'");
            return view('conexion.tablas', compact('resultado'));
        }
    }


    public function tablasCOMBO(Request $request)
    {
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        if ($tipoConexion == 'sqlserver') {
            //dd($dsn);
            $connection = new PDO($dsn);
            $datosModal = null;
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('exepciones.secue', compact('resultado', 'datosModal'));
        } else {
            $base_datos = $request->session()->get('base_datos');
            $usuario = $request->session()->get('usuario');
            $contrasena = $request->session()->get('contrasena');
            $connection = new PDO($dsn, $usuario, $contrasena);
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$base_datos'");
            return view('escepciones.secue', compact('resultado'));
        }
    }

    public function tablasExcepcionCampo(Request $request)
    {
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        if ($tipoConexion == 'sqlserver') {
            //dd($dsn);
            $connection = new PDO($dsn);
            $datosModal = null;
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('exepciones.campos', compact('resultado', 'datosModal'));
        } else {
            $base_datos = $request->session()->get('base_datos');
            $usuario = $request->session()->get('usuario');
            $contrasena = $request->session()->get('contrasena');
            $connection = new PDO($dsn, $usuario, $contrasena);
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$base_datos'");
            return view('escepciones.campos', compact('resultado'));
        }
    }

    public function cabeceraExcepcion(Request $request)
    {
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        if ($tipoConexion == 'sqlserver') {
            //dd($dsn);
            $connection = new PDO($dsn);
            $datosModal = null;
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('exepciones.cabecera', compact('resultado', 'datosModal'));
        } else {
            $base_datos = $request->session()->get('base_datos');
            $usuario = $request->session()->get('usuario');
            $contrasena = $request->session()->get('contrasena');
            $connection = new PDO($dsn, $usuario, $contrasena);
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$base_datos'");
            return view('escepciones.cabecera', compact('resultado'));
        }
    }

    public function mostrarComboDetalle2($opcionSeleccionada, Request $request){
        $this->tablaSeleccionada = $opcionSeleccionada;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        // Ejecutar una sentencia SQL para obtener los datos para combo2
        $connection = new PDO($dsn);

        $datoscolumnas = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$opcionSeleccionada'");
        // Generar el HTML para las opciones de combo2
        $radioButton = '';
        foreach ($datoscolumnas as $fila) {
            $nombreTabla2 = $fila['COLUMN_NAME'];
            $radioButton .= "
            <input type='radio' id='opcion1' name='grupoDETALLE' value='$nombreTabla2'>
            <label for='opcion1'>$nombreTabla2</label><br>
            ";
        }
        return response()->json($radioButton);
    }

    public function mostrarComboCabecera2($opcionSeleccionada, Request $request)
    {
        $this->tablaSeleccionada = $opcionSeleccionada;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);
        $tipoConexion = $request->session()->get('tipoConexion');
        $dsn = $request->session()->get('dsn');
        // Ejecutar una sentencia SQL para obtener los datos para combo2
        $connection = new PDO($dsn);

        $datoscolumnas = $connection->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$opcionSeleccionada'");
        // Generar el HTML para las opciones de combo2
        $radioButton = '';
        foreach ($datoscolumnas as $fila) {
            $nombreTabla2 = $fila['COLUMN_NAME'];
            $radioButton .= "
            <input type='radio' id='opcion1Cabecera' name='grupoCABECERA' value='$nombreTabla2'>
            <label for='opcion1Cabecera'>$nombreTabla2</label><br>
            ";
        }

        $datosCombo2 = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
        // Generar el HTML para las opciones de combo2
        $htmlCombo2 = '';
        foreach ($datosCombo2 as $fila) {
            if ($fila['TABLE_NAME']!= $opcionSeleccionada) {
                $nombreTabla = $fila['TABLE_NAME'];
            $htmlCombo2 .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
            }
            
        }

        $datos = ['rdButton' =>$radioButton,
                    'cmb2'=>$htmlCombo2];

        // Devolver el HTML en formato JSON
        return response()->json($datos);
    }



    public function conection(Request $request)
    {
        $servidor = $request->input('Host');
        $this->base_datos = $request->input('nombreBD');
        $this->usuario = $request->input('Usuario');
        $this->contrasena = $request->input('Contrasena');
        $this->tipoConexion = $request->input('tipoSql');
        $request->session()->put('tipoConexion', $this->tipoConexion);
        $request->session()->put('usuario', $this->usuario);
        $request->session()->put('contrasena', $this->contrasena);
        $request->session()->put('base_datos', $this->base_datos);
        if ($this->tipoConexion == 'sqlserver') {
            $this->dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$this->base_datos;Uid=$this->usuario;Pwd=$this->contrasena";
            $request->session()->put('dsn', $this->dsn);
            // Crear una instancia de PDO
            $connection = new PDO($this->dsn);

            // Configurar el modo de errores de PDO a excepciones  
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('conexion.tablas', compact('resultado'));
        } else {
            $this->dsn = "mysql:host=$servidor;dbname=$this->base_datos;charset=utf8mb4";
            $request->session()->put('dsn', $this->dsn);
            $connection = new PDO($this->dsn, $this->usuario, $this->contrasena);
            $resultado = $connection->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$this->base_datos'");
            return view('conexion.tablas', compact('resultado'));
        }
    }
}
