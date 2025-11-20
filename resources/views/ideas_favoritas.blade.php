<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis ideas favoritas</title>
    <style>
        :root{
            --ink:#0e1a18; --muted:#5b6f6d; --brand:#86b7b2; --brand-dark:#255d47;
            --bg:#ffffff; /* ya no se usa, pero lo dejo por si lo necesitas en otro momento */
        }
        body{
            font-family:Arial, sans-serif;
            /* Fondo con imagen solicitada */
            background-image: url('{{ asset('imagenes/metalplastico/corazones.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;

            color:var(--ink); margin:0; padding:0; min-height:100vh; display:flex; flex-direction:column;
        }
        nav{ background:rgba(73,72,71,.8); text-align:center; padding:10px 0; margin-bottom:12px; }
        nav a{ display:inline-block; color:#fff; padding:12px 30px; text-decoration:none; font-size:18px; margin:0 15px; transition:.2s; }
        nav a:hover{ background:var(--brand); border-radius:5px; transform:scale(1.05); }
        .wrapper{ max-width:1280px; margin:0 auto; padding:0 18px 24px; width:100%; }
        h1{ margin:12px 0 16px; }
        .top-actions{ display:flex; gap:10px; align-items:center; margin: 6px 0 12px; }
        .btn{ display:inline-block; background:var(--brand); color:#fff; border:none; text-decoration:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
        .btn.secondary{ background:#e7efee; color:var(--brand-dark); }
        .btn:hover{ background:var(--brand-dark); }
        .grid{ display:grid; gap:16px; grid-template-columns:repeat(3, minmax(0,1fr)); }
        @media (max-width:1100px){ .grid{ grid-template-columns:repeat(2, minmax(0,1fr)); } }
        @media (max-width:700px){ .grid{ grid-template-columns:1fr; } }
        .card{ background:#fff; border:1px solid #eee; border-radius:12px; overflow:hidden; box-shadow:0 8px 16px rgba(0,0,0,.06); display:flex; flex-direction:column; }
        .card::before{ content:""; display:block; height:4px; width:100%; background:linear-gradient(90deg, var(--brand), #9fc7c3); }
        .card img{ width:100%; height:170px; object-fit:cover; background:#eee; }
        .card-body{ padding:12px 12px 6px; }
        .card-title{ font-size:18px; margin:0 0 6px; color:#1d1d1d; }
        .card-meta{ font-size:12px; color:#666; margin-bottom:8px; }
        .card-desc{ font-size:14px; color:#333; }
        .actions{ display:flex; gap:8px; padding:10px 12px 14px; }
        .empty{ text-align:center; padding:32px 0 16px; font-size:18px; color:var(--muted); }
        .flash{ background:#e7f7ed; border:1px solid #bfe8cd; color:#155724; padding:10px 14px; border-radius:8px; margin:10px 0; }
        footer{ background:rgba(73,72,71,.8); color:#fff; text-align:center; padding:20px 0; margin-top:auto; }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            <a href="{{ route('ad.index') }}">Administración</a>
        @endif
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('opiniones') }}">Opiniones</a>
        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main class="wrapper">
    <div class="top-actions">
        <a class="btn secondary" href="{{ route('ideas') }}">← Volver a ideas</a>
    </div>

    <h1>Mis ideas favoritas</h1>

    {{-- Mensaje al eliminar desde esta página --}}
    @if(session('success'))
        <div class="flash">Tu idea favorita ha sido eliminada.</div>
    @endif

    @if($ideas->count())
        <div class="grid">
            @foreach($ideas as $idea)
                <article class="card">
                    <img src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/metalplastico/mariquitas.jpg') }}"
                         alt="Imagen de {{ $idea->nombre }}">
                    <div class="card-body">
                        <h2 class="card-title">{{ $idea->nombre }}</h2>
                        <div class="card-meta">
                            Tipo: {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}
                            @if($idea->autor)&nbsp;·&nbsp;Autor: {{ $idea->autor->apodo }}@endif
                        </div>
                        <p class="card-desc">{{ \Illuminate\Support\Str::limit($idea->descripcion, 160) }}</p>
                    </div>

                    <div class="actions">
                        <a class="btn" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">
                            Ver detalle
                        </a>
                        {{-- Quitar de favoritas (marca el origen para mostrar el mensaje aquí) --}}
                        <form action="{{ route('ideas.favorito', $idea->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <input type="hidden" name="from" value="favoritas">
                            <button type="submit" class="btn">❌ Quitar de favoritas</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <p class="empty">No tienes ideas favoritas todavía.</p>
    @endif
</main>

<footer>
    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
</footer>

</body>
</html>
