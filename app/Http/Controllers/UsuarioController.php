<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    // Muestra la vista del formulario de registro
    public function verRegistro()
    {
        return view('registro_usuario');
    }

    // Procesa el formulario de registro
    public function registrarse(Request $request)
    {
        // Comprobar si el nombre de usuario o el correo ya existen
        $usuarioExistente = Usuario::where('apodo', $request->apodo)
            ->orWhere('email', $request->email)
            ->exists();

        if ($usuarioExistente) {
            // Si el usuario o el correo ya existen, redirigir a la vista de error o mensaje de error
            return redirect()->route('login')->with('error', 'Usuario o mail ya registrado.');
        }

        // Validar los datos del formulario
        $validated = $request->validate([
            'apodo' => 'required|max:25', // Asegurarse de que el nombre de usuario sea único
            'contrasena' => 'required|min:6', // Contraseña con al menos 6 caracteres
            'email' => 'required|email',  // Asegurarse de que el correo electrónico sea único
            // el resto opcional
            'nombre'        => 'nullable|string|max:255',
            'apellidos'     => 'nullable|string|max:255',
            'rol'           => 'required|in:Visitante,Autor,Administrador',
            'fotoPerfil'    => 'nullable|image|max:5120',
            'admin_key'     => 'nullable|string', // <-- clave oculta admin

        ]);

        // Crear un nuevo usuario en la base de datos
        // El mutador de password se encargará de hashear la contraseña automáticamente


        $rutaFoto = null;

        if ($request->hasFile('fotoPerfil')) {
            $foto = $request->file('fotoPerfil');
            $nombreFoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('imagenes'), $nombreFoto);
            $rutaFoto = 'imagenes/' . $nombreFoto;
        }
        // Si introducen la clave exacta, será admin
        $esAdmin = trim((string)$request->admin_key) === 'adminreutiliza' ? 1 : 0;
        $rol = $esAdmin ? 'Administrador' : $request->rol;
        Usuario::create([
            'apodo' => $request->apodo,
            'email' => $request->email,
            'password' => $request->contrasena,  // No necesitas aplicar Hash::make aquí
            'nombre' => $request->nombre, // Valor predeterminado para nombre
            'apellidos' => $request->apellidos, // Valor predeterminado para apellidos
            'esAdmin' => $esAdmin,
            'fotoPerfil' => $rutaFoto,
            'rol' => $rol,
        ]);



        // Redirigir al usuario a la página principal con un mensaje de éxito
        return redirect()->route('home')->with('success', 'Te has registrado con exito');
    }

    // Muestra el formulario de inicio de sesión
    public function verLogin()
    {
        return view('login_usuario');
    }

