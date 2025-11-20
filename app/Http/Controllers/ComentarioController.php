<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Receta;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function index()
    {
        $comentarios = Comentario::with(['autor', 'receta','responde'])->latest()->paginate(3);
        $recetas = Receta::all(); // Pasar recetas a la vista

        return view('VerComentarios', compact('comentarios', 'recetas'));

    }

    public function guardar(Request $request)
    {
        $request->validate([
            'texto' => 'required|string',
            'valoracion' => 'required|integer|min:1|max:5',
            'id_receta' => 'required|exists:recetas,id',
            'id_respondido' => 'nullable|exists:comentarios,id',
        ]);

        Comentario::create([
            'texto' => $request->texto,
            'valoracion' => $request->valoracion,
            'id_receta' => $request->id_receta,
            'id_autor' => Auth::id(),
            'id_respondido' => $request->id_respondido,
        ]);

        return redirect()->route('comentarios')->with('success', 'Comentario publicado correctamente.');
    }




    // Mostrar lista de comentarios
    public function verComentariosAdmin()
    {
        if (!Auth::check() || !Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $comentarios = Comentario::with(['autor', 'receta', 'responde'])->paginate(3);

        return view('admin.comentarios_admin', compact('comentarios'));
    }

// Mostrar formulario de edición
    public function formularioEditarComentario($id)
    {
        if (!Auth::check() || !Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $comentario = Comentario::with(['receta', 'autor'])->findOrFail($id);
        $recetas = \App\Models\Receta::all(); // Por si quieres permitir cambiar receta
        $comentarios = Comentario::where('id', '!=', $id)->get(); // para elegir si responde a otro comentario

        return view('admin.editar_comentarios', compact('comentario', 'recetas', 'comentarios'));
    }

// Procesar modificación
    public function modificarComentario(Request $request, $id)
    {
        $comentario = Comentario::findOrFail($id);

        if (!Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $request->validate([
            'texto' => 'required|string|max:1000',
            'valoracion' => 'required|integer|min:1|max:5',
            'id_receta' => 'required|exists:recetas,id',
            'id_respondido' => 'nullable|exists:comentarios,id|different:' . $id,
        ]);

        $comentario->texto = $request->texto;
        $comentario->valoracion = $request->valoracion;
        $comentario->id_receta = $request->id_receta;
        $comentario->id_respondido = $request->id_respondido;
        $comentario->save();

        return redirect()->route('admin.comentarios')->with('success', 'Comentario actualizado correctamente.');
    }

// Eliminar comentario
    public function eliminarComentario($id)
    {
        $comentario = Comentario::findOrFail($id);

        if (!Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $comentario->delete();

        return redirect()->route('admin.comentarios')->with('success', 'Comentario eliminado correctamente.');
    }

}
