<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Opiniones — {{ $idea->nombre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root{
            --c-text:#333;
            --c-primary:#00cccc;
            --c-primary-hover:#0099a1;
            --c-link:#007b8f;
            --c-card-bg: rgba(255,255,255,0.65);
            --c-border:#cccccc;
            --c-darkbar: rgba(73,72,71,0.8);
        }
        *{ box-sizing:border-box; }
        body{
            font-family: Arial, sans-serif; color: var(--c-text); margin:0;
            min-height:100vh; display:flex; flex-direction:column;
            background-image: url('{{ asset('imagenes/tejidos/posavasos.jpg') }}');
            background-size:cover; background-position:center; background-attachment:fixed;
            padding-top:70px;
        }
        nav{ background:var(--c-darkbar); text-align:center; padding:10px 0; position:fixed; top:0; left:0; right:0; z-index:10; }
        nav a{ color:#fff; padding:12px 30px; text-decoration:none; font-size:18px; margin:0 15px; transition:.3s; }
        nav a:hover{ background: var(--c-primary); border-radius:5px; transform: scale(1.06); }
        main{ flex:1; width:100%; max-width:1100px; margin:0 auto; padding: 18px; }
        h1{ margin: 6px 0 10px; color: var(--c-link); }
        .muted{ color:#555; }

        .actions{ display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
        .btn{ display:inline-block; background:var(--c-primary); color:#fff; text-decoration:none; padding:8px 12px; border-radius:8px; font-weight:600; }
        .btn.secondary{ background:#fff; color:var(--c-link); border:1px solid var(--c-border); }
        .btn:hover{ background:var(--c-primary-hover); }
        .card{ background:var(--c-card-bg); border:1px solid var(--c-border); border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,.2); padding:14px; margin-bottom:14px; }
        .ok{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724;padding:8px;border-radius:8px;margin-bottom:10px;}
        .err{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000;padding:10px;border-radius:6px;margin-bottom:12px;}
        .thread{ background:var(--c-card-bg); border:1px solid var(--c-border); border-radius:10px; padding:14px; margin-bottom:14px; }
        footer{ background:var(--c-darkbar); color:#fff; text-align:center; padding:20px 0; }
        .form label{ display:block; font-weight:700; margin-top:10px; }
        .form textarea, .form select, .form input[type="number"]{ width:100%; padding:10px; border:1px solid #ccd3d6; border-radius:8px; margin-top:6px; background:#fff; }
        .form button{ margin-top:14px; background:var(--c-primary); color:#fff; border:none; border-radius:8px; padding:10px 18px; cursor:pointer; font-weight:600; }
        .form button:hover{ background:var(--c-primary-hover); }
        .pager{ display:flex; justify-content:center; align-items:center; gap:10px; margin: 16px 0; }
        .pager .btn[disabled]{ opacity:.5; pointer-events:none; }
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
            <a href="{{ route('autores.lista') }}">Autores</a>
        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main>
    <h1>Opiniones — {{ $idea->nombre }}</h1>
    <div class="muted" style="margin-bottom:10px;">
        Tipo: {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}
        @if($idea->autor) · Autor: {{ $idea->autor->apodo }} @endif
    </div>

    <div class="actions">
        <a class="btn secondary" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">← Volver a la idea</a>
        <a class="btn secondary" href="{{ route('ideas') }}">← Ver más ideas</a>
    </div>

    {{-- Formulario de nueva opinión SOLO para esta idea --}}
    @auth
        <section class="card">
            @if (session('success'))
                <div class="ok">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="err">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form class="form" action="{{ route('opiniones.guardar') }}" method="POST">
                @csrf
                {{-- Fijamos la idea: hidden --}}
                <input type="hidden" name="id_idea" value="{{ $idea->id }}">
                {{-- Para que el store sepa volver aquí --}}
                <input type="hidden" name="redir_a_idea" value="1">

                <label for="valoracion">Valoración</label>
                <select name="valoracion" id="valoracion" required>
                    <option value="">Selecciona…</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }} estrella{{ $i>1?'s':'' }}</option>
                    @endfor
                </select>

                <label for="texto">Tu opinión</label>
                <textarea name="texto" id="texto" rows="4" required>{{ old('texto') }}</textarea>

                <label for="id_respondido">Responder a (ID opinión) — opcional</label>
                <input type="number" name="id_respondido" id="id_respondido" placeholder="ID de la opinión padre">

                <button type="submit">Publicar opinión</button>
            </form>
        </section>
    @else
        <section class="card">
            Para opinar necesitas iniciar sesión. <a href="{{ route('login') }}">Entrar</a>
        </section>
    @endauth

    {{-- Listado de opiniones raíz (solo de esta idea) con sus respuestas --}}
    @forelse($opiniones as $op)
        <div class="thread">
            @include('verOpinionesRaiz', ['opinion' => $op])
        </div>
    @empty
        <p class="muted">No hay opiniones para esta idea todavía.</p>
    @endforelse

    @if($opiniones->total() > $opiniones->perPage())
        <div class="pager">
            <a class="btn" href="{{ $opiniones->previousPageUrl() }}" @if($opiniones->onFirstPage()) disabled @endif>← Anterior</a>
            <span>Página {{ $opiniones->currentPage() }} de {{ $opiniones->lastPage() }}</span>
            <a class="btn" href="{{ $opiniones->nextPageUrl() }}" @if(!$opiniones->hasMorePages()) disabled @endif>Siguiente →</a>
        </div>
    @endif
</main>

<footer>
    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
</footer>
</body>
</html>
