<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autores (Admin)</title>
    <style>
        :root{--ink:#0e1a18;--muted:#5b6f6d;--brand:#86b7b2;--brand-dark:#255d47;--bg:#ffffff;}
        body{font-family:Arial,sans-serif;background:var(--bg);color:var(--ink);margin:0}
        nav{background:rgba(73,72,71,.8);text-align:center;padding:10px 0;margin-bottom:10px}
        nav a{display:inline-block;color:#fff;padding:12px 30px;text-decoration:none;font-size:18px;margin:0 15px;transition:.3s}
        nav a:hover{background:var(--brand);border-radius:5px;transform:scale(1.05)}
        .wrapper{max-width:1100px;margin:0 auto;padding:18px}

        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        @media (max-width:900px){.grid{grid-template-columns:1fr}}

        .card{
            background: rgba(255,255,255,0.65); /* translúcido */
            border: 1px solid rgba(255,255,255,0.35); /* borde más suave */
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,.06);
        }
        .fila{display:flex;gap:14px;align-items:flex-start;flex-wrap:wrap}
        .avatar{width:72px;height:72px;border-radius:50%;object-fit:cover;background:#eee}
        .muted{color:var(--muted)}
        .inputs{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:10px}
        @media (max-width:900px){.inputs{grid-template-columns:1fr}}

        label{display:block;font-weight:700;margin-bottom:4px}
        input, select{width:100%;padding:9px;border:1px solid #ddd;border-radius:8px;background:#fff}

        .row-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
        .btn{display:inline-block;background:var(--brand);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
        .btn:hover{background:var(--brand-dark)}
        .btn.secondary{background:#e7efee;color:var(--brand-dark)}
        .btn.secondary:hover{background:#dfe8e7}
        .btn.danger{background:#c0392b}
        .btn.danger:hover{background:#922b21}

        .flash{padding:10px 12px;border-radius:8px;margin:10px 0}
        .flash.ok{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724}
        .flash.err{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000}

        body{
            font-family:Arial,sans-serif;
            color:var(--ink);
            margin:0;

            /* no uses 'background:' aquí para no resetear la imagen */
            background-color: var(--bg);
            background-image: url('{{ asset('imagenes/tejidos/cupcakes.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('ad.index') }}">Panel</a>
    <a href="{{ route('ad.usuarios') }}">Usuarios</a>
    <a href="{{ route('ad.autores') }}">Autores</a>
    <a href="{{ route('ad.ideas') }}">Ideas</a>
    <a href="{{ route('ad.opiniones') }}">Opiniones</a>
    <a href="{{ route('usuario.logout') }}">Cerrar sesión</a>
</nav>

<div class="wrapper">
    <h1 style="margin:6px 0 12px;">Autores</h1>

    @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="flash err">{{ session('error') }}</div> @endif

    @if($autores->count())
        <section class="grid">
            @foreach($autores as $autor)
                <article class="card">
                    <div class="fila">
                        <img class="avatar" src="{{ $autor->foto_perfil ? asset($autor->foto_perfil) : asset('imagenes/placeholder.jpg') }}" alt="Avatar">
                        <div>
                            <h3 style="margin:0 0 6px">{{ $autor->apodo }}</h3>
                            <div class="muted">Alta: {{ $autor->created_at?->format('d/m/Y') }}</div>
                            <div class="muted">Actualizado: {{ $autor->updated_at?->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    {{-- Formulario mínimo para actualizar nombre, apellidos, email y rol --}}
                    <form action="{{ route('ad.autores.actualizar', $autor->id) }}" method="POST" style="margin-top:12px">
                        @csrf
                        @method('PATCH')
                        <div class="inputs">
                            <div>
                                <label>Nombre</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $autor->nombre) }}">
                            </div>
                            <div>
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $autor->apellidos) }}">
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $autor->email) }}">
                            </div>
                            <div>
                                <label>Rol</label>
                                <select name="rol">
                                    <option value="Visitante" {{ $autor->rol==='Visitante' ? 'selected':'' }}>Visitante</option>
                                    <option value="Autor" {{ $autor->rol==='Autor' ? 'selected':'' }}>Autor</option>
                                    <option value="Administrador" {{ $autor->rol==='Administrador' ? 'selected':'' }}>Administrador</option>
                                </select>
                            </div>
                        </div>

                        <div class="row-actions">
                            <button type="submit" class="btn">Guardar</button>
                            <a class="btn secondary" href="{{ route('ad.autores.ver', $autor->id) }}">Ver perfil</a>
                        </div>
                    </form>

                    {{-- Eliminar autor (individual) --}}
                    <form action="{{ route('ad.autores.eliminar', $autor->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a {{ $autor->apodo }}? Se eliminarán también sus ideas y opiniones.');" style="margin-top:8px">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn danger">Eliminar autor</button>
                    </form>
                </article>
            @endforeach
        </section>

        @if($autores->total() > $autores->perPage())
            <div style="display:flex;justify-content:center;align-items:center;gap:10px;margin-top:16px">
                <a class="btn" href="{{ $autores->previousPageUrl() }}" @if($autores->onFirstPage()) style="pointer-events:none;opacity:.5" @endif>← Anterior</a>
                <span>Página {{ $autores->currentPage() }} de {{ $autores->lastPage() }}</span>
                <a class="btn" href="{{ $autores->nextPageUrl() }}" @if(!$autores->hasMorePages()) style="pointer-events:none;opacity:.5" @endif>Siguiente →</a>
            </div>
        @endif
    @else
        <p class="muted">No hay autores.</p>
    @endif
</div>
</body>
</html>
