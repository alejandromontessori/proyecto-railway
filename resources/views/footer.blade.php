<link rel="stylesheet" href="{{ asset('css/Footer/footer.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<style>
    /* ===== FAQ Overlay (estilos locales al componente) ===== */
    .faq-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,.45); display: none;
    }
    .faq-panel {
        position: absolute; left: 50%; top: 52%;
        transform: translate(-50%, -50%);
        width: min(1000px, 94vw); max-height: 86vh; overflow: auto;
        background: #fff; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.2);
        padding: 1.25rem 1.25rem 1.5rem;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji";
    }
    .faq-header-row{
        display:flex; align-items:center; justify-content:space-between; gap:1rem;
        position: sticky; top:0; background:#fff; z-index: 2; padding-bottom:.5rem; border-bottom:1px solid #eee;
    }
    .faq-title{ font-size:1.35rem; font-weight:700; margin:0; }
    .faq-close{
        background: transparent; border:0; font-size:1.6rem; line-height:1; cursor:pointer; color:#333;
    }
    .faq-categories{
        display:flex; gap:.5rem; flex-wrap:wrap; margin: .9rem 0 1rem 0;
    }
    .faq-cat{
        border:1px solid #ddd; padding:.4rem .7rem; border-radius:999px; background:#f8f8f8; cursor:pointer; font-size:.92rem;
    }
    .faq-cat.active{ background:#e7f1ff; border-color:#8ab6ff; }

    .faq-item{ border:1px solid #eee; border-radius:10px; margin:.5rem 0; overflow:hidden; }
    .faq-q{
        background:#fafafa; padding:.75rem 1rem; cursor:pointer; font-weight:600; display:flex; justify-content:space-between; align-items:center;
    }
    .faq-a{
        display:none; padding: .85rem 1rem; background:#fff;
    }
    .faq-q .chev{ transition: transform .2s ease; }
    .faq-item.open .faq-q .chev{ transform: rotate(180deg); }
    .faq-footer-cta{
        text-align:center; padding-top: 1rem; margin-top:1rem; border-top:1px dashed #e5e5e5;
    }
    .btn-ghost{
        display:inline-block; padding:.55rem 1rem; border-radius:8px;
        border:1px solid #2e6cff; background:#2e6cff; color:#fff; text-decoration:none;
    }

    /* Botón flotante/ícono FAQ en el footer */
    .faq_icon{
        display:inline-flex; align-items:center; justify-content:center;
        width:44px; height:44px; border-radius:50%;
        background:#2e6cff; color:#fff; text-decoration:none; border:0; cursor:pointer;
    }
</style>

<footer>
    {{-- Cambiamos el enlace por un botón que abre el overlay --}}
    <button type="button" class="faq_icon" id="openFaq" aria-controls="faqOverlay" aria-expanded="false" title="Preguntas frecuentes">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <div class="footer-container">
        <div class="footer-item">
            <h3>DÓNDE ESTAMOS</h3>
            <p>c/ Moret nº 4</p>
            <p>50001 Zaragoza</p>
        </div>
        <div class="footer-item">
            <h3>CONTÁCTENOS</h3>
            <p>Tfnos. 976 22 48 34 / 976 22 88 91</p>
            <p>Email: fundacion@lacaridad.org</p>
        </div>
        <div class="footer-item">
            <h3>SÍGUENOS</h3>
            <div class="social-icons">
                <a href="#"><img src="{{ asset('Img/Footer/facebook.png') }}" alt="Facebook"></a>
                <a href="#"><img src="{{ asset('Img/Footer/twitter.png') }}" alt="Twitter"></a>
                <a href="https://www.youtube.com/channel/UCMSIxaOXKx35Y5kH_L394dA"><img src="{{ asset('Img/Footer/youtube.png') }}" alt="YouTube"></a>
                <a href="#"><img src="{{ asset('Img/Footer/instagram.png') }}" alt="Instagram"></a>
            </div>
        </div>
    </div>
</footer>

{{-- ===== Overlay FAQ dentro del mismo componente ===== --}}
<div class="faq-overlay" id="faqOverlay" aria-hidden="true">
    <div class="faq-panel" role="dialog" aria-modal="true" aria-labelledby="faqTitle">
        <div class="faq-header-row">
            <h2 id="faqTitle" class="faq-title">Preguntas Frecuentes</h2>
            <button class="faq-close" id="closeFaq" aria-label="Cerrar"><i class="bi bi-x-lg"></i></button>
        </div>

        {{-- Categorías --}}
        <div class="faq-categories" id="faqCats">
            <button class="faq-cat active" data-category="all">Todas</button>
            <button class="faq-cat" data-category="general">General</button>
            <button class="faq-cat" data-category="inscripcion">Inscripción</button>
            <button class="faq-cat" data-category="programas">Programas</button>
            <button class="faq-cat" data-category="certificados">Certificados</button>
        </div>

        {{-- Ítems (contenido reusado de tu vista de FAQ) --}}
        <div id="faqList">
            {{-- GENERAL --}}
            <div class="faq-item" data-category="general">
                <div class="faq-q">
                    <span>¿Qué es el voluntariado y por qué es importante?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>El voluntariado es ofrecer tiempo y habilidades sin compensación económica. Es importante porque:</p>
                    <ul>
                        <li>Genera impacto en la comunidad</li>
                        <li>Promueve solidaridad y empatía</li>
                        <li>Desarrolla habilidades y experiencias</li>
                        <li>Conecta a las personas</li>
                        <li>Aporta a soluciones sociales y ambientales</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item" data-category="general">
                <div class="faq-q">
                    <span>¿Qué tipos de actividades de voluntariado ofrecen?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ul>
                        <li><b>Educación:</b> Apoyo escolar, alfabetización</li>
                        <li><b>Medio ambiente:</b> Reforestación, limpieza</li>
                        <li><b>Asistencia social:</b> Mayores, bancos de alimentos</li>
                        <li><b>Salud:</b> Acompañamiento, prevención</li>
                        <li><b>Cultura:</b> Patrimonio, actividades artísticas</li>
                        <li><b>Tecnología:</b> Web, alfabetización digital</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item" data-category="general">
                <div class="faq-q">
                    <span>¿Cuánto tiempo necesito dedicar al voluntariado?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ul>
                        <li><b>Puntual:</b> Un día o fin de semana</li>
                        <li><b>Regular:</b> 2–4 horas/semana</li>
                        <li><b>Intensivo:</b> 1–3 meses</li>
                        <li><b>Virtual:</b> Horario flexible</li>
                    </ul>
                </div>
            </div>

            {{-- INSCRIPCIÓN --}}
            <div class="faq-item" data-category="inscripcion">
                <div class="faq-q">
                    <span>¿Cómo puedo inscribirme como voluntario/a?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ol>
                        <li>Rellena el formulario en “Únete”</li>
                        <li>Completa tu perfil</li>
                        <li>Asiste a la orientación</li>
                        <li>Elige programas</li>
                        <li>Firma el acuerdo</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item" data-category="inscripcion">
                <div class="faq-q">
                    <span>¿Existen requisitos de edad?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ul>
                        <li>Generalmente +18 años</li>
                        <li>14–17 con autorización</li>
                        <li>Algunos programas piden +21</li>
                        <li>Sin límite por arriba</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item" data-category="inscripcion">
                <div class="faq-q">
                    <span>¿Necesito experiencia previa?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>No en la mayoría, damos formación. Algunos especializados sí la requieren.</p>
                </div>
            </div>

            {{-- PROGRAMAS --}}
            <div class="faq-item" data-category="programas">
                <div class="faq-q">
                    <span>¿Puedo participar en más de un programa?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>Sí, siempre que puedas cumplir tiempos. Recomendamos empezar por uno.</p>
                </div>
            </div>

            <div class="faq-item" data-category="programas">
                <div class="faq-q">
                    <span>¿Tienen programas internacionales?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>Sí, con entidades aliadas. Suelen requerir selección y cubrir costes de viaje/seguro.</p>
                </div>
            </div>

            <div class="faq-item" data-category="programas">
                <div class="faq-q">
                    <span>¿Qué formación se ofrece?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ul>
                        <li><b>Básica:</b> Principios, derechos, protección de datos</li>
                        <li><b>Específica:</b> Según programa</li>
                        <li><b>Continua:</b> Talleres mensuales</li>
                        <li><b>Tutorías:</b> Acompañamiento inicial</li>
                    </ul>
                </div>
            </div>

            {{-- CERTIFICADOS --}}
            <div class="faq-item" data-category="certificados">
                <div class="faq-q">
                    <span>¿Entregan certificados?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <ul>
                        <li>Participación (programa y horas)</li>
                        <li>Competencias (desde 50h)</li>
                        <li>Carta de recomendación (+100h)</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item" data-category="certificados">
                <div class="faq-q">
                    <span>¿Cuenta como experiencia profesional?</span>
                    <i class="bi bi-chevron-down chev"></i>
                </div>
                <div class="faq-a">
                    <p>Sí, es valorado por empresas y universidades. Emitimos documentos con firma electrónica.</p>
                </div>
            </div>
        </div>

        <div class="faq-footer-cta">
            <p>¿No encuentras tu respuesta?</p>
            <a href="mailto:fundacion@lacaridad.org" class="btn-ghost">Contáctanos</a>
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

        const open = () => { $overlay.style.display = 'block'; $open?.setAttribute('aria-expanded','true'); document.body.style.overflow = 'hidden'; };
        const close = () => { $overlay.style.display = 'none'; $open?.setAttribute('aria-expanded','false'); document.body.style.overflow = ''; };

        $open?.addEventListener('click', open);
        $close?.addEventListener('click', close);
        $overlay?.addEventListener('click', (e) => { if (e.target === $overlay) close(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && $overlay.style.display === 'block') close(); });

        // Filtro por categoría
        $cats?.addEventListener('click', (e) => {
            const btn = e.target.closest('.faq-cat'); if (!btn) return;
            [...$cats.querySelectorAll('.faq-cat')].forEach(b => b.classList.toggle('active', b === btn));
            const cat = btn.dataset.category;
            [...$list.children].forEach(item => {
                if (cat === 'all' || item.dataset.category === cat) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Acordeón
        $list?.addEventListener('click', (e) => {
            const q = e.target.closest('.faq-q'); if (!q) return;
            const item = q.parentElement;
            item.classList.toggle('open');
            const a = item.querySelector('.faq-a');
            a.style.display = item.classList.contains('open') ? 'block' : 'none';
        });
    })();
</script>
