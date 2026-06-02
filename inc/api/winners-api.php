<?php
/**
 * REST API for Winners Page
 *
 * Provides a secure, cacheable endpoint for fetching winners data
 * with filtering, pagination, and rate limiting.
 *
 * ## Usage
 *
 * Endpoint: GET /wp-json/nera/v1/winners
 *
 * Query Parameters:
 * - page (int, default: 1) - Page number
 * - per_page (int, default: 12) - Items per page
 * - category (string, optional) - Filter by 'all', 'live-draw', 'instant-win'
 *
 * Example:
 * ```javascript
 * fetch('/wp-json/nera/v1/winners?page=1&per_page=12&category=all')
 *   .then(response => response.json())
 *   .then(data => {
 *     console.log(data.data.winners);
 *     console.log(data.data.pagination);
 *   });
 * ```
 *
 * ## Response Format
 *
 * Success (200):
 * ```json
 * {
 *   "success": true,
 *   "data": {
 *     "winners": [
 *       {
 *         "id": "hash123",
 *         "name": "John D.",
 *         "prize": "Prize Name",
 *         "date": "2026-01-05",
 *         "quote": "Quote text",
 *         "category": "live-draw",
 *         "image": "https://..."
 *       }
 *     ],
 *     "pagination": {
 *       "current_page": 1,
 *       "total_pages": 3,
 *       "total_items": 36,
 *       "per_page": 12,
 *       "has_more": true
 *     },
 *     "filters": {
 *       "all_count": 36,
 *       "live_draw_count": 24,
 *       "instant_win_count": 12
 *     }
 *   },
 *   "cached": false
 * }
 * ```
 *
 * Error Responses:
 * - 400: Invalid parameters
 * - 404: Winners page not found
 * - 429: Rate limit exceeded (60 req/min per IP)
 * - 500: Server error
 *
 * ## Security Features
 *
 * - Rate limiting: 60 requests/minute per IP
 * - Response caching: 5 minute TTL (WordPress transients)
 * - Data sanitization: Only public data exposed
 * - Unique hash IDs instead of sequential IDs
 *
 * ## Cache Management
 *
 * Clear cache programmatically:
 * ```php
 * nera_clear_winners_cache();
 * ```
 *
 * Cache is automatically cleared when winners are updated.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

/**
 * Winners REST API Class
 */
class Nera_Winners_API
{
  /**
   * API namespace
   */
  const NAMESPACE = 'nera/v1';

  /**
   * Rate limit: requests per minute per IP
   * Increased from 30 to 60 to accommodate TanStack Query caching
   */
  const RATE_LIMIT = 60;

