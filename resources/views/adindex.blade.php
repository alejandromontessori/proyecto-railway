<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <style>
        :root{
            --ink:#0e1a18;
            --muted:#5b6f6d;
            --brand:#86b7b2;
            --brand-dark:#255d47;
            --brand-alt:#9fc7c3;
        }

        /* Fondo con imagen */
        body{
            font-family:Arial, sans-serif;
            background-image: url('{{ asset('imagenes/tejidos/cables.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color:#f0f0f0;
            margin:0;
        }

        nav{background:rgba(73,72,71,.8); text-align:center; padding:10px 0; margin-bottom:10px}
        nav a{display:inline-block; color:#fff; padding:12px 30px; text-decoration:none; font-size:18px; margin:0 15px; transition:background .3s, transform .3s}
        nav a:hover{background:#86b7b2; border-radius:5px; transform:scale(1.1)}
        .wrapper{max-width:1280px; margin:0 auto; padding:0 18px 28px}

        /* ===== Tarjetas KPI (no blancas) ===== */
        .cards{display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px}
        @media (max-width:1000px){.cards{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media (max-width:600px){.cards{grid-template-columns:1fr}}

        .card{
            border-radius:12px;
            padding:16px;
            box-shadow:0 8px 16px rgba(0,0,0,.08);
            border:1px solid rgba(255,255,255,.35);
            backdrop-filter: blur(2px);
        }

        /* Variantes de color translúcidas */
        .kpi-card{color:#fff;}
        .kpi-users   { background: linear-gradient(135deg, rgba(134,183,178,.88), rgba(37,93,71,.78)); }
        .kpi-authors { background: linear-gradient(135deg, rgba(37,93,71,.88), rgba(15,60,45,.78)); }
        .kpi-ideas   { background: linear-gradient(135deg, rgba(159,199,195,.90), rgba(134,183,178,.80)); }
        .kpi-opins   { background: linear-gradient(135deg, rgba(91,111,109,.88), rgba(14,26,24,.78)); }

        .kpi{font-size:32px; font-weight:800; margin:6px 0 2px}
        .muted{color:#eef6f5; font-size:13px; opacity:.9}
        .kpi-card .btn{background:#ffffff; color:#134836; text-decoration:none; padding:8px 12px; border-radius:8px; display:inline-block; margin-top:8px; font-weight:700}
        .kpi-card .btn:hover{background:#f3fbfa}

        h2{margin:16px 0 8px; font-size:20px; color:#0e1a18}

        /* ===== Listas MUY compactas y translúcidas ===== */
        .card.compacto{
            padding:6px 10px !important;
            border-radius:10px;
            background: rgba(255,255,255,0.70);
            border:1px solid rgba(255,255,255,0.40);
        }
        .card.compacto .list-compact{ list-style:none; padding:0; margin:0; }
        .card.compacto .list-compact li{
            padding:3px 0 !important;
            border-bottom:1px solid rgba(240,240,240,.8);
            line-height:1.24;
            font-size:14px;
            color:#18322e;
        }
        .card.compacto .list-compact li:last-child{ border-bottom:0; }
        .card.compacto .stamp{ display:inline-block; margin-left:8px; }
        .card.compacto .muted{ font-size:12px; color:#5b6f6d; opacity:.9; }

        /* Opción 1: mismo ancho para todos los bloques compacto */
        .card.compacto{
            width: 100%;
            max-width: 720px;      /* AJUSTA aquí: 520–760px suele quedar bien */
            margin-left: auto;
            margin-right: auto;
        }

        /* Opción 2 (opcional): aún más estrecho en pantallas grandes */
        @media (min-width: 1200px){
            .card.compacto{ max-width: 640px; }
        }

        /* Si quieres afinar por sección sin tocar HTML, usamos el patrón h2 + card */
        .wrapper > h2 + .card.compacto{
            /* puedes dar un ancho distinto a estas listas en concreto */
            /* max-width: 680px; */
        }

        /* Tarjetas "compacto" alineadas a la izquierda bajo cada H2 */
        .wrapper > h2 + .card.compacto{
            display: block;
            width: 100%;
            max-width: 640px;                 /* ajusta el ancho deseado */
            margin-left: 0 !important;        /* pegado a la izquierda */
            margin-right: auto !important;    /* libera el lado derecho */
            float: none !important;           /* anula floats anteriores */
            clear: both;                      /* evita solaparse con otros floats */
            text-align: left;                 /* contenido alineado a la izquierda */
        }

        /* En móviles: ocupar todo el ancho */
        @media (max-width: 700px){
            .wrapper > h2 + .card.compacto{
                max-width: none;
            }
        }

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
    <div class="cards">
        <!-- Usuarios -->
        <div class="card kpi-card kpi-users">
            <div class="muted">Usuarios</div>
            <div class="kpi">{{ $usuariosCount }}</div>
            <a class="btn" href="{{ route('ad.usuarios') }}">Ver usuarios</a>
        </div>

        <!-- Autores -->
        <div class="card kpi-card kpi-authors">
            <div class="muted">Autores</div>
            <div class="kpi">{{ $autoresCount }}</div>
            <a class="btn" href="{{ route('ad.autores') }}">Ver autores</a>
        </div>

        <!-- Ideas -->
        <div class="card kpi-card kpi-ideas">
            <div class="muted">Ideas</div>
            <div class="kpi">{{ $ideasCount }}</div>
            <a class="btn" href="{{ route('ad.ideas') }}">Ver ideas</a>
        </div>

        <!-- Opiniones -->
        <div class="card kpi-card kpi-opins">
            <div class="muted">Opiniones</div>
            <div class="kpi">{{ $opinionesCount }}</div>
            <a class="btn" href="{{ route('ad.opiniones') }}">Ver opiniones</a>
        </div>
    </div>

    <!-- Últimos usuarios -->
    <h2>Últimos usuarios</h2>
    <div class="card compacto">
        <ul class="list-compact">
            @forelse($usuariosRecientes as $u)
                <li>
                    {{ $u->apodo }} — {{ $u->email }}
                    @if($u->created_at)
                        <span class="muted stamp">· Creado: {{ $u->created_at->format('d/m/Y H:i') }}</span>
                    @endif
                    @if($u->updated_at)
                        <span class="muted stamp">· Actualizado: {{ $u->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </li>
            @empty
                <li class="muted">Sin usuarios todavía.</li>
            @endforelse
        </ul>
    </div>

    <!-- Últimos autores (nuevo) -->
    <h2>Últimos autores</h2>
    <div class="card compacto">
        <ul class="list-compact">
            @forelse($autoresRecientes as $a)
                <li>
                    {{ $a->apodo }} — {{ $a->email }}
                    @if($a->created_at)
                        <span class="muted stamp">· Creado: {{ $a->created_at->format('d/m/Y H:i') }}</span>
                    @endif
                    @if($a->updated_at)
                        <span class="muted stamp">· Actualizado: {{ $a->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </li>
            @empty
                <li class="muted">Sin autores todavía.</li>
            @endforelse
        </ul>
    </div>

    <!-- Últimas ideas -->
    <h2>Últimas ideas</h2>
    <div class="card compacto">
        <ul class="list-compact">
            @forelse($ideasRecientes as $i)
                <li>
                    {{ $i->nombre }}
                    @if($i->autor) — <span class="muted">{{ $i->autor->apodo }}</span>@endif
                    @if($i->created_at)
                        <span class="muted stamp">· Creada: {{ $i->created_at->format('d/m/Y H:i') }}</span>
                    @endif
                    @if($i->updated_at)
                        <span class="muted stamp">· Actualizada: {{ $i->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </li>
            @empty
                <li class="muted">Sin ideas todavía.</li>
            @endforelse
        </ul>
    </div>

    <!-- Últimas opiniones -->
    <h2>Últimas opiniones</h2>
    <div class="card compacto">
        <ul class="list-compact">
            @forelse($opinionesRecientes as $op)
                <li>
                    #{{ $op->id }} — {{ \Illuminate\Support\Str::limit($op->texto, 80) }}
                    @if($op->autor) <span class="muted"> · {{ $op->autor->apodo }}</span>@endif
                    @if($op->idea)  <span class="muted"> · en {{ $op->idea->nombre }}</span>@endif
                    @if($op->created_at)
                        <span class="muted stamp">· Creada: {{ $op->created_at->format('d/m/Y H:i') }}</span>
                    @endif
                    @if($op->updated_at)
                        <span class="muted stamp">· Actualizada: {{ $op->updated_at->format('d/m/Y H:i') }}</span>
                    @endif
                </li>
            @empty
                <li class="muted">Sin opiniones todavía.</li>
            @endforelse
        </ul>
    </div>
</div>
</body>
</html>
