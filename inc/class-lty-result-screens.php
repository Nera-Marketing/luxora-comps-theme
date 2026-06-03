<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a result-screen hero icon: uploaded ACF image if set, else emoji fallback.
 *
 * @param string $field          ACF field name (options context).
 * @param string $fallback_emoji HTML entity string used when no image is set.
 * @param string $extra_classes  Additional ltyrs-* classes for the wrapper div.
 */
function lty_rs_screen_icon( $field, $fallback_emoji, $extra_classes = '' ) {
	$img  = function_exists( 'get_field' ) ? get_field( $field, 'option' ) : null;
	$wrap = trim( 'ltyrs-block ltyrs-mb-2 ' . $extra_classes );

	if ( is_array( $img ) && ! empty( $img['url'] ) ) {
		printf(
			'<div class="%s" aria-hidden="true"><img src="%s" alt="%s" class="ltyrs-inline-block ltyrs-h-20 ltyrs-w-auto" /></div>',
			esc_attr( $wrap ),
			esc_url( $img['url'] ),
			esc_attr( $img['alt'] ?? '' )
		);
		return;
	}
	printf( '<div class="%s" aria-hidden="true">%s</div>', esc_attr( trim( $wrap . ' ltyrs-text-5xl' ) ), $fallback_emoji );
}

