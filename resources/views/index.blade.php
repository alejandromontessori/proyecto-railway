<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    {{-- Iconos de Bootstrap para el botón de FAQ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('/imagenes/portada.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #000000;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        nav {
            background-color: rgba(73, 72, 71, 0.8);
            text-align: center;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        nav a {
            display: inline-block;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        nav a:hover {
            background-color: #86b7b2;
            border-radius: 5px;
            transform: scale(1.1);
        }

        .welcome-search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 40px;
            text-align: center;
        }

        footer {
            background-color: rgba(73, 72, 71, 0.8);
            color: white;
            text-align: center;
            padding: 20px 0;
            position: relative;
        }

        footer a {
            color: #fff;
        }

        /* ===== FAQ Overlay (estilos) ===== */
        .faq-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, .45);
            display: none;
        }

        .faq-panel {
            position: absolute;
            left: 50%;
            top: 52%;
            transform: translate(-50%, -50%);
            width: min(1000px, 94vw);
            max-height: 86vh;
            overflow: auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
            padding: 1.25rem 1.25rem 1.5rem;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        }

        .faq-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 2;
            padding-bottom: .5rem;
            border-bottom: 1px solid #eee;
        }

        .faq-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
        }

        .faq-close {
            background: transparent;
            border: 0;
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
            color: #333;
        }

        .faq-categories {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin: .9rem 0 1rem 0;
        }

        .faq-cat {
            border: 1px solid #ddd;
            padding: .4rem .7rem;
            border-radius: 999px;
            background: #f8f8f8;
            cursor: pointer;
            font-size: .92rem;
        }

        .faq-cat.active {
            background: #e0f7f4;         /* ANTES: #e7f1ff (azulado) */
            border-color: #86b7b2;       /* ANTES: #8ab6ff */
        }

        .faq-item {
            border: 1px solid #eee;
            border-radius: 10px;
            margin: .5rem 0;
            overflow: hidden;
        }

        .faq-q {
            background: #fafafa;
            padding: .75rem 1rem;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-a {
            display: none;
            padding: .85rem 1rem;
            background: #fff;
        }

        .faq-q .chev {
            transition: transform .2s ease;
        }

        .faq-item.open .faq-q .chev {
            transform: rotate(180deg);
        }

        .faq-footer-cta {
            text-align: center;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px dashed #e5e5e5;
        }

        .btn-ghost {
            display: inline-block;
            padding: .55rem 1rem;
            border-radius: 8px;
            border: 1px solid #86b7b2;
            background: #86b7b2;
            color: #fff;
            text-decoration: none;
        }

        /* Botón FAQ en el footer */
        .faq_icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #86b7b2;   
            color: #fff;
            text-decoration: none;
            border: 0;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>

<nav>
    @auth
        @if(Auth::user()->esAdmin())
            {{-- Ruta correcta del panel de administración --}}
            <a href="{{ route('ad.index') }}">Administración</a>
        @endif
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('opiniones') }}">Opiniones</a>
        <a href="{{ route('usuario.cuenta') }}">Mi Perfil</a>
        <a href="{{ route('autores.lista') }}">Autores</a>
        <a href="{{ route('usuario.logout') }}">Cerrar Sesión</a>
    @else
        <a href="{{ route('ideas') }}">Ideas</a>
        <a href="{{ route('login') }}">Iniciar Sesión</a>
        <a href="{{ route('registro.form') }}">Registrarse</a>
    @endauth
</nav>

<main>
    <div class="welcome-search-container">
        <h1>
            @auth
                Bienvenido de nuevo {{ Auth::user()->apodo }}
            @else
                Bienvenido a reutiliza, ¡Esperamos sorprenderte!
            @endauth
        </h1>
    </div>
</main>

<footer>
    {{-- Botón para abrir Preguntas Frecuentes --}}
    <button type="button" class="faq_icon" id="openFaq"
            aria-controls="faqOverlay" aria-expanded="false"
            title="Preguntas frecuentes">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <p>&copy; 2025 reutiliza.com. Todos los derechos reservados.</p>
    <p>
        <a href="https://www.facebook.com/foroplatos" target="_blank">Facebook</a> |
        <a href="https://www.instagram.com/foroplatos" target="_blank">Instagram</a> |
        <a href="https://twitter.com/foroplatos" target="_blank">X</a>
    </p>
</footer>

