<?php

namespace App\Http\Controllers;

use App\Models\conexion as ModelsConexion;
use App\Models\consulta1;
use App\Models\consulta3;
use App\Models\excepcion;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use PhpParser\Node\Stmt\TryCatch;

class conexion extends Controller
{

    public $servidor = null;
    public $nombreBase = null;
    public $usuario = null;
    public $contraseña = null;
    public $tipoConexion = null;
    public $puerto = null;
    public $tablaSeleccionada = null;
    public $arraySecuencial = array();
    public $tablaExcepcionBD = null;
    public $columnaExcepcionBD = null;

    public function index()
    {
        $estado = ModelsConexion::estado(); // Obtener el estado desde tu modelo Conexion
        // Pasar el estado a la vista
        return view('layout.plantilla', compact('estado'));
    }

    //CONEXIONES INICIO
    public function desconexion()
    {
        //$user = User::where('email', $email)->firstOrFail();
        $conexion = ModelsConexion::where('estado', 1)->firstOrFail();
        $conexion->estado = 0;
        $conexion->save();
        //return view('conexion.index');
    }

    public function datosConexion()
    {
        $conexion = ModelsConexion::where('estado', 1)->first();
        return view('conexion.index', compact('conexion'));
    }


    public function mostrarModal(Request $request, $tabla)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $modal = '';
        $informacion = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
            foreach ($resultado as $fila) {
                $COLUMN_NAME = $fila['COLUMN_NAME'];
                $IS_NULLABLE = $fila['IS_NULLABLE'];
                $DATA_TYPE = $fila['DATA_TYPE'];
                $informacion .= "<tr>
                            <td>$COLUMN_NAME</td> 
                            <td>$IS_NULLABLE</td>
                            <td>$DATA_TYPE</td>
                                </tr>
                ";
            }
            $modal = "
            <div class='modal fade' id='tablaModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Tabla: $tabla</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                <div class='modal-body'>
                <table class='table'>
                    <thead>
                        <tr>
                        <th scope='col'>Nombre de la columna</th>
                        <th scope='col'>Null</th>
                        <th scope='col'>Tipo de datos</th>
                        </tr>
                    </thead>
                    <tbody> 
                    $informacion
                    </tbody>
                </table>
                </div>
            <div class='modal-footer'>
            </div>
            </div>
            </div>
            </div>
            ";
            return $modal;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
            foreach ($resultado as $fila) {
                $COLUMN_NAME = $fila['COLUMN_NAME'];
                $IS_NULLABLE = $fila['IS_NULLABLE'];
                $DATA_TYPE = $fila['DATA_TYPE'];
                $informacion .= "<tr>
                            <td>$COLUMN_NAME</td> 
                            <td>$IS_NULLABLE</td>
                            <td>$DATA_TYPE</td>
                                </tr>
                ";
            }
            $modal = "
            <div class='modal fade' id='tablaModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Tabla: $tabla</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                <div class='modal-body'>
                <table class='table'>
                    <thead>
                        <tr>
                        <th scope='col'>Nombre de la columna</th>
                        <th scope='col'>Null</th>
                        <th scope='col'>Tipo de datos</th>
                        </tr>
                    </thead>
                    <tbody> 
                    $informacion
                    </tbody>
                </table>
                </div>
            <div class='modal-footer'>
            </div>
            </div>
            </div>
            </div>
            ";

