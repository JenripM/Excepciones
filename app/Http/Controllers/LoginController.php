<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function index()
    {
        $user = User::leftjoin('ROL', 'ROL.idRol', '=', 'users.idRol')->where('users.estado', 1)->get();
        return view("login.index",compact("user"));
    }

    public function create()
    {
        $rol = rol::where('estado', 1)->get();
        return view('login.create',compact("rol"));
    }


    public function store(Request $request){
        $request->validate([
            'name' => ['required',
                        Rule::unique('users')->where(function ($query) {
                        return $query->where('estado', 1);
                        }),  
                        'max:50'],
            'password' => ['required', Rules\Password::default()->mixedCase()->numbers()],
            ],
        );

        $user = new User();
        $user->idRol = $request->idRol;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->conectado = '0';
        $user->estado = '1';
        $user->save();

        //Auth::login($user);
        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $user =User::findOrFail($id);
        $rol = rol::where('estado', 1)->get();
        return view('login.edit',compact('user','rol'));
    }

    public function update(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $user->idRol = $request->idRol;
        //$user->name = $request->name;
        //$user->password = Hash::make($request->password);
        //$user->conectado = '0';
        //$user->estado = '1';
        $user->save();

        return redirect()->route('user.index');
    }

    public function update2(Request $request,$id)
    {
        $request->validate([
            'name' => ['required',
                        Rule::unique('users')->where(function ($query) use ($id)  {
                        return $query->where('id', '!=', $id)->where('estado', 1);
                        }),  
                        'max:50'],
            'password' => ['required', Rules\Password::default()->mixedCase()->numbers()],
            ],
        );
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('master');
    }


    public function confirmar($id)
    {
        $item = User::leftJoin('ROL', 'ROL.idRol', '=', 'users.idRol')->where('id','like','%'.$id.'%')->get();

        foreach ($item as $user){
        }

        return view('login.confirmar', compact('user'));
    }


    function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->conectado = 0;
        $user->estado = 0;
        $user->save();


        return redirect()->route('user.index');
    }



    public function perfil($id)
    {
        $user = User::join('ROL', 'ROL.idRol', '=', 'users.idRol')->where('id','like','%'.$id.'%')->first();

        return view('login.perfil', compact('user'));
    }






    public function login(Request $request){
        $credentials=[
            "name" => $request->name,
            "password" => $request->password,
            "estado" => '1',
        ];
        $errors = [];
        if(Auth::attempt($credentials)){

            if(Auth::user()->idRol != NULL){
                $request->session()->regenerate();
                $user = User::findOrFail(Auth::user()->id);
                $user->conectado = 1;
                $user->save();

                $rol = rol::findOrFail(Auth::user()->idRol);
                $request->session()->put('rol', $rol);
                return redirect()->intended(route('master'));
            }else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $errors = ['name' => 'Cuenta sin rol asignado.'];
                return redirect('login')->withInput($request->only('name'))->withErrors($errors);
            }
            
        }else{
            $user = User::where('name', $request->name)->where('estado','1')->first();
            if(!$user){
                $errors = ['name' => 'El usuario no está registrado.'];
            }else{
                $errors = ['password' => 'La contraseña ingresada no es válida.'];
            }

            return redirect('login')->withInput($request->only('name'))->withErrors($errors);
        }
    }





    public function logout(Request $request){

        $user = User::findOrFail(Auth::user()->id);
        $user->conectado = 0;
        $user->save();


        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

}
