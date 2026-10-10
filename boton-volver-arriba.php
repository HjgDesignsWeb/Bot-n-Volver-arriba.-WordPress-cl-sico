<?php
/**
 * Botón Volver arriba.
 *
 * Reemplaza por completo el bloque actual del componente en footer.php,
 * en contexto HTML, antes de wp_footer() o del cierre de </body>.
 *
 * Fuente de configuración: get_theme_mod( 'scroll_to_top_position' ).
 * Valores admitidos: left, right, hide. hide no imprime HTML, CSS ni JavaScript.
 *
 * El dominio de traducción 'mi-tema' se conserva: el dominio real del tema
 * no venía en el código entregado. Cámbialo solo si style.css declara otro.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$scroll_to_top_position = get_theme_mod( 'scroll_to_top_position', 'left' );

if ( ! in_array( $scroll_to_top_position, array( 'left', 'right', 'hide' ), true ) ) {
	$scroll_to_top_position = 'left';
}

if ( 'hide' !== $scroll_to_top_position ) :
	$scroll_to_top_class = ( 'right' === $scroll_to_top_position ) ? 'is-right' : 'is-left';
	?>
<button id="scroll-to-top" class="scroll-to-top <?php echo esc_attr( $scroll_to_top_class ); ?>" type="button" aria-label="<?php
	/* translators: Etiqueta accesible del botón que desplaza la página al inicio. */
	esc_attr_e( 'Volver arriba', 'mi-tema' );
?>">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>
<style id="scroll-to-top-css">
	.scroll-to-top {
		position: fixed;
		bottom: 32px;
		z-index: 9998;
		width: 46px;
		height: 46px;
		padding: 0;
		margin: 0;
		border: none;
		border-radius: 50%;
		background-color: var(--wp--preset--color--primary, #111827);
		color: #ffffff;
		cursor: pointer;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
		opacity: 0;
		visibility: hidden;
		transform: translateY(20px);
		transition:
			opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
			transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
			background-color 0.2s ease,
			visibility 0s linear 0.3s;
	}

	.scroll-to-top.is-left {
		left: 32px;
	}

	.scroll-to-top.is-right {
		right: 32px;
	}

	.scroll-to-top.is-visible {
		opacity: 1;
		visibility: visible;
		transform: translateY(0);
		transition:
			opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
			transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
			background-color 0.2s ease,
			visibility 0s linear 0s;
	}

	.scroll-to-top.is-visible:hover {
		background-color: var(--wp--preset--color--contrast, #374151);
	}

	@media (hover: hover) and (pointer: fine) {
		.scroll-to-top.is-visible:hover {
			transform: translateY(-2px);
		}
	}

	.scroll-to-top.is-visible:active {
		transform: translateY(0);
	}

	.scroll-to-top:focus-visible {
		outline: 2px solid var(--wp--preset--color--contrast, #111827);
		outline-offset: 3px;
	}

	.scroll-to-top:focus:not(:focus-visible) {
		outline: none;
	}

	@media (max-width: 768px) {
		.scroll-to-top {
			width: 40px;
			height: 40px;
			bottom: 20px;
		}

		.scroll-to-top.is-left {
			left: 20px;
		}

		.scroll-to-top.is-right {
			right: 20px;
		}
	}

	@media (prefers-reduced-motion: reduce) {
		.scroll-to-top,
		.scroll-to-top.is-visible {
			transition: none;
		}
	}
</style>
<script>
(function () {
	function initScrollToTop() {
		var btn = document.getElementById('scroll-to-top');

		if (!btn) {
			return;
		}

		var scrollThreshold = 1000;
		var isVisible = false;
		var ticking = false;

		function updateScrollState() {
			var currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;

			if (currentScroll > scrollThreshold) {
				if (!isVisible) {
					isVisible = true;
					btn.classList.add('is-visible');
				}
			} else if (isVisible) {
				isVisible = false;
				btn.classList.remove('is-visible');
			}
		}

		function onScroll() {
			if (ticking) {
				return;
			}

			ticking = true;
			window.requestAnimationFrame(function () {
				updateScrollState();
				ticking = false;
			});
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('pageshow', updateScrollState);
		updateScrollState();

		btn.addEventListener('click', function () {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			window.scrollTo({
				top: 0,
				behavior: reduceMotion ? 'auto' : 'smooth'
			});

			btn.blur();
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initScrollToTop);
	} else {
		initScrollToTop();
	}
}());
</script>
	<?php
endif;

unset( $scroll_to_top_position, $scroll_to_top_class );
?>
