<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Sucursal;
use App\Models\UsuarioPerfil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     * Name: /usuarios
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        if ($usuario->perfil_id === 1) {
            $usuarios = User::orderBy('name')
                ->paginate(10);
        } else {
            $usuarios = User::where('perfil_id', '!=', 1)
                ->orderBy('name')
                ->paginate(10);
        }

        $roles = Role::select('label')
            ->get()
            ->toArray();
        $sucursales = Sucursal::select('nombre')
            ->get()
            ->toArray();

        return view('usuarios.index', compact('usuarios', 'roles', 'sucursales'));
    }

    /**
     * Show form for create the specified resource.
     * Name: /usuario/create
     *
     * @param  Request $request
     * @param  Response $response
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Response $response)
    {
        $usuario = Auth::user();
        $wherePerfiles = [
            ['estado', '=', 1]
        ];
        $empresas = Empresa::where('estado', 1)
            ->orderBy('razon_social')
            ->get();
        $sucursales = Sucursal::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        if ($usuario->perfil_id !== 1) {    // Si el usuario no es Admin no presenta perfil de admin
            $wherePerfiles[] = ['perfil_id', '>', 1];
        }

        $perfiles = UsuarioPerfil::where($wherePerfiles)
            ->orderBy('descripcion')
            ->get();

        $data = compact(
            'empresas',
            'sucursales',
            'perfiles',
        );

        return view('usuarios.create', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * Name: /usuarios/{id}/edit
     *
     * @param  \App\Models\User  $usuario
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario) //, $id)
    {
        $wherePerfiles = [
            ['estado', '=', 1]
        ];
        $empresas = Empresa::where('estado', 1)
            ->orderBy('razon_social')
            ->get();
        $sucursales = Sucursal::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        if ($usuario->perfil_id == 2) {    // Si el usuario no es Admin no presenta perfil de admin
            $wherePerfiles[] = ['id', '>', 1];
        }

        $perfiles = UsuarioPerfil::where($wherePerfiles)
            ->orderBy('descripcion')
            ->get();

        $data = compact(
            'usuario',
            'empresas',
            'sucursales',
            'perfiles',
        );

        return view('usuarios.edit', $data);
    }

    /**
     * Update the specified resource in storage. (PUT)
     * Name: usuario.update (/usuario/{id})
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        // $request()->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'string|email|max:255|unique:users',
        //     'password' => 'required|string|confirmed|min:6',
        //     'nombre_usuario' => 'required|string|max:255',
        // ]);

        if (strlen($request->password) < 20) {
            $password = Hash::make($request->password);
        } else {
            $password = $request->password;
        }

        $usuario->update([
            'name' => $request->name,
            'nombre_usuario' => $request->input('nombre_usuario'),
            'email' => $request->email,
            'password' => $password,
            'empresa_id' => $request->empresa_id,
            'sucursal_id' => $request->sucursal_id,
            'perfil_id' => $request->perfil_id,
            'estado' => $request->estado
        ]);

        $usuario->save();

        return redirect("usuarios");
    }

    /**
     * Store a newly created resource in storage. (POST)
     * Name: usuario.store (/usuario)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'nombre_usuario' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'empresa_id' => $request->empresa_id,
            'sucursal_id' => $request->sucursal_id,
            'perfil_id' => $request->perfil_id,
            'estado' => $request->estado
        ]);

        $role_user = RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $user->perfil_id
        ]);

        return redirect('usuarios');
    }

    /**
     * Cambiar el password del usuario
     * Name: usuario.changepassw (GET)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changepassw(Request $request)
    {
        $usuario = Auth::user();
        $data = compact(
            'usuario',
        );

        return view('usuarios.changepassw', $data);
    }

    /**
     * Actualiza cambio de password. (PUT)
     * Name: usuario.putchangepassw (/usuario/putchangepassw/{id})
     *
     * @param  Request  $request
     * @param  integer $id
     * @return Response
     */
    public function putChangePassw(Request $request, $id)
    {
        $usuario = User::find($id);
        $usuario->update(['password' => Hash::make($request->password)]);
        $usuario->save();

        //return redirect('usuario.changepassw')->with('status', 'Contraseña actualizada !!  ');
        return back()->with('status', 'Contraseña actualizada !!  ');
    }

}
