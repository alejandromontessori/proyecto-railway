<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    /** Listado general (todas las opiniones raíz) */
    public function index()
    {
        $opiniones = Opinion::with([
            'autor', 'idea', 'responde',
            'respuestas.autor', 'respuestas.idea',
            'respuestas.respuestas.autor', 'respuestas.respuestas.idea',
        ])
            ->whereNull('id_respondido')
            ->latest()
            ->paginate(10);

        $ideas = Idea::select('id', 'nombre')->orderBy('nombre')->get();

        return view('verOpiniones', compact('opiniones', 'ideas'));
    }

    /** Opiniones de una idea concreta */
    public function porIdea($id)
    {
        $idea = Idea::findOrFail($id);

        $opiniones = Opinion::with([

            'autor','idea','responde',
            'respuestas.autor','respuestas.idea',
            'respuestas.respuestas.autor','respuestas.respuestas.idea',

        ])
            ->where('id_idea', $idea->id)
            ->whereNull('id_respondido')
            ->latest()
            ->paginate(10);

        return view('opiniones_por_idea', compact('idea', 'opiniones'));
    }

    /** Guardar una opinión */
    public function store(Request $request)
    {
        $request->validate([
            'texto'         => 'required|string|max:1000',
            'valoracion'    => 'required|integer|min:1|max:5',
            'id_idea'       => 'required|exists:ideas,id',
            'id_respondido' => 'nullable|exists:opiniones,id',
        ]);

        Opinion::create([
            'texto'         => $request->input('texto'),
            'valoracion'    => (int) $request->input('valoracion'),
            'id_idea'       => (int) $request->input('id_idea'),
            'id_autor'      => Auth::id(),
            'id_respondido' => $request->input('id_respondido'),
        ]);

        // Si el formulario venía desde la vista de "opiniones de una idea", volvemos allí:
        if ($request->boolean('redir_a_idea')) {
            return redirect()
                ->route('opiniones.porIdea', $request->id_idea)
                ->with('success', 'Opinión publicada correctamente.');
        }

        // En otro caso, volvemos al listado general
        return redirect()
            ->route('opiniones')
            ->with('success', 'Opinión publicada correctamente.');
    }
}