// Procesa el inicio de sesión



    public function loguearse(Request $request)
    {
        $validated = $request->validate([
            'apodo' => 'required|max:25',
            'contrasena' => 'required|min:6',
        ]);

        $usuario = Usuario::where('apodo', $request->apodo)->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
            Auth::login($usuario);

            if ($usuario->esAdmin) {
                // Va a la ruta de bienvenida de administradores
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('login')->with('error', 'Usuario o contraseña incorrecta.');
        }
    }


    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('success', 'Has cerrado tu sesion');
    }



    // Muestra la cuenta del usuario autenticado

    public function miCuenta()
    {
        if (!Auth::check()) {
            return response("<h1>Acceso denegado. Debes iniciar sesión.</h1>", 400);
        }

        $usuario = Auth::user();
        $ideas = $usuario->ideas; // sin paginación
        $comentarios = $usuario->comentarios()->get(); // sin paginación
        //$comentarios = $usuario->comentarios()->with('receta')->get(); // sin paginación
        return view('VerDatosUsuarioSR', compact('usuario', 'ideas', 'comentarios'));
    }
    //Listar usuarios

    public function listarUsuarios()
    {
        $usuarios = Usuario::paginate(3);
        return view('usuarios_lista', compact('usuarios'));
    }


    // Muestra el perfil de un usuario


    public function perfil($id)
    {
        $usuario = Usuario::findOrFail($id);
        $ideas = $usuario->ideas; // sin paginación
        $comentarios = $usuario->comentarios()->get(); // sin paginación
       //$comentarios = $usuario->comentarios()->with('receta')->get(); // sin paginación

        return view('VerDatosUsuarioSR', compact('usuario', 'ideas', 'comentarios'));
    }

    public function verIdeasUsuario($id){
        $usuario = Usuario::with('recetas')->findOrFail($id);
        return view('usuarioIdeas', compact('usuario'));
    }




    public function miCuentaEliminar(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }

        $usuario = Auth::user(); // Obtener el usuario autenticado

        $request->validate([
            'pwd' => 'required',
            'confirmarPwd' => 'required|same:pwd',
            'casilla' => 'required'
        ]);

        // Verificar si la contraseña es correcta
        if (!Hash::check($request->pwd, $usuario->contrasena)) {
            return back()->with('error', 'La contraseña es incorrecta o no coincide.');
        }

        // Eliminar cuenta
        $usuario->delete();

        // Cerrar sesión y redirigir
        Auth::logout();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }


    public function miCuentaModificar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }

        //$usuario = Auth::user();

        $usuario = Usuario::findOrFail(Auth::id());

        // Validar los datos de entrada
        $request->validate([
            'apodo' => 'nullable|max:25',
            'email' => 'nullable|email',
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'experiencia' => 'nullable|string',
            'fotoPerfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pwdActual' => 'nullable|string|min:6',
            'pwd' => 'nullable|string|min:6|confirmed',
        ]);

        // Comprobación de campos únicos
        if ($request->filled('apodo') && $request->apodo !== $usuario->apodo) {
            if (Usuario::where('apodo', $request->apodo)->where('id', '!=', $usuario->id)->exists()) {
                return back()->with('error', 'El nombre de usuario ya está en uso.');
            }
        }

        if ($request->filled('email') && $request->email !== $usuario->email) {
            if (Usuario::where('email', $request->email)->where('id', '!=', $usuario->id)->exists()) {
                return back()->with('error', 'El correo electrónico ya está en uso.');
            }
        }

        $datosActualizados = [];

        if ($request->filled('apodo')) {
            $datosActualizados['apodo'] = $request->apodo;
        }
        if ($request->filled('email')) {
            $datosActualizados['email'] = $request->email;
        }
        if ($request->filled('nombre')) {
            $datosActualizados['nombre'] = $request->nombre;
        }
        if ($request->filled('apellidos')) {
            $datosActualizados['apellidos'] = $request->apellidos;
        }
        if ($request->filled('experiencia')) {
            $datosActualizados['experiencia'] = $request->experiencia;
        }

        if ($request->hasFile('fotoPerfil')) {
            $file = $request->file('fotoPerfil');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'imagenes/' . $fileName;
            $file->move(public_path('imagenes'), $fileName);
            $datosActualizados['fotoPerfil'] = $filePath;
        }

        // Si se quiere cambiar la contraseña
        if ($request->filled('pwdActual') || $request->filled('pwd')) {
            if (!Hash::check($request->pwdActual, $usuario->contrasena)) {
                return back()->with('error', 'La contraseña actual no es correcta.');
            }

            $datosActualizados['contrasena'] = $request->pwd; // Se hashea automáticamente por el mutador en el modelo
        }

        if (!empty($datosActualizados)) {
            $usuario->update($datosActualizados);
            return redirect()->route('usuario.cuenta')->with('success', 'Perfil actualizado correctamente.');
        }

        return redirect()->route('usuario.cuenta')->with('info', 'No se realizaron cambios.');
    }


// Método para ver el formulario de edición (editarUsuario)

    public function formularioEditarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);

        if (!Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        return view('admin.editar_usuarios', compact('usuario'));
    }


    //Método para modificar datos (modificarUsuario)

    public function modificarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        if (!Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $request->validate([
            'apodo' => 'required|string|max:25',
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'experiencia' => 'nullable|string',
            'esAdmin' => 'required|boolean',
            'fotoPerfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'contrasena' => 'nullable|string|min:6|confirmed',
        ]);

        $usuario->apodo = $request->apodo;
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->email;
        $usuario->experiencia = $request->experiencia;
        $usuario->esAdmin = $request->esAdmin;

        if (Usuario::where('apodo', $request->apodo)->where('id', '!=', $usuario->id)->exists()) {
            return back()->with('error', 'El apodo ya está en uso por otro usuario.');
        }

        if (Usuario::where('email', $request->email)->where('id', '!=', $usuario->id)->exists()) {
            return back()->with('error', 'El correo ya está en uso por otro usuario.');
        }

        if ($request->hasFile('fotoPerfil')) {
            $file = $request->file('fotoPerfil');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $fileName);
            $usuario->fotoPerfil = 'imagenes/' . $fileName;
        }

        if ($request->filled('contrasena')) {
            $usuario->contrasena = $request->contrasena;
        }

        $usuario->save();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }


    //eliminar usuario
    public function eliminarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);

        if (!Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }



    public function verUsuariosAdmin()
    {
        if (!Auth::check() || !Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $usuarios = Usuario::paginate(3);
        return view('admin.usuarios_admin', compact('usuarios'));
    }





}


