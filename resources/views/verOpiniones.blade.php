<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Opiniones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        /* Paleta y estética como registro_usuario */
        :root{
            --c-text:#333333;
            --c-primary:#00cccc;
            --c-primary-hover:#0099a1;
            --c-link:#007b8f;
            --c-card-bg: rgba(255,255,255,0.65);
            --c-border:#cccccc;
            --c-darkbar: rgba(73,72,71,0.8);
        }

        *{ box-sizing:border-box; }
        body{
            font-family: Arial, sans-serif;
            color:#000;
            margin:0;
            min-height:100vh; display:flex; flex-direction:column;
            /* Fondo solicitado */
            background-image: url('{{ asset('imagenes/tejidos/posavasos.jpg') }}');
            background-size:cover; background-position:center; background-attachment:fixed;
            padding-top:70px; /* espacio para nav fija */
        }

        /* NAV consistente con el resto */
        nav{
            background-color: var(--c-darkbar);
            text-align: center;
            padding: 10px 0;
            position: fixed; top:0; left:0; right:0; z-index: 10;
        }
        nav a{
            color: #fff;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color .3s ease, transform .3s ease;
        }
        nav a:hover{ background-color: var(--c-primary); border-radius: 5px; transform: scale(1.06); }

        /* Contenedor principal */
        main{ flex:1; width:100%; max-width:1200px; margin:0 auto; padding: 20px 18px 28px; }

        h1{ margin: 8px 0 16px; color: var(--c-link); }

        /* Tarjeta de formulario y avisos con estética registro */
        .card{
            background: var(--c-card-bg);
            border:1px solid var(--c-border);
            border-radius:10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            padding:16px;
            margin-bottom:22px;
        }

        .formulario { /* mantiene tu clase, ahora con la tarjeta */
            background: transparent;
            border: none;
            padding: 0;
            border-radius: 0;
            margin-bottom: 0;
        }

        .formulario label { display:block; font-weight:700; margin-top:10px; color:#333; }
        .formulario textarea, .formulario select, .formulario input[type="number"]{
            width:100%; padding:10px; border:1px solid #ccd3d6; border-radius:8px; margin-top:6px; background:#fff;
        }

        .formulario button{
            margin-top:14px; background: var(--c-primary); color:#fff; border:none; border-radius:8px;
            padding:10px 18px; cursor:pointer; font-weight:600;
            transition: background-color .2s ease;
        }
        .formulario button:hover{ background: var(--c-primary-hover); }

        /* Caja de login requerido */
        .notice-login{ background:#fff7e5; border:1px solid #ffe0a6; color:#7a5300; border-radius:10px; padding:14px; }

        /* Paginación en la misma paleta */
        .pager{ display:flex; justify-content:center; align-items:center; gap:10px; margin: 18px 0; color:#333; }
        .btn{ background: var(--c-primary); color:#fff; text-decoration:none; padding:8px 12px; border-radius:8px; font-weight:600; }
        .btn[disabled]{ opacity:.5; pointer-events:none; }

        /* Hilos: puedes envolver cada raíz en tarjeta si quieres que todo tenga fondo translúcido */
        .thread-card{
            background: var(--c-card-bg);
            border:1px solid var(--c-border);
            border-radius:10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            padding:14px;
            margin-bottom:14px;
        }

        footer{
            background-color: var(--c-darkbar);
            color: white; text-align: center; padding: 20px 0;
        }

        /* Mensajes de estado (como ya usabas) */
        .ok{background:#e7f7ed;border:1px solid #bfe8cd;color:#155724;padding:8px;border-radius:8px;margin-bottom:10px;}
        .err{background:#ffe9e9;border:1px solid #ffb3b3;color:#a40000;padding:10px;border-radius:6px;margin-bottom:12px;}


        /* === Anchos reducidos para algunos campos del formulario === */
        @media (min-width: 640px){
            /* Idea, Valoración e ID de respuesta: más estrechos */
            #id_idea,
            #valoracion,
            #id_respondido{
                max-width: 360px;   /* ajusta a tu gusto: 300–420px */
                display: block;
            }

            /* Texto de la opinión: algo más ancho pero no a todo lo ancho */
            #texto{
                max-width: 560px;   /* ajusta a tu gusto: 480–640px */
                display: block;
            }
        }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            <a href="{{ route('ad.index') }}">Administración</a>
        @endif
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('autores.lista') }}">Autores</a>
        <a href="{{ route('ideas') }}">Ideas</a>
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
    <h1>Opiniones de la comunidad</h1>

    {{-- Formulario: solo usuarios logueados --}}
    @auth
        <section class="card">
            <div class="formulario">
                @if (session('success'))
                    <div class="ok">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="err">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('opiniones.guardar') }}" method="POST">
                    @csrf
                    <label for="id_idea">Idea</label>
                    <select name="id_idea" id="id_idea" required>
                        <option value="">Selecciona una idea…</option>
                        @foreach($ideas as $idea)
                            <option value="{{ $idea->id }}">{{ $idea->nombre }}</option>
                        @endforeach
                    </select>

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
            </div>
        </section>
    @else
        <section class="card">
            <div class="notice-login">
                Para opinar necesitas iniciar sesión. <a href="{{ route('login') }}">Entrar</a>
            </div>
        </section>
    @endauth

    {{-- LISTADO EN HILOS (opinión raíz + respuestas recursivas) --}}
    @forelse($opiniones as $op)
        <div class="thread-card">
            @include('verOpinionesRaiz', ['opinion' => $op])
        </div>
    @empty
        <p style="color:#333;">No hay opiniones todavía.</p>
    @endforelse

    {{-- Paginación de opiniones raíz --}}
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
    <p>
        <a href="https://www.facebook.com/foroplatos" target="_blank">Facebook</a> |
        <a href="https://www.instagram.com/foroplatos" target="_blank">Instagram</a> |
        <a href="https://twitter.com/foroplatos" target="_blank">X</a>
    </p>
</footer>

</body>
</html>