  /**
   * Cache TTL in seconds (5 minutes - winners change less frequently)
   */
  const CACHE_TTL = 300;

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
    register_rest_route(self::NAMESPACE, '/winners', [
      'methods' => WP_REST_Server::READABLE,
      'callback' => [__CLASS__, 'get_winners'],
      'permission_callback' => '__return_true', // Public endpoint
      'args' => [
        'page' => [
          'default' => 1,
          'type' => 'integer',
          'minimum' => 1,
          'validate_callback' => function ($param) {
            return is_numeric($param) && $param > 0;
          },
          'sanitize_callback' => 'absint',
        ],
        'per_page' => [
          'default' => 12,
          'type' => 'integer',
          'minimum' => 1,
          'maximum' => 100,
          'validate_callback' => function ($param) {
            return is_numeric($param) && $param > 0 && $param <= 100;
          },
          'sanitize_callback' => 'absint',
        ],
        'category' => [
          'default' => 'all',
          'type' => 'string',
          'validate_callback' => function ($param) {
            $allowed = array_merge(['all'], array_keys(nera_get_win_categories()));
            return in_array($param, $allowed, true);
          },
          'sanitize_callback' => 'sanitize_text_field',
        ],
      ],
    ]);
  }

  /**
   * Get winners data
   *
   * @param WP_REST_Request $request Request object.
   * @return WP_REST_Response|WP_Error Response object or error.
   */
  public static function get_winners($request)
  {
    $page = $request->get_param('page');
    $per_page = $request->get_param('per_page');
    $category = $request->get_param('category');

    // Rate limiting check
    $rate_limit_check = self::check_rate_limit();
    if (is_wp_error($rate_limit_check)) {
      return $rate_limit_check;
    }

    // Check cache first
    $cache_key = self::get_cache_key($page, $per_page, $category);
    $cached_data = get_transient($cache_key);

    if (false !== $cached_data) {
      return rest_ensure_response([
        'success' => true,
        'data' => $cached_data,
        'cached' => true,
      ]);
    }

    // Fetch winners data
    $data = self::fetch_winners_data($page, $per_page, $category);

    if (is_wp_error($data)) {
      return $data;
    }

    // Cache the result
    set_transient($cache_key, $data, self::CACHE_TTL);

    // Return response
    return rest_ensure_response([
      'success' => true,
      'data' => $data,
      'cached' => false,
    ]);
  }

  /**
   * Fetch winners data from ACF field
   *
   * @param int    $page     Page number.
   * @param int    $per_page Items per page.
   * @param string $category Category filter.
   * @return array|WP_Error Array of formatted data or WP_Error.
   */
  private static function fetch_winners_data($page, $per_page, $category)
  {
    // Find the winners page
    $winners_page = get_pages([
      'meta_key' => '_wp_page_template',
      'meta_value' => 'page-templates/winners-template.php',
      'number' => 1,
    ]);

    if (empty($winners_page)) {
      return new WP_Error(
        'winners_page_not_found',
        __('Winners page not found.', 'nera-competitions'),
        ['status' => 404],
      );
    }

    $page_id = $winners_page[0]->ID;

    // Get winners list from ACF
    $winners_list = get_field('winners_list', $page_id);

    if (!$winners_list || empty($winners_list)) {
      $categories = nera_get_win_categories($page_id);
      $filter_items = self::build_filter_items([], $categories);
      return [
        'winners' => [],
        'pagination' => [
          'current_page' => 1,
          'total_pages' => 0,
          'total_items' => 0,
          'per_page' => $per_page,
          'has_more' => false,
        ],
        'filters' => ['items' => $filter_items],
      ];
    }

    try {
      $categories = nera_get_win_categories($page_id);
      $category_counts = array_fill_keys(array_keys($categories), 0);
      $all_winners = [];

      foreach ($winners_list as $winner) {
        $name = $winner['name'] ?? '';
        $prize = $winner['prize'] ?? '';

        // Skip if missing required fields
        if (empty($name) || empty($prize)) {
          continue;
        }

        $winner_category = $winner['category'] ?? 'live-draw';
        if (isset($category_counts[$winner_category])) {
          $category_counts[$winner_category]++;
        }

        // Get image URL
        $image_url = '';
        $image = $winner['image'] ?? null;
        if ($image && is_array($image)) {
          $image_url = $image['sizes']['large'] ?? ($image['url'] ?? '');
        }

        $resolved_label = $categories[$winner_category] ?? ucfirst(str_replace('-', ' ', $winner_category));

        // Create winner object
        $winner_data = [
          'id' => md5($name . $prize . ($winner['date'] ?? '')), // Unique hash ID
          'name' => sanitize_text_field($name),
          'prize' => sanitize_text_field($prize),
          'date' => sanitize_text_field($winner['date'] ?? ''),
          'quote' => sanitize_text_field($winner['quote'] ?? ''),
          'category' => sanitize_text_field($winner_category),
          'category_label' => $resolved_label,
          'image' => esc_url($image_url),
        ];

        $all_winners[] = $winner_data;
      }

      // Filter by category
      $filtered_winners = $all_winners;
      if ($category !== 'all') {
        $filtered_winners = array_filter($all_winners, function ($winner) use ($category) {
          return $winner['category'] === $category;
        });
        // Re-index array
        $filtered_winners = array_values($filtered_winners);
      }

      // Calculate pagination
      $total_items = count($filtered_winners);
      $total_pages = ceil($total_items / $per_page);
      $offset = ($page - 1) * $per_page;

      // Get page of winners
      $paginated_winners = array_slice($filtered_winners, $offset, $per_page);

      $filter_items = self::build_filter_items($all_winners, $categories, $category_counts);

      return [
        'winners' => $paginated_winners,
        'pagination' => [
          'current_page' => $page,
          'total_pages' => (int) $total_pages,
          'total_items' => $total_items,
          'per_page' => $per_page,
          'has_more' => $page < $total_pages,
        ],
        'filters' => ['items' => $filter_items],
      ];
    } catch (Exception $e) {
      return new WP_Error(
        'data_fetch_error',
        __('Error fetching winners data.', 'nera-competitions'),
        ['status' => 500],
      );
    }
  }

  /**
   * Build filter items array for API response
   *
   * @param array      $all_winners     All winner entries.
   * @param array      $categories      Value => label from nera_get_win_categories().
   * @param array|null $category_counts Optional. Value => count. If null, computes from all_winners.
   * @return array Array of { value, label, count } objects, "all" first.
   */
  private static function build_filter_items($all_winners, $categories, $category_counts = null)
  {
    if ($category_counts === null) {
      $category_counts = array_fill_keys(array_keys($categories), 0);
      foreach ($all_winners as $w) {
        $c = $w['category'] ?? '';
        if (isset($category_counts[$c])) {
          $category_counts[$c]++;
        }
      }
    }
    $all_count = count($all_winners);
    $items = [['value' => 'all', 'label' => __('All Winners', 'nera-competitions'), 'count' => $all_count]];
    foreach ($categories as $value => $label) {
      $items[] = [
        'value' => $value,
        'label' => $label,
        'count' => $category_counts[$value] ?? 0,
      ];
    }
    return $items;
  }

  /**
   * Check rate limit for IP
   *
   * @return true|WP_Error True if allowed, WP_Error if rate limit exceeded.
   */
  private static function check_rate_limit()
  {
    $ip = self::get_client_ip();
    $rate_key = self::get_rate_limit_key($ip);

    $request_count = get_transient($rate_key);

    if (false === $request_count) {
      // First request in this window
      set_transient($rate_key, 1, MINUTE_IN_SECONDS);
      return true;
    }

    if ($request_count >= self::RATE_LIMIT) {
      return new WP_Error(
        'rate_limit_exceeded',
        sprintf(
          __('Rate limit exceeded. Maximum %d requests per minute.', 'nera-competitions'),
          self::RATE_LIMIT,
        ),
        ['status' => 429],
      );
    }

    // Increment counter
    set_transient($rate_key, $request_count + 1, MINUTE_IN_SECONDS);

    return true;
  }

  /**
   * Get client IP address
   *
   * @return string Client IP address.
   */
  private static function get_client_ip()
  {
    $ip_keys = [
      'HTTP_CLIENT_IP',
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_FORWARDED_FOR',
      'HTTP_FORWARDED',
      'REMOTE_ADDR',
    ];

    foreach ($ip_keys as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
          $ip = trim($ip);

          if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            return $ip;
          }
        }
      }
    }

    return '0.0.0.0';
  }

  /**
   * Get rate limit transient key
   *
   * @param string $ip Client IP.
   * @return string Transient key.
   */
  private static function get_rate_limit_key($ip)
  {
    return 'nera_winners_rate_' . md5($ip);
  }

  /**
   * Get cache transient key
   *
   * @param int    $page     Page number.
   * @param int    $per_page Items per page.
   * @param string $category Category filter.
   * @return string Transient key.
   */
  private static function get_cache_key($page, $per_page, $category)
  {
    return 'nera_winners_cache_v2_' . md5($page . '_' . $per_page . '_' . $category);
  }

  /**
   * Clear all winners cache
   * Called when winners are updated
   */
  public static function clear_cache()
  {
    global $wpdb;

    // Delete all winners cache transients
    $wpdb->query(
      "DELETE FROM {$wpdb->options}
			WHERE option_name LIKE '_transient_nera_winners_cache_%'
			OR option_name LIKE '_transient_timeout_nera_winners_cache_%'",
    );
  }
}

// Initialize the API
Nera_Winners_API::init();

/**
 * Clear winners cache when ACF field is updated
 */
add_action(
  'acf/save_post',
  function ($post_id) {
    // Check if this is the winners page
    $page_template = get_page_template_slug($post_id);

    if ($page_template === 'page-templates/winners-template.php') {
      nera_clear_winners_cache();
    }
  },
  20,
);

/**
 * Helper function to clear winners cache
 * Can be called from other parts of the theme
 */
function nera_clear_winners_cache()
{
  Nera_Winners_API::clear_cache();
}
