<?php

use App\Http\Controllers\LoginController;
use App\Models\Receta;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AdministradorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\AdController;





//Route::get('/recetas', function () {
    //return view('welcome');
//});



Route::get('/receta/{id}', function ($id) {
    $receta=Receta::where('id',$id)->get();
     return "Te respondo con la receta ". $receta[0]->nombre;
})->where('id','[0-9]+');

/*Route::get('/index', function () {
    return view('index');
});*/



//Route::post('/login',[LoginController::class,"check_login"]);

//preguntar

//Route::post('/index',[LoginController::class,"index"]);


Route::get('/comentarios', [ComentarioController::class, 'index'])->name('comentarios');

//aqui


// Ruta de la página de inicio con el controlador InicioController, cuando yo entre en / se ejecutara el metodo index de InicioController y se le da un nombre mas sencillo para
// hacer cosas como     return redirect()->route('home');    o       <a href="{{ route('home') }}">Ir a la página principal</a>
Route::get('/', [InicioController::class, 'index'])->name('home');

//******* Ruta para mostrar todas las IDEAS
//Route::get('ideas', [RecetaController::class, 'index'])->name('ideas');

// Ruta para la búsqueda de recetas
//Route::get('buscar', [RecetaController::class, 'buscar'])->name('buscar');

//* Ruta para los detalles de una receta
//Route::get('receta/detalle/{titulo}', [RecetaController::class, 'mostrarDetalle'])->name('receta.detalle');

Route::get('receta/detalle/{titulo}', [RecetaController::class, 'mostrarDetalle'])
    ->where('titulo', '.*')
    ->name('receta.detalle');

//* Ruta guardar recetas

Route::post('/recetas/crear', [RecetaController::class, 'guardar'])->name('recetas.guardar')->middleware('auth');

// Ruta para el registro
//Route::get('registro', [UsuarioController::class, 'verRegistro'])->name('usuario.registro');
//Route::post('registro', [UsuarioController::class, 'registrarse'])->name('usuario.registrarse');

