<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ideas recicladas</title>
    <style>
        :root{
            --ink:#0e1a18;
            --muted:#5b6f6d;
            --brand:#00cccc;
            --brand-dark:#0099a1;
            --link:#007b8f;
            --card-glass: rgba(255,255,255,0.65);
            --border:#cccccc;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            color: var(--ink);
            margin: 0;
            min-height: 100vh; display: flex; flex-direction: column;
            padding-top: 0; /* nav sticky */
            background-image: url('{{ asset('imagenes/metalplastico/cds.jpg') }}');
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        main { flex: 1; }

        /* NAV sticky y adaptable */
        nav {
            background-color: rgba(73, 72, 71, 0.8);
            text-align: center;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 10;

            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
        }
        nav a {
            display: inline-block;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        @media (max-width: 480px) {
            nav a { padding: 8px 12px; margin: 4px 6px; font-size: 16px; }
        }
        nav a:hover { background-color: var(--brand); border-radius: 5px; transform: scale(1.06); }

        .wrapper { max-width: 1280px; margin: 0 auto; padding: 0 18px 20px; }

        footer {
            background-color: rgba(73, 72, 71, 0.8);
            color: white; text-align: center; padding: 20px 0;
        }

        .top-actions{
            display:flex; align-items:center; justify-content:space-between;
            gap:12px; margin: 6px 0 10px;
        }
        .btn{
            display:inline-block; text-decoration:none; cursor:pointer;
            background: var(--brand); color:#fff; border:none;
            padding:10px 16px; border-radius:8px;
            transition: transform .2s ease, background .2s ease;
            font-weight:600;
        }
        .btn:hover{ background: var(--brand-dark); transform: translateY(-1px); }
        .btn.secondary{ background:#ffffff; color: var(--link); border:1px solid var(--border); }
        .btn.secondary:hover{ background:#f6ffff; }

        /* ===== Gateway (tarjetas traslúcidas) ===== */
        .gateway{
            display:grid; gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: stretch;
        }
        @media (max-width: 980px){ .gateway{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width: 560px){ .gateway{ grid-template-columns: 1fr; } }

        .tile{
            border-radius: 14px; padding: 20px; min-height: 180px;
            background: transparent;
            border: 1px solid rgba(255,255,255,.55);
            color:#fff; text-decoration:none;
            display:flex; flex-direction:column; justify-content:space-between;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, backdrop-filter .18s ease;
            backdrop-filter: saturate(1.1) brightness(1.05);
            box-shadow: 0 8px 18px rgba(0,0,0,.12);
        }
        .tile:hover{
            transform: translateY(-4px);
            box-shadow: 0 12px 22px rgba(0,0,0,.18);
            border-color: rgba(255,255,255,.8);
            backdrop-filter: saturate(1.2) brightness(1.1);
        }
        .tile h2{ margin:0 0 8px; font-size: 20px; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,.35); }
        .tile p { margin:0; color:#f2f2f2; line-height:1.4; text-shadow: 0 1px 2px rgba(0,0,0,.35); }
        .pill{ margin-top:12px; background: var(--brand); color:#fff; padding:6px 10px; border-radius:999px; font-size:11px; letter-spacing:.4px; text-transform:uppercase; }

        /* Listado */
        .headline{ margin: 10px 0 14px; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,.35); }
        .filters{ display:flex; flex-wrap:wrap; gap:10px; margin: 8px 0 18px; }
        .filter-link{
            background: rgba(0,204,204,.95); color:#fff;
            padding:8px 14px; border-radius:18px; text-decoration:none; font-weight:600; font-size:14px;
            transition: transform .18s ease, background .18s ease;
        }
        .filter-link:hover{ background: var(--brand-dark); transform: translateY(-1px); }
        .filter-link.active{ outline: 2px solid #fff; }

        .grid{ display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        @media (max-width: 1100px){ .grid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
        @media (max-width: 700px){ .grid{ grid-template-columns: 1fr; } }

        /* Card idea */
        .card{
            background: var(--card-glass);
            border:1px solid var(--border);
            border-radius:12px; overflow:hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display:flex; flex-direction:column;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .card:hover{ transform: translateY(-3px); box-shadow: 0 8px 18px rgba(0,0,0,.24); border-color:#bcdede; }
        .card::before{ content:""; display:block; height:4px; width:100%; background: linear-gradient(90deg, var(--brand) , #9fc7c3); }
        .card img{ width:100%; height: 170px; object-fit:cover; background:#eee; }
        .card-body{ padding: 12px 12px 16px; }
        .card-title{ font-size: 18px; margin: 0 0 8px; color:var(--link); }
        .card-meta{ font-size: 12px; color:#444; margin-bottom:10px; }
        .card-desc{ font-size: 14px; color:#333; }
        .card .actions{ display:flex; flex-wrap:wrap; gap:8px; align-items:center; padding: 8px 12px 14px; }

        .empty{ text-align:center; padding: 32px 0 16px; font-size:18px; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,.35); }

        /* Paginación simple */
        .pager{ display:flex; justify-content:center; align-items:center; gap:10px; margin-top:18px; color:#fff; text-shadow: 0 1px 2px rgba(0,0,0,.35); }
        .pager .btn{ padding:8px 12px; }
        .pager .info{ font-size:14px; }
        .pager .btn[disabled]{ opacity:.5; pointer-events:none; }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            <a href="{{ route('ad.index') }}">Administración</a>
        @endif
        {{-- SIN enlace a “Ideas” porque estamos en la vista de ideas --}}
        <a href="{{ route('opiniones') }}">Opiniones</a>
        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('autores.lista') }}">Autores</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        {{-- Invitados --}}
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main class="wrapper">
    <div class="top-actions">
        <a class="btn secondary" href="{{ route('home') }}">← Inicio</a>
        <a class="btn secondary" href="{{ route('ideas.favoritas') }}">Mis favoritas</a><span></span>
    </div>

    @if(session('fav_msg'))
        <div style="background:#e7f7ed;border:1px solid #bfe8cd;color:#155724;padding:10px 14px;border-radius:8px; margin:10px 0;">
            {{ session('fav_msg') }}
        </div>
    @endif

    @php
        $grupo = $tipo ?? null;
        $items = [
            'todas' => ['label' => 'Todas', 'href' => route('ideas', ['tipo' => 'todas'])],
            'madera' => ['label' => 'Madera', 'href' => route('ideas', ['tipo' => 'madera'])],
            'plastico-metal' => ['label' => 'Plástico / Metal', 'href' => route('ideas', ['tipo' => 'plastico-metal'])],
            'tejidos' => ['label' => 'Tejidos', 'href' => route('ideas', ['tipo' => 'tejidos'])],
            'carton' => ['label' => 'Cartón', 'href' => route('ideas', ['tipo' => 'carton'])],
        ];
    @endphp

    {{-- Gateway (transparente) --}}
    @if(empty($grupo))
        <section class="gateway">
            <a class="tile" href="{{ $items['todas']['href'] }}">
                <div>
                    <h2>{{ $items['todas']['label'] }}</h2>
                    <p>Explora todas las ideas recicladas en formato listado.</p>
                </div>
                <span class="pill">Ver todo</span>
            </a>

            <a class="tile" href="{{ $items['madera']['href'] }}">
                <div>
                    <h2>{{ $items['madera']['label'] }}</h2>
                    <p>Palets, muebles, marcos, y más.</p>
                </div>
                <span class="pill">Madera</span>
            </a>

            <a class="tile" href="{{ $items['plastico-metal']['href'] }}">
                <div>
                    <h2>{{ $items['plastico-metal']['label'] }}</h2>
                    <p>Botellas, latas y piezas con segunda vida.</p>
                </div>
                <span class="pill">Plástico / Metal</span>
            </a>

            <a class="tile" href="{{ $items['tejidos']['href'] }}">
                <div>
                    <h2>{{ $items['tejidos']['label'] }}</h2>
                    <p>Ropa, retales, tapizados y textil creativo.</p>
                </div>
                <span class="pill">Tejidos</span>
            </a>

            <a class="tile" href="{{ $items['carton']['href'] }}">
                <div>
                    <h2>{{ $items['carton']['label'] }}</h2>
                    <p>Packaging, prototipos, juegos y más.</p>
                </div>
                <span class="pill">Cartón</span>
            </a>
        </section>
    @else
        <h1 class="headline">
            @if($grupo === 'todas')
                Todas las ideas
            @else
                Ideas de {{ str_replace('-', ' ', ucfirst($grupo)) }}
            @endif
        </h1>

        <div class="filters">
            <a class="filter-link {{ $grupo === 'todas' ? 'active' : '' }}" href="{{ $items['todas']['href'] }}">Todas</a>
            <a class="filter-link {{ $grupo === 'madera' ? 'active' : '' }}" href="{{ $items['madera']['href'] }}">Madera</a>
            <a class="filter-link {{ $grupo === 'plastico-metal' ? 'active' : '' }}" href="{{ $items['plastico-metal']['href'] }}">Plástico / Metal</a>
            <a class="filter-link {{ $grupo === 'tejidos' ? 'active' : '' }}" href="{{ $items['tejidos']['href'] }}">Tejidos</a>
            <a class="filter-link {{ $grupo === 'carton' ? 'active' : '' }}" href="{{ $items['carton']['href'] }}">Cartón</a>
        </div>

        {{-- Paginación arriba (opcional) --}}
        @if($ideas->total() > $ideas->perPage())
            <div class="pager">
                <a class="btn" href="{{ $ideas->previousPageUrl() }}" @if($ideas->onFirstPage()) disabled @endif>← Anterior</a>
                <span class="info">Página {{ $ideas->currentPage() }} de {{ $ideas->lastPage() }}</span>
                <a class="btn" href="{{ $ideas->nextPageUrl() }}" @if(!$ideas->hasMorePages()) disabled @endif>Siguiente →</a>
            </div>
        @endif

        @if($ideas->count())
            <div class="grid">
                @foreach($ideas as $idea)
                    <article class="card">
                        <img src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/placeholder.jpg') }}"
                             alt="Imagen de {{ $idea->nombre }}">
                        <div class="card-body">
                            <h2 class="card-title">{{ $idea->nombre }}</h2>
                            <div class="card-meta">
                                Tipo: {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}
                                @if($idea->autor)&nbsp;·&nbsp;Autor: {{ $idea->autor->apodo }}@endif
                            </div>
                            <p class="card-desc">
                                {{ \Illuminate\Support\Str::limit($idea->descripcion, 160) }}
                            </p>
                        </div>

                        {{-- Acciones: favorito + ver opiniones + ver detalle --}}
                        <div class="actions">
                            @auth
                                @php
                                    $yaEsFavorita = isset($favoritasIds) && in_array($idea->id, $favoritasIds ?? []);
                                @endphp
                                <form action="{{ route('ideas.favorito', $idea->id) }}" method="POST" class="fav-form" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn fav-btn" data-idea-id="{{ $idea->id }}">
                                        {{ $yaEsFavorita ? 'Quitar de favoritos' : 'Marcar favorito' }}
                                    </button>
                                </form>
                            @endauth>

                            <a class="btn" href="{{ route('opiniones.porIdea', $idea->id) }}">
                                Ver opiniones ({{ $idea->opiniones_count ?? 0 }})
                            </a>

                            <a class="btn" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">
                                Ver detalle
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Paginación abajo --}}
            @if($ideas->total() > $ideas->perPage())
                <div class="pager">
                    <a class="btn" href="{{ $ideas->previousPageUrl() }}" @if($ideas->onFirstPage()) disabled @endif>← Anterior</a>
                    <span class="info">Página {{ $ideas->currentPage() }} de {{ $ideas->lastPage() }}</span>
                    <a class="btn" href="{{ $ideas->nextPageUrl() }}" @if(!$ideas->hasMorePages()) disabled @endif>Siguiente →</a>
                </div>
            @endif
        @else
            <p class="empty">No hay ideas para este grupo todavía.</p>
        @endif
    @endif
</main>

<footer>
    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
    <p>
        <a href="https://www.facebook.com/foroplatos" target="_blank">Facebook</a> |
        <a href="https://www.instagram.com/foroplatos" target="_blank">Instagram</a> |
        <a href="https://twitter.com/foroplatos" target="_blank">X</a>
    </p>
</footer>

<!-- Opcional: cambio de texto instantáneo -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.fav-form').forEach(form => {
            form.addEventListener('submit', () => {
                const btn = form.querySelector('.fav-btn');
                if (!btn) return;
                const marcar = 'Marcar favorito';
                const quitar = 'Quitar de favoritos';
                btn.textContent = (btn.textContent.trim() === marcar) ? quitar : marcar;
                btn.disabled = true;
                setTimeout(() => { btn.disabled = false; }, 1200);
            });
        });
    });
</script>

</body>
</html>