class LTY_Result_Screens {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'woocommerce_thankyou', array( $this, 'render_result_overlay' ), 10, 1 );
		add_action( 'wp_enqueue_scripts',   array( $this, 'enqueue_assets' ), 9999 );
		add_action( 'acf/init',             array( $this, 'register_acf' ) );
	}

	public function register_acf() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( array(
			'key'    => 'group_lty_rs_settings',
			'title'  => 'Result Screens',
			'fields' => array(

				// ── Win screen ────────────────────────────────────────────────
				array(
					'key'   => 'field_lty_rs_tab_win',
					'label' => 'Win screen',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_lty_rs_win_heading',
					'label'         => 'Heading',
					'name'          => 'lty_rs_win_heading',
					'type'          => 'text',
					'default_value' => "You've won!",
				),
				array(
					'key'          => 'field_lty_rs_win_icon',
					'label'        => 'Icon image',
					'name'         => 'lty_rs_win_icon',
					'type'         => 'image',
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library'      => 'all',
					'mime_types'   => 'jpg,jpeg,png,webp,gif',
					'instructions' => 'Optional. Overrides the default emoji icon. ~96–128px square, PNG/WebP recommended.',
				),
				array(
					'key'           => 'field_lty_rs_win_email_note',
					'label'         => 'Email confirmation note',
					'name'          => 'lty_rs_win_email_note',
					'type'          => 'text',
					'default_value' => 'A confirmation email is on its way to you.',
				),
				array(
					'key'           => 'field_lty_rs_win_button',
					'label'         => 'Button text',
					'name'          => 'lty_rs_win_button',
					'type'          => 'text',
					'default_value' => 'Claim my prize!',
				),

				// ── No-win screen ─────────────────────────────────────────────
				array(
					'key'   => 'field_lty_rs_tab_no_win',
					'label' => 'No-win screen',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_lty_rs_no_win_heading',
					'label'         => 'Heading',
					'name'          => 'lty_rs_no_win_heading',
					'type'          => 'text',
					'default_value' => 'Thanks for entering!',
				),
				array(
					'key'          => 'field_lty_rs_no_win_icon',
					'label'        => 'Icon image',
					'name'         => 'lty_rs_no_win_icon',
					'type'         => 'image',
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library'      => 'all',
					'mime_types'   => 'jpg,jpeg,png,webp,gif',
					'instructions' => 'Optional. Overrides the default emoji icon. ~96–128px square, PNG/WebP recommended.',
				),
				array(
					'key'           => 'field_lty_rs_no_win_message',
					'label'         => 'Message',
					'name'          => 'lty_rs_no_win_message',
					'type'          => 'textarea',
					'instructions'  => 'Shown below the heading.',
					'default_value' => "Not this time \xe2\x80\x94 but every entry brings you closer. There are always more competitions to enter!",
					'rows'          => 3,
					'new_lines'     => 'br',
				),
				array(
					'key'           => 'field_lty_rs_no_win_button',
					'label'         => 'Button text',
					'name'          => 'lty_rs_no_win_button',
					'type'          => 'text',
					'default_value' => 'Browse more competitions',
				),
				array(
					'key'          => 'field_lty_rs_browse_url',
					'label'        => 'Button URL',
					'name'         => 'lty_rs_browse_url',
					'type'         => 'url',
					'instructions' => 'Defaults to the WooCommerce shop page if left blank.',
					'placeholder'  => 'https://',
				),

				// ── Prize draw screen ─────────────────────────────────────────
				array(
					'key'   => 'field_lty_rs_tab_draw',
					'label' => 'Prize draw screen',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_lty_rs_draw_heading',
					'label'         => 'Heading',
					'name'          => 'lty_rs_draw_heading',
					'type'          => 'text',
					'default_value' => "You're in the draw!",
				),
				array(
					'key'          => 'field_lty_rs_draw_icon',
					'label'        => 'Icon image',
					'name'         => 'lty_rs_draw_icon',
					'type'         => 'image',
					'return_format' => 'array',
					'preview_size' => 'medium',
					'library'      => 'all',
					'mime_types'   => 'jpg,jpeg,png,webp,gif',
					'instructions' => 'Optional. Overrides the default emoji icon. ~96–128px square, PNG/WebP recommended.',
				),
				array(
					'key'           => 'field_lty_rs_draw_subtext',
					'label'         => 'Subtext',
					'name'          => 'lty_rs_draw_subtext',
					'type'          => 'text',
					'default_value' => "Your entry is confirmed \xe2\x80\x94 fingers crossed!",
				),
				array(
					'key'           => 'field_lty_rs_draw_good_luck',
					'label'         => 'Good luck text',
					'name'          => 'lty_rs_draw_good_luck',
					'type'          => 'text',
					'default_value' => 'Good luck!',
				),
				array(
					'key'           => 'field_lty_rs_draw_button',
					'label'         => 'Button text',
					'name'          => 'lty_rs_draw_button',
					'type'          => 'text',
					'default_value' => 'Got it!',
				),

				// ── Pending / confirming screen ───────────────────────────────────
				array(
					'key'   => 'field_lty_rs_tab_pending',
					'label' => 'Pending screen',
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_lty_rs_pending_heading',
					'label'         => 'Heading',
					'name'          => 'lty_rs_pending_heading',
					'type'          => 'text',
					'default_value' => 'Confirming your entry\xe2\x80\xa6',
					'instructions'  => 'Shown while the payment webhook is being processed.',
				),
				array(
					'key'           => 'field_lty_rs_pending_subtext',
					'label'         => 'Subtext',
					'name'          => 'lty_rs_pending_subtext',
					'type'          => 'text',
					'default_value' => 'Hang tight \xe2\x80\x94 your result will appear here in a moment.',
					'instructions'  => 'Reassuring line shown below the spinner.',
				),
				array(
					'key'           => 'field_lty_rs_pending_fallback_message',
					'label'         => 'Timeout / failed fallback message',
					'name'          => 'lty_rs_pending_fallback_message',
					'type'          => 'text',
					'default_value' => 'Your entry is confirmed \xe2\x80\x94 we\'ll email your result shortly.',
					'instructions'  => 'Shown when we cannot confirm the result in time or the payment fails. Never shows "no-win".',
				),

			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'acf-options-woocommerce',
					),
				),
			),
		) );
	}

	public function enqueue_assets() {
		if ( ! is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		$order_id = absint( get_query_var( 'order-received' ) );
		if ( ! $order_id ) {
			global $wp;
			if ( isset( $wp->query_vars['order-received'] ) ) {
				$order_id = absint( $wp->query_vars['order-received'] );
			}
		}

		$order = $order_id ? wc_get_order( $order_id ) : false;
		if ( ! $order || null === $this->get_overlay_result_for_order( $order ) ) {
			return;
		}

		$css_file = get_template_directory() . '/assets/css/lty-result-screens.css';
		$js_file  = get_template_directory() . '/assets/js/lty-result-screens.js';

		wp_enqueue_style(
			'lty-result-screens',
			get_template_directory_uri() . '/assets/css/lty-result-screens.css',
			array(),
			file_exists( $css_file ) ? filemtime( $css_file ) : NERA_VERSION
		);
		wp_enqueue_script(
			'lty-result-screens',
			get_template_directory_uri() . '/assets/js/lty-result-screens.js',
			array(),
			file_exists( $js_file ) ? filemtime( $js_file ) : NERA_VERSION,
			true
		);

		// When the result is still pending, pass polling config to JS.
		$result = $this->get_overlay_result_for_order( $order );
		if ( $result && 'pending' === $result['slug'] ) {
			$pending_fallback = get_field( 'lty_rs_pending_fallback_message', 'option' );
			if ( ! $pending_fallback ) {
				$pending_fallback = __( 'Your entry is confirmed \xe2\x80\x94 we\'ll email your result shortly.', 'lty-result-screens' );
			}

			wp_localize_script(
				'lty-result-screens',
				'ltyResultScreens',
				array(
					'restUrl'         => rest_url( 'nera/v1/order-result/' . $order_id ),
					'orderKey'        => $order->get_order_key(),
					'pollMs'          => 2000,
					'timeoutMs'       => 90000,
					'fallbackMessage' => $pending_fallback,
				)
			);
		}
	}

	/**
	 * Decide which result overlay applies (if any).
	 *
	 * Skipped when no line item is a lottery product, or none match win / no-win / prize-draw rules.
	 * When an instant-win item is present but the payment webhook hasn't fired yet
	 * (lty_lottery_ticket_updated_once meta is absent), returns a 'pending' result so
	 * the template can show a confirming screen and the JS can poll for the real result.
	 *
	 * @param WC_Order $order Order object.
	 * @return array{slug:string,template:string,args:array}|null
	 */
	private function get_overlay_result_for_order( $order ) {
		if ( ! $order || ! $order->get_id() ) {
			return null;
		}

		$has_instant_win_item = false;
		$prize_draw_product   = null;

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( ! $product || ! lty_is_lottery_product( $product ) ) {
				continue;
			}

			if ( $product->is_instant_winner() ) {
				$has_instant_win_item = true;
			} elseif ( null === $prize_draw_product ) {
				$prize_draw_product = $product;
			}
		}

		// No lottery items at all → no overlay.
		if ( ! $has_instant_win_item && null === $prize_draw_product ) {
			return null;
		}

		// Instant-win item present but webhook hasn't confirmed yet → pending screen.
		if ( $has_instant_win_item && ! $order->get_meta( 'lty_lottery_ticket_updated_once' ) ) {
			return array(
				'slug'     => 'pending',
				'template' => 'pending-confirming.php',
				'args'     => array( 'order' => $order ),
			);
		}

		// Result is final — resolve to won / no-win / draw.
		if ( $has_instant_win_item ) {
			return $this->resolve_final_scenario( $order );
		}

		// Prize-draw only (no instant-win item).
		return array(
			'slug'     => 'prize-draw',
			'template' => 'prize-draw-good-luck.php',
			'args'     => array( 'order' => $order, 'product' => $prize_draw_product ),
		);
	}

	/**
	 * Resolve the final won / no-win / prize-draw scenario for an order whose result is confirmed.
	 *
	 * Extracted so it can be called independently by render_final_card_html() and the REST endpoint.
	 * Prize-draw-only orders should not reach this method — they are handled above.
	 *
	 * @param WC_Order $order Order object.
	 * @return array{slug:string,template:string,args:array}|null
	 */
	private function resolve_final_scenario( $order ) {
		if ( ! $order || ! $order->get_id() ) {
			return null;
		}

		$order_id             = $order->get_id();
		$instant_win_won      = array();
		$instant_win_no_win   = null;
		$prize_draw_product   = null;

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( ! $product || ! lty_is_lottery_product( $product ) ) {
				continue;
			}

			if ( $product->is_instant_winner() ) {
				$log_ids = lty_get_instant_winner_log_ids_by_order_id( $order_id, $product->get_id(), 'lty_won' );
				if ( ! empty( $log_ids ) ) {
					$instant_win_won = $log_ids;
					break;
				}
				if ( null === $instant_win_no_win ) {
					$instant_win_no_win = $product;
				}
			} elseif ( null === $prize_draw_product ) {
				$prize_draw_product = $product;
			}
		}

		if ( ! empty( $instant_win_won ) ) {
			return array(
				'slug'     => 'instant-win-won',
				'template' => 'instant-win-won.php',
				'args'     => array( 'log_ids' => $instant_win_won, 'order' => $order ),
			);
		}

		if ( null !== $instant_win_no_win ) {
			return array(
				'slug'     => 'instant-win-no-win',
				'template' => 'instant-win-no-win.php',
				'args'     => array( 'order' => $order, 'product' => $instant_win_no_win ),
			);
		}

		if ( null !== $prize_draw_product ) {
			return array(
				'slug'     => 'prize-draw',
				'template' => 'prize-draw-good-luck.php',
				'args'     => array( 'order' => $order, 'product' => $prize_draw_product ),
			);
		}

		return null;
	}

	/**
	 * Render the final (confirmed) result card as an HTML string for the REST endpoint.
	 *
	 * Returns an empty string when the result is not yet final or there is no scenario.
	 *
	 * @param WC_Order $order Order object.
	 * @return string Rendered template HTML or empty string.
	 */
	public function render_final_card_html( $order ) {
		$result = $this->resolve_final_scenario( $order );
		if ( null === $result ) {
			return '';
		}

		ob_start();
		$this->render_template( $result['template'], $result['args'] );
		return ob_get_clean();
	}

	/**
	 * Output the result modal on thank-you when the order qualifies.
	 *
	 * Nothing is rendered when there is no lottery line item or no matching scenario (use filter `lty_rs_show_overlay` to override).
	 *
	 * @param int $order_id Order ID.
	 */
	public function render_result_overlay( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$result = $this->get_overlay_result_for_order( $order );
		if ( null === $result ) {
			return;
		}

		if ( ! apply_filters( 'lty_rs_show_overlay', true, $result['slug'], $order ) ) {
			return;
		}

		$this->render_template( $result['template'], $result['args'] );
	}

	/**
	 * Render a template file from theme template-parts/result-screens/.
	 *
	 * @param string $template Filename (e.g. instant-win-won.php).
	 * @param array  $args     Variables to extract into template scope.
	 */
	private function render_template( $template, $args = array() ) {
		$template_path = NERA_DIR . '/template-parts/result-screens/' . $template;

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract
		include $template_path;
	}
}
