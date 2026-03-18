<?php
/**
 * REST API for Archive Winners Page
 *
 * Provides an automated endpoint for fetching finished lotteries,
 * winner details, and entry list downloads.
 *
 * Endpoint: GET /wp-json/nera/v1/archive
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

class Nera_Archive_API
{
  const NAMESPACE = 'nera/v1';

  /**
   * Initialize the API
   */
  public static function init()
  {
    add_action('rest_api_init', [__CLASS__, 'register_routes']);
  }

  /**
   * Register REST API routes
   */
  public static function register_routes()
  {
    register_rest_route(self::NAMESPACE , '/archive', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => [__CLASS__, 'get_archive'],
      'permission_callback' => '__return_true',
      'args' => [
        'page' => [
          'default' => 1,
          'sanitize_callback' => 'absint',
        ],
        'per_page' => [
          'default' => 12,
          'sanitize_callback' => 'absint',
        ],
        'search' => [
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field',
        ],
        'pdf_nonce' => [
          'default' => '',
          'sanitize_callback' => 'sanitize_text_field',
        ],
      ],
    ]);
  }

  /**
   * Get archive data
   */
  public static function get_archive($request)
  {
    $page = $request->get_param('page');
    $per_page = $request->get_param('per_page');
    $search = $request->get_param('search');
    $pdf_nonce_param = $request->get_param('pdf_nonce');

    $args = [
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => $per_page,
      'paged' => $page,
      's' => $search,
      'tax_query' => [
        [
          'taxonomy' => 'product_type',
          'field' => 'slug',
          'terms' => 'lottery',
        ],
      ],
      'meta_query' => [
        [
          'key' => '_lty_lottery_status',
          'value' => 'lty_lottery_finished',
          'compare' => '=',
        ],
      ],
      'orderby' => 'meta_value',
      'meta_key' => '_lty_finished_date_gmt',
      'order' => 'DESC',
    ];

    $query = new WP_Query($args);
    $items = [];

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);

        if (!$product)
          continue;

        // 1. Winner Name & Ticket
        $winner_name = '';
        $winner_ticket = '';

        // Check ACF override first
        $acf_winner_override = get_field('winner_display_name', $product_id);
        if ($acf_winner_override) {
          $winner_name = $acf_winner_override;
        } else {
          // Fallback to plugin winner logs
          if (function_exists('lty_get_lottery_winner_ids')) {
            $winner_ids = lty_get_lottery_winner_ids(['product_id' => $product_id]);
            if (!empty($winner_ids)) {
              $winner_post_id = $winner_ids[0];
              $winner_name = get_post_meta($winner_post_id, 'lty_user_name', true);
              $winner_ticket = get_post_meta($winner_post_id, 'lty_ticket_number', true);
            }
          }
        }

        // 2. Entry List URL
        $entry_list_url = '';
        $acf_entry_list_override = get_field('entry_list_file', $product_id);
        if ($acf_entry_list_override) {
          $entry_list_url = $acf_entry_list_override;
        } else {
          // Use automated PDF generator link
          if (function_exists('lty_encode')) {
            $key_args = (object) ['lty_lottery_id' => $product_id];
            $encoded_key = lty_encode($key_args, true);

            // Use provided nonce if available (handles session mismatch between REST and Browser)
            $nonce = !empty($pdf_nonce_param) ? $pdf_nonce_param : wp_create_nonce('lty-lottery-entry-list-pdf');

            $entry_list_url = home_url("/?action=lty-download&lty_key={$encoded_key}&lty_pdf_nonce={$nonce}");
          }
        }

        // 3. Draw Video URL
        $draw_video_url = get_field('draw_video_url', $product_id);

        // 4. End Date Formatting
        $finished_date = get_post_meta($product_id, '_lty_finished_date_gmt', true);
        $date_formatted = $finished_date ? date_i18n(get_option('date_format'), strtotime($finished_date)) : '';

        $items[] = [
          'id' => $product_id,
          'title' => get_the_title(),
          'image' => get_the_post_thumbnail_url($product_id, 'large'),
          'winner_name' => $winner_name,
          'winner_ticket' => $winner_ticket,
          'entry_list_url' => $entry_list_url,
          'draw_video_url' => $draw_video_url,
          'end_date' => $finished_date,
          'end_date_formatted' => $date_formatted,
        ];
      }
      wp_reset_postdata();
    }

    return rest_ensure_response([
      'success' => true,
      'data' => [
        'items' => $items,
        'pagination' => [
          'current_page' => (int) $page,
          'total_pages' => (int) $query->max_num_pages,
          'total_items' => (int) $query->found_posts,
          'per_page' => (int) $per_page,
          'has_more' => $page < $query->max_num_pages,
        ]
      ]
    ]);
  }
}

Nera_Archive_API::init();
