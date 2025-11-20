<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $idea->nombre }} — Idea reciclada</title>
    <style>
        :root{
            --ink:#0e1a18; --muted:#5b6f6d; --brand:#86b7b2; --brand-dark:#255d47;
            --bg: linear-gradient(135deg, #c8efe9 0%, #8fd3c6 100%);
        }
        body{
            font-family: Arial, sans-serif;
            background-image: url('{{ asset('imagenes/carton/casamunecas.jpg') }}');
            color: var(--ink);
            margin: 0; padding: 0;
            min-height: 100vh; display: flex; flex-direction: column;
        }
        main{ flex: 1; }

        nav{
            background-color: rgba(73,72,71,0.8);
            text-align: center;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        nav a{
            display: inline-block;
            color: #fff;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color .3s ease, transform .3s ease;
        }
        nav a:hover{
            background-color: var(--brand);
            border-radius: 5px;
            transform: scale(1.1);
        }

        .wrapper{ max-width: 1100px; margin: 0 auto; padding: 0 18px 24px; }

        .top-actions{
            display:flex; flex-wrap:wrap; align-items:center; gap:10px;
            margin: 8px 0 12px;
        }
        .btn{
            display:inline-block; background: var(--brand); color:#fff;
            padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:600;
            transition: transform .2s ease, background .2s ease;
        }
        .btn:hover{ background: var(--brand-dark); transform: translateY(-1px); }
        .btn.secondary{ background:#e7efee; color: var(--brand-dark); }
        .btn.secondary:hover{ background:#dfe8e7; color:#134836; }

        .card{
            background:#fff; border:1px solid #eee; border-radius:12px;
            box-shadow: 0 8px 16px rgba(0,0,0,.06); overflow:hidden;
        }
        .hero{ width:100%; max-height:460px; object-fit:cover; display:block; background:#eee; }
        .content{ padding:18px; }

        h1{ margin: 0 0 6px; }
        .meta{
            display:flex; flex-wrap:wrap; gap:10px 14px; align-items:center;
            color:#555; font-size:14px; margin: 8px 0 12px;
        }
        .badge{
            background: var(--brand); color:#fff;
            padding: 6px 10px; border-radius: 999px; font-size: 12px;
            text-transform: uppercase; letter-spacing: .4px;
        }
        .meta a{ color:#134836; text-decoration:none; }
        .meta a:hover{ text-decoration: underline; }

        .desc{ font-size:16px; line-height:1.55; color:#222; white-space: pre-line; }

        footer{
            background-color: rgba(73,72,71,0.8);
            color: #fff; text-align: center; padding: 20px 0;
        }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            <a href="{{ route('ad.index') }}">Panel</a>
            <a href="{{ route('ad.ideas') }}">Ideas</a>
            <a href="{{ route('ad.opiniones') }}">Opiniones</a>
            <a href="{{ route('ad.usuarios') }}">Usuarios</a>
        @else
            <a href="{{ route('ideas') }}">Ideas</a>
            <a href="{{ route('opiniones') }}">Opiniones</a>
            <a href="{{ route('usuarios.lista') }}">Usuarios</a>
        @endif

        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main class="wrapper">
    <div class="top-actions">
        @php
            // 1) Si llega ?autor=ID usamos ese
            $autorIdParam = request()->filled('autor') ? (int) request('autor') : null;
            // 2) Si no llega, usamos el autor de la idea (si lo hay)
            $fallbackAutorId = $idea->autor->id ?? null;
            $targetAutorId = $autorIdParam ?: $fallbackAutorId;
        @endphp

        @if($targetAutorId)
            @auth
                @if(Auth::user()->esAdmin())
                    <a class="btn secondary" href="{{ route('ad.autores.ver', $targetAutorId) }}">
                        ← Volver al autor
                    </a>
                @else
                    <a class="btn secondary" href="{{ route('usuario.perfil', ['id' => $targetAutorId]) }}">
                        ← Volver al autor
                    </a>
                @endif
            @else
                <a class="btn secondary" href="{{ route('usuario.perfil', ['id' => $targetAutorId]) }}">
                    ← Volver al autor
                </a>
            @endauth
        @endif
    </div>

    <article class="card">
        <img class="hero"
             src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/placeholder.jpg') }}"
             alt="Imagen de {{ $idea->nombre }}">

        <div class="content">
            <h1>{{ $idea->nombre }}</h1>

            <div class="meta">
                <span class="badge">{{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}</span>

                @if($idea->autor)
                    <span>
                        Autor:
                        <a href="{{ route('usuario.perfil', ['id' => $idea->autor->id]) }}">
                            {{ $idea->autor->apodo }}
                        </a>
                    </span>
                @endif

                @if($idea->created_at)
                    <span>Fecha de alta: {{ $idea->created_at->format('d/m/Y') }}</span>
                @endif
            </div>

            <div class="desc">{{ $idea->descripcion }}</div>
        </div>
    </article>
</main>

<footer>
    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
    <p>
        <a href="https://www.facebook.com/foroplatos" target="_blank">Facebook</a> |
        <a href="https://www.instagram.com/foroplatos" target="_blank">Instagram</a> |
        <a href="https://twitter.com/foroplatos" target="_blank">X</a>
    </p>
</footer>

</body>
</html>
