<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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

			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'acf-options-woocommerce',
					),
				),
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'theme-settings',
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

		wp_enqueue_style(
			'lty-result-screens',
			LTY_RS_URL . 'assets/css/lty-result-screens.css',
			array(),
			LTY_RS_VERSION
		);
		wp_enqueue_script(
			'lty-result-screens',
			LTY_RS_URL . 'assets/js/lty-result-screens.js',
			array(),
			LTY_RS_VERSION,
			true
		);
	}

	/**
	 * Decide which result overlay applies (if any).
	 *
	 * Skipped when no line item is a lottery product, or none match win / no-win / prize-draw rules.
	 * Same logic used for enqueue (assets) and render (HTML).
	 *
	 * @param WC_Order $order Order object.
	 * @return array{slug:string,template:string,args:array}|null
	 */
	private function get_overlay_result_for_order( $order ) {
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
	 * Render a template file, with support for theme overrides.
	 *
	 * Themes can override templates by placing files under:
	 *   {theme}/lty-result-screens/{template}
	 *
	 * @param string $template Filename relative to /templates/.
	 * @param array  $args     Variables to extract into template scope.
	 */
	private function render_template( $template, $args = array() ) {
		$theme_override = get_stylesheet_directory() . '/lty-result-screens/' . $template;
		$plugin_default = LTY_RS_PATH . 'templates/' . $template;

		$template_path = file_exists( $theme_override ) ? $theme_override : $plugin_default;

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract
		include $template_path;
	}
}
