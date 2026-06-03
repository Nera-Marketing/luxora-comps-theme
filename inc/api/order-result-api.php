<?php
/**
 * REST API — Order Result Polling Endpoint
 *
 * Returns the final instant-win result (won / no_win / draw) once the payment
 * webhook has fired and the lottery plugin has committed its logs. While the
 * result is still pending it returns {state:'pending'} so the JS poller can
 * retry. Failed / cancelled / refunded orders return {state:'unresolved'}.
 *
 * Endpoint: GET /wp-json/nera/v1/order-result/{order_id}?key={order_key}
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Order Result REST API Class
 */
class Nera_Order_Result_API {

	/**
	 * API namespace (shared with Nera_Instant_Wins_API).
	 */
	const NAMESPACE = 'nera/v1';

	/**
	 * Per-order rate limit: requests per minute (keyed on order + key hash).
	 */
	const RATE_LIMIT = 60;

	/**
	 * Initialize the API.
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Register REST API routes.
	 */
	public static function register_routes() {
		register_rest_route(
			self::NAMESPACE,
			'/order-result/(?P<order_id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_order_result' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'order_id' => array(
						'required'          => true,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
					),
					'key'      => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
	}

	/**
	 * Handle GET /nera/v1/order-result/{order_id}?key={order_key}.
	 *
	 * @param WP_REST_Request $request REST request.
	 * @return WP_REST_Response|WP_Error
	 */
	public static function get_order_result( $request ) {
		$order_id = absint( $request->get_param( 'order_id' ) );
		$key      = sanitize_text_field( (string) $request->get_param( 'key' ) );

		// Load order and authenticate via order key.
		$order = wc_get_order( $order_id );
		if ( ! $order || ! hash_equals( $order->get_order_key(), $key ) ) {
			return new WP_Error( 'forbidden', '', array( 'status' => 403 ) );
		}

		// Per-order rate limit.
		$rate_check = self::check_rate_limit( $order_id, $key );
		if ( is_wp_error( $rate_check ) ) {
			return $rate_check;
		}

		// Determine state.
		$unresolved_statuses = array( 'failed', 'cancelled', 'refunded' );
		if ( $order->has_status( $unresolved_statuses ) ) {
			$data  = array( 'state' => 'unresolved' );
			$response = rest_ensure_response( $data );
			$response->header( 'Cache-Control', 'no-store, max-age=0' );
			return $response;
		}

		if ( ! $order->get_meta( 'lty_lottery_ticket_updated_once' ) ) {
			$data     = array( 'state' => 'pending' );
			$response = rest_ensure_response( $data );
			$response->header( 'Cache-Control', 'no-store, max-age=0' );
			return $response;
		}

		// Result is final — render the card HTML via the controller.
		$controller = LTY_Result_Screens::instance();
		$html       = $controller->render_final_card_html( $order );

		// Map internal slug to API state string.
		$state = self::resolve_state_string( $order );

		$data = array(
			'state' => $state,
			'html'  => $html,
		);

		$response = rest_ensure_response( $data );
		$response->header( 'Cache-Control', 'no-store, max-age=0' );
		return $response;
	}

	/**
	 * Derive a public state string (won / no_win / draw) for a confirmed order.
	 *
	 * Mirrors the logic of resolve_final_scenario() without re-rendering templates.
	 *
	 * @param WC_Order $order Confirmed order.
	 * @return string
	 */
	private static function resolve_state_string( $order ) {
		$order_id = $order->get_id();

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( ! $product || ! lty_is_lottery_product( $product ) ) {
				continue;
			}

			if ( $product->is_instant_winner() ) {
				$log_ids = lty_get_instant_winner_log_ids_by_order_id( $order_id, $product->get_id(), 'lty_won' );
				if ( ! empty( $log_ids ) ) {
					return 'won';
				}
				// Instant-win item present but no won log → no-win.
				return 'no_win';
			}

			// Prize-draw product.
			return 'draw';
		}

		return 'no_win';
	}

	/**
	 * Check per-order rate limit (60 requests/minute keyed on order_id + key hash).
	 *
	 * @param int    $order_id Order ID.
	 * @param string $key      Order key.
	 * @return true|WP_Error
	 */
	private static function check_rate_limit( $order_id, $key ) {
		$transient_key = 'nera_order_result_rate_' . md5( $order_id . '|' . $key );
		$count         = get_transient( $transient_key );

		if ( false === $count ) {
			set_transient( $transient_key, 1, MINUTE_IN_SECONDS );
			return true;
		}

		if ( $count >= self::RATE_LIMIT ) {
			return new WP_Error(
				'rate_limit_exceeded',
				sprintf(
					/* translators: %d: max requests per minute */
					__( 'Rate limit exceeded. Maximum %d requests per minute.', 'nera-competitions' ),
					self::RATE_LIMIT
				),
				array( 'status' => 429 )
			);
		}

		set_transient( $transient_key, $count + 1, MINUTE_IN_SECONDS );
		return true;
	}
}

// Bootstrap.
Nera_Order_Result_API::init();
