<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        /* Paleta y estética de registro_usuario */
        :root{
            --c-text:#333333;
            --c-primary:#00cccc;
            --c-primary-hover:#0099a1;
            --c-link:#007b8f;
            --c-card-bg: rgba(255,255,255,0.65);
            --c-border:#cccccc;
            --c-darkbar: rgba(73,72,71,0.8);
        }

        * { box-sizing: border-box; }
        body{
            margin:0; font-family: Arial, sans-serif; color:var(--c-text);
            /* Fondo cupcakes como en perfil */
            background-image: url('{{ asset('imagenes/tejidos/cupcakes.jpg') }}');
            background-size: cover; background-position: center; background-attachment: fixed;
            min-height:100vh; display:flex; flex-direction:column;
            padding-top: 70px;
        }

        /* Nav consistente con index/registro */
        nav{
            background-color: var(--c-darkbar);
            text-align:center; padding:10px 0;
            position: fixed; top:0; left:0; right:0; z-index: 5;
        }
        nav a{
            display:inline-block; color:#fff; text-decoration:none; padding:12px 30px; margin:0 8px;
            font-size:18px; transition: background-color .25s, transform .25s;
        }
        nav a:hover{ background:var(--c-primary); border-radius:6px; transform:scale(1.06); }

        .wrapper{ max-width:1200px; margin:0 auto; padding: 0 18px 28px; width:100%; flex:1; }

        /* Encabezado */
        h1{ margin: 10px 0 16px; color: var(--c-link); }
        .top{ display:flex;justify-content:space-between;align-items:center;gap:12px;margin:6px 0 12px; }

        /* Botón secundario adaptado a la paleta */
        .btn{
            display:inline-block; text-decoration:none; cursor:pointer; background: var(--c-primary); color:#fff; border:none;
            padding:10px 14px; border-radius:8px; font-weight:600;
            transition: background-color .2s ease;
        }
        .btn:hover{ background: var(--c-primary-hover); }
        .btn[disabled]{ opacity:.55; pointer-events:none; }
        .btn.secondary{ background:#ffffff; color: var(--c-link); border:1px solid var(--c-border); }
        .btn.secondary:hover{ background:#f6ffff; }

        /* Flash (mantenemos tu estilo de éxito si lo usas aquí) */
        .flash{
            background:#e7f7ed; border:1px solid #bfe8cd; color:#155724; padding:10px 14px; border-radius:8px; margin:10px 0;
        }

        /* Autor card con tarjeta translúcida como registro */
        .autor-card{
            background: var(--c-card-bg);
            border:1px solid var(--c-border);
            border-radius:14px;
            padding:16px; margin: 14px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
        }
        .autor-head{
            display:flex; align-items:center; gap:14px; margin-bottom:10px;
        }
        .avatar{
            width:64px; height:64px; border-radius:50%; object-fit:cover; background:#eee; border:2px solid var(--c-primary);
        }
        .autor-meta .apodo{ font-size: 20px; font-weight: 700; margin:0; color: var(--c-link); }
        .autor-meta .detalle{ color:#555; font-size: 14px; margin-top:2px; }

        /* Grid de ideas dentro de la tarjeta (sin cambios de estructura) */
        .ideas-grid{
            display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-top: 10px;
        }
        @media (max-width: 980px){ .ideas-grid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width: 620px){ .ideas-grid{ grid-template-columns: 1fr; } }

        .idea-card{
            border:1px solid var(--c-border); border-radius:10px; overflow:hidden; text-decoration:none; color:inherit;
            display:flex; gap:10px; padding:8px; background:#fff; transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .idea-card:hover{ transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,.12); border-color:#bcdede; }
        .idea-thumb{
            width:110px; height:78px; border-radius:8px; object-fit:cover; background:#eee; border:1px solid #ddd;
            flex-shrink:0;
        }
        .idea-body{ display:flex; flex-direction:column; gap:4px; overflow:hidden; }
        .idea-title{ font-weight:700; font-size:16px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color: var(--c-link); }
        .idea-meta{ font-size:12px; color:#666; }
        .idea-desc{ font-size:13px; color:#333; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

        /* Paginación adaptada */
        .pager{ display:flex; justify-content:center; align-items:center; gap:10px; margin-top: 16px; color:#333; }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            <a href="{{ route('ad.index') }}">Administración</a>
        @endif
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('opiniones') }}">Opiniones</a>
        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main class="wrapper">
    <div class="top">
        <h1 style="margin:0;">Autores</h1>
        <a class="btn secondary" href="{{ route('home') }}">← Inicio</a>
    </div>

    {{-- Lista de autores (paginada o no) --}}
    @forelse($autores as $autor)
        <section class="autor-card">
            <div class="autor-head">
                <img class="avatar"
                     src="{{ $autor->foto_perfil ? asset($autor->foto_perfil) : asset('imagenes/placeholder.jpg') }}"
                     alt="Avatar de {{ $autor->apodo }}">
                <div class="autor-meta">
                    <p class="apodo">{{ $autor->apodo }}</p>
                    <p class="detalle">
                        Rol: {{ $autor->rol }} ·
                        Ideas publicadas: {{ $autor->ideas->count() }}
                    </p>
                </div>
            </div>

            @if($autor->ideas->isEmpty())
                <p style="color:#555; margin: 6px 0 2px;">Este autor todavía no tiene ideas publicadas.</p>
            @else
                <div class="ideas-grid">
                    @foreach($autor->ideas as $idea)
                        <a class="idea-card"
                           href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">
                            <img class="idea-thumb"
                                 src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/placeholder.jpg') }}"
                                 alt="Imagen de {{ $idea->nombre }}">
                            <div class="idea-body">
                                <div class="idea-title">{{ $idea->nombre }}</div>
                                <div class="idea-meta">
                                    {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}
                                    @if(!empty($idea->dificultad)) · {{ $idea->dificultad }} @endif
                                </div>
                                <div class="idea-desc">{{ \Illuminate\Support\Str::limit($idea->descripcion, 120) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @empty
        <p style="color:#555;">No hay autores disponibles.</p>
    @endforelse

    {{-- Paginación si $autores es LengthAwarePaginator --}}
    @if(method_exists($autores, 'total') && $autores->total() > $autores->perPage())
        <div class="pager">
            <a class="btn" href="{{ $autores->previousPageUrl() }}" @if($autores->onFirstPage()) disabled @endif>← Anterior</a>
            <span>Página {{ $autores->currentPage() }} de {{ $autores->lastPage() }}</span>
            <a class="btn" href="{{ $autores->nextPageUrl() }}" @if(!$autores->hasMorePages()) disabled @endif>Siguiente →</a>
        </div>
    @endif
</main>

<footer style="background: var(--c-darkbar); color:#fff; text-align:center; padding:20px 0;">
    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
</footer>

</body>
</html>
