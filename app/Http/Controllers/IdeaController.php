<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorito;
use App\Models\Idea;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $grupo = $request->query('tipo'); // 'todas' | 'madera' | 'plastico-metal' | 'tejidos' | 'carton' | null

        $tipoFilter = $grupo === 'todas' ? null : $grupo;
        if ($tipoFilter === 'metalplastico') {
            $tipoFilter = 'plastico-metal';
        }

        $ideas = Idea::with('autor')
            ->withCount('opiniones')
            ->when($tipoFilter, function ($q) use ($tipoFilter) {
                if ($tipoFilter === 'plastico-metal') {
                    $q->whereIn('tipo', ['plastico-metal', 'metalplastico']);
                } else {
                    $q->where('tipo', $tipoFilter);
                }
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $favoritasIds = [];
        if (Auth::check()) {
            $favoritasIds = Favorito::where('id_usuario', Auth::id())
                ->pluck('id_idea')
                ->all();
        }

        return view('ideas', [
            'ideas' => $ideas,
            'tipo'  => $grupo,
            'favoritasIds' => $favoritasIds, // <-- AÑADIDO
        ]);
    }

    /** CREAR (desde perfil o donde quieras) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo'        => 'required|string|in:madera,plastico-metal,tejidos,carton',
            'fotoIdea'    => 'nullable|image|max:2048',
        ]);

        $ruta = null;
        if ($request->hasFile('fotoIdea')) {
            $file = $request->file('fotoIdea');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $name);
            $ruta = 'imagenes/' . $name;
        }

        Idea::create([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'tipo'        => $data['tipo'],
            'fotoIdea'    => $ruta,
            'id_autor'    => Auth::id(),
        ]);

        return back()->with('success', 'Idea creada');
    }

    /** DETALLE por título */
    public function mostrarDetalle($titulo)
    {
        $tituloDecod = urldecode($titulo);

        $idea = Idea::whereRaw('LOWER(nombre) = ?', [strtolower($tituloDecod)])
            ->with('autor')
            ->first();

        if (!$idea) {
            return redirect()->route('ideas')->with('error', 'Idea no encontrada.');
        }

        return view('idea_detalle', compact('idea'));
    }

    /** BUSCAR por nombre */
    public function buscar(Request $request)
    {
        $q = strtolower(trim($request->query('query', '')));


        $favoritasIds = [];
        if (Auth::check()) {
            $favoritasIds = Favorito::where('id_usuario', Auth::id())
                ->pluck('id_idea')
                ->all();
        }

        return view('ideas', [
            'ideas' => $ideas,
            'tipo'  => null,
            'favoritasIds' => $favoritasIds, // <-- AÑADIDO
        ]);
    }

    /** FAVORITOS: alternar */
    public function alternarFavorito($id)
    {
        $idea = Idea::findOrFail($id);

        $fav = Favorito::where('id_usuario', Auth::id())
            ->where('id_idea', $idea->id)
            ->first();

        if ($fav) {
            $fav->delete();

            if (request()->input('from') === 'favoritas') {
                return redirect()
                    ->route('ideas.favoritas')
                    ->with('success', 'Tu idea favorita ha sido eliminada.');
            }

            return back()->with('fav_msg', 'Quitado de tus favoritos.');
        }

        Favorito::create([
            'id_usuario' => Auth::id(),
            'id_idea'    => $idea->id,
        ]);

        return back()->with('fav_msg', 'Añadido a tus favoritos.');
    }

    /** LISTAR favoritas del usuario */
    public function verFavoritos()
    {
        $ideas = Favorito::with('idea')
            ->where('id_usuario', Auth::id())
            ->latest()
            ->get()
            ->pluck('idea')
            ->filter();

        return view('ideas_favoritas', compact('ideas'));
    }

    /** ACTUALIZAR idea (solo autor o admin) */
    public function update(Request $request, $id)
    {
        $idea = Idea::findOrFail($id);

        // permiso: autor de la idea o admin
        if (Auth::id() !== $idea->id_autor && !(Auth::user()?->esAdmin())) {
            return back()->with('error', 'No autorizado.');
        }

        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo'        => 'required|string|in:madera,plastico-metal,tejidos,carton',
            'fotoIdea'    => 'nullable|image|max:2048',
        ]);

        // si hay nueva foto, sustituimos
        if ($request->hasFile('fotoIdea')) {
            // opcional: borrar la anterior
            if ($idea->fotoIdea && file_exists(public_path($idea->fotoIdea))) {
                @unlink(public_path($idea->fotoIdea));
            }

            $file = $request->file('fotoIdea');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $name);
            $idea->fotoIdea = 'imagenes/' . $name;
        }

        $idea->nombre      = $data['nombre'];
        $idea->descripcion = $data['descripcion'];
        $idea->tipo        = $data['tipo'];
        $idea->save();

        return back()->with('success', 'Idea actualizada');
    }

    /** BORRAR idea (solo autor o admin) */
    public function destroy($id)
    {
        $idea = Idea::findOrFail($id);

        if (Auth::id() !== $idea->id_autor && !(Auth::user()?->esAdmin())) {
            return back()->with('error', 'No autorizado.');
        }

        // opcional: borrar foto
        if ($idea->fotoIdea && file_exists(public_path($idea->fotoIdea))) {
            @unlink(public_path($idea->fotoIdea));
        }

        $idea->delete();

        return back()->with('success', 'Idea eliminada');
    }
}
