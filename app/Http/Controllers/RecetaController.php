<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Receta;
use App\Models\Comentario;
use App\Models\Favorito;

class RecetaController extends Controller
{
    // Mostrar todas las recetas con su autor
    public function index()
    {
        $ideas = Receta::with('autor')->paginate(3); // Cargamos la relación 'autor'
        return view('ideas', compact('ideas'));
    }

//*Guardar receta creada

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'fotoReceta' => 'nullable|image|max:2048', // imagen opcional
        ]);

        $rutaFoto = null;

        if ($request->hasFile('fotoReceta')) {
            $archivo = $request->file('fotoReceta');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivo->move(public_path('Imagenes/recetas'), $nombreArchivo);
            $rutaFoto = 'Imagenes/recetas/' . $nombreArchivo;
        }

        Receta::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_autor' => Auth::id(),
            'fotoReceta' => $rutaFoto, // se guarda solo si se sube
            'valoracion' => 0,   // solo si este campo existe en tu tabla
        ]);

        return redirect()->route('recetas')->with('success', 'Receta creada correctamente.');
    }





    // Mostrar detalle de una receta por su título
    public function mostrarDetalle($titulo)
    {
        // Decodificar el título recibido por la URL (por si contiene espacios o símbolos codificados)
        $tituloDecodificado = urldecode($titulo);

        // Buscar la receta ignorando mayúsculas y minúsculas
        $receta = Receta::whereRaw('LOWER(nombre) = ?', [strtolower($tituloDecodificado)])
            ->with(['autor', 'comentarios.autor'])
            ->first();

        // Si no se encuentra, redirigir con mensaje de error
        if (!$receta) {
            return redirect()->route('recetas')->with('error', 'Receta no encontrada.');
        }

        // Calcular media de valoración
        $mediaValoracion = Comentario::where('id_receta', $receta->id)->avg('valoracion');
        $mediaValoracion = round($mediaValoracion, 2);

        // Devolver vista con la receta y su valoración
        return view('recetaDetallada', compact('receta', 'mediaValoracion'));
    }

    //Buscar receta

    public function buscar(Request $request)
    {
        $query = strtolower(trim($request->query('query')));

        // Buscar recetas por nombre
        $recetasPorNombre = Receta::whereRaw('LOWER(nombre) LIKE ?', ["%{$query}%"])
            ->with('autor')
            ->get();

        // Buscar recetas por ingredientes
        $recetasPorIngrediente = Receta::whereHas('ingredientes', function ($q) use ($query) {
            $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$query}%"]);
        })->with('autor')->get();

        // Unir y quitar duplicados
        $recetas = $recetasPorNombre->merge($recetasPorIngrediente)->unique('id');

        return view('verRecetas', compact('recetas'));
    }

//Administrador gestion de recetas
    public function verRecetasAdmin()
    {
        if (!Auth::check() || !Auth::user()->esAdmin) {
            return redirect()->route('home')->with('error', 'No autorizado.');
        }

        $recetas = Receta::paginate(3);
        return view('admin.recetas_admin', compact('recetas'));
    }

    public function formularioCrearReceta()
    {
        return view('admin.crear_receta');
    }

    public function guardarReceta(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fotoReceta' => 'nullable|image|max:2048',
        ]);

        $ruta = null;
        if ($request->hasFile('fotoReceta')) {
            $foto = $request->file('fotoReceta');
            $nombre = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('imagenes'), $nombre);
            $ruta = 'imagenes/' . $nombre;
        }

        Receta::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fotoReceta' => $ruta,
            'id_autor' => Auth::id(),
            'valoracion' => 0, // O lo que uses por defecto
        ]);

        return redirect()->route('admin.recetas')->with('success', 'Receta creada correctamente.');
    }

    public function formularioEditarReceta($id)
    {
        $receta = Receta::findOrFail($id);
        return view('admin.editar_receta', compact('receta'));
    }

    public function modificarReceta(Request $request, $id)
    {
        $receta = Receta::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fotoReceta' => 'nullable|image|max:2048',
        ]);

        $receta->nombre = $request->nombre;
        $receta->descripcion = $request->descripcion;

        if ($request->hasFile('fotoReceta')) {
            $foto = $request->file('fotoReceta');
            $nombre = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('imagenes'), $nombre);
            $receta->fotoReceta = 'imagenes/' . $nombre;
        }

        $receta->save();

        return redirect()->route('admin.recetas')->with('success', 'Receta actualizada correctamente.');
    }

    public function eliminarReceta($id)
    {
        $receta = Receta::findOrFail($id);
        $receta->delete();

        return redirect()->route('admin.recetas')->with('success', 'Receta eliminada correctamente.');
    }

    //recetas favoritas

    public function toggleFavorito($id)
    {
        $usuarioId = auth()->id();

        $favorito = Favorito::where('id_usuario', $usuarioId)
            ->where('id_receta', $id)
            ->first();

        if ($favorito) {
            $favorito->delete();
        } else {
            Favorito::create([
                'id_usuario' => $usuarioId,
                'id_receta' => $id
            ]);
        }

        return back();
    }

    public function verFavoritos()
    {
        $usuario = auth()->user();
        $favoritos = $usuario->favoritos()->with('receta')->get();

        $recetasFavoritas = $usuario->favoritos()->with('receta')->get()->pluck('receta');
        return view('favoritas', compact('recetasFavoritas'));
    }



}

