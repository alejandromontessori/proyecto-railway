<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <style>
        :root{
            --c-text:#333;
            --c-primary:#00cccc;
            --c-primary-hover:#0099a1;
            --c-link:#007b8f;
            --c-card-bg: rgba(255,255,255,0.65);
            --c-border:#ccc;
            --c-darkbar: rgba(73,72,71,0.8);
        }

        body {
            font-family: Arial, sans-serif;
            color: var(--c-text);
            background-image: url('{{ asset('imagenes/tejidos/cupcakes.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding-top: 80px;
            min-height: 100vh;
        }

        nav {
            background-color: var(--c-darkbar);
            padding: 12px 0; text-align: center;
            position: fixed; top: 0; width: 100%; z-index: 999;
        }
        nav a {
            color: white; text-decoration: none;
            padding: 12px 20px; display: inline-block; font-size: 18px; margin: 0 8px;
            transition: background-color .3s, transform .3s;
        }
        nav a:hover { background-color: var(--c-primary); border-radius: 5px; transform: scale(1.06); }

        .wrap { max-width: 1100px; margin: 20px auto 40px; padding: 0 16px; }
        .layout {
            display: grid; gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        }

        .bloque {
            background: var(--c-card-bg); border: 1px solid var(--c-border);
            padding: 16px; border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        h1, h2 { margin-top: 0; color: var(--c-link); text-align: left; }
        .fila { display:flex; gap:16px; align-items:center; }
        img.avatar { border-radius: 50%; width: 110px; height:110px; object-fit: cover; background:#e5e5e5; border:2px solid var(--c-primary); }

        .scroll { max-height: 360px; overflow-y: auto; }
        ul { list-style: none; padding: 0; margin: 0; }
        li { background: #fff; border:1px solid var(--c-border); margin: 10px 0; padding: 12px; border-radius: 8px; }

        label { display:block; margin-bottom:5px; font-weight: bold; }
        input, select, textarea {
            width: 100%; padding: 10px; margin-top: 6px; margin-bottom: 12px;
            border-radius: 5px; border: 1px solid #ccc; background:#fff; font-size:14px;
        }
        input[type="file"] { padding: 5px; }
        textarea { resize: vertical; }

        .grid-2 { display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:14px; }
        .grid-3 { display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:14px; }

        button, .btn { background-color: var(--c-primary); border: none; color: white; padding: 10px 16px; border-radius: 5px; cursor: pointer; text-decoration:none; display:inline-block; font-weight:600; }
        button:hover, .btn:hover { background-color: var(--c-primary-hover); }
        .btn.danger { background:#d9534f; }
        .acciones { display:flex; gap:8px; flex-wrap:wrap; }

        .flash{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724;padding:8px;border-radius:8px;margin:12px 0;}
        .error{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000;padding:10px;border-radius:6px;margin:12px 0;}

        .muted { color:#555; font-size: 13px; }
        .sep { height:1px; background:#ddd; margin: 16px 0; border:0; }
        footer { background-color: var(--c-darkbar); color: white; text-align: center; padding: 20px 0; }
        .idea-mini { display:flex; gap:12px; align-items:flex-start; }
        .idea-mini img { width:110px; height:80px; object-fit:cover; border-radius:6px; background:#eee; }
        .titulo-idea { color: var(--c-link); font-weight: bold; text-decoration: none; }
        .titulo-idea:hover { text-decoration: underline; }

        .layout .modificar{ grid-column: 1 / -1; }
        .modificar .grid-2{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
        }
        .modificar .grid-3{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
        }
        .modificar input[type="file"]{ padding: 6px; }
        .modificar .sep{ margin: 18px 0; }

        *, *::before, *::after{ box-sizing: border-box; }
        .grid-2 > div, .grid-3 > div{ min-width: 0; }
        input, select, textarea{ max-width: 100%; }
        .fila > *{ min-width: 0; }
        .bloque form{ width: 100%; max-width: 100%; }
    </style>
</head>
<body>
<nav>
    @auth
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('opiniones') }}">Opiniones</a>
        <a href="{{ route('autores.lista') }}">Autores</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @endauth
</nav>

<div class="wrap">
    @if(session('success')) <div class="flash">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="error">{{ session('error') }}</div>   @endif
    @if($errors->any())
        <div class="error">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <h1>Perfil de {{ $user->apodo }}</h1>

    <section class="layout">
        <!-- DATOS BÁSICOS -->
        <div class="bloque">
            <div class="fila">
                <img class="avatar" src="{{ $user->foto_perfil ? asset($user->foto_perfil) : asset('imagenes/placeholder.jpg') }}" alt="Foto de perfil">
                <div>
                    <p><strong>Nombre:</strong> {{ $user->nombre }}</p>
                    <p><strong>Apellidos:</strong> {{ $user->apellidos }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Rol:</strong> {{ $user->rol }}</p>
                    <p class="muted"><strong>Registrado el:</strong> {{ $user->created_at?->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        @auth
            @if(Auth::id() === $user->id)
                <!-- MODIFICAR MIS DATOS -->
                <div class="bloque modificar">
                    <h2>Modificar mis datos</h2>
                    <form method="POST" action="{{ route('cuenta.modificar') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid-2">
                            <div>
                                <label>Apodo</label>
                                <input type="text" name="apodo" value="{{ old('apodo', $user->apodo) }}">
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}">
                            </div>
                            <div>
                                <label>Nombre</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}">
                            </div>
                            <div>
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}">
                            </div>
                            <div>
                                <label>Rol</label>
                                <select name="rol">
                                    @foreach(['Visitante','Autor','Administrador'] as $r)
                                        <option value="{{ $r }}" {{ $user->rol===$r ? 'selected':'' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label>Foto de perfil</label>
                                <input type="file" name="fotoPerfil" accept="image/*">
                            </div>
                        </div>

                        <hr class="sep">

                        <div class="grid-3">
                            <div>
                                <label>Contraseña actual</label>
                                <input type="password" name="pwdActual">
                            </div>
                            <div>
                                <label>Nueva contraseña</label>
                                <input type="password" name="pwd">
                            </div>
                            <div>
                                <label>Confirmar nueva contraseña</label>
                                <input type="password" name="pwd_confirmation">
                            </div>
                        </div>

                        <button type="submit" class="btn">Guardar cambios</button>
                    </form>
                </div>

                <!-- ELIMINAR CUENTA -->
                <div class="bloque">
                    <h2>Eliminar mi cuenta</h2>
                    <form method="POST" action="{{ route('cuenta.eliminar') }}">
                        @csrf
                        <label>Contraseña</label>
                        <input type="password" name="pwd" required>

                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmarPwd" required>

                        <label class="muted" style="display:flex;gap:8px;align-items:center;">
                            <input type="checkbox" name="casilla" required>
                            Estoy seguro de que quiero eliminar mi cuenta
                        </label>

                        <button type="submit" class="btn danger">Eliminar mi cuenta</button>
                    </form>
                </div>
            @endif
        @endauth

        <!-- MIS IDEAS -->
        <div class="bloque">
            <h2>Mis Ideas</h2>

            @auth
                @php
                    $esMiPerfil = Auth::id() === $user->id;
                    $rolUser    = strtolower($user->rol ?? '');
                    $puedeCrear = in_array($rolUser, ['autor','administrador']);
                @endphp

                @if($esMiPerfil && $puedeCrear)
                    <details style="background:#fff;border:1px solid #ccc;padding:10px;border-radius:8px;margin-bottom:12px;">
                        <summary style="cursor:pointer;font-weight:bold;color:var(--c-link);">Crear nueva idea</summary>
                        <form method="POST" action="{{ route('ideas.store') }}" enctype="multipart/form-data" style="margin-top:10px;">
                            @csrf
                            <div class="grid-2">
                                <div>
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" required>
                                </div>
                                <div>
                                    <label>Tipo</label>
                                    <select name="tipo" required>
                                        <option value="">Selecciona</option>
                                        <option value="madera">Madera</option>
                                        <option value="plastico-metal">Plástico / Metal</option>
                                        <option value="tejidos">Tejidos</option>
                                        <option value="carton">Cartón</option>
                                    </select>
                                </div>
                            </div>

                            <label>Descripción</label>
                            <textarea name="descripcion" rows="3" required></textarea>

                            <label>Foto (opcional)</label>
                            <input type="file" name="fotoIdea" accept="image/*">

                            <button type="submit" class="btn">Publicar idea</button>
                        </form>
                    </details>
                @endif
            @endauth

            <div class="scroll">
                @if($ideas->isEmpty())
                    <p class="muted">No hay ideas para este usuario.</p>
                @else
                    <ul>
                        @foreach($ideas as $idea)
                            <li>
                                <div class="idea-mini">
                                    <img src="{{ $idea->fotoIdea ? asset($idea->fotoIdea) : asset('imagenes/placeholder.jpg') }}" alt="Foto de {{ $idea->nombre }}">
                                    <div style="flex:1;">
                                        <a class="titulo-idea" href="{{ route('idea.detalle', ['titulo' => urlencode($idea->nombre)]) }}">
                                            {{ $idea->nombre }}
                                        </a>
                                        <div class="muted">{{ ucfirst($idea->tipo) }}</div>
                                        <div class="muted">{{ \Illuminate\Support\Str::limit($idea->descripcion, 180) }}</div>

                                        @auth
                                            @if(Auth::id() === $user->id && $puedeCrear)
                                                <details style="margin-top:10px;">
                                                    <summary style="cursor:pointer;color:var(--c-link);">Editar esta idea</summary>

                                                    <form method="POST" action="{{ route('ideas.actualizar', $idea->id) }}" enctype="multipart/form-data" style="margin-top:10px;">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="grid-2">
                                                            <div>
                                                                <label>Nombre</label>
                                                                <input type="text" name="nombre" value="{{ old('nombre', $idea->nombre) }}" required>
                                                            </div>
                                                            <div>
                                                                <label>Tipo</label>
                                                                <select name="tipo" required>
                                                                    @foreach(['madera','plastico-metal','tejidos','carton'] as $t)
                                                                        <option value="{{ $t }}" {{ $idea->tipo===$t ? 'selected':'' }}>
                                                                            {{ ucfirst($t) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <label>Descripción</label>
                                                        <textarea name="descripcion" rows="3" required>{{ old('descripcion', $idea->descripcion) }}</textarea>

                                                        <label>Nueva foto (opcional)</label>
                                                        <input type="file" name="fotoIdea" accept="image/*">

                                                        <div class="acciones" style="margin-top:8px;">
                                                            <button type="submit" class="btn">Guardar cambios</button>
                                                        </div>
                                                    </form>

                                                    <form method="POST" action="{{ route('ideas.eliminar', $idea->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta idea?');" style="margin-top:10px;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn danger">Eliminar</button>
                                                    </form>
                                                </details>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- MIS OPINIONES -->
        <div class="bloque">
            <h2>Mis Opiniones</h2>
            <div class="scroll">
                @if($opiniones->isEmpty())
                    <p class="muted">No hay opiniones para este usuario.</p>
                @else
                    <ul>
                        @foreach($opiniones as $op)
                            <li>
                                <strong>#{{ $op->id }}</strong> —
                                <em>{{ $op->idea->nombre ?? 'Idea eliminada' }}</em><br>
                                {{ $op->texto }}
                                <div class="muted">{{ $op->created_at?->format('d/m/Y H:i') }} · {{ $op->valoracion }} ⭐</div>
                                @if($op->responde)
                                    <div class="muted" style="margin-top:6px;">
                                        En respuesta a #{{ $op->responde->id }}: “{{ \Illuminate\Support\Str::limit($op->responde->texto, 100) }}”
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
</div>

<footer>
    &copy; 2025 reutiliza.com. Todos los derechos reservados.
</footer>
</body>
</html>
