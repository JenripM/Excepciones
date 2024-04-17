<?php

namespace App\Http\Controllers;
use App\Models\rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolController extends Controller
{
    public function index()
    {
        $rol = Rol::where('estado', 1)->get();
        return view("rol.index",compact("rol"));
    }

    public function create()
    {
        return view('rol.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/',
                                Rule::unique('ROL')->where(function ($query) {
                                return $query->where('estado', 1);
                                }), 
                                'max:50'],
            ],
        );

        $rol=new rol();
        $rol->descripcion = $request->descripcion;
        $rol->tablas = $request->has('tablas') ? 1 : 0;
        $rol->vista_sql = $request->has('vista_sql') ? 1 : 0;
        $rol->excepciones_s = $request->has('excepciones_s') ? 1 : 0;
        $rol->excepciones_c = $request->has('excepciones_c') ? 1 : 0;
        $rol->excepciones_i = $request->has('excepciones_i') ? 1 : 0;
        $rol->reportes_s = $request->has('reportes_s') ? 1 : 0;
        $rol->reportes_c = $request->has('reportes_c') ? 1 : 0;
        $rol->reportes_i = $request->has('reportes_i') ? 1 : 0;
        $rol->roles = $request->has('roles') ? 1 : 0;
        $rol->usuarios = $request->has('usuarios') ? 1 : 0;
        $rol->estado = 1;

        $rol->save();
        return redirect()->route('rol.index')->with('datos','Registro Nuevo Guardado...!');
    }

    public function edit($id)
    {
        $rol =rol::findOrFail($id);
        return view('rol.edit',compact('rol'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'descripcion' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/',
                            Rule::unique('ROL')->where(function ($query) use ($id) {
                                return $query->where('idRol', '!=', $id)->where('estado', 1);
                            }),
                            'max:50'],
            ],
        );
        $rol=rol::findOrFail($id);
        $rol->descripcion = $request->descripcion;
        $rol->tablas = $request->has('tablas') ? 1 : 0;
        $rol->vista_sql = $request->has('vista_sql') ? 1 : 0;
        $rol->excepciones_s = $request->has('excepciones_s') ? 1 : 0;
        $rol->excepciones_c = $request->has('excepciones_c') ? 1 : 0;
        $rol->excepciones_i = $request->has('excepciones_i') ? 1 : 0;
        $rol->reportes_s = $request->has('reportes_s') ? 1 : 0;
        $rol->reportes_c = $request->has('reportes_c') ? 1 : 0;
        $rol->reportes_i = $request->has('reportes_i') ? 1 : 0;
        $rol->roles = $request->has('roles') ? 1 : 0;
        $rol->usuarios = $request->has('usuarios') ? 1 : 0;
        //$rol->estado = 1;

        $rol->save();
        return redirect()->route('rol.index')->with('datos','Registro Nuevo Guardado...!');
    }


    public function confirmar($id)
    {
        $rol = rol::findOrFail($id);
        return view('rol.confirmar', compact('rol'));
    }


    function destroy($id)
    {
        $rol = rol::findOrFail($id);
        $rol->estado = 0;
        $rol->save();

        //FK idRol de user cambiar a NULL
        User::where('idRol', $id)->update(['idRol' => null]);

        return redirect()->route('rol.index')->with('datos', 'Registro Eliminado');
    }
}
