<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /** GET /registro: muestra el formulario de registro */
    public function showRegisterForm()
    {
        return view('registro_usuario'); // resources/views/registro_usuario.blade.php
    }

    /** POST /registro: procesa el registro */
    public function register(Request $request)
    {
        $request->validate([
            'apodo'      => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6',
            'nombre'     => 'required|string|max:255',
            'apellidos'  => 'required|string|max:255',
            // Solo Visitante o Autor en el select
            'rol'        => 'required|in:Visitante,Autor',
            'fotoPerfil' => 'nullable|image|max:5120',
            'admin_key'  => 'nullable|string',
        ]);

        // Rol base desde el select
        $rol = $request->rol;

        // Si puso la clave correcta, lo ascendemos a Administrador
        if ($request->filled('admin_key') && $request->admin_key === 'adminreutiliza') {
            $rol = 'Administrador';
        }

        // Subida de foto (opcional)
        $ruta = null;
        if ($request->hasFile('fotoPerfil')) {
            $file = $request->file('fotoPerfil');
            $name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('imagenes/perfil'), $name);
            $ruta = 'imagenes/perfil/'.$name;
        }

        // Crear usuario con el $rol final
        User::create([
            'apodo'       => $request->apodo,
            'email'       => $request->email,
            'password'    => \Illuminate\Support\Facades\Hash::make($request->password),
            'nombre'      => $request->nombre,
            'apellidos'   => $request->apellidos,
            'rol'         => $rol, // ← aquí va el rol ya promovido si corresponde
            'foto_perfil' => $ruta,
        ]);

        return redirect()->route('home')->with('success', 'Cuenta creada correctamente.');
    }

    /** GET /login: muestra el formulario de login */
    public function showLoginForm()
    {
        return view('login_usuario'); // resources/views/login_usuario.blade.php
    }

    /** POST /login: procesa el login (por apodo + password) */
    public function login(Request $request)
    {
        $request->validate([
            'apodo'    => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('apodo', $request->apodo)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->esAdmin()) {
                return redirect()->route('ad.index');
            }
            return redirect()->route('home');
        }

        return back()->with('error', 'Usuario o contraseña incorrecta.');
    }

    /** GET /logout: cierra la sesión */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Has cerrado tu sesión');
    }

    public function miCuenta()
    {
        $user = Auth::user();

        // Relaciones: ideas del autor y opiniones del autor
        $ideas = $user->ideas()->latest()->get();
        $opiniones = $user->opiniones()
            ->with(['idea', 'responde'])
            ->latest()
            ->get();

        // Reutilizamos tu vista existente
        return view('VerDatosUsuarioSR', compact('user', 'ideas', 'opiniones'));
    }

    public function miCuentaModificar(Request $request)
    {
        $user = Auth::user();

        // Validación
        $request->validate([
            'apodo'       => 'nullable|string|max:255|unique:users,apodo,' . $user->id,
            'email'       => 'nullable|email|unique:users,email,' . $user->id,
            'nombre'      => 'nullable|string|max:255',
            'apellidos'   => 'nullable|string|max:255',
            'rol'         => 'nullable|in:Visitante,Autor,Administrador', // lo filtramos abajo
            'fotoPerfil'  => 'nullable|image|max:5120',
            'pwdActual'   => 'nullable|string|min:6',
            'pwd'         => 'nullable|string|min:6|confirmed',
        ]);

        // Campos básicos opcionales (estos puede cambiarlos el propio usuario)
        foreach (['apodo','email','nombre','apellidos'] as $campo) {
            if ($request->filled($campo)) {
                $user->{$campo} = $request->{$campo};
            }
        }

        // CAMBIO DE ROL: solo si quien modifica es admin
        if ($request->filled('rol') && $user->esAdmin()) {
            $user->rol = $request->rol;
        }

        // Foto de perfil (opcional)
        if ($request->hasFile('fotoPerfil')) {
            $f = $request->file('fotoPerfil');
            $name = time().'.'.$f->getClientOriginalExtension();
            $f->move(public_path('imagenes/perfil'), $name);
            $user->foto_perfil = 'imagenes/perfil/'.$name;
        }

        // Cambio de contraseña (opcional, exige contraseña actual correcta)
        if ($request->filled('pwd') || $request->filled('pwdActual')) {
            if (
                !$request->filled('pwdActual') ||
                !\Illuminate\Support\Facades\Hash::check($request->pwdActual, $user->password)
            ) {
                return back()->with('error', 'La contraseña actual no es correcta.');
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->pwd);
        }

        $user->save();

        return redirect()->route('usuario.cuenta')->with('success', 'Perfil actualizado correctamente.');
    }

    public function miCuentaEliminar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'pwd'          => 'required|string|min:6',
            'confirmarPwd' => 'required|same:pwd',
            'casilla'      => 'required',
        ]);

        if (!Hash::check($request->pwd, $user->password)) {
            return back()->with('error', 'La contraseña no es correcta.');
        }

        Auth::logout();
        $user->delete();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }

    public function listarAutores()
    {
        $autores = \App\Models\User::query()
            ->where('rol', 'Autor')
            ->withCount('ideas')
            ->with(['ideas' => function ($q) {
                $q->latest(); // opcional
            }])
            ->orderBy('apodo')
            ->paginate(12);

        return view('autores_lista', compact('autores'));
    }

    public function perfil($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Cargamos ideas y opiniones del usuario visitado
        $ideas = $user->ideas()->latest()->get();
        $opiniones = $user->opiniones()
            ->with(['idea', 'responde'])
            ->latest()
            ->get();

        // Reutiliza la vista de perfil que ya usas
        return view('VerDatosUsuarioSR', compact('user', 'ideas', 'opiniones'));
    }
}