{{-- Overlay FAQ --}}
<div class="faq-overlay" id="faqOverlay" aria-hidden="true">
    <div class="faq-panel" role="dialog" aria-modal="true" aria-labelledby="faqTitle">
        <div class="faq-header-row">
            <h2 id="faqTitle" class="faq-title">Preguntas Frecuentes</h2>
            <button class="faq-close" id="closeFaq" aria-label="Cerrar">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Categorías --}}
        <div class="faq-categories" id="faqCats">
            <button class="faq-cat active" data-category="all">Todas</button>
            <button class="faq-cat" data-category="general">General</button>
            <button class="faq-cat" data-category="inscripcion">Inscripción</button>
            <button class="faq-cat" data-category="programas">Programas</button>
            <button class="faq-cat" data-category="certificados">Certificados</button>
        </div>

        {{-- Lista de preguntas (luego las adaptarás a reutiliza) --}}
        <div id="faqList">
            {{-- GENERAL --}}
            <div class="faq-item" data-category="general">
                <div class="faq-q">
                    <span>¿Qué es esta plataforma y para qué sirve?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        Es una comunidad donde se comparten ideas para reutilizar y reciclar objetos,
                        materiales y recursos, dándoles una segunda vida de forma creativa.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-category="general">
                <div class="faq-q">
                    <span>¿Tengo que registrarme para ver las ideas?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        No. Puedes navegar y ver las ideas sin registrarte. Solo necesitas cuenta si quieres
                        publicar tus propias ideas, opinar o guardar favoritas.
                    </p>
                </div>
            </div>

            {{-- INSCRIPCIÓN --}}
            <div class="faq-item" data-category="inscripcion">
                <div class="faq-q">
                    <span>¿Cómo me registro como usuario?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        Haz clic en “Registrarse” en la parte superior, rellena tus datos básicos y confirma
                        el registro. A partir de ahí podrás crear y gestionar tus ideas.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-category="inscripcion">
                <div class="faq-q">
                    <span>He olvidado mi contraseña, ¿qué puedo hacer?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        En la pantalla de inicio de sesión, usa la opción de recuperación de contraseña (si la tienes
                        implementada) o ponte en contacto con el administrador para restablecerla.
                    </p>
                </div>
            </div>

            {{-- PROGRAMAS --}}
            <div class="faq-item" data-category="programas">
                <div class="faq-q">
                    <span>¿Cómo puedo publicar una nueva idea reciclada?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        Una vez iniciada sesión, ve al apartado “Ideas” y utiliza el botón de crear o publicar idea.
                        Completa el título, descripción, materiales y, si quieres, añade imágenes.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-category="programas">
                <div class="faq-q">
                    <span>¿Puedo editar o borrar mis ideas?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        Sí. Desde tu perfil o desde el listado de tus ideas puedes modificarlas o eliminarlas cuando
                        lo necesites.
                    </p>
                </div>
            </div>

            {{-- CERTIFICADOS (aquí adaptado a “opiniones/favoritos”) --}}
            <div class="faq-item" data-category="certificados">
                <div class="faq-q">
                    <span>¿Cómo puedo dejar una opinión sobre una idea?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        En la ficha de cada idea encontrarás el apartado de opiniones. Inicia sesión,
                        escribe tu valoración y comentario y publícalo.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-category="certificados">
                <div class="faq-q">
                    <span>¿Puedo guardar ideas como favoritas?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>
                        Sí, si tienes cuenta. En la ficha de la idea verás la opción para marcarla
                        como favorita y luego consultarlas desde tu perfil.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-footer-cta">
            <p>¿No encuentras tu respuesta?</p>
            <a href="mailto:fundacion@lacaridad.org" class="btn-ghost">Escríbenos</a>
            {{-- Cambia el correo por el que quieras usar para soporte --}}
        </div>
    </div>
</div>

<script>
    (() => {
        const $overlay = document.getElementById('faqOverlay');
        const $open = document.getElementById('openFaq');
        const $close = document.getElementById('closeFaq');
        const $cats = document.getElementById('faqCats');
        const $list = document.getElementById('faqList');

        const open = () => {
            $overlay.style.display = 'block';
            $open?.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        };

        const close = () => {
            $overlay.style.display = 'none';
            $open?.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };

        $open?.addEventListener('click', open);
        $close?.addEventListener('click', close);
        $overlay?.addEventListener('click', (e) => {
            if (e.target === $overlay) close();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && $overlay.style.display === 'block') close();
        });

        // Filtro por categoría
        $cats?.addEventListener('click', (e) => {
            const btn = e.target.closest('.faq-cat');
            if (!btn) return;
            [...$cats.querySelectorAll('.faq-cat')]
                .forEach(b => b.classList.toggle('active', b === btn));
            const cat = btn.dataset.category;
            [...$list.children].forEach(item => {
                if (cat === 'all' || item.dataset.category === cat) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Acordeón de preguntas
        $list?.addEventListener('click', (e) => {
            const q = e.target.closest('.faq-q');
            if (!q) return;
            const item = q.parentElement;
            item.classList.toggle('open');
            const a = item.querySelector('.faq-a');
            a.style.display = item.classList.contains('open') ? 'block' : 'none';
        });
    })();
</script>

</body>
</html>
