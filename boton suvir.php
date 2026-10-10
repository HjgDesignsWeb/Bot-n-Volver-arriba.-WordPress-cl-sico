<?php
/**
 * Botón volver arriba.
 *
 * Pegar en footer.php, antes de wp_footer().
 * El text domain 'mi-tema' debe ser el Text Domain real de style.css.
 */
$scroll_position = get_theme_mod( 'scroll_to_top_position', 'left' );

if ( ! in_array( $scroll_position, array( 'left', 'right', 'hide' ), true ) ) {
	$scroll_position = 'left';
}

if ( 'hide' !== $scroll_position ) :
	$position_class = ( 'right' === $scroll_position ) ? 'is-right' : 'is-left';
	?>
	<style>
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
			transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
				transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
				visibility 0.3s cubic-bezier(0.16, 1, 0.3, 1),
				background-color 0.2s ease;
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
		}

		.scroll-to-top.is-visible:hover {
			background-color: var(--wp--preset--color--contrast, #374151);
			transform: translateY(-2px);
		}

		.scroll-to-top.is-visible:active {
			transform: translateY(0);
		}

		.scroll-to-top:focus-visible {
			outline: 2px solid var(--wp--preset--color--contrast, #111827);
			outline-offset: 3px;
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
			.scroll-to-top.is-visible,
			.scroll-to-top.is-visible:hover,
			.scroll-to-top.is-visible:active {
				transition: none;
				transform: none;
			}
		}
	</style>
	<button id="scroll-to-top" class="scroll-to-top <?php echo esc_attr( $position_class ); ?>" aria-label="<?php esc_attr_e( 'Volver arriba', 'mi-tema' ); ?>" type="button">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
	</button>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var btn = document.getElementById('scroll-to-top');
			if (!btn) {
				return;
			}

			var scrollThreshold = 1000;
			var isVisible = false;
			var ticking = false;

			function updateScrollState() {
				var currentScroll = window.pageYOffset || document.documentElement.scrollTop;

				if (currentScroll > scrollThreshold && !isVisible) {
					isVisible = true;
					btn.classList.add('is-visible');
				} else if (currentScroll <= scrollThreshold && isVisible) {
					isVisible = false;
					btn.classList.remove('is-visible');
				}
			}

			window.addEventListener('scroll', function () {
				if (!ticking) {
					window.requestAnimationFrame(function () {
						updateScrollState();
						ticking = false;
					});
					ticking = true;
				}
			}, { passive: true });

			window.addEventListener('pageshow', updateScrollState);
			updateScrollState();

			btn.addEventListener('click', function () {
				var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

				window.scrollTo({
					top: 0,
					behavior: reduceMotion ? 'auto' : 'smooth'
				});
			});
		});
	</script>
	<?php
endif;
?>
