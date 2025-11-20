<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Idea;
use App\Models\Opinion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdController extends Controller
{
    public function index()
    {
        // Totales globales (sin filtrar por autor)
        $usuariosCount  = User::count();
        $autoresCount   = User::where('rol', 'Autor')->count();
        $ideasCount     = Idea::count();
        $opinionesCount = Opinion::count();

        // Recientes (solo para mostrar algo en el panel)
        $usuariosRecientes  = User::latest()->take(5)->get();
        $autoresRecientes = User::where('rol','Autor')->latest()->take(5)->get();
        $ideasRecientes     = Idea::with('autor')->latest()->take(5)->get();
        $opinionesRecientes = Opinion::with(['autor','idea'])->latest()->take(5)->get();

        return view('adindex', compact(
            'usuariosCount','autoresCount','ideasCount','opinionesCount',
            'usuariosRecientes','autoresRecientes','ideasRecientes','opinionesRecientes'
        ));
    }

    public function usuarios()
    {
        $usuarios = User::orderBy('apodo')->paginate(15);
        return view('adusuarios', compact('usuarios'));
    }

    public function ideas()
    {
        $ideas = Idea::with('autor')->latest()->paginate(12);
        return view('adideas', compact('ideas'));
    }

    public function opiniones()
    {
        $opiniones = Opinion::with(['autor','idea','responde'])->latest()->paginate(15);
        $autoresAll = User::orderBy('apodo')->get(['id','apodo','email']);
        $ideasAll   = Idea::orderBy('nombre')->get(['id','nombre']);
        return view('adopiniones', compact('opiniones','autoresAll','ideasAll'));

    }

    public function crearOpinion(Request $request)
    {
        if (!auth()->user()?->esAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'id_autor' => ['required', 'integer', 'exists:users,id'],
            'id_idea' => ['required', 'integer', 'exists:ideas,id'],
            'id_respondido' => ['nullable', 'integer', 'exists:opinions,id'],
            'valoracion' => ['required', 'integer', 'min:1', 'max:5'],
            'texto' => ['required', 'string', 'max:5000'],
        ]);

        $op = new Opinion();
        $op->id_autor = $data['id_autor'];
        $op->id_idea = $data['id_idea'];
        $op->id_respondido = $data['id_respondido'] ?? null;
        $op->valoracion = $data['valoracion'];
        $op->texto = $data['texto'];
        $op->save();

        return back()->with('success', "Opinión #{$op->id} creada correctamente.");
    }


    public function actualizarOpinion(Request $request, $id)
    {
        if (!auth()->user()?->esAdmin()) {
            abort(403);
        }

        $op = Opinion::findOrFail($id);

        $data = $request->validate([
            'id_autor'    => ['required','integer','exists:users,id'],
            'id_idea'     => ['required','integer','exists:ideas,id'],
            'id_respondido' => ['nullable','integer','exists:opinions,id','not_in:'.$op->id], // evitar autoreferencia
            'valoracion'  => ['required','integer','min:1','max:5'],
            'texto'       => ['required','string','max:5000'],
        ]);

        $op->id_autor    = $data['id_autor'];
        $op->id_idea     = $data['id_idea'];
        $op->id_respondido = $data['id_respondido'] ?? null;
        $op->valoracion  = $data['valoracion'];
        $op->texto       = $data['texto'];
        $op->save();

        return back()->with('success', "Opinión #{$op->id} actualizada correctamente.");
    }

    public function eliminarOpinion($id)
    {
        if (!auth()->user()?->esAdmin()) {
            abort(403);
        }

        $opinion = Opinion::findOrFail($id);

        \DB::transaction(function () use ($opinion) {
            // Si otras opiniones respondían a esta, desengancharlas
            Opinion::where('id_respondido', $opinion->id)
                ->update(['id_respondido' => null]);

            $opinion->delete();
        });

        return back()->with('success', 'Opinión eliminada correctamente.');
    }


    public function autores()
    {
        $autores = \App\Models\User::where('rol', 'Autor')
            ->orderBy('apodo')
            ->paginate(15);

        // Si quieres además cuántas ideas tiene cada autor:
        // ->withCount('ideas')

        return view('adautores', compact('autores'));
    }

    public function cambiarRolAutor(Request $request, $id)
    {
        // Solo admin
        if (!Auth::user()?->esAdmin()) {
            return back()->with('error', 'No autorizado.');
        }

        $data = $request->validate([
            'rol' => 'required|in:Visitante,Autor,Administrador',
        ]);

        $user = User::findOrFail($id);
        $user->rol = $data['rol'];
        $user->save();

        return back()->with('success', "Rol actualizado a {$data['rol']} para {$user->apodo}.");
    }

    public function eliminarAutor($id)
    {
        // Solo admin
        if (!\Illuminate\Support\Facades\Auth::user()?->esAdmin()) {
            return back()->with('error', 'No autorizado.');
        }

        $autor = User::findOrFail($id);

        if ($autor->rol !== 'Autor') {
            return back()->with('error', 'El usuario seleccionado no es Autor.');
        }

        // Evitar que el admin se borre a sí mismo por accidente si también es Autor
        if ($autor->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo desde aquí.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($autor) {
            // Si tus FKs NO tienen cascade, borra dependencias mínimas:
            \App\Models\Idea::where('id_autor', $autor->id)->delete();
            \App\Models\Opinion::where('id_autor', $autor->id)->delete();

            $autor->delete();
        });

        return back()->with('success', 'Autor eliminado correctamente.');
    }

    public function verUsuario($id)
    {
        // Solo admins
        if (!\Illuminate\Support\Facades\Auth::user()?->esAdmin()) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $user = \App\Models\User::with(['ideas' => function($q){ $q->latest(); }, 'opiniones' => function($q){
            $q->with(['idea','responde'])->latest();
        }])->findOrFail($id);

        return view('adusuario_show', compact('user'));
    }

    public function verAutor($id)
    {
        // Solo admins
        if (!\Illuminate\Support\Facades\Auth::user()?->esAdmin()) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        // Carga el usuario + relaciones (aunque no fuerces que sea Autor,
        // la vista es de admin y la usamos para autores)
        $user = \App\Models\User::with([
            'ideas' => function($q){ $q->latest(); },
            'opiniones' => function($q){ $q->with(['idea','responde'])->latest(); }
        ])->findOrFail($id);

        return view('adautor_show', compact('user'));
    }

    public function actualizarAutor(\Illuminate\Http\Request $request, $id)
    {
        if (!auth()->user()?->esAdmin()) {
            abort(403);
        }

        $autor = User::findOrFail($id);

        // Evitamos que un admin se rompa a sí mismo el login cambiando su email a uno usado:
        $data = $request->validate([
            'nombre'    => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255|unique:users,email,' . $autor->id,
            'rol'       => 'required|in:Visitante,Autor,Administrador',
        ]);

        // Asignaciones (solo esas 4 cosas)
        if (array_key_exists('nombre', $data))     $autor->nombre    = $data['nombre'];
        if (array_key_exists('apellidos', $data))  $autor->apellidos = $data['apellidos'];
        if (array_key_exists('email', $data))      $autor->email     = $data['email'];
        $autor->rol = $data['rol'];

        $autor->save();

        return back()->with('success', 'Autor actualizado correctamente.');
    }

    public function actualizarUsuario(Request $request, $id)
    {
        if (!auth()->user()?->esAdmin()) {
            return back()->with('error', 'No autorizado.');
        }

        $user = User::findOrFail($id);

        $data = $request->validate([
            'nombre'    => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'rol'       => 'required|in:Visitante,Autor,Administrador',
        ]);

        if (array_key_exists('nombre', $data))    $user->nombre    = $data['nombre'];
        if (array_key_exists('apellidos', $data)) $user->apellidos = $data['apellidos'];
        if (array_key_exists('email', $data))     $user->email     = $data['email'];
        $user->rol = $data['rol'];

        $user->save();

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminarUsuario($id)
    {
        if (!auth()->user()?->esAdmin()) {
            return back()->with('error', 'No autorizado.');
        }

        $usuario = User::findOrFail($id);

        // Evitar que el admin se borre a sí mismo
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo desde aquí.');
        }

        DB::transaction(function () use ($usuario) {
            \App\Models\Idea::where('id_autor', $usuario->id)->delete();
            \App\Models\Opinion::where('id_autor', $usuario->id)->delete();
            $usuario->delete();
        });

        return redirect()->route('ad.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }




}
