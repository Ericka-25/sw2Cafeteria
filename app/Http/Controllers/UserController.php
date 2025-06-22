<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:users.index')->only(['index', 'show']);
        $this->middleware('can:users.create')->only(['create', 'store']);
        $this->middleware('can:users.edit')->only(['edit', 'update']);
        $this->middleware('can:users.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::where('estado',true)->orderBy('id', 'asc')->paginate(5);
        // Retornar la vista 'usuario.index' y pasar los datos de los usuarios a la vista
        return view('usuario.index', compact('users'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Retornar la vista 'usuario.show' y pasar los datos del usuario a la vista
        $user = Auth::user();
        return view('usuario.show', compact('user'));
    }
    public function create()
    {
        // Obtener todos los roles disponibles
        $roles = Role::all();

        // Retornar la vista de creación con los roles
        return view('usuario.register', compact('roles'));
    }
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|exists:roles,id',
        ]);

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Asignar el rol al usuario
        $role = Role::findById($validatedData['roles'], 'web');
        $user->assignRole($role);

        // Retornar una respuesta de éxito y redirigir a la vista de usuarios
        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('usuario.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validar los datos de entrada para la actualización
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|exists:roles,id',
        ]);

        // Actualizar los datos del usuario
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        // Buscar el rol por ID
        $role = Role::findById($validatedData['roles'], 'web');

        if (!$role) {
            return redirect()->back()->withErrors(['roles' => 'El rol seleccionado no existe.'])->withInput();
        }

        // Asignar el rol al usuario
        $user->syncRoles([$role]);

        // Retornar una respuesta de éxito con el usuario actualizado
        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Eliminar el usuario
        //$user->delete();
        $user->estado = false;
        $user->save();

        // Retornar una respuesta de éxito
        return redirect()->route('users.index');
    }
}
