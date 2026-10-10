<?php
/**
 * Componente: Botón Volver Arriba (Scroll to Top) - Versión con Carga Diferida
 * Ubicación: Dentro de footer.php
 */
$scroll_position = get_theme_mod( 'scroll_to_top_position', 'left' );

if ( $scroll_position !== 'hide' ) :
    $position_class = ( $scroll_position === 'right' ) ? 'is-right' : 'is-left';
?>

<!-- 1. Estilos colocados ANTES del HTML para evitar FOUC ante carga retrasada -->
<style>
    .scroll-to-top {
        position: fixed;
        bottom: 32px;
        z-index: 9998;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background-color: var(--wp--preset--color--primary, #111827);
        color: #ffffff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden;
        pointer-events: none; /* Evita clics accidentales mientras está oculto */
        transform: translateY(20px);
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                    transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                    visibility 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                    background-color 0.2s ease;
    }

    /* Lógica de posicionamiento vía clases HTML */
    .scroll-to-top.is-left {
        left: 32px;
    }

    .scroll-to-top.is-right {
        right: 32px;
    }

    /* Estado visible */
    .scroll-to-top.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0);
    }

    .scroll-to-top:hover {
        background-color: var(--wp--preset--color--contrast, #374151);
        transform: translateY(-2px);
    }

    .scroll-to-top:active {
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .scroll-to-top {
            width: 40px;
            height: 40px;
            bottom: 20px;
        }
        .scroll-to-top.is-left { left: 20px; }
        .scroll-to-top.is-right { right: 20px; }
    }
</style>

<!-- 2. Estructura HTML con clase dinámica -->
<button id="scroll-to-top" class="scroll-to-top <?php echo esc_attr( $position_class ); ?>" aria-label="<?php esc_attr_e( 'Volver arriba', 'mi-tema' ); ?>" type="button">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>

<!-- 3. Lógica JavaScript robusta ante carga diferenciada -->
<script>
    (function() {
        function initScrollToTop() {
            const btn = document.getElementById('scroll-to-top');
            if (!btn) return;

            const scrollThreshold = 1000;
            let isVisible = false;

            function updateScrollState() {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                
                if (currentScroll > scrollThreshold && !isVisible) {
                    isVisible = true;
                    btn.classList.add('is-visible');
                } else if (currentScroll <= scrollThreshold && isVisible) {
                    isVisible = false;
                    btn.classList.remove('is-visible');
                }
            }

            // Throttle con requestAnimationFrame para garantizar 60 FPS
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        updateScrollState();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            // Comportamiento de scroll suave
            btn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Verificación inicial inmediata (por si el usuario recarga la página ya scrolleado)
            updateScrollState();
        }

        /**
         * Patrón defensivo para carga diferenciada/retrasada:
         * Si el documento ya pasó la fase de carga (readyState completo o interactivo),
         * ejecutamos de inmediato. Si todavía está cargando, usamos el listener.
         */
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initScrollToTop);
        } else {
            initScrollToTop();
        }
    })();
</script>

<?php endif; ?>