            return $modal;
        }
    }


    public function tablasMostrar(Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');

        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            // Configurar el modo de errores de PDO a excepciones  
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('conexion.tablasMostrar', compact('resultado'));
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$nombreBase'");
            return view('conexion.tablasMostrar', compact('resultado'));
        }
    }

    public function conexion(Request $request)
    {
        // try {
        $request->validate([
            'Host' => 'required',
            'nombreBD' => 'required',
            'tipoSql' => 'required',
            'puerto' => 'required',
        ], [
            'Host.required' => 'El campo Host es obligatorio.',
            'nombreBD.required' => 'El campo Nombre de la conexion es obligatorio.',
            'puerto.required' => 'El campo puerto de la conexion es obligatorio.',
            'tipoSql.required' => 'El campo Tipo SQL es obligatorio.',
        ]);


        if ($request->tipoSql == 'sqlserver') {
            try {
                $dsn = "odbc:Driver={SQL Server};Server=$request->Host;Port=$request->puerto;Database=$request->nombreBD;Uid=$request->Usuario;Pwd=$request->Contrasena";
                $instanciaConexion = new PDO($dsn);
                $conexion = new ModelsConexion();
                $conexion->servidor = $request->Host;
                $conexion->nombreBase = $request->nombreBD;
                $conexion->usuario = $request->Usuario;
                $conexion->contraseña = $request->Contrasena;
                $conexion->tipoConexion = $request->tipoSql;
                $conexion->puerto = $request->puerto;
                $conexion->estado = '1';
                $conexion->save();
                $conexion = ModelsConexion::where('estado', 1)->firstOrFail();
                $this->servidor = $conexion->servidor;
                $this->nombreBase = $conexion->nombreBase;
                $this->usuario = $conexion->usuario;
                $this->contraseña = $conexion->contraseña;
                $this->tipoConexion = $conexion->tipoConexion;
                $this->puerto = $conexion->puerto;
                $request->session()->put('servidor', $this->servidor);
                $request->session()->put('nombreBase', $this->nombreBase);
                $request->session()->put('usuario', $this->usuario);
                $request->session()->put('contraseña', $this->contraseña);
                $request->session()->put('tipoConexion', $this->tipoConexion);
                return view('conexion.exitosa');
            } catch (Exception $e) {
                return view('conexion.error')->with('mensaje', $e->getMessage());
            }
        } else {
            try {
                $dsn = "mysql:host=$request->Host;dbname=$request->nombreBD;charset=utf8mb4";
                $instanciaConexion = new PDO($dsn, $request->Usuario, $request->Contrasena);
                $conexion = new ModelsConexion();
                $conexion->servidor = $request->Host;
                $conexion->nombreBase = $request->nombreBD;
                $conexion->usuario = $request->Usuario;
                $conexion->contraseña = $request->Contrasena;
                $conexion->tipoConexion = $request->tipoSql;
                $conexion->puerto = $request->puerto;
                $conexion->estado = '1';
                $conexion->save();
                $conexion = ModelsConexion::where('estado', 1)->firstOrFail();
                $this->servidor = $conexion->servidor;
                $this->nombreBase = $conexion->nombreBase;
                $this->usuario = $conexion->usuario;
                $this->contraseña = $conexion->contraseña;
                $this->tipoConexion = $conexion->tipoConexion;
                $this->puerto = $conexion->puerto;
                $request->session()->put('servidor', $this->servidor);
                $request->session()->put('nombreBase', $this->nombreBase);
                $request->session()->put('usuario', $this->usuario);
                $request->session()->put('contraseña', $this->contraseña);
                $request->session()->put('tipoConexion', $this->tipoConexion);
                return view('conexion.exitosa');
            } catch (Exception $e) {
                return view('conexion.error')->with('mensaje', $e->getMessage());
            }
        }
    }

    //CONEXIONES FIN
    //=====================================================================

    //EXEPCION SECUENCIALIDAD INICIO
    public function secuencialTablas(Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');

        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            // Configurar el modo de errores de PDO a excepciones  
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
            $consulta__1Encontrada = consulta1::where('tipoConexion', $tipoConexion)
            ->where('basenombre', $nombreBase)
            ->where('usuarioID', $usuarioC)->get();
            return view('exepciones.secuencialidad', compact('resultado', 'consulta__1Encontrada'));
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$nombreBase'");
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
            $consulta__1Encontrada = consulta1::where('tipoConexion', $tipoConexion)
            ->where('basenombre', $nombreBase)
            ->where('usuarioID', $usuarioC)->get();
            return view('exepciones.secuencialidad', compact('resultado', 'consulta__1Encontrada'));
        }
    }

    public function secuenciasTablaShowColumn($tablaSelec, Request $request)
    {
        $this->tablaSeleccionada = $tablaSelec;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);

        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $comboColumna = '<option disabled selected>...</option>';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaSelec'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $comboColumna .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
            }
            return $comboColumna;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaSelec'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $comboColumna .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
            }
            return $comboColumna;
        }
    }

    public function showContenidoColumn($tabla, $columna, Request $request)
    {

        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $this->tablaExcepcionBD = $tabla;
        $this->columnaExcepcionBD = $columna;
        $request->session()->put('tablaExcepcionBD', $this->tablaExcepcionBD);
        $request->session()->put('columnaExcepcionBD', $this->columnaExcepcionBD);
        //$tablaExcepcionBD = $request->session()->get('tablaExcepcionBD');
        //$columnaExcepcionBD = $request->session()->get('columnaExcepcionBD');
        $contenido = '';
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("select top 100 $columna from $tabla 
            ");
            foreach ($resultado as $fila) {
                $registro = $fila[$columna];
                //$this->arraySecuencial[] = $registro;
                $contenido .= "<tr><td>$registro<td/></tr>";
            }
            $this->arraySecuencial[] = 11;
            $request->session()->put('arraySecuencial', $this->arraySecuencial);
            $tablaContent = "
                <div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>Vista de los 100 primeros registros de la columna: $columna</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        $contenido
                       
                    </tbody>
                </table>
            </div>
            ";
            return $tablaContent;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("select $columna from $tabla");
            foreach ($resultado as $fila) {
                $registro = $fila[$columna];
                $this->arraySecuencial[] = $registro;
                $contenido .= "<tr><td>$registro<td/></tr>";
            }
            $request->session()->put('arraySecuencial', $this->arraySecuencial);
            $tablaContent = "
                <div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th scope='col'>Vista de los 100 primeros registros de la columna: $columna</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        $contenido
                       
                    </tbody>
                </table>
            </div>
            ";
            return $tablaContent;
        }
    }

    public function evaluaSecuencialEXP1($val, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaExcepcionBD = $request->session()->get('tablaExcepcionBD');
        $columnaExcepcionBD = $request->session()->get('columnaExcepcionBD');
        $arraySecuencial = $request->session()->get('arraySecuencial');
        $tablaContent = '';
        $tablita = '';
        $excepcionesEncontradas = false;
        $conteo = 1;
        $conteo2 = 1;
        $alert = '';
        $RegistroExcepcion = '';
        $RegistroExcepcion2 = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $instanciaDato = new PDO($dsn);
            $tipoDato = $instanciaDato->query("select data_type from INFORMATION_SCHEMA.COLUMNS
            where TABLE_NAME = '$tablaExcepcionBD' and COLUMN_NAME = '$columnaExcepcionBD'");
            foreach ($tipoDato as $fila) {
                $datoContenido = $fila['data_type'];
            }
            if (in_array($datoContenido, ['int', 'bigint', 'smallint', 'decimal', 'numeric', 'float', 'real', 'tinyint']) || ($val == 'YES')) {
                if ($val == 'YES') {
                     $resultado = $instanciaConexion->query("
                     DECLARE @cadena VARCHAR(100);
                     SELECT TOP 1 @cadena = $columnaExcepcionBD FROM $tablaExcepcionBD;
                     DECLARE @posicion_digito INT = PATINDEX('%[0-9]%', @cadena);
                     DECLARE @menos INT = @posicion_digito -1;
                     DECLARE @prefijo VARCHAR(100) = LEFT(@cadena, @posicion_digito - 1);
                     SELECT @prefijo + CAST(n.num AS VARCHAR(10)) AS num
                     FROM (
                         SELECT TOP (SELECT MAX(TRY_CAST(RIGHT($columnaExcepcionBD, LEN($columnaExcepcionBD) - @menos) AS INT)) FROM $tablaExcepcionBD) 
                                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS num
                         FROM sys.columns c1
                         CROSS JOIN sys.columns c2
                     ) AS n
                    LEFT JOIN $tablaExcepcionBD r ON TRY_CAST(RIGHT(r.$columnaExcepcionBD, LEN(r.$columnaExcepcionBD) - @menos) AS INT) = n.num
                     WHERE TRY_CAST(RIGHT(r.$columnaExcepcionBD, LEN(r.$columnaExcepcionBD) - @menos) AS INT) IS NULL;");
                  
                       
                } else {
                    $resultado = $instanciaConexion->query(
                        "if exists(SELECT n.num
                            FROM (
                                SELECT TOP (SELECT MAX($columnaExcepcionBD) FROM $tablaExcepcionBD) 
                                    ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS num
                                FROM sys.columns c1
                                CROSS JOIN sys.columns c2
                            ) AS n
                            LEFT JOIN $tablaExcepcionBD r ON n.num = r.$columnaExcepcionBD
                            WHERE r.$columnaExcepcionBD IS NULL) 
                            SELECT n.num
                    FROM (
                        SELECT TOP (SELECT MAX($columnaExcepcionBD) FROM $tablaExcepcionBD) 
                            ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS num
                        FROM sys.columns c1
                        CROSS JOIN sys.columns c2
                    ) AS n
                    LEFT JOIN $tablaExcepcionBD r ON n.num = r.$columnaExcepcionBD
                    WHERE r.$columnaExcepcionBD IS NULL
                    else select 'No hay excepciones' as num;"
                    );
                }


                foreach ($resultado as $fila) {
                    $RegistroExcepcion .= $fila['num'] . ', ';
                    $tablaContent .= "<tr><td>{$fila['num']}</td></tr>";
                    if ($fila['num'] == 'No hay excepciones') {
                        $excepcionesEncontradas = false;
                    } else {
                        $alert = 'Excepciones encontradas. Posible eliminación o ingreso incorrecto de datos';
                        $excepcionesEncontradas = true;
                    }
                }
            } else {
                $error = 'ERROR';
                return $error;
            }

            $tablita = "
                <div class='table-responsive' style='height: 300px; text-align: center; '>
                    <table class='table table-striped' style='margin: 0 auto;'>
                    <label>$alert</label>
                    <thead>
                        <tr>
                            <th  colspan='2' scope='col'>R E S U L T A D O S</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope='col'>¿Excepción?</th> 
                        </tr>
                    </thead>
                    <tbody>
                        $tablaContent
                    </tbody>
                </table>
            </div>
            ";

            if ($excepcionesEncontradas == true) {
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Secuencialidad";
                $excepcion->fecha = Carbon::now()->setTimezone('America/Lima');
                $excepcion->tabla = $tablaExcepcionBD;
                $excepcion->columna = $columnaExcepcionBD;
                $excepcion->detalle = $RegistroExcepcion;
                $excepcion->save();
            }
            return $tablita;
        } else {

            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $instanciaDato = new PDO($dsn, $usuario, $contraseña);
            $insta = new PDO($dsn, $usuario, $contraseña);
            $tipoDato2 = $instanciaDato->query("select data_type from INFORMATION_SCHEMA.COLUMNS
            where TABLE_NAME = '$tablaExcepcionBD' and COLUMN_NAME = '$columnaExcepcionBD'");

            foreach ($tipoDato2 as $fila) {
                $datoContenido2 = $fila['DATA_TYPE'];
            }

            if (in_array($datoContenido2, ['int', 'bigint', 'smallint', 'decimal', 'numeric', 'float', 'real', 'tinyint']) || ($val == 'YES')) {

                if ($val == 'YES') {
                    $resultado = $insta->query("WITH RECURSIVE Numeros AS (
                    SELECT 1 AS num2
                    UNION ALL
                    SELECT num2 + 1
                    FROM Numeros
                    WHERE num2 < (SELECT MAX(CONVERT(REGEXP_SUBSTR($columnaExcepcionBD, '[0-9]+'), UNSIGNED)) FROM $tablaExcepcionBD)
                )
                SELECT CONCAT((SELECT REGEXP_SUBSTR($columnaExcepcionBD, '[a-zA-Z]+') FROM $tablaExcepcionBD LIMIT 1), Numeros.num2) AS 'num'
                FROM Numeros
                LEFT JOIN $tablaExcepcionBD ON Numeros.num2 = CONVERT(REGEXP_SUBSTR($columnaExcepcionBD, '[0-9]+'), UNSIGNED)
                WHERE CONVERT(REGEXP_SUBSTR($columnaExcepcionBD, '[0-9]+'), UNSIGNED) IS NULL;
                ");
                } else {
                    $resultado = $instanciaConexion->query(
                        "WITH RECURSIVE Numeros AS (
                         SELECT 1 AS num
                         UNION ALL
                         SELECT num + 1
                         FROM Numeros
                         WHERE num < (SELECT MAX($columnaExcepcionBD) FROM $tablaExcepcionBD)
                     )
                     SELECT num
                     FROM Numeros
                     LEFT JOIN $tablaExcepcionBD ON Numeros.num = $tablaExcepcionBD.$columnaExcepcionBD
                     WHERE $tablaExcepcionBD.$columnaExcepcionBD IS NULL
                     ;"
                    );
                }
                if ($resultado->rowCount() > 0) {

                    $excepcionesEncontradas = true;
                    foreach ($resultado as $fila) {
                        $RegistroExcepcion2 .= $fila['num'] . ', ';
                        $tablaContent .= "<tr><td>{$fila['num']}</td></tr>";
                    }
                } else {

                    $excepcionesEncontradas = false;
                    $tablaContent .= "<tr><td>No excepciones</td></tr>";
                }
            } else {
                $error = 'ERROR';
                return $error;
            }

            $tablita = "
                <div class='table-responsive' style='height: 300px; text-align: center; '>
                    <table class='table table-striped' style='margin: 0 auto;'>
                    <label>$alert</label>
                    <thead>
                        <tr>
                            <th  colspan='2' scope='col'>R E S U L T A D O S</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th scope='col'>¿Excepción?</th> 
                        </tr>
                    </thead>
                    <tbody>
                        $tablaContent
                    </tbody>
                </table>
            </div>
            ";

            if ($excepcionesEncontradas == true) {
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Secuencialidad";
                $excepcion->fecha = Carbon::now()->setTimezone('America/Lima');
                $excepcion->tabla = $tablaExcepcionBD;
                $excepcion->columna = $columnaExcepcionBD;
                $excepcion->detalle = $RegistroExcepcion2;
                $excepcion->save();
            }
            return $tablita;
        }
    }

    public function mostrarConsulta__1()
    {
        $consulta__1Encontrada = consulta1::all();
    
        // Variables para almacenar el resultado de la verificación
        $consultaEncontrada = false;
        $tablaHtml = '';
    
        foreach ($consulta__1Encontrada as $consulta) {
            $CCBasenombre = $consulta->basenombre;
            $CCTipoConex = $consulta->tipoConexion;
            $CCUser = $consulta->usuarioID;
    
            $conexion = ModelsConexion::where('estado', 1)->first();
            $nombreBase = $conexion->nombreBase;
            $tipoConex = $conexion->tipoConexion;
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
    
            if (($CCBasenombre == $nombreBase) && ($CCTipoConex == $tipoConex) && ($CCUser == $usuarioC)) {
                // Si se cumple la condición para al menos un elemento, establece la variable $consultaEncontrada en true
                $consultaEncontrada = true;
    
                // Generar el HTML para la tabla solo si se cumple la condición
                $tablaHtml .= '<tr>
                                <td class="tabnom">' . $consulta->tablaNombre . '</td>
                                <td class="colnom">' . $consulta->columnaNombre . '</td>
                                <td class="colform">' . $consulta->formatoEspecial . '</td>
                                <td> <button class="btn btn-primary seleccion">Seleccionar</button> </td>
                            </tr>';
            }
        }
    
        // Generar el HTML completo de la tabla fuera del bucle foreach
        if ($consultaEncontrada) {
            // Si al menos una consulta cumple con la condición, generar la tabla completa
            $tablaHtml = '
                <div class="table-responsive" style="height: 500px;">
                <h1>CONSULTAS</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>tabla</th>
                            <th>Columna</th>
                            <th>Formato distinto</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>' . $tablaHtml . '</tbody>
                </table>
                </div> ';
        } else {
            // Si ninguna consulta cumple con la condición, generar un mensaje indicando que no hay consultas registradas
            $tablaHtml = '
                <div class="table-responsive" style="height: 500px;">
                <h1>CONSULTAS</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">No hay consultas registradas</td>
                        </tr>
                    </tbody>
                </table>
                </div> ';
        }
    
        return $tablaHtml;
    }
    

    public function guardarConsulta__1($tabla, $columna, $sel, Request $request)
    {
        $conexion = ModelsConexion::where('estado', 1)->first();
        $user = User::where('conectado',1)->first();
        $usuarioC =$user ->id;
        $idConexion = $conexion->IdConexion;
        $nombreBase = $conexion->nombreBase;
        $tipoConex = $conexion->tipoConexion;
        $consultas__1 = new consulta1();
        $consultas__1->tablaNombre = $tabla;
        $consultas__1->columnaNombre = $columna;
        $consultas__1->formatoEspecial = $sel;
        $consultas__1->usuarioID = $usuarioC;
        $consultas__1->basenombre = $nombreBase;
        $consultas__1->tipoConexion = $tipoConex;
        $consultas__1->idConexion = $idConexion;
        $consultas__1->save();
        $tablaHtml = $this->mostrarConsulta__1();

        // Devuelve el HTML generado por mostrarConsulta__1() como respuesta a la solicitud AJAX
        return $tablaHtml;
    }

    public function guardarConsulta__3($tablaCabecera, $tablaDetalle, $ColumnaCabecera, $columnaDetalle, $sel, Request $request)
    {
        $conexion = ModelsConexion::where('estado', 1)->first();
        $user = User::where('conectado',1)->first();
        $usuarioC =$user ->id;
        $idConexion = $conexion->IdConexion;
        $nombreBase = $conexion->nombreBase;
        $tipoConex = $conexion->tipoConexion;
        $consultas__3 = new consulta3();
        $consultas__3->tablaCabecera = $tablaCabecera;
        $consultas__3->tablaDetalle = $tablaDetalle;
        $consultas__3->columnaCabecera = $ColumnaCabecera;
        $consultas__3->columnaDetalle = $columnaDetalle;
        $consultas__3->formatoEspecial = $sel;
        $consultas__3->usuarioID = $usuarioC;
        $consultas__3->basenombre = $nombreBase;
        $consultas__3->tipoConexion = $tipoConex;
        $consultas__3->idConexion = $idConexion;
        $consultas__3->save();
        $tablaHtml = $this->mostrarConsulta__3();

        // Devuelve el HTML generado por mostrarConsulta__1() como respuesta a la solicitud AJAX
        return $tablaHtml;
    }

    
    public function mostrarConsulta__3()
    {
        $consulta__3Encontrada = consulta3::all();
    
        // Variables para almacenar el resultado de la verificación
        $consultaEncontrada = false;
        $tablaHtml = '';
    
        foreach ($consulta__3Encontrada as $consulta) {
            $CCBasenombre = $consulta->basenombre;
            $CCTipoConex = $consulta->tipoConexion;
            $CCUser = $consulta->usuarioID;
    
            $conexion = ModelsConexion::where('estado', 1)->first();
            $nombreBase = $conexion->nombreBase;
            $tipoConex = $conexion->tipoConexion;
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
    
            if (($CCBasenombre == $nombreBase) && ($CCTipoConex == $tipoConex) && ($CCUser == $usuarioC)) {
                // Si se cumple la condición para al menos un elemento, establece la variable $consultaEncontrada en true
                $consultaEncontrada = true;
    
                // Generar el HTML para la tabla solo si se cumple la condición
                $tablaHtml .= '<tr>
                                <td class="tabnom">' . $consulta->tablaCabecera . '</td>
                                <td class="colnom">' . $consulta->tablaDetalle . '</td>
                                <td class="colform">' . $consulta->columnaCabecera . '</td>
                                <td class="colform">' . $consulta->columnaDetalle . '</td>
                                <td class="colform">' . $consulta->formatoEspecial . '</td>
                                <td> <button class="btn btn-primary seleccion">Seleccionar</button> </td>
                            </tr>';
            }
        }
    
        // Generar el HTML completo de la tabla fuera del bucle foreach
        if ($consultaEncontrada) {
            // Si al menos una consulta cumple con la condición, generar la tabla completa
            $tablaHtml = '
                <div class="table-responsive-x" style="height: 500px;">
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
                    <tbody>' . $tablaHtml . '</tbody>
                </table>
                </div> ';
        } else {
            // Si ninguna consulta cumple con la condición, generar un mensaje indicando que no hay consultas registradas
            $tablaHtml = '
                <div class="table-responsive" style="height: 500px;">
                <h1>CONSULTAS</h1>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">No hay consultas registradas</td>
                        </tr>
                    </tbody>
                </table>
                </div> ';
        }
    
        return $tablaHtml;
    }

    //EXEPCION SECUENCIALIDAD FIN
    //=====================================================================


    //EXCEPCION CAMPOS INICIO



    public function integridadCampoTablas(Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');

        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            // Configurar el modo de errores de PDO a excepciones  
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            return view('exepciones.integridadCampo', compact('resultado'));
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$nombreBase'");
            return view('exepciones.integridadCampo', compact('resultado'));
        }
    }

    public function secuenciasTablaShowColumnParam($tablaSelec, Request $request)
    {
        $this->tablaSeleccionada = $tablaSelec;
        $request->session()->put('tablaSeleccionada', $this->tablaSeleccionada);

        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $comboColumna = '<option disabled selected>...</option>';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaSelec'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $comboColumna .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
            }
            return $comboColumna;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaSelec'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $comboColumna .= "<option value=\"$nombreTabla\">$nombreTabla</option>";
            }
            return $comboColumna;
        }
    }

    public function showOptionParam(Request $request)
    {
        $radioButton = '';
        $radioButton .= "
               
                    <input type='radio' class='btn-check' name='grupoParam' value='1' id='Null' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='Null'>Not Null</label>

                    <input type='radio' class='btn-check' name='grupoParam' value='2' id='LetraPermi' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='LetraPermi'>Registros Permitidos</label>

                    <input type='radio' class='btn-check' name='grupoParam' value='3' id='TipoDato' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='TipoDato'>Tipo dato</label>

                ";
        return $radioButton;
    }

    public function mostrarParametrosIntegridadCampo($tablaIntegridadCampo, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaIntegridadCampo'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $tablaContent .= "
                <tr>
                <td class='nombreColumna' value='$nombreTabla'>$nombreTabla</td>
                <td>
                <div class='form-check'>
                <input type='checkbox' class='form-check-input miCheckbox'  name='miCheckbox'>
                <label class='form-check-label' for='miCheckbox'>NOT NULL</label>
                </div>
                </td>
                <td>
                    <div class='form-check'>
                    <input type='checkbox' class='form-check-input checkParametro' name='checkParametro' onchange='toggleInput(this)'>
                    <input type='text' disabled name='textoParametro' class='form-control camposColumn' placeholder='A,B'>
                    </div>
                </td>
                <td>
                <select class='form-select tipoDato'>
                <option value='' selected disabled>...</option>
                <option value='L'>Cadena de texto</option>
                <option value='N'>Números</option>
                </select>
                </td>
                <td>
                <button class='btn btn-primary btnExcepcionCampo'>
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
                }</script>
                ";
            }

            return $tablaContent;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tablaIntegridadCampo'");
            foreach ($resultado as $fila) {
                $nombreTabla = $fila['COLUMN_NAME'];
                $tablaContent .= "
                <tr>
                <td class='nombreColumna' value='$nombreTabla'>$nombreTabla</td>
                <td>
                <div class='form-check'>
                <input type='checkbox' class='form-check-input miCheckbox'  name='miCheckbox'>
                <label class='form-check-label' for='miCheckbox'>NOT NULL</label>
                </div>
                </td>
                <td>
                    <div class='form-check'>
                    <input type='checkbox' class='form-check-input checkParametro' name='checkParametro' onchange='toggleInput(this)'>
                    <input type='text' disabled name='textoParametro' class='form-control camposColumn' placeholder='A,B'>
                    </div>
                </td>
                <td>
                <select class='form-select tipoDato'>
                <option value='' selected disabled>...</option>
                <option value='L'>Cadena de texto</option>
                <option value='N'>Números</option>
                </select>
                </td>
                <td>
                <button class='btn btn-primary btnExcepcionCampo'>
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
                }</script>
                ";
            }

            return $tablaContent;
        }
    }

    public function evaluaIntegridadCampos($tabla, $columna, $nullOp, $camposColumn, $tipoDato, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $valores = null;
        $evaluaCampos = false;
        $tablaContentNULLOP = '';
        $tablaContentCAMPOS = '';
        $tablaContentTIPODATO = '';
        $excepcionesE1 = false;
        $excepcionesE2 = false;
        $excepcionesE3 = false;
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
        }
        if ($nullOp == 'NONL') {
            $excepcionesE1 = true;
            $tablaContentNULLOP = "<tr><td>No se envío parámetros de evaluación</td><td>No se envío parámetros de evaluación</td></tr>";
        }
        if ($nullOp == 'YES') {
            $contador = 0;
            $consultaNullOp = $instanciaConexion->query("select $columna from $tabla");
            foreach ($consultaNullOp as $fila) {
                $registro1 = $fila[$columna];
                $contador = $contador + 1;
                if ($registro1 == NULL) {
                    $excepcionesE1 = true;
                    $tablaContentNULLOP .= "<tr><td>$registro1</td><td>$contador</td></tr>";
                }
                // else {
                //     $tablaContentNULLOP .= "<td>No se encontraron excepciones en la columna: $columna perteneciente a la tabla: $tabla</td>";
                // }
            }
        }

        //NONL - NOPR - null
        if ($camposColumn == 'NOPR') {
            $excepcionesE2 = true;
            $tablaContentCAMPOS = "<tr><td>No se envío parámetros de evaluación</td><td>No se envío parámetros de evaluación</td></tr>";
        }
        if ($camposColumn != 'NOPR') {
            $conteo = 0;
            $valores = explode(', ', $camposColumn);
            $consultaCamposColumn = $instanciaConexion->query("select $columna from $tabla");
            foreach ($consultaCamposColumn  as $fila) {
                $registro2 = $fila[$columna];
                $conteo = $conteo + 1;
                if (in_array($registro2, $valores)) {
                    //$tablaContentCAMPOS = "<td>No se encontraron excepciones en la columna: $columna perteneciente a la tabla: $tabla</td>";
                    $excepcionesE2 = false;
                } else {
                    $excepcionesE2 = true;
                    $tablaContentCAMPOS .= "<tr><td>$registro2</td><td>$conteo</td></tr>";
                }
            }
        }
        if ($tipoDato == 'null') {
            $excepcionesE3 = true;
            $tablaContentTIPODATO = "<tr><td>No se envío parámetros de evaluación</td><td>No se envío parámetros de evaluación</td></tr>";
        }
        if ($tipoDato == "L") {
            $cuenta = 0;
            $consultatipoDato = $instanciaConexion->query("select $columna from $tabla");
            foreach ($consultatipoDato as $fila) {
                $registro3 = $fila[$columna];
                $cuenta = $cuenta + 1;
                if (preg_match('/[a-zA-Z]/', $registro3) || preg_match('/[a-zA-Z]/', $registro3) && preg_match('/\d/', $registro3)) {
                    //$tablaContentTIPODATO = "<td>No se encontraron excepciones en la columna: $columna perteneciente a la tabla: $tabla</td>";
                    $excepcionesE3 = false;
                } else {
                    $excepcionesE3 = true;
                    $tablaContentTIPODATO .= "<tr><td>$registro3</td><td>$cuenta</td></tr>";
                }
            }
        }
        if ($tipoDato == "N") {
            $cuenta = 0;
            $consulta4tipoDato = $instanciaConexion->query("select $columna from $tabla");
            foreach ($consulta4tipoDato as $fila4) {
                $registro4 = $fila4[$columna];
                $cuenta = $cuenta + 1;
                if (preg_match('/\d/', $registro4)) {
                    $excepcionesE3 = false;
                    //$tablaContentTIPODATO = "<td>No se encontraron excepciones</td> <br/>";
                } else {
                    $excepcionesE3 = true;
                    $tablaContentTIPODATO .= "<tr><td>$registro4</td><td>$cuenta</td></tr>";
                }
            }
        }





        if (!$excepcionesE1) {
            $tablaContentNULLOP = "<tr><td>NO EXCEPCIONES</td><td>NO EXCEPCIONES</td></tr>";
        }
        if (!$excepcionesE2) {
            $tablaContentCAMPOS = "<tr><td>NO EXCEPCIONES</td><td>NO EXCEPCIONES</td></tr>";
        }
        if (!$excepcionesE3) {
            $tablaContentTIPODATO = "<tr><td>NO EXCEPCIONES</td><td>NO EXCEPCIONES</td></tr>";
        }

        $insertarBD = "";
        $insertarBD .= "$nullOp-$camposColumn-$tipoDato";
        $insertarBD .= "&$tablaContentNULLOP";
        $insertarBD .= "&$tablaContentCAMPOS";
        $insertarBD .= "&$tablaContentTIPODATO";

        $conexion = ModelsConexion::where('estado', 1)->first();
        $idConexion = $conexion->IdConexion;

        $excepcion = new excepcion();
        $excepcion->IdConexion = $idConexion;
        $excepcion->tipoExcepcion = "Campos";
        $excepcion->fecha = Carbon::now();
        $excepcion->tabla = $tabla;
        $excepcion->columna = $columna;
        $excepcion->detalle = $insertarBD;
        $excepcion->save();


        return response()->json([
            'tablaContentNULLOP' => $tablaContentNULLOP,
            'tablaContentCAMPOS' => $tablaContentCAMPOS,
            'tablaContentTIPODATO' => $tablaContentTIPODATO,
        ]);
    }

    public function obtieneTipoDatoParam($tabla, $columna, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $tipoDato1 = $instanciaConexion->query("select data_type from INFORMATION_SCHEMA.COLUMNS
            where TABLE_NAME = '$tabla' and COLUMN_NAME = '$columna'");
            foreach ($tipoDato1 as $fila) {
                $datoContenidocabecera = $fila['data_type'];
            }
            return $datoContenidocabecera;
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion22 = new PDO($dsn, $usuario, $contraseña);
            $tipoDato1 = $instanciaConexion22->query("select data_type from INFORMATION_SCHEMA.COLUMNS
            where TABLE_NAME = '$tabla' and COLUMN_NAME = '$columna'");
            foreach ($tipoDato1 as $fila) {
                $datoContenidocabecera = $fila['DATA_TYPE'];
            }
            return $datoContenidocabecera;
        }
    }

    /*
    FUNCIONES PARA CADA RADIO DE CAMPOS
    1. NULL
    */
    public function parametroNULL($tabla, $columna, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        $carga = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $instanciaConexion2 = new PDO($dsn);
            $resultado = $instanciaConexion->query("
                SELECT * FROM $tabla WHERE $columna IS NULL
                ");
               
                $resultadoID = $instanciaConexion2->query("
				SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_SCHEMA + '.' + CONSTRAINT_NAME), 'IsPrimaryKey') = 1
                AND TABLE_NAME = '$tabla';
                ");
                foreach($resultadoID as $fil){
                    $primaryKey = $fil['COLUMN_NAME'];
                }
                $cuenta = 0;
                $batchSize = 500; // Tamaño del lote
                $totalFilas = $resultado->rowCount();
                
                if ($totalFilas != 0) {
                    
                
                $carga = '';
                
                $content = '';
               


                foreach ($resultado as $fila) {
                    $carga .= $fila[$primaryKey] . ", ";
                    $content .=   '<tr><td>'.$fila[$primaryKey].'</td><td>'.$fila[$columna].'</td></tr>';
                    // Verifica si es el último elemento del lote actual
                    if ($cuenta % $batchSize == 0 || $cuenta == $totalFilas) {
                        // Realiza la operación de guardado
                        $conexion = ModelsConexion::where('estado', 1)->first();
                        $idConexion = $conexion->IdConexion;
                        $excepcion = new excepcion();
                        $excepcion->IdConexion = $idConexion;
                        $excepcion->tipoExcepcion = "Campos";
                        $excepcion->fecha = Carbon::now();
                        $excepcion->tabla = "Excepción encontrada en la tabla: $tabla";
                        $excepcion->columna = "La columna: $columna presenta registros nulos: ";
                        $excepcion->detalle = $carga;
                        $excepcion->save();
                
                        // Reinicia la cadena de carga para el próximo lote
                        $carga = '';
                    }
                    
                    // Incrementa el contador
                    $cuenta++;
                }
            $content2 = "<tr><td>$primaryKey</td><td>$columna</td></tr>";
           
        }else{
            $content2 = "<tr><td>Resultado</td></tr>";
            $content = "<tr><td>No excepciones</td></tr>";
        }
          



            $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                        $content2
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        $content
                    </tr>
                
                </tbody>
            </table></div>";
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
           
            $instanciaConexionq = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexion2e = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexionq->query("SELECT *
            FROM $tabla
            WHERE $columna IS NULL;");

                                    $resultadoID = $instanciaConexion2e->query("
                                    SHOW KEYS FROM $tabla WHERE Key_name = 'PRIMARY';
                                    ");
                                    $filacont =  $resultadoID->rowCount();
                                    if ($filacont != 0) {
                                        foreach($resultadoID as $fil){
                                            $primaryKey = $fil['Column_name'];
                                        }
                                    }else{
                                        $primaryKey = $columna;
                                    }
                                    
                                    $cuenta = 0;
                                    $batchSize = 500; // Tamaño del lote
                                    $totalFilas = $resultado->rowCount();
                                    
                                    if ($totalFilas != 0) {
                                        
                                    
                                    $carga = '';
                                    
                                    $content = '';
                                   
                    
                    
                                    foreach ($resultado as $fila) {
                                        $carga .= $fila[$primaryKey] . ", ";
                                        $content .=   '<tr><td>'.$fila[$primaryKey].'</td><td>'.$fila[$columna].'</td></tr>';
                                        // Verifica si es el último elemento del lote actual
                                        if ($cuenta % $batchSize == 0 || $cuenta == $totalFilas) {
                                            // Realiza la operación de guardado
                                            $conexion = ModelsConexion::where('estado', 1)->first();
                                            $idConexion = $conexion->IdConexion;
                                            $excepcion = new excepcion();
                                            $excepcion->IdConexion = $idConexion;
                                            $excepcion->tipoExcepcion = "Campos";
                                            $excepcion->fecha = Carbon::now();
                                            $excepcion->tabla = "Excepción encontrada en la tabla: $tabla";
                        $excepcion->columna = "La columna: $columna presenta registros nulos: ";
                                            $excepcion->detalle = $carga;
                                            $excepcion->save();
                                    
                                            // Reinicia la cadena de carga para el próximo lote
                                            $carga = '';
                                        }
                                        
                                        // Incrementa el contador
                                        $cuenta++;
                                    }
                                $content2 = "<tr><td>$primaryKey</td><td>$columna</td></tr>";
                               
                            }else{
                                $content2 = "<tr><td>Resultado</td></tr>";
                                $content = "<tr><td>No excepciones</td></tr>";
                            }
                              
                    
                    
                    
                                $tablita = "<div class='table-responsive' style='height: 300px;'>
                                    <table class='table table-striped'>
                                    <thead>
                                        <tr>
                                            $content2
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            $content
                                        </tr>
                                    
                                    </tbody>
                                </table></div>";
        }
        
        return $tablita;
    }
    /*
    FUNCIONES PARA CADA RADIO DE CAMPOS
    2.VALORES PERMITIDOS
    */
    public function parametrovalores($tabla, $columna, $valores, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        $valoresArray = explode(', ', $valores);
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("
            SELECT * FROM $tabla WHERE $columna NOT IN ('" . implode("', '", $valoresArray) . "')
           ");
            $content2 = '';
            $content = '';
            $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            if ($totalFilas != 0){
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $carga .= $fila[$columna].', ';
                    $content .= "<tr>";
    
                    foreach ($nombresColumnas as $nombreColumna) {
                        
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
    
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
        
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Campos";
                $excepcion->fecha = Carbon::now();
                $excepcion->tabla = "Excepción en la tabla: $tabla";
                $excepcion->columna = "En la columna: $columna se han encontrado registros que no cumplen los parametros establecidos: $valores";
                $excepcion->detalle = $carga;
                $excepcion->save();
                return $tablita;
            }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
            }
           
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM $tabla WHERE $columna NOT IN ('" . implode("', '", $valoresArray) . "')");
            $content2 = '';
            $content = '';
            $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            if ($totalFilas != 0){
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $carga .= $fila[$columna].', ';
                    $content .= "<tr>";
    
                    foreach ($nombresColumnas as $nombreColumna) {
                       
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
    
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                
        $conexion = ModelsConexion::where('estado', 1)->first();
        $idConexion = $conexion->IdConexion;

        $excepcion = new excepcion();
        $excepcion->IdConexion = $idConexion;
        $excepcion->tipoExcepcion = "Campos";
        $excepcion->fecha = Carbon::now();
        $excepcion->tabla = "Excepción en la tabla: "+$tabla;
        $excepcion->columna = "En la columna: "+$columna+" se han encontrado registros que no cumplen los parametros establecidos: "+$valores;
        $excepcion->detalle = $carga;
        $excepcion->save();
        return $tablita;
            }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
            }
            
        }

    }
    /*
    FUNCIONES PARA CADA RADIO DE CAMPOS
    3.TIPO DATO
    */
    public function parametroRango($tabla, $columna, $valores, $val, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("
            if exists(
            SELECT *
            FROM $tabla
            WHERE $columna NOT BETWEEN $valores AND $val)
            SELECT *
            FROM $tabla
            WHERE $columna NOT BETWEEN $valores AND $val
            else
            select 'No hay excepciones';
            ");
            $content2 = '';
            $content = '';
                $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            if ($totalFilas != 0) {
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $content .= "<tr>";
                    $carga .= $fila[$columna].', ';
                    foreach ($nombresColumnas as $nombreColumna) {
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
    
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
        
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Campos";
                $excepcion->fecha = Carbon::now();
                $excepcion->tabla = "Excepción en la tabla: "+$tabla;
                $excepcion->columna = "Se ha encontrado excepciones en la tabla: " +  $columna +" no cumplen con los valores establecidos como minímo y máximo: "+$valores+" "+ $val;
                $excepcion->detalle = $carga;
                $excepcion->save();
                return $tablita;
            }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
            }
          
        } else {

            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT *
            FROM $tabla
            WHERE $columna NOT BETWEEN $valores AND $val");
            $content2 = '';
            $content = '';
            $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            if ($totalFilas != 0) {

                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $content .= "<tr>";
                    $carga .= $fila[$columna].', ';
                    foreach ($nombresColumnas as $nombreColumna) {
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
    
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
        
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Campos";
                $excepcion->fecha = Carbon::now();
                $excepcion->tabla = "Excepción en la tabla: "+$tabla;
                $excepcion->columna = "Se ha encontrado excepciones en la tabla: " +  $columna +" no cumplen con los valores establecidos como minímo y máximo: "+$valores+" "+ $val;
                $excepcion->detalle = $carga;
                $excepcion->save();
                return $tablita;
            }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
            }

        }

       
    }


    public function parametroRangoFecha($tabla, $columna, $valores, $val, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("
            if exists(
                SELECT *
                FROM $tabla
                WHERE $columna < '$valores' OR $columna > '$val')
                SELECT *
                FROM $tabla
                WHERE $columna < '$valores' OR $columna > '$val'
                else select 'No excepciones';
            ");
            $content2 = '';
            $content = '';
                $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            if ($totalFilas != 0) {
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $content .= "<tr>";
                    $carga .= $fila[$columna].', ';
                    foreach ($nombresColumnas as $nombreColumna) {
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>no
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
        
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Campos";
                $excepcion->fecha = Carbon::now();
                $excepcion->tabla = "Error en la tabla: "+ $tabla;
                $excepcion->columna ="Se encontro excepciones en la columna: " +$columna+" las fechas no estan dentro del rango permitido: "+ $valores + " "+$val;
                $excepcion->detalle = $carga;
                $excepcion->save();
                return $tablita;
            }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
            }
            
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT *
            FROM $tabla
            WHERE $columna NOT BETWEEN $valores AND $val");
            $content2 = '';
            $content = '';
            $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
             $totalFilas = $resultado->rowCount();
             if ($totalFilas != 0) {
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $content .= "<tr>";
                    $carga .= $fila[$columna].', ';
                    foreach ($nombresColumnas as $nombreColumna) {
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                     <table class='table table-striped'>
                     <thead>
                         <tr>
                             $content2
                         </tr>
                     </thead>
                     <tbody>
                         
                         <tr>
                             $content
                         </tr>
                     
                     </tbody>
                 </table></div>";
                 $conexion = ModelsConexion::where('estado', 1)->first();
                 $idConexion = $conexion->IdConexion;
         
                 $excepcion = new excepcion();
                 $excepcion->IdConexion = $idConexion;
                 $excepcion->tipoExcepcion = "Campos";
                 $excepcion->fecha = Carbon::now();
                 $excepcion->tabla = "Error en la tabla: "+ $tabla;
                 $excepcion->columna ="Se encontro excepciones en la columna: " +$columna+" las fechas no estan dentro del rango permitido: "+ $valores + " "+$val;
                 $excepcion->detalle = $carga;
                 $excepcion->save();
                 return $tablita;
             }else{
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                <table class='table table-striped'>
                <thead>
                    <tr>
                       <td>Resultado</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                    <td>No excepcion</td>
                    </tr>
                
                </tbody>
            </table></div>";
            return $tablita;
             }
        }
    
    }

    public function parametroTipoDato($tabla, $columna, $valores, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $tablaContent = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            $resultado = $instanciaConexion->query("
            
            SELECT * FROM $tabla WHERE $columna LIKE '$valores';
          ");
            $content2 = '';
            $content = '';
            $carga = '';
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            $totalFilas = $resultado->rowCount();
            return $totalFilas;
            if ($totalFilas != 0) {
                for ($i = 0; $i < $resultado->columnCount(); $i++) {
                    $meta = $resultado->getColumnMeta($i);
                    $nombresColumnas[] = $meta['name'];
                    $content2 .= "<td>$nombresColumnas[$i]</td>";
                }
    
                // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
    
                foreach ($resultado as $fila) {
                    $content .= "<tr>";
                    $carga .= $fila[$columna].', ';
                    foreach ($nombresColumnas as $nombreColumna) {
                        $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                    }
    
                    $content .= "</tr>";
                }
    
    
    
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                            $content2
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            $content
                        </tr>
                    
                    </tbody>
                </table></div>";
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
        
                $excepcion = new excepcion();
                $excepcion->IdConexion = $idConexion;
                $excepcion->tipoExcepcion = "Campos";
                $excepcion->fecha = Carbon::now();
                $excepcion->tabla =  "Exepción en la tabla: "+$tabla;
                $excepcion->columna = "Se ha encontrado en la columna: "+$columna+" registros que no cumplen con el parametro siguiente:" + $valores;
                $excepcion->detalle = $carga;
                $excepcion->save();
                return $tablita;
            }else{
                
                $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>
                           <td>Resultado</td>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                        <td>No excepcion</td>
                        </tr>
                    
                    </tbody>
                </table></div>";
                return $tablita;
            }
            
        } else {


            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT * FROM $tabla WHERE $columna  LIKE '$valores';");
             $content2 = '';
             $content = '';
            $carga = '';
             // Obtener los nombres de las columnas
             $nombresColumnas = [];
             $totalFilas = $resultado->rowCount();
             return $totalFilas;
             return $totalFilas;
             if ($totalFilas != 0) {
                 for ($i = 0; $i < $resultado->columnCount(); $i++) {
                     $meta = $resultado->getColumnMeta($i);
                     $nombresColumnas[] = $meta['name'];
                     $content2 .= "<td>$nombresColumnas[$i]</td>";
                 }
     
                 // // Iterar sobre los resultados de la consulta y construir las filas de la tabla
     
                 foreach ($resultado as $fila) {
                     $content .= "<tr>";
                     $carga .= $fila[$columna].', ';
                     foreach ($nombresColumnas as $nombreColumna) {
                         $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                     }
     
                     $content .= "</tr>";
                 }
     
     
     
                 $tablita = "<div class='table-responsive' style='height: 300px;'>
                     <table class='table table-striped'>
                     <thead>
                         <tr>
                             $content2
                         </tr>
                     </thead>
                     <tbody>
                         
                         <tr>
                             $content
                         </tr>
                     
                     </tbody>
                 </table></div>";
                 $conexion = ModelsConexion::where('estado', 1)->first();
                 $idConexion = $conexion->IdConexion;
         
                 $excepcion = new excepcion();
                 $excepcion->IdConexion = $idConexion;
                 $excepcion->tipoExcepcion = "Campos";
                 $excepcion->fecha = Carbon::now();
                 $excepcion->tabla =  "Exepción en la tabla: "+$tabla;
                 $excepcion->columna = "Se ha encontrado en la columna: "+$columna+" registros que no cumplen con el parametro siguiente:" + $valores;
                 $excepcion->detalle = $carga;
                 $excepcion->save();
                 return $tablita;
             }else{
                 
                 $tablita = "<div class='table-responsive' style='height: 300px;'>
                     <table class='table table-striped'>
                     <thead>
                         <tr>
                            <td>Resultado</td>
                         </tr>
                     </thead>
                     <tbody>
                         
                         <tr>
                         <td>No excepcion</td>
                         </tr>
                     
                     </tbody>
                 </table></div>";
                 return $tablita;
             }
        }

       
       
    }




    //EXCEPCION CAMPOS FIN
    //===================================================================


    //EXCEPCION CABECERA INICIO

    public function cabeceraTablas(Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');

        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            // Configurar el modo de errores de PDO a excepciones  
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
            $consulta__3Encontrada = consulta3::where('tipoConexion', $tipoConexion)
            ->where('basenombre', $nombreBase)
            ->where('usuarioID', $usuarioC)->get();
            return view('exepciones.cabeDetalle', compact('resultado','consulta__3Encontrada'));
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $resultado = $instanciaConexion->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$nombreBase'");
            $user = User::where('conectado', 1)->first();
            $usuarioC = $user->id;
            $consulta__3Encontrada = consulta3::where('tipoConexion', $tipoConexion)
            ->where('basenombre', $nombreBase)
            ->where('usuarioID', $usuarioC)->get();
            return view('exepciones.cabeDetalle', compact('resultado','consulta__3Encontrada'));
        }
    }

    public function cabeShowCmbSel($tabla, $sel,  Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $resultado = null;
        $resultado2 = null;
        $contador = 0;
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion2 = new PDO($dsn);
            $instanciaConexion = new PDO($dsn);
            $instanciaConexion3 = new PDO($dsn);
            if ($sel == 'YES') {
                $resultado1 = $instanciaConexion->query("EXEC sp_fkeys '$tabla'");
                $tabla2Select = '<option disabled selected>...</option>';

                if ($resultado1->rowCount() < 0) {
                    $resultado = $instanciaConexion3->query("EXEC sp_fkeys '$tabla'");
                    $radioButton = '';
                    foreach ($resultado as $fila) {
                        if ($contador < 1) {
                            $contador  = $contador  + 1;
                            $registro = $fila['PKCOLUMN_NAME'];
                            $radioButton .= "
                            <input type='radio' class='btn-check' name='grupoCABECERA' value='$registro' id='opcion1Cabecera$registro' autocomplete='off' >
                            <label class='btn btn-outline-primary' for='opcion1Cabecera$registro'>$registro</label>
                         ";
                        }
                    }

                    $tabla2Array = array();

                    foreach ($resultado1 as $fila) {
                        $registro2 = $fila['FKTABLE_NAME'];
                        if (!in_array($registro2, $tabla2Array)) {
                            $tabla2Array[] = $registro2;
                        }
                    }

                    foreach ($tabla2Array as $registro2) {
                        $tabla2Select .= "<option value=\"$registro2\">$registro2</option>";
                    }

                    $datos = [
                        'radioButton' => $radioButton,
                        'tabla2Select' => $tabla2Select
                    ];
                    return response()->json($datos);
                } else {

                    $radioButton =  "La tabla $tabla no tiene ninguna clave foránea asociada. Esto significa que no hay ninguna otra tabla en la base de datos que tenga una relación de dependencia directa con esta tabla. Seleccionar otra tabla";
                    $tabla2Select = 0;
                    $datos = [
                        'radioButton' => $radioButton,
                        'tabla2Select' => $tabla2Select
                    ];
                    return response()->json($datos);
                }
            } else {
                $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
                $resultado2 = $instanciaConexion2->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");
                $radioButton = '';
                foreach ($resultado as $fila) {
                    $registro = $fila['COLUMN_NAME'];
                    $radioButton .= "
               
                    <input type='radio' class='btn-check' name='grupoCABECERA' value='$registro' id='opcion1Cabecera$registro' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='opcion1Cabecera$registro'>$registro</label>
                ";
                }
                $tabla2Select = '<option disabled selected>...</option>';
                foreach ($resultado2 as $fila) {
                    if ($fila['TABLE_NAME'] != $tabla) {
                        $registro2 = $fila['TABLE_NAME'];
                        $tabla2Select .= "
                    <option value=\"$registro2\">$registro2</option>
                    ";
                    }
                }
                $datos = [
                    'radioButton' => $radioButton,
                    'tabla2Select' => $tabla2Select
                ];
                return response()->json($datos);
            }
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion22 = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexion2222 = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexionYES = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexionYES2 = new PDO($dsn, $usuario, $contraseña);
        if ($sel == 'YES') {
                $resultadoMysqlYES = $instanciaConexionYES->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_NAME = '$tabla';");
                $totalFilas =  $resultadoMysqlYES->rowCount();
        if ($totalFilas == 0) {
                    $radioButton =  "La tabla $tabla no tiene ninguna clave foránea asociada. Esto significa que no hay ninguna otra tabla en la base de datos que tenga una relación de dependencia directa con esta tabla. Seleccionar otra tabla";
                    $tabla2Select = 0;
                    $datos = [
                        'radioButton' => $radioButton,
                        'tabla2Select' => $tabla2Select
                    ];
                    return response()->json($datos);
        }else{
                    $resultadoMysqlYES = $instanciaConexionYES->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE REFERENCED_TABLE_NAME = '$tabla';");
                    $radioButton = '';
                    $tabla2Select = '<option disabled selected>...</option>';
                    
                    foreach ($resultadoMysqlYES as $fila) {
                        if ($contador < 1) {
                            $contador  = $contador  + 1;
                            $registro = $fila['REFERENCED_COLUMN_NAME'];
                            $radioButton .= "
                            <input type='radio' class='btn-check' name='grupoCABECERA' value='$registro' id='opcion1Cabecera$registro' autocomplete='off' >
                            <label class='btn btn-outline-primary' for='opcion1Cabecera$registro'>$registro</label>
                         ";
                        }
                    }

                    $tabla2Array = array();
                    $resultadoMysqlYES2 = $instanciaConexionYES2->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE REFERENCED_TABLE_NAME = '$tabla';");
                    foreach ($resultadoMysqlYES2 as $fila) {
                        $registro2 = $fila['TABLE_NAME'];
                        $tabla2Select .= "<option value=\"$registro2\">$registro2</option>";
                    }

                  

                    $datos = [
                        'radioButton' => $radioButton,
                        'tabla2Select' => $tabla2Select
                    ];
                    return response()->json($datos);
                }

            }else{
                $resultado = $instanciaConexion22->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
                $resultado2 = $instanciaConexion2222->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '$nombreBase'");
            $radioButton = '';
            foreach ($resultado as $fila) {
                $registro = $fila['COLUMN_NAME'];
                $radioButton .= "
               
                    <input type='radio' class='btn-check' name='grupoCABECERA' value='$registro' id='opcion1Cabecera$registro' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='opcion1Cabecera$registro'>$registro</label>
                

                ";
            }
            $tabla2Select = '<option disabled selected>...</option>';
            foreach ($resultado2 as $fila) {
                if ($fila['TABLE_NAME'] != $tabla) {
                    $registro2 = $fila['TABLE_NAME'];
                    $tabla2Select .= "
                    <option value=\"$registro2\">$registro2</option>
                    ";
                }
            }
            $datos = [
                'radioButton' => $radioButton,
                'tabla2Select' => $tabla2Select
            ];
            return response()->json($datos);
        }
        }
    }

    public function detaRdbShow($tabla, $tablac, $sel, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');

        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
            if ($sel == 'YES') {
                $resultado = $instanciaConexion->query("EXEC sp_fkeys '$tablac'");
                $radioButton = '';
                $cue = 0;
                foreach ($resultado as $fila) {

                    if ($fila['FKTABLE_NAME'] == $tabla) {
                        $regis = $fila['FKCOLUMN_NAME'];
                        $radioButton .= "
                     <input type='radio' class='btn-check' name='grupoDETALLE' value='$regis' id='opcion1Detalle$regis' autocomplete='off' >
                     <label class='btn btn-outline-primary' for='opcion1Detalle$regis'>$regis</label>
                     ";
                    }
                }
                return $radioButton;
            } else {
                $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
                $radioButton = '';
                foreach ($resultado as $fila) {
                    $registro = $fila['COLUMN_NAME'];
                    $radioButton .= "
                    <input type='radio' class='btn-check' name='grupoDETALLE' value='$registro' id='opcion1Detalle$registro' autocomplete='off' >
                    <label class='btn btn-outline-primary' for='opcion1Detalle$registro'>$registro</label>
                    ";
                }
                return $radioButton;
            }
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexionYESdeta = new PDO($dsn, $usuario, $contraseña);
            if ($sel == 'YES') {
                $resultado2 = $instanciaConexionYESdeta->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE REFERENCED_TABLE_NAME = '$tablac';");
                $radioButton = '';
                $cue = 0;
                foreach ($resultado2 as $fila) {
                    if ($fila['TABLE_NAME'] == $tabla) {
                        $regis2 = $fila['COLUMN_NAME'];
                        $radioButton .= "
                     <input type='radio' class='btn-check' name='grupoDETALLE' value='$regis2' id='opcion1Detalle$regis2' autocomplete='off' >
                     <label class='btn btn-outline-primary' for='opcion1Detalle$regis2'>$regis2</label>
                     ";
                    }
                }
                return $radioButton;
            }else{
            $resultado = $instanciaConexion->query("SELECT * FROM information_schema.columns WHERE TABLE_NAME = '$tabla'");
            $radioButton = '';
            foreach ($resultado as $fila) {
                $registro = $fila['COLUMN_NAME'];
                $radioButton .= "
                <input type='radio' class='btn-check' name='grupoDETALLE' value='$registro' id='opcion1Detalle$registro' autocomplete='off' >
                <label class='btn btn-outline-primary' for='opcion1Detalle$registro'>$registro</label>
                ";
            }

            return $radioButton;
        }
        }
    }

    public function evaluaCabeDetalle($tablaCabecera, $tablaDetalle, $columnaCabecera, $columnaDetalle, $sel, Request $request)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $content = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexionOF = new PDO($dsn);
            $instancia1 = new PDO($dsn);
            $instanciaConexiona = new PDO($dsn);
            $instanciatipoDATOCABE = new PDO($dsn);
            $instanciatipoDATODETA = new PDO($dsn);
            $instanciaexc = new PDO($dsn);
            $instanciaexc2 = new PDO($dsn);
            $instanciaConexionb = new PDO($dsn);
            if ($sel == 'YES') {
                $resultado = $instanciaConexionOF->query("	
               SELECT $tablaDetalle.$columnaDetalle
                FROM $tablaDetalle
                LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                WHERE $tablaCabecera.$columnaCabecera IS NULL
                    ");

                    $filas = $resultado->fetchAll(PDO::FETCH_ASSOC);
                    $totalFilasYES = count($filas);
                if ($totalFilasYES == 0) {
                    $content2 = $columnaDetalle;
                    $content = "<tr><td>No excepciones</tr></td>";
                    $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>$content2</tr>
                        </thead>
                    <tbody>
                        <tr>$content</tr>
                    </tbody>
                </table></div>";
                return $tablita;
                } else {
                    $resultado2 = $instanciaConexionOF->query("	
                    SELECT $tablaDetalle.$columnaDetalle
                     FROM $tablaDetalle
                     LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                     WHERE $tablaCabecera.$columnaCabecera IS NULL
                         ");
                    $content2 = $columnaDetalle;
                    $a = 0;
                    if ($totalFilasYES > 500) {
                        $b = 500;
                    }else{$b = $totalFilasYES;}
                    
                    $totalFilas = $totalFilasYES;
                    $guardaCaja = '';
                    
                    $tablasBD = '';
                    $tablasBD .= $tablaCabecera;
                    $tablasBD .= "&$tablaDetalle";
            
                    $columnaBD = '';
                    $columnaBD .= $columnaCabecera;
                    $columnaBD .= "&$columnaDetalle";
                                          
                    $conexion = ModelsConexion::where('estado', 1)->first();
                    $idConexion = $conexion->IdConexion;
            
                    $excepcion = new excepcion();
                    $resultados_array = $resultado2->fetchAll(PDO::FETCH_ASSOC);
                    while ($a < $totalFilas) {      
                       
                        $resultados_subconjunto = array_slice($resultados_array, $a, $b, true);
                        foreach ($resultados_subconjunto as $fila) {
                                $guardaCaja .= $fila[$columnaDetalle] . ", ";
                                $content .= "<tr><td>" . $fila[$columnaDetalle] . "</td></tr>";
                            }
                            
                                $excepcion = new excepcion();
                                $excepcion->IdConexion = $idConexion;
                                $excepcion->tipoExcepcion = "Cabecera";
                                $excepcion->fecha = Carbon::now();
                                $excepcion->tabla = $tablasBD;
                                $excepcion->columna = $columnaBD;
                                $excepcion->detalle = $guardaCaja;
                                $excepcion->save();
                                $guardaCaja = '';
                                $a = $a + $b;
                                $b = min($b, $totalFilas - $a);
                    }
                    $tablita = "<div class='table-responsive ' style='height: 300px;'>
                                    <table class='table table-striped'>
                                        <thead>
                                            <tr>$content2</tr>
                                        </thead>
                                        <tbody>
                                            <tr>$content</tr>
                                        </tbody>
                                    </table>
                                    </div>";

                    return $tablita; 
                }
            } else {
                $tipoDato1 = $instanciatipoDATOCABE->query("select data_type from INFORMATION_SCHEMA.COLUMNS
                                where TABLE_NAME = '$tablaCabecera' and COLUMN_NAME = '$columnaCabecera'");
                
                foreach ($tipoDato1 as $fila) {
                    $datoContenidocabecera = $fila['data_type'];
                }

                $tipoDato2 = $instanciatipoDATODETA->query("select data_type from INFORMATION_SCHEMA.COLUMNS
                                where TABLE_NAME = '$tablaDetalle' and COLUMN_NAME = '$columnaDetalle'");
                foreach ($tipoDato2 as $fila) {
                    $datoContenidodetalle = $fila['data_type'];
                }

                if ($datoContenidocabecera != $datoContenidodetalle) {
                    $errordato = 'ERROR1';
                    return $errordato;
                }

                $resultado = $instanciaConexionOF->query("	
                SELECT $tablaDetalle.$columnaDetalle
                 FROM $tablaDetalle
                 LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                 WHERE $tablaCabecera.$columnaCabecera IS NULL
                     ");
                   
                $filas = $resultado->fetchAll(PDO::FETCH_ASSOC);
                $totalFilasNO = count($filas);

                if ($totalFilasNO == 0) {
                    $content2 = $columnaDetalle;
                    $content = "<tr><td>No excepciones</td></tr>";
                    $tablita = "<div class='table-responsive ' style='height: 300px;'>
                                    <table class='table table-striped'>
                                        <thead>
                                            <tr>$content2</tr>
                                        </thead>
                                        <tbody>
                                        <tr>$content</tr>
                                        </tbody>
                                    </table>
                                </div>";
                    return $tablita;
                } else {
                    $resultado2 = $instanciaConexionOF->query("	
                    SELECT $tablaDetalle.$columnaDetalle
                     FROM $tablaDetalle
                     LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                     WHERE $tablaCabecera.$columnaCabecera IS NULL
                         ");
                    $content2 = $columnaDetalle;
                    $a = 0;
                    if ($totalFilasNO > 500) {
                        $b = 500;
                    }else{$b = $totalFilasNO;}
                    
                    $totalFilas = $totalFilasNO;
                    $guardaCaja = '';
                    
                    $tablasBD = '';
                    $tablasBD .= $tablaCabecera;
                    $tablasBD .= "&$tablaDetalle";
            
                    $columnaBD = '';
                    $columnaBD .= $columnaCabecera;
                    $columnaBD .= "&$columnaDetalle";
                                          
                    $conexion = ModelsConexion::where('estado', 1)->first();
                    $idConexion = $conexion->IdConexion;
            
                    $excepcion = new excepcion();
                    $resultados_array = $resultado2->fetchAll(PDO::FETCH_ASSOC);
                    while ($a < $totalFilas) {      
                       
                        $resultados_subconjunto = array_slice($resultados_array, $a, $b, true);
                        foreach ($resultados_subconjunto as $fila) {
                                $guardaCaja .= $fila[$columnaDetalle] . ", ";
                                $content .= "<tr><td>" . $fila[$columnaDetalle] . "</td></tr>";
                            }
                            
                                $excepcion = new excepcion();
                                $excepcion->IdConexion = $idConexion;
                                $excepcion->tipoExcepcion = "Cabecera";
                                $excepcion->fecha = Carbon::now();
                                $excepcion->tabla = $tablasBD;
                                $excepcion->columna = $columnaBD;
                                $excepcion->detalle = $guardaCaja;
                                $excepcion->save();
                                $guardaCaja = '';
                                $a = $a + $b;
                                $b = min($b, $totalFilas - $a);
                    }
                    $tablita = "<div class='table-responsive ' style='height: 300px;'>
                                    <table class='table table-striped'>
                                        <thead>
                                            <tr>$content2</tr>
                                        </thead>
                                        <tbody>
                                            <tr>$content</tr>
                                        </tbody>
                                    </table>
                                    </div>";

                    return $tablita; 
                }
            }
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexiona = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexionOF = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexiona = new PDO($dsn, $usuario, $contraseña);
            $instanciatipoDATOCABE = new PDO($dsn, $usuario, $contraseña);
            $instanciatipoDATODETA = new PDO($dsn, $usuario, $contraseña);
            $instanciaexc = new PDO($dsn, $usuario, $contraseña);
            $instanciaexc2 = new PDO($dsn, $usuario, $contraseña);
            $instanciaConexionb = new PDO($dsn, $usuario, $contraseña);
if ($sel == 'YES') {
                $resultadoYESE = $instanciaConexiona->query("SELECT $tablaDetalle.$columnaDetalle
                FROM $tablaDetalle
                LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                WHERE $tablaCabecera.$columnaCabecera IS NULL;
                    ");
                     $numeroFilasYES = $resultadoYESE->rowCount();
                if ($numeroFilasYES == 0) {
                    $content2 = $columnaDetalle;
                    $content = "<tr><td>No excepciones</tr></td>";
                    $tablita = "<div class='table-responsive' style='height: 300px;'>
                    <table class='table table-striped'>
                    <thead>
                        <tr>$content2</tr>
                        </thead>
                    <tbody>
                        <tr>$content</tr>
                    </tbody>
                </table></div>";
                return $tablita;
                }else{
                    
                $resultadoYESNOEX = $instanciaexc->query("	
                SELECT $tablaDetalle.$columnaDetalle
                FROM $tablaDetalle
                LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                WHERE $tablaCabecera.$columnaCabecera IS NULL;
              ");

                    

                $numeroFilasNO = $resultadoYESNOEX->rowCount();
              
                
                $content2 = $columnaDetalle;
                $a = 0;
                if ($numeroFilasNO > 500) {
                    $b = 500;
                }else{$b = $numeroFilasNO;}
                $guardaCaja = '';
              
                $tablasBD = '';
                $tablasBD .= $tablaCabecera;
                $tablasBD .= "&$tablaDetalle";
      
                $columnaBD = '';
                $columnaBD .= $columnaCabecera;
                $columnaBD .= "&$columnaDetalle";
                                    
                $conexion = ModelsConexion::where('estado', 1)->first();
                $idConexion = $conexion->IdConexion;
      
                $excepcion = new excepcion();
                $resultados_array = $resultadoYESNOEX->fetchAll(PDO::FETCH_ASSOC);
                while ($a < $numeroFilasNO) {      
                   $resultados_subconjunto = array_slice($resultados_array, $a, $b, true);
                   foreach ($resultados_subconjunto as $fila) {
                           $guardaCaja .= $fila[$columnaDetalle] . ", ";
                           $content .= "<tr><td>" . $fila[$columnaDetalle] . "</td></tr>";
                       }
                           $excepcion = new excepcion();
                           $excepcion->IdConexion = $idConexion;
                           $excepcion->tipoExcepcion = "Cabecera";
                           $excepcion->fecha = Carbon::now();
                           $excepcion->tabla = $tablasBD;
                           $excepcion->columna = $columnaBD;
                           $excepcion->detalle = $guardaCaja;
                           $excepcion->save();
                           $guardaCaja = '';
                           $a = $a + $b;
                           $b = min($b, $numeroFilasNO - $a);
               }
               $tablita = "<div class='table-responsive ' style='height: 300px;'>
                               <table class='table table-striped'>
                                   <thead>
                                       <tr>$content2</tr>
                                   </thead>
                                   <tbody>
                                       <tr>$content</tr>
                                   </tbody>
                               </table>
                               </div>";

               return $tablita;
                }
    }else{
                
                $tipoDato1 = $instanciatipoDATOCABE->query("select data_type from INFORMATION_SCHEMA.COLUMNS
                where TABLE_NAME = '$tablaCabecera' and COLUMN_NAME = '$columnaCabecera'");
                foreach ($tipoDato1 as $fila) {
                    $datoContenidocabecera = $fila['DATA_TYPE'];
                }

                $tipoDato2 = $instanciatipoDATODETA->query("select data_type from INFORMATION_SCHEMA.COLUMNS
                where TABLE_NAME = '$tablaDetalle' and COLUMN_NAME = '$columnaDetalle'");
                foreach ($tipoDato2 as $fila) {
                    $datoContenidodetalle = $fila['DATA_TYPE'];
                }

                if ($datoContenidocabecera != $datoContenidodetalle) {
                    $errordato = 'ERROR1';
                    return $errordato;
                }
                
                $resultadoNOMYSQL = $instanciaConexionOF->query("	
                    SELECT $tablaDetalle.$columnaDetalle
                    FROM $tablaDetalle
                    LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
                    WHERE $tablaCabecera.$columnaCabecera IS NULL;
                  ");
                    $numeroFilasNO = $resultadoNOMYSQL->rowCount();
                    if ($numeroFilasNO == 0) {
                        $content2 = $columnaDetalle;
                        $content = "<tr><td>No excepciones</tr></td>";
                        $tablita = "<div class='table-responsive' style='height: 300px;'>
                        <table class='table table-striped'>
                        <thead>
                            <tr>$content2</tr>
                            </thead>
                        <tbody>
                            <tr>$content</tr>
                        </tbody>
                    </table></div>";
                    return $tablita;
                    }else{

                    
                    $content2 = $columnaDetalle;
                    $a = 0;
                    if ($numeroFilasNO > 500) {
                        $b = 500;
                    }else{$b = $numeroFilasNO;}
                    $guardaCaja = '';
                  
                    $tablasBD = '';
                    $tablasBD .= $tablaCabecera;
                    $tablasBD .= "&$tablaDetalle";
          
                    $columnaBD = '';
                    $columnaBD .= $columnaCabecera;
                    $columnaBD .= "&$columnaDetalle";
                                        
                    $conexion = ModelsConexion::where('estado', 1)->first();
                    $idConexion = $conexion->IdConexion;
          
                    $excepcion = new excepcion();
                    $resultados_array = $resultadoNOMYSQL->fetchAll(PDO::FETCH_ASSOC);
                    while ($a < $numeroFilasNO) {      
                       $resultados_subconjunto = array_slice($resultados_array, $a, $b, true);
                       foreach ($resultados_subconjunto as $fila) {
                               $guardaCaja .= $fila[$columnaDetalle] . ", ";
                               $content .= "<tr><td>" . $fila[$columnaDetalle] . "</td></tr>";
                           }
                          
                               $excepcion = new excepcion();
                               $excepcion->IdConexion = $idConexion;
                               $excepcion->tipoExcepcion = "Cabecera";
                               $excepcion->fecha = Carbon::now();
                               $excepcion->tabla = $tablasBD;
                               $excepcion->columna = $columnaBD;
                               $excepcion->detalle = $guardaCaja;
                               $excepcion->save();
                               $guardaCaja = '';
                               $a = $a + $b;
                               $b = min($b, $numeroFilasNO - $a);
                   }
                   $tablita = "<div class='table-responsive ' style='height: 300px;'>
                                   <table class='table table-striped'>
                                       <thead>
                                           <tr>$content2</tr>
                                       </thead>
                                       <tbody>
                                           <tr>$content</tr>
                                       </tbody>
                                   </table>
                                   </div>";

                   return $tablita;
                }
    }
            

            
            // $tablaex1 = '';
            // $columnaex1 = '';



            // $resexc1 = $instanciaexc->query("SELECT  REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            //             FROM information_schema.KEY_COLUMN_USAGE
            //             WHERE TABLE_SCHEMA = '$nombreBase'
            //             AND TABLE_NAME = '$columnaCabecera';");
            // foreach ($resexc1 as $fila) {
            //     $tablaex1 .= $fila['REFERENCED_TABLE_NAME'] . ' '; // Concatenar el nombre de la tabla
            //     $columnaex1 .= $fila['REFERENCED_COLUMN_NAME'] . ' '; // Concatenar el nombre de la columna
            // }

           

            // // Dividir las cadenas en arrays
            // $tablasExistentes = explode(' ', trim($tablaex1)); // trim() para eliminar espacios adicionales al principio y al final
            // $columnasExistentes = explode(' ', trim($columnaex1));

            // // Verificar si al menos una tabla o una columna coincide
            // $coincidenciaTabla = false;
            // $coincidenciaColumna = false;

            // foreach ($tablasExistentes as $tablaExistente) {
            //     if ($tablaExistente == $tablaDetalle) {
            //         $coincidenciaTabla = true;
            //         break; // No necesitamos continuar si encontramos una coincidencia
            //     }
            // }

            // foreach ($columnasExistentes as $columnaExistente) {
            //     if ($columnaExistente == $columnaDetalle) {
            //         $coincidenciaColumna = true;
            //         break; // No necesitamos continuar si encontramos una coincidencia
            //     }
            // }

            // // Verificar si no hay coincidencias
            // if (!$coincidenciaTabla || !$coincidenciaColumna) {
            //     $errordato = 'ERROR2: Por favor, tomar como referencia las siguientes relaciones: TABLA DETALLE->' . $tablaex1 . ' y COLUMNA DETALLE->' . $columnaex1 . ' .A su criterio.';
            //     return $errordato;
            // }

            // Si al menos una tabla o una columna coincide, no se muestra ningún error

            // $resultado = $instanciaConexionOF->query("	
            //   SELECT $tablaDetalle.*
            //         FROM $tablaDetalle
            //         LEFT JOIN $tablaCabecera ON $tablaDetalle.$columnaDetalle = $tablaCabecera.$columnaCabecera
            //         WHERE $tablaCabecera.$columnaCabecera IS NULL;
            //       ");
            ////////////////////////////////////////////////////////////////////////////////////////////
        }
        // $content2 = '';


        // // Obtener los nombres de las columnas
        // $nombresColumnas = [];
        // for ($i = 0; $i < $resultado->columnCount(); $i++) {
        //     $meta = $resultado->getColumnMeta($i);
        //     $nombresColumnas[] = $meta['name'];
        //     $content2 .= "<td>$nombresColumnas[$i]</td>";
        // }
        // //return $content2;
        // // // Iterar sobre los resultados de la consulta y construir las filas de la tabla

        // foreach ($resultado as $fila) {
        //     $content .= "<tr>";

        //     foreach ($nombresColumnas as $nombreColumna) {
        //         $content .= "<td>" . $fila[$nombreColumna] . "</td>";
        //     }

        //     $content .= "</tr>";
        // }



        // $tablita = "<div class='table-responsive' style='height: 300px;'>
        //     <table class='table table-striped'>
        //     <thead>
        //         <tr>
        //             $content2
        //         </tr>
                
        //     </thead>
        //     <tbody>
                
        //         <tr>
        //             $content
        //         </tr>
            
        //     </tbody>
        // </table></div>";

        // $tablasBD = '';
        // $tablasBD .= $tablaCabecera;
        // $tablasBD .= "&$tablaDetalle";

        // $columnaBD = '';
        // $columnaBD .= $columnaCabecera;
        // $columnaBD .= "&$columnaDetalle";

        // $conexion = ModelsConexion::where('estado', 1)->first();
        // $idConexion = $conexion->IdConexion;

        // $excepcion = new excepcion();
        // $excepcion->IdConexion = $idConexion;
        // $excepcion->tipoExcepcion = "Cabecera";
        // $excepcion->fecha = Carbon::now();
        // $excepcion->tabla = $tablasBD;
        // $excepcion->columna = $columnaBD;
        // $excepcion->detalle = $content;
        // $excepcion->save();

        // return $tablita;
    }
    //EXCEPCION CABECERA FIN
    //===================================================================

    //REPORTES
    public function secuencialidadREPORTE()
    {
        $user = User::where('conectado', 1)->first();
        $conexion = ModelsConexion::where('estado', 1)->first();
        $idConexion = $conexion->IdConexion;
        $excepcion = excepcion::select('tabla', 'columna', 'detalle')
                        ->where('IdConexion', $idConexion)
                        ->where('tipoExcepcion', 'Secuencialidad')
                        ->groupBy('tabla', 'columna', 'detalle')
                        ->get();
       
        return view('reporte.secuencialidad', compact('excepcion', 'conexion','user'));
    }

    public function camposREPORTE()
    {
        $user = User::where('conectado', 1)->first();
        $conexion = ModelsConexion::where('estado', 1)->first();
        $idConexion = $conexion->IdConexion;
        $detalle = $conexion->detalle;
        $excepcion = excepcion::where('IdConexion', $idConexion)->where('tipoExcepcion', 'Campos')->get();
        // $PRnull = [];
        // $PRcampo = [];
        // $PRdato = [];
        // $RESnull = [];
        // $REScampo = [];
        // $RESdato = [];

        // foreach ($excepcion as $itemexcepcion) {
        //     $detalles = explode('&', $itemexcepcion->detalle);
        //     $RESparametro = $detalles[0];
        //     $RESnull[] = $detalles[1];
        //     $REScampo[] = $detalles[2];
        //     $RESdato[] = $detalles[3];

        //     $detalles2 = explode('-', $RESparametro);
        //     $PRnull[] = $detalles2[0];
        //     $PRcampo[] = $detalles2[1];
        //     $PRdato[] = $detalles2[2];
        // }
        return view('reporte.campos', compact('excepcion', 'detalle', 'conexion','user'));
    }

    public function cabeceraREPORTE()
    {
        $conexion = ModelsConexion::where('estado', 1)->first();
        $user = User::where('conectado', 1)->first();
        $idConexion = $conexion->IdConexion;
        $excepcion = excepcion::where('IdConexion', $idConexion)->where('tipoExcepcion', 'Cabecera')->get();
        if ($excepcion->isNotEmpty()) {
            foreach ($excepcion as $itemexcepcion) {
                $detalles1 = explode('&', $itemexcepcion->tabla);
                $tablaCabecera[] = $detalles1[0];
                $tablaDetalle[] = $detalles1[1];
            }
            
            foreach ($excepcion as $itemexcepcion) {
                $detalles = explode('&', $itemexcepcion->columna);
                $ColumnaCabecera[] = $detalles[0];
                $ColumnaDetalle[] = $detalles[1];
            }
        } else {
            // Si $excepcion está vacío, inicializa las variables como arreglos vacíos
            $tablaCabecera = [];
            $tablaDetalle = [];
            $ColumnaCabecera = [];
            $ColumnaDetalle = [];
        }
        
        return view('reporte.cabecera', compact('excepcion', 'tablaCabecera', 'tablaDetalle', 'ColumnaCabecera', 'ColumnaDetalle','user','conexion'));
    }

    public function sqlDinamico(Request $request)
    {
        return view('conexion.sqlDinamico');
    }

    public function sqlDinamico2(Request $request, $sd)
    {
        $servidor = $request->session()->get('servidor');
        $nombreBase = $request->session()->get('nombreBase');
        $usuario = $request->session()->get('usuario');
        $contraseña = $request->session()->get('contraseña');
        $tipoConexion = $request->session()->get('tipoConexion');
        $arrayCabecera = array();
        $arrayDetalle = array();
        $encontroExcepcion = false;
        $content = '';
        $content2 = '';
        if ($tipoConexion == 'sqlserver') {
            $dsn = "odbc:Driver={SQL Server};Server=$servidor;Database=$nombreBase;Uid=$usuario;Pwd=$contraseña";
            $instanciaConexion = new PDO($dsn);
        } else {
            $dsn = "mysql:host=$servidor;dbname=$nombreBase;charset=utf8mb4";
            $instanciaConexion = new PDO($dsn, $usuario, $contraseña);
        }
        $consulta = $instanciaConexion->query("$sd");

        // Verificar si la consulta se ejecutó correctamente
        if ($consulta) {
            // Obtener los nombres de las columnas
            $nombresColumnas = [];
            for ($i = 0; $i < $consulta->columnCount(); $i++) {
                $meta = $consulta->getColumnMeta($i);
                $nombresColumnas[] = $meta['name'];
                $content2 .= "<td>$nombresColumnas[$i]</td>";
            }

            // // Iterar sobre los resultados de la consulta y construir las filas de la tabla

            foreach ($consulta as $fila) {
                $content .= "<tr>";
                foreach ($nombresColumnas as $nombreColumna) {
                    $content .= "<td>" . $fila[$nombreColumna] . "</td>";
                }
                $content .= "</tr>";
            }
        } else {
            // Manejar el caso en que la consulta falla
            $content = "Error al ejecutar la consulta.";
        }

        $tablita = "<div class='table-responsive' style='height: 300px;'>
            <table class='table table-striped'>
            <thead>
                <tr>
                    $content2
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    $content
                </tr>
            
            </tbody>
        </table></div>";

        return $tablita;
    }
}