// Ruta para el login
Route::get('/login',  [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.enviar');

/*Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index')->middleware('auth');*/

// Ruta para cerrar sesión
Route::get('/logout', [UserController::class, 'logout'])->name('usuario.logout')->middleware('auth');


//*Ruta para añadir comentarios

//Route::get('/comentarios/crear', [ComentarioController::class, 'crear'])->name('comentarios.crear')->middleware('auth');

// Procesar envío del formulario
//Route::post('/comentarios/crear', [ComentarioController::class, 'guardar'])->name('comentarios.guardar')->middleware('auth');

//*Ruta para acceder a mi cuenta
Route::get('cuenta', [UserController::class, 'miCuenta'])->name('usuario.cuenta')->middleware('auth');
//*Ruta listar usuarios
Route::get('usuarios', [UserController::class, 'listarUsuarios'])->name('usuarios.lista');

//*Ruta para ver perfil de otro usuario
Route::get('usuario/{id}', [UserController::class, 'perfil'])->name('usuario.perfil')->middleware('auth');

//*Ruta usuario y sus recetas
//Route::get('usuario/{id}/recetas', [UsuarioController::class, 'verRecetasUsuario'])->name('usuario.recetas')->middleware('auth');

//Ruta para cuando no estas autorizado especifica, aunque auth ya deriva a la pagina de login por defecto
Route::get('/error', function () {return view('error');})->name('error.vista');

//*Ruta para eliminar mi cuenta

Route::post('cuenta/eliminar', [UserController::class, 'miCuentaEliminar'])->name('cuenta.eliminar')->middleware('auth');

// Ruta para modificar mi cuenta (put se usa en casos de actualizacion de datos)

Route::put('cuenta/modificar', [UserController::class, 'miCuentaModificar'])->name('cuenta.modificar')->middleware('auth');




//Ruta recetas administrador (no se si podran aprovechar)

/*Route::middleware(['auth'])->group(function () {
    Route::get('/admin/recetas', [RecetaController::class, 'gestionarTodas'])->name('recetas.unificada');
    Route::post('/recetas', [RecetaController::class, 'guardarReceta'])->name('recetas.guardar');
    Route::post('/recetas/{id}/actualizar', [RecetaController::class, 'actualizarDesdeUnificada'])->name('recetas.actualizarDesdeUnificada');
    Route::delete('/recetas/{id}', [RecetaController::class, 'eliminar'])->name('recetas.eliminar');
});*/

//*Ruta para administrador modificar usuarios

/*Route::get('admin/usuario/{id}/editar', [UsuarioController::class, 'formularioEditarUsuario'])
    ->name('admin.usuario.editar')
    ->middleware('auth');*/

/*Route::put('admin/usuario/{id}', [UsuarioController::class, 'modificarUsuario'])
    ->name('admin.usuario.modificar')
    ->middleware('auth');*/

/*Route::delete('admin/usuario/{id}', [UsuarioController::class, 'eliminarUsuario'])
    ->name('admin.usuario.eliminar')
    ->middleware('auth');*/

/*Route::get('admin/usuarios', [UsuarioController::class, 'verUsuariosAdmin'])
    ->name('admin.usuarios')
    ->middleware('auth');*/


/*Route::get('admin/comentarios', [ComentarioController::class, 'verComentariosAdmin'])
    ->name('admin.comentarios')
    ->middleware('auth');*/

//*Ruta admin modificar comentarios

/*Route::get('admin/comentarios', [ComentarioController::class, 'verComentariosAdmin'])
    ->name('admin.comentarios')
    ->middleware('auth');*/

/*Route::get('admin/comentario/{id}/editar', [ComentarioController::class, 'formularioEditarComentario'])
    ->name('admin.comentario.editar')
    ->middleware('auth');*/

/*Route::put('admin/comentario/{id}', [ComentarioController::class, 'modificarComentario'])
    ->name('admin.comentario.modificar')
    ->middleware('auth');*/

/*Route::delete('admin/comentario/{id}', [ComentarioController::class, 'eliminarComentario'])
    ->name('admin.comentario.eliminar')
    ->middleware('auth');*/


//*Ruta admin recetas

/*Route::middleware(['auth'])->group(function () {
    Route::get('/admin/recetas', [RecetaController::class, 'verRecetasAdmin'])->name('admin.recetas');
    Route::get('/admin/recetas/crear', [RecetaController::class, 'formularioCrearReceta'])->name('admin.receta.crear');
    Route::post('/admin/recetas', [RecetaController::class, 'guardarReceta'])->name('admin.receta.guardar');
    Route::get('/admin/recetas/{id}/editar', [RecetaController::class, 'formularioEditarReceta'])->name('admin.receta.editar');
    Route::put('/admin/recetas/{id}', [RecetaController::class, 'modificarReceta'])->name('admin.receta.modificar');
    Route::delete('/admin/recetas/{id}', [RecetaController::class, 'eliminarReceta'])->name('admin.receta.eliminar');
});*/

//*Ruta recetas favoritas

/*Route::post('/recetas/{id}/favorito', [RecetaController::class, 'toggleFavorito'])->name('recetas.favorito');*/
/*Route::get('/mis-favoritos', [RecetaController::class, 'verFavoritos'])->name('recetas.favoritas');*/

/*Route::get('/favoritos', [RecetaController::class, 'verFavoritos'])->name('recetas.favoritas')->middleware('auth');*/




//IDEAS//

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas');
Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store')->middleware('auth');

Route::get('/idea/detalle/{titulo}', [IdeaController::class, 'mostrarDetalle'])
    ->where('titulo', '.*')
    ->name('idea.detalle');

Route::get('/ideas/buscar', [IdeaController::class, 'buscar'])->name('ideas.buscar');
Route::put('/ideas/{id}',    [IdeaController::class, 'update'])->name('ideas.actualizar')->middleware('auth');
Route::delete('/ideas/{id}', [IdeaController::class, 'destroy'])->name('ideas.eliminar')->middleware('auth');

//MIGRACION//

Route::get('/recetas', fn () => redirect()->route('ideas'))->name('recetas');


//NUEVO USER

Route::get('/registro',  [UserController::class, 'showRegisterForm'])->name('registro.form');
Route::post('/registro', [UserController::class, 'register'])->name('registro.enviar');

//OPINIONES

Route::get('/opiniones', [OpinionController::class, 'index'])->name('opiniones');
Route::post('/opiniones/crear', [OpinionController::class, 'store'])
    ->name('opiniones.guardar')
    ->middleware('auth');


Route::post('/ideas/{id}/favorito', [IdeaController::class, 'alternarFavorito'])
    ->name('ideas.favorito')->middleware('auth');

Route::get('/mis-favoritos', [IdeaController::class, 'verFavoritos'])
    ->name('ideas.favoritas')->middleware('auth');

// Lista pública de autores
Route::get('/autores', [UserController::class, 'listarAutores'])->name('autores.lista');

//ADMIN


Route::middleware(['auth'])
    ->prefix('ad')       // URLs: /ad, /ad/usuarios, /ad/ideas, /ad/opiniones
    ->name('ad.')     // Nombres: admin.index, admin.usuarios, admin.ideas, admin.opiniones
    ->group(function () {
        Route::get('/',           [AdController::class, 'index'])->name('index');
        Route::get('/usuarios',   [AdController::class, 'usuarios'])->name('usuarios');
        Route::patch('/usuarios/{id}',  [AdController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
        Route::delete('/usuarios/{id}', [AdController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
        Route::get('/ideas',      [AdController::class, 'ideas'])->name('ideas');
        Route::get('/opiniones',  [AdController::class, 'opiniones'])->name('opiniones');
        Route::post('/opiniones', [AdController::class, 'crearOpinion'])->name('opiniones.crear');
        Route::put('/opiniones/{id}',    [AdController::class, 'actualizarOpinion'])->name('opiniones.actualizar');
        Route::delete('/opiniones/{id}', [AdController::class, 'eliminarOpinion'])->name('opiniones.eliminar');
        Route::get('/autores',    [AdController::class, 'autores'])->name('autores');
        Route::patch('/autores/{id}/rol', [AdController::class, 'cambiarRolAutor'])->name('autores.cambiarRol');
        Route::patch('/autores/{id}', [AdController::class, 'actualizarAutor'])->name('autores.actualizar');
        Route::delete('/autores/{id}', [AdController::class, 'eliminarAutor'])->name('autores.eliminar');

        Route::get('/usuarios/{id}', [AdController::class, 'verUsuario'])->name('usuarios.ver');

        Route::get('/autores/{id}', [AdController::class, 'verAutor'])->name('autores.ver');
});

// IDEAS-OPINIONES


Route::get('/ideas/{id}/opiniones', [OpinionController::class, 'porIdea'])
    ->name('opiniones.porIdea');
