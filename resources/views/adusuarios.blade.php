<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios (Admin)</title>
    <style>
        :root{--ink:#0e1a18;--muted:#5b6f6d;--brand:#86b7b2;--brand-dark:#255d47;--bg:#ffffff;}
        body{font-family:Arial,sans-serif;color:var(--ink);margin:0;background-color:var(--bg);
            background-image: url('{{ asset('imagenes/tejidos/cupcakes.jpg') }}');
            background-size: cover;background-position: center;background-attachment: fixed;}
        nav{background:rgba(73,72,71,.8);text-align:center;padding:10px 0;margin-bottom:10px}
        nav a{display:inline-block;color:#fff;padding:12px 30px;text-decoration:none;font-size:18px;margin:0 15px;transition:.3s}
        nav a:hover{background:var(--brand);border-radius:5px;transform:scale(1.05)}
        .wrapper{max-width:1100px;margin:0 auto;padding:18px}
        .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        @media (max-width:900px){.grid{grid-template-columns:1fr}}
        .card{
            background: rgba(255,255,255,0.65); /* translúcido */
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 16px rgba(0,0,0,.06);
        }
        .avatar{width:72px;height:72px;border-radius:50%;object-fit:cover;background:#eee}
        .muted{color:var(--muted)}
        .pager{display:flex;justify-content:center;align-items:center;gap:10px;margin-top:18px}
        .btn{display:inline-block;background:var(--brand);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
        .btn:hover{background:var(--brand-dark)}
        .btn.secondary{background:#e7efee;color:var(--brand-dark)}
        .btn.secondary:hover{background:#dfe8e7}
        .btn.danger{background:#c0392b}
        details summary{cursor:pointer;user-select:none;margin-top:8px;color:#134836;font-weight:700}
        details{margin-top:8px}
        form{margin:0}
        input,select{width:100%;padding:8px;border:1px solid #ddd;border-radius:8px;margin-top:6px}
        .row{display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap}
        .col{flex:1 1 220px}
        .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
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
    <h1>Usuarios</h1>

    @if(session('success')) <div class="flash ok">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="flash err">{{ session('error') }}</div> @endif

    @if($usuarios->count())
        <section class="grid">
            @foreach($usuarios as $u)
                <article class="card">
                    <div class="row">
                        <img class="avatar" src="{{ $u->foto_perfil ? asset($u->foto_perfil) : asset('imagenes/placeholder.jpg') }}" alt="Avatar">
                        <div style="flex:1">
                            <h3 style="margin:0 0 6px">{{ $u->apodo }}</h3>
                            <div class="muted">{{ $u->nombre }} {{ $u->apellidos }} · {{ $u->email }}</div>
                            <div class="muted">Rol: {{ $u->rol }} · Alta: {{ $u->created_at?->format('d/m/Y') }}</div>

                            <div class="actions">
                                <a class="btn secondary" href="{{ route('ad.usuarios.ver', $u->id) }}">Ver perfil</a>

                                {{-- Editar inline (nombre, apellidos, email y rol) --}}
                                <details>
                                    <summary>Editar</summary>
                                    <form action="{{ route('ad.usuarios.actualizar', $u->id) }}" method="POST" style="margin-top:8px">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row">
                                            <div class="col">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" value="{{ old('nombre', $u->nombre) }}">
                                            </div>
                                            <div class="col">
                                                <label>Apellidos</label>
                                                <input type="text" name="apellidos" value="{{ old('apellidos', $u->apellidos) }}">
                                            </div>
                                            <div class="col">
                                                <label>Email</label>
                                                <input type="email" name="email" value="{{ old('email', $u->email) }}">
                                            </div>
                                            <div class="col">
                                                <label>Rol</label>
                                                <select name="rol">
                                                    @foreach(['Visitante','Autor','Administrador'] as $r)
                                                        <option value="{{ $r }}" {{ $u->rol===$r?'selected':'' }}>{{ $r }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <button type="submit" class="btn">Guardar</button>
                                        </div>
                                    </form>
                                </details>

                                {{-- Eliminar --}}
                                @if(auth()->id() !== $u->id)
                                    <form action="{{ route('ad.usuarios.eliminar', $u->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario? Se borrarán también sus ideas y opiniones.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn danger">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        @if($usuarios->total() > $usuarios->perPage())
            <div class="pager">
                <a class="btn" href="{{ $usuarios->previousPageUrl() }}" @if($usuarios->onFirstPage()) disabled @endif>← Anterior</a>
                <span>Página {{ $usuarios->currentPage() }} de {{ $usuarios->lastPage() }}</span>
                <a class="btn" href="{{ $usuarios->nextPageUrl() }}" @if(!$usuarios->hasMorePages()) disabled @endif>Siguiente →</a>
            </div>
        @endif
    @else
        <p class="muted">No hay usuarios.</p>
    @endif
</div>
</body>
</html>
