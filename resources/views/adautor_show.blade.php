<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autor (Admin) — {{ $user->apodo }}</title>
    <style>
        :root{
            --ink:#0e1a18;
            --muted:#5b6f6d;
            --brand:#86b7b2;
            --brand-dark:#255d47;
            --bg:#ffffff; /* solo color de respaldo */
        }

        /* Fondo correctamente aplicado al body */
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

        nav{background:rgba(73,72,71,.8);text-align:center;padding:10px 0;margin-bottom:10px}
        nav a{display:inline-block;color:#fff;padding:12px 30px;text-decoration:none;font-size:18px;margin:0 15px;transition:.3s}
        nav a:hover{background:#86b7b2;border-radius:5px;transform:scale(1.05)}
        .wrapper{max-width:1100px;margin:0 auto;padding:18px}
        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        @media (max-width:900px){.grid{grid-template-columns:1fr}}
        .card{background:#fff;border:1px solid #eee;border-radius:12px;padding:16px;box-shadow:0 8px 16px rgba(0,0,0,.06)}
        .title{margin:0 0 10px}
        .row{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
        .avatar{width:88px;height:88px;border-radius:50%;object-fit:cover;background:#eee}
        .muted{color:var(--muted)}
        .btn{display:inline-block;background:var(--brand);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
        .btn:hover{background:var(--brand-dark)}
        .btn.secondary{background:#e7efee;color:var(--brand-dark)}
        .btn.secondary:hover{background:#dfe8e7}
        .btn.danger{background:#c0392b}
        select{padding:8px;border:1px solid #ddd;border-radius:8px}
        .list{list-style:none;padding:0;margin:0}
        .list li{padding:8px 0;border-bottom:1px solid #f0f0f0}
        .list li:last-child{border-bottom:0}
        .flash{padding:10px 12px;border-radius:8px;margin:10px 0}
        .flash.ok{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724}
        .flash.err{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000}
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
    @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="flash err">{{ session('error') }}</div> @endif

    <h1 class="title">Autor (Admin) — {{ $user->apodo }}</h1>

    <section class="grid">
        <!-- Datos básicos + cambio SOLO de rol -->
        <article class="card">
            <div class="row">
                <img class="avatar" src="{{ $user->foto_perfil ? asset($user->foto_perfil) : asset('imagenes/placeholder.jpg') }}" alt="Avatar">
                <div>
                    <div><strong>Nombre:</strong> {{ $user->nombre }} {{ $user->apellidos }}</div>
                    <div><strong>Email:</strong> {{ $user->email }}</div>
                    <div><strong>Rol actual:</strong> {{ $user->rol }}</div>
                    <div class="muted">Alta: {{ $user->created_at?->format('d/m/Y H:i') }}</div>
                    <div class="muted">Actualizado: {{ $user->updated_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            {{-- Cambiar SOLO el rol (admin) --}}
            @if(Auth::user()?->esAdmin() && Auth::id() !== $user->id)
                <hr style="border:none;height:1px;background:#eee;margin:14px 0">
                <form class="row" action="{{ route('ad.autores.cambiarRol', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label for="rol-{{ $user->id }}" class="muted">Cambiar rol:</label>
                    <select id="rol-{{ $user->id }}" name="rol">
                        <option value="Visitante" {{ $user->rol==='Visitante' ? 'selected':'' }}>Visitante</option>
                        <option value="Autor" {{ $user->rol==='Autor' ? 'selected':'' }}>Autor</option>
                        <option value="Administrador" {{ $user->rol==='Administrador' ? 'selected':'' }}>Administrador</option>
                    </select>
                    <button class="btn" type="submit">Guardar</button>
                    <a class="btn secondary" href="{{ route('ad.autores') }}">← Volver a Autores</a>
                </form>
            @endif
        </article>

        <!-- Ideas del autor -->
        <article class="card">
            <h2 class="title" style="margin-top:0">Ideas de {{ $user->apodo }}</h2>
            @if(($user->ideas ?? collect())->count())
                <ul class="list">
                    @foreach($user->ideas->take(10) as $idea)
                        <li>
                            {{ $idea->nombre }}
                            <span class="muted"> · {{ ucfirst(str_replace('-', ' ', $idea->tipo)) }}</span>
                            <span class="muted"> · {{ $idea->created_at?->format('d/m/Y') }}</span>
                            <a class="btn secondary" style="margin-left:8px" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">Ver</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="muted">No tiene ideas publicadas.</p>
            @endif
        </article>
    </section>

    <!-- Opiniones del autor -->
    <section class="card" style="margin-top:16px">
        <h2 class="title" style="margin-top:0">Opiniones de {{ $user->apodo }}</h2>
        @php $ops = $user->opiniones ?? collect(); @endphp
        @if($ops->count())
            <ul class="list">
                @foreach($ops->take(12) as $op)
                    <li>
                        #{{ $op->id }} — {{ \Illuminate\Support\Str::limit($op->texto, 120) }}
                        @if($op->idea) <span class="muted"> · en {{ $op->idea->nombre }}</span>@endif
                        <span class="muted"> · {{ $op->created_at?->format('d/m/Y H:i') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="muted">No tiene opiniones.</p>
        @endif
    </section>

    <div class="row" style="margin-top:12px">
        <a class="btn secondary" href="{{ route('ad.index') }}">← Panel</a>
        <a class="btn secondary" href="{{ route('ad.autores') }}">← Volver a Autores</a>
    </div>
</div>

</body>
</html>
