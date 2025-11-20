<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ideas (Admin)</title>
    <style>
        :root{--ink:#0e1a18;--muted:#5b6f6d;--brand:#86b7b2;--brand-dark:#255d47;--bg:#ffffff;}
        body{font-family:Arial,sans-serif;background:var(--bg);color:var(--ink);margin:0}
        nav{background:rgba(73,72,71,.8);text-align:center;padding:10px 0;margin-bottom:10px}
        nav a{display:inline-block;color:#fff;padding:12px 30px;text-decoration:none;font-size:18px;margin:0 15px;transition:.3s}
        nav a:hover{background:var(--brand);border-radius:5px;transform:scale(1.05)}
        .wrapper{max-width:1280px;margin:0 auto;padding:18px}
        .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
        @media (max-width:1100px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media (max-width:700px){.grid{grid-template-columns:1fr}}

        /* === Traslúcido en tarjetas y bloques === */
        .card{
            background: rgba(255,255,255,0.65);           /* antes #fff */
            border: 1px solid rgba(255,255,255,0.35);     /* antes #eee */
            border-radius:12px; overflow:hidden;
            box-shadow:0 8px 16px rgba(0,0,0,.06);
            display:flex; flex-direction:column
        }
        .card::before{content:"";display:block;height:4px;background:linear-gradient(90deg,var(--brand),#9fc7c3)}
        .card img{width:100%;height:170px;object-fit:cover;background:#eee}
        .card-body{padding:12px 12px 16px}
        .title{font-size:18px;margin:0 0 8px}
        .meta{font-size:12px;color:#333;margin-bottom:8px}
        .desc{font-size:14px;color:#1e1e1e}

        .pager{display:flex;justify-content:center;align-items:center;gap:10px;margin-top:18px}
        .btn{background:var(--brand);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px}
        .btn[disabled]{opacity:.5;pointer-events:none}
        .btn.danger{background:#c0392b}
        .muted{color:var(--muted)}

        /* formularios */
        .form-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
        .form-row-1{display:grid;grid-template-columns:1fr;gap:8px}
        label{font-size:12px;color:#1f2a29;margin-top:6px}
        input[type="text"], select, textarea, input[type="file"]{
            width:100%;padding:8px;border:1px solid #ddd;border-radius:8px;font-size:14px;background:#fff;
        }
        textarea{min-height:90px;resize:vertical}
        .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}

        /* details translúcido */
        details{
            background: rgba(255,255,255,0.65);          /* antes #fafafa */
            border: 1px solid rgba(255,255,255,0.35);    /* antes #eee */
            border-radius:10px; padding:10px; margin-top:10px
        }
        details summary{cursor:pointer;font-weight:700;color:#255d47;margin:-10px -10px 8px -10px;padding:10px}

        /* avisos translúcidos */
        .flash{margin:10px 0;padding:10px 12px;border-radius:8px}
        .flash.ok{background:rgba(231,247,237,.85);border:1px solid rgba(191,232,205,.85);color:#155724}
        .flash.err{background:rgba(255,233,233,.9);border:1px solid rgba(255,179,179,.85);color:#a40000}

        /* barra inferior de acciones por tarjeta */
        .card-actions{display:flex;gap:8px;align-items:center;justify-content:flex-end;padding:10px 12px}
        .divider{height:1px;background:#eee;margin:10px 0}

        /* Fondo con imagen (no pisar con 'background:') */
        body{
            font-family:Arial,sans-serif;
            color:#f0f0f0;
            margin:0;
            background-color: #f0f0f0;
            background-image: url('{{ asset('imagenes/tejidos/banquetas.jpg') }}');
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
    <a href="{{ route('ad.autores') }}">Autores</a>  <!-- añadido -->
    <a href="{{ route('ad.ideas') }}">Ideas</a>
    <a href="{{ route('ad.opiniones') }}">Opiniones</a>
    <a href="{{ route('usuario.logout') }}">Cerrar sesión</a>
</nav>

<div class="wrapper">
    <h1>Ideas publicadas</h1>

    @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="flash err">{{ session('error') }}</div> @endif
    @if(session('fav_msg')) <div class="flash ok">{{ session('fav_msg') }}</div> @endif

    {{-- CREAR NUEVA IDEA (admin) --}}
    @auth
        @if(Auth::user()->esAdmin())
            <details style="margin-bottom:14px;">
                <summary>➕ Crear nueva idea</summary>
                <form action="{{ route('ideas.store') }}" method="POST" enctype="multipart/form-data" style="margin-top:8px;">
                    @csrf
                    <div class="form-row">
                        <div>
                            <label>Nombre</label>
                            <input type="text" name="nombre" required>
                        </div>
                        <div>
                            <label>Tipo</label>
                            <select name="tipo" required>
                                <option value="">Selecciona…</option>
                                <option value="madera">Madera</option>
                                <option value="plastico-metal">Plástico / Metal</option>
                                <option value="tejidos">Tejidos</option>
                                <option value="carton">Cartón</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-1">
                        <div>
                            <label>Descripción</label>
                            <textarea name="descripcion" required></textarea>
                        </div>
                        <div>
                            <label>Foto (opcional)</label>
                            <input type="file" name="fotoIdea" accept="image/*">
                        </div>
                    </div>

                    <div class="actions">
                        <button class="btn" type="submit">Crear</button>
                    </div>
                </form>
            </details>
        @endif
    @endauth

    @if($ideas->count())
        <section class="grid">
            @foreach($ideas as $idea)
                <article class="card">
                    <img src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/placeholder.jpg') }}" alt="Imagen de {{ $idea->nombre }}">
                    <div class="card-body">
                        <h3 class="title">{{ $idea->nombre }}</h3>
                        <div class="meta">
                            Tipo: {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}
                            @if($idea->autor) · Autor: {{ $idea->autor->apodo }} @endif
                            · {{ $idea->created_at?->format('d/m/Y') }}
                        </div>
                        <div class="desc">{{ \Illuminate\Support\Str::limit($idea->descripcion, 160) }}</div>

                        @auth
                            @if(Auth::user()->esAdmin())
                                <details>
                                    <summary>Editar esta idea</summary>
                                    <form action="{{ route('ideas.actualizar', $idea->id) }}" method="POST" enctype="multipart/form-data" style="margin-top:8px;">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-row">
                                            <div>
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" value="{{ old('nombre', $idea->nombre) }}" required>
                                            </div>
                                            <div>
                                                <label>Tipo</label>
                                                <select name="tipo" required>
                                                    @foreach(['madera','plastico-metal','tejidos','carton'] as $t)
                                                        <option value="{{ $t }}" {{ $idea->tipo===$t ? 'selected':'' }}>
                                                            {{ ucfirst(str_replace('-', ' ', $t)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row-1">
                                            <div>
                                                <label>Descripción</label>
                                                <textarea name="descripcion" required>{{ old('descripcion', $idea->descripcion) }}</textarea>
                                            </div>
                                            <div>
                                                <label>Nueva foto (opcional)</label>
                                                <input type="file" name="fotoIdea" accept="image/*">
                                            </div>
                                        </div>

                                        <div class="actions">
                                            <button class="btn" type="submit">Guardar cambios</button>
                                        </div>
                                    </form>
                                </details>
                            @endif
                        @endauth
                    </div>

                    {{-- Acciones fuera del bloque de edición --}}
                    @auth
                        @if(Auth::user()->esAdmin())
                            <div class="divider"></div>
                            <div class="card-actions">
                                <a class="btn" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">Ver detalle</a>

                                <form action="{{ route('ideas.eliminar', $idea->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar esta idea definitivamente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">🗑️ Eliminar</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </article>
            @endforeach
        </section>

        @if($ideas->total() > $ideas->perPage())
            <div class="pager">
                <a class="btn" href="{{ $ideas->previousPageUrl() }}" @if($ideas->onFirstPage()) disabled @endif>← Anterior</a>
                <span>Página {{ $ideas->currentPage() }} de {{ $ideas->lastPage() }}</span>
                <a class="btn" href="{{ $ideas->nextPageUrl() }}" @if(!$ideas->hasMorePages()) disabled @endif>Siguiente →</a>
            </div>
        @endif
    @else
        <p class="muted">No hay ideas.</p>
    @endif
</div>
</body>
</html>
