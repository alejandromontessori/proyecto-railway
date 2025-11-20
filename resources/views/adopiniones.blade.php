<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Opiniones (Admin)</title>
    <style>
        :root{
            --ink:#0e1a18; --muted:#5b6f6d; --brand:#86b7b2; --brand-dark:#255d47;
            --card-bg: rgba(255,255,255,0.65); /* translúcido */
            --card-border: rgba(255,255,255,0.35);
        }

        /* Fondo con imagen */
        body{
            font-family:Arial,sans-serif;
            color:var(--ink);
            margin:0;

            background-image: url('{{ asset('imagenes/tejidos/cupcakes.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        nav{background:rgba(73,72,71,.8);text-align:center;padding:10px 0;margin-bottom:10px}
        nav a{display:inline-block;color:#fff;padding:12px 30px;text-decoration:none;font-size:18px;margin:0 15px;transition:.3s}
        nav a:hover{background:var(--brand);border-radius:5px;transform:scale(1.05)}

        .wrapper{max-width:1100px;margin:0 auto;padding:18px}
        .btn{background:var(--brand);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
        .btn:hover{background:var(--brand-dark)}
        .btn.danger{background:#c0392b}
        .btn[disabled]{opacity:.5;pointer-events:none}

        .list{display:flex;flex-direction:column;gap:12px}
        .card{
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius:12px;
            padding:14px;
            box-shadow:0 8px 16px rgba(0,0,0,.06)
        }
        .meta{font-size:13px;color:#555;margin-bottom:6px}
        .text{font-size:15px;color:#222}
        .reply{margin-top:8px;padding-left:12px;border-left:3px solid var(--brand);color:#333}

        .pager{display:flex;justify-content:center;align-items:center;gap:10px;margin-top:18px}
        .muted{color:var(--muted)}

        .flash{margin:10px 0;padding:10px 12px;border-radius:8px}
        .flash.ok{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724}
        .flash.err{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000}

        /* formulario crear/editar */
        details{background: var(--card-bg); border:1px solid var(--card-border); border-radius:10px; padding:10px; margin:10px 0}
        details summary{cursor:pointer;font-weight:700;color:#255d47;margin:-10px -10px 8px -10px;padding:10px}
        form .row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
        form .row-1{display:grid;grid-template-columns:1fr;gap:10px}
        label{font-size:12px;color:#444;margin-top:6px}
        select, input[type="number"], textarea{
            width:100%;padding:8px;border:1px solid #ddd;border-radius:8px;font-size:14px;background:#fff;
        }
        textarea{min-height:90px;resize:vertical}

        .card-actions{display:flex;gap:8px;align-items:center;justify-content:flex-end;margin-top:10px}
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
    <h1>Opiniones</h1>

    @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="flash err">{{ session('error') }}</div> @endif

    {{-- CREAR NUEVA OPINIÓN (solo admin) --}}
    @auth
        @if(Auth::user()->esAdmin())
            <details>
                <summary>➕ Crear nueva opinión</summary>
                <form action="{{ route('ad.opiniones.crear') }}" method="POST" style="margin-top:8px;">
                    @csrf
                    <div class="row">
                        <div>
                            <label>Idea</label>
                            <select name="id_idea" required>
                                <option value="">Selecciona una idea…</option>
                                @foreach(($ideasAll ?? collect()) as $idea)
                                    <option value="{{ $idea->id }}">{{ $idea->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Autor de la opinión</label>
                            <select name="id_autor" required>
                                <option value="">Selecciona un usuario…</option>
                                @foreach(($autoresAll ?? collect()) as $u)
                                    <option value="{{ $u->id }}">{{ $u->apodo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div>
                            <label>Valoración (1–5 ⭐)</label>
                            <input type="number" name="valoracion" min="1" max="5" value="5" required>
                        </div>
                        <div>
                            <label>Responde a (opcional)</label>
                            <select name="id_respondido">
                                <option value="">(Sin respuesta)</option>
                                @foreach(($opinionesAll ?? collect()) as $o)
                                    <option value="{{ $o->id }}">#{{ $o->id }} — {{ \Illuminate\Support\Str::limit($o->texto, 60) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row-1">
                        <div>
                            <label>Texto</label>
                            <textarea name="texto" required></textarea>
                        </div>
                    </div>

                    <button class="btn" type="submit">Crear opinión</button>
                </form>
            </details>
        @endif
    @endauth

    {{-- LISTA --}}
    @if($opiniones->count())
        <section class="list">
            @foreach($opiniones as $op)
                <article class="card">
                    <div class="meta">
                        <strong>#{{ $op->id }}</strong>
                        · {{ $op->created_at?->format('d/m/Y H:i') }}
                        · Autor: {{ $op->autor->apodo ?? 'Anónimo' }}
                        · Idea: {{ $op->idea->nombre ?? 'Eliminada' }}
                        · Valoración: {{ $op->valoracion }} ⭐
                    </div>
                    <div class="text">“{{ $op->texto }}”</div>

                    @if($op->responde)
                        <div class="reply">
                            En respuesta a #{{ $op->responde->id }} — “{{ \Illuminate\Support\Str::limit($op->responde->texto, 120) }}”
                        </div>
                    @endif

                    @auth
                        @if(Auth::user()->esAdmin())
                            <details style="margin-top:10px;">
                                <summary> Editar esta opinión</summary>
                                <form action="{{ route('ad.opiniones.actualizar', $op->id) }}" method="POST" style="margin-top:8px;">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div>
                                            <label>Idea</label>
                                            <select name="id_idea" required>
                                                @foreach(($ideasAll ?? collect()) as $idea)
                                                    <option value="{{ $idea->id }}" {{ $op->id_idea == $idea->id ? 'selected' : '' }}>
                                                        {{ $idea->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label>Autor</label>
                                            <select name="id_autor" required>
                                                @foreach(($autoresAll ?? collect()) as $u)
                                                    <option value="{{ $u->id }}" {{ $op->id_autor == $u->id ? 'selected' : '' }}>
                                                        {{ $u->apodo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div>
                                            <label>Valoración (1–5 ⭐)</label>
                                            <input type="number" name="valoracion" min="1" max="5" value="{{ old('valoracion', $op->valoracion) }}" required>
                                        </div>
                                        <div>
                                            <label>Responde a (opcional)</label>
                                            <select name="id_respondido">
                                                <option value="">(Sin respuesta)</option>
                                                @foreach(($opinionesAll ?? collect()) as $o)
                                                    <option value="{{ $o->id }}" {{ $op->id_respondido == $o->id ? 'selected' : '' }}>
                                                        #{{ $o->id }} — {{ \Illuminate\Support\Str::limit($o->texto, 60) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row-1">
                                        <div>
                                            <label>Texto</label>
                                            <textarea name="texto" required>{{ old('texto', $op->texto) }}</textarea>
                                        </div>
                                    </div>

                                    <button class="btn" type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            <div class="card-actions">
                                <form action="{{ route('ad.opiniones.eliminar', $op->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente esta opinión?');">
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

        @if($opiniones->total() > $opiniones->perPage())
            <div class="pager">
                <a class="btn" href="{{ $opiniones->previousPageUrl() }}" @if($opiniones->onFirstPage()) disabled @endif>← Anterior</a>
                <span>Página {{ $opiniones->currentPage() }} de {{ $opiniones->lastPage() }}</span>
                <a class="btn" href="{{ $opiniones->nextPageUrl() }}" @if(!$opiniones->hasMorePages()) disabled @endif>Siguiente →</a>
            </div>
        @endif
    @else
        <p class="muted">No hay opiniones.</p>
    @endif
</div>
</body>
</html>
