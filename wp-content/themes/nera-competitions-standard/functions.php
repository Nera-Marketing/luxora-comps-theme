<?php
/**
 * Nera Competitions Standard Theme
 *
 * @package Nera_Competitions
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Load theme env from .env.local (no wp-config changes needed)
require_once __DIR__ . '/inc/env-loader.php';

// Define theme constants
define('NERA_VERSION', '1.0.0');
define('NERA_DIR', get_stylesheet_directory());
define('NERA_URI', get_stylesheet_directory_uri());

/**
 * Check if Vite development server is running
 */
function nera_is_vite_dev_server_running()
{
  // Check if we're in development mode (you can set this via wp-config.php)
  if (!defined('NERA_DEV_MODE') || !NERA_DEV_MODE) {
    return false;
  }

  // Check if Vite dev server is accessible
  $dev_server_url = NERA_VITE_DEV_SERVER_URL;
  $response = @file_get_contents(
    $dev_server_url . '/@vite/client',
    false,
    stream_context_create([
      'http' => ['timeout' => 1],
    ]),
  );

  return $response !== false;
}

/**
 * Get Vite asset URL (production with manifest or development)
 */
function nera_get_vite_asset($entry_point)
{
  $dev_server_url = NERA_VITE_DEV_SERVER_URL;
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';

  // Development mode with Vite dev server
  if (nera_is_vite_dev_server_running()) {
    return $dev_server_url . '/' . $entry_point;
  }

  // Production mode with manifest
  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (isset($manifest[$entry_point])) {
      return NERA_URI . '/dist/' . $manifest[$entry_point]['file'];
    }
  }

  return null;
}

/**
 * Get Vite CSS files from manifest
 */
function nera_get_vite_css_files($entry_point)
{
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';
  $css_files = [];

  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (isset($manifest[$entry_point]['css'])) {
      foreach ($manifest[$entry_point]['css'] as $css_file) {
        $css_files[] = NERA_URI . '/dist/' . $css_file;
      }
    }
  }

  return $css_files;
}

/**
 * Google Fonts - load first in head (matches concept file)
 */
function nera_google_fonts_head()
{
  if (is_customize_preview()) {
    return;
  }
  echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap">' . "\n";
}
add_action('wp_head', 'nera_google_fonts_head', 0);

/**
 * Enqueue theme styles - TailwindCSS
 */
function nera_enqueue_styles()
{
  // Material Symbols Icons
  wp_enqueue_style(
    'nera-material-symbols',
    'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap',
    [],
    null,
  );

  // AOS (Animate On Scroll) CSS
  wp_enqueue_style('aos', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css', [], '2.3.4');

  // Luxora Google Fonts (Playfair Display, Dancing Script, Jost) — site-wide light theme
  wp_enqueue_style(
    'nera-luxora-fonts',
    'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500&family=Dancing+Script:wght@600&family=Jost:wght@300;400;500&display=swap',
    [],
    null
  );

  // Luxora light theme CSS — global header, footer, body overrides
  $luxora_css_path = get_template_directory() . '/assets/css/luxora-homepage.css';
  $luxora_css_uri = get_template_directory_uri() . '/assets/css/luxora-homepage.css';
  $luxora_ver = file_exists($luxora_css_path) ? (string) filemtime($luxora_css_path) : NERA_VERSION;
  wp_enqueue_style('nera-luxora-theme', $luxora_css_uri, ['nera-luxora-fonts'], $luxora_ver);

  // Child theme style.css (for WordPress theme info, minimal styles)
  wp_enqueue_style('nera-style', get_stylesheet_directory_uri() . '/style.css', [], NERA_VERSION);

  // Vite/TailwindCSS assets - PRIMARY STYLING
  if (nera_is_vite_dev_server_running()) {
    // Development mode - load from Vite dev server
    // CSS is injected via JS in dev mode, no separate enqueue needed
  } else {
    // Production mode - load CSS from manifest
    $css_files = nera_get_vite_css_files('src/main.js');
    foreach ($css_files as $index => $css_url) {
      wp_enqueue_style(
        'nera-vite-css-' . $index,
        $css_url,
        ['nera-style'], // Load after base style.css
        NERA_VERSION,
      );
    }
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_styles', 15);

/**
 * Disable WordPress global styles inline CSS
 * Prevents WordPress from injecting inline CSS that overrides Tailwind classes
 */
function nera_disable_global_styles()
{
  // Remove global styles stylesheet
  wp_dequeue_style('global-styles');

  // Remove the actions that inject global styles
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
  remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

  // Remove core block library styles (optional - uncomment if needed)
  // wp_dequeue_style('wp-block-library');
  // wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'nera_disable_global_styles', 100);

/**
 * Completely disable WordPress theme.json global styles
 * This prevents WordPress from generating any global styles CSS
 */
add_filter('wp_theme_json_get_style_nodes', '__return_empty_array');

/**
 * Enqueue theme scripts
 */
function nera_enqueue_scripts()
{
  // AOS (Animate On Scroll) JS
  wp_enqueue_script('aos', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js', [], '2.3.4', true);

  // Alpine Stores and Components (MUST load before AlpineJS initializes)
  // Load in specific order to ensure dependencies are met

  // 1. Toast store (includes postDialog)
  wp_enqueue_script(
    'nera-alpine-toast',
    get_template_directory_uri() . '/assets/js/alpine-toast.js',
    [],
    NERA_VERSION,
    true,
  );

  // 2. Countdown component
  wp_enqueue_script(
    'nera-alpine-countdown',
    get_template_directory_uri() . '/assets/js/alpine-countdown.js',
    [],
    NERA_VERSION,
    true,
  );

  // 3. Checkout component (must load before Alpine.js initializes)
  if (is_checkout() && !is_order_received_page()) {
    wp_enqueue_script(
      'nera-checkout',
      get_template_directory_uri() . '/assets/js/checkout.js',
      [],
      NERA_VERSION,
      true,
    );
  }

  // 4. AlpineJS Collapse Plugin - loads AFTER stores/components
  wp_enqueue_script(
    'alpinejs-collapse',
    'https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.1/dist/cdn.min.js',
    ['nera-alpine-toast', 'nera-alpine-countdown'],
    '3.14.1',
    true,
  );

  // 5. AlpineJS Core - loads LAST, after everything else
  wp_enqueue_script(
    'alpinejs',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js',
    ['alpinejs-collapse'],
    '3.14.1',
    true,
  );

  // Vite/TailwindCSS assets
  if (nera_is_vite_dev_server_running()) {
    // Development mode - load Vite client and main entry
    add_action('wp_head', function () {
      $url = NERA_VITE_DEV_SERVER_URL;
      echo '<script type="module" src="' . esc_url($url . '/@vite/client') . '"></script>';
      echo '<script type="module" src="' . esc_url($url . '/src/main.js') . '"></script>';
    });
  } else {
    // Production mode - load bundled JS from manifest
    $main_js = nera_get_vite_asset('src/main.js');
    if ($main_js) {
      wp_enqueue_script('nera-vite-main', $main_js, [], NERA_VERSION, true);
    }
  }

  // Localize script with theme settings
  wp_localize_script('alpinejs', 'neraSettings', [
    'themeUrl' => NERA_URI,
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('nera_nonce'),
  ]);
}
add_action('wp_enqueue_scripts', 'nera_enqueue_scripts');

/**
 * Enqueue WooCommerce cart-fragments for real-time header cart icon updates
 * Required for NeraCart.removeItem and other cart changes to update the header badge
 */
function nera_enqueue_cart_fragments()
{
  if (function_exists('wc_get_cart_url')) {
    wp_enqueue_script('wc-cart-fragments');
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_cart_fragments', 15);

/**
 * Enqueue product gallery assets
 * Only loads on single product pages with Keen Slider
 */
function nera_enqueue_product_gallery()
{
  if (!is_product()) {
    return;
  }

  if (nera_is_vite_dev_server_running()) {
    add_action('wp_footer', function () {
      $url = NERA_VITE_DEV_SERVER_URL;
      echo '<script type="module" src="' . esc_url($url . '/frontend/components/ProductGallery/index-vue.js') . '"></script>';
    }, 5);
    return;
  }

  // Get Vite manifest
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';

  if (!file_exists($manifest_path)) {
    return;
  }

  $manifest = json_decode(file_get_contents($manifest_path), true);

  // Find product-gallery entry (Vue version)
  $gallery_entry = 'frontend/components/ProductGallery/index-vue.js';

  if (!isset($manifest[$gallery_entry])) {
    return;
  }

  $gallery_file = $manifest[$gallery_entry]['file'];
  $deps = [];

  // Check for vendor chunk imports (Vue)
  if (isset($manifest[$gallery_entry]['imports'])) {
    foreach ($manifest[$gallery_entry]['imports'] as $import_key) {
      if (isset($manifest[$import_key]) && strpos($import_key, 'vue-vendor') !== false) {
        // Enqueue vendor chunk first
        $vendor_file_path = NERA_DIR . '/dist/' . $manifest[$import_key]['file'];
        wp_enqueue_script(
          'nera-vue-vendor',
          NERA_URI . '/dist/' . $manifest[$import_key]['file'],
          [],
          file_exists($vendor_file_path) ? filemtime($vendor_file_path) : NERA_VERSION,
          true,
        );
        $deps[] = 'nera-vue-vendor';
      }
    }
  }

  // Enqueue Product Gallery JS
  $gallery_file_path = NERA_DIR . '/dist/' . $gallery_file;
  wp_enqueue_script(
    'nera-product-gallery',
    NERA_URI . '/dist/' . $gallery_file,
    $deps,
    file_exists($gallery_file_path) ? filemtime($gallery_file_path) : NERA_VERSION,
    true,
  );

  // Enqueue CSS if exists
  if (isset($manifest[$gallery_entry]['css'])) {
    foreach ($manifest[$gallery_entry]['css'] as $css_file) {
      wp_enqueue_style('nera-product-gallery-style', NERA_URI . '/dist/' . $css_file, [], null);
    }
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_product_gallery');

/**
 * Add type="module" to Vite scripts
 * Vite builds ES modules that need to be loaded with type="module"
 */
function nera_add_module_type_to_scripts($tag, $handle, $src)
{
  // Only add type="module" to Vite-generated scripts (NOT Alpine or other CDN scripts)
  if (
    strpos($handle, 'nera-vite-') === 0 ||
    strpos($handle, 'nera-instant-wins-vue') === 0 ||
    strpos($handle, 'nera-product-gallery') === 0 ||
    strpos($handle, 'nera-vue-vendor') === 0
  ) {
    // Make sure we don't add type="module" twice
    if (strpos($tag, 'type="module"') === false) {
      $tag = str_replace('<script ', '<script type="module" ', $tag);
    }
  }
  return $tag;
}
add_filter('script_loader_tag', 'nera_add_module_type_to_scripts', 10, 3);

/**
 * Enqueue Instant Wins Scripts (Vue.js)
 * Only loads on lottery product pages with instant wins enabled
 * Supports Vite dev server and production build
 */
function nera_enqueue_instant_wins_vue()
{
  // Only load on single product pages
  if (!is_product()) {
    return;
  }

  // Always get the product directly, don't rely on global $product
  $product = wc_get_product(get_the_ID());

  if (!$product || !is_a($product, 'WC_Product')) {
    return;
  }

  // Check if product has instant wins
  if (!function_exists('lty_is_lottery_product') || !lty_is_lottery_product($product)) {
    return;
  }

  if (!method_exists($product, 'is_instant_winner') || !$product->is_instant_winner()) {
    return;
  }

  if (nera_is_vite_dev_server_running()) {
    add_action('wp_footer', function () {
      $url = NERA_VITE_DEV_SERVER_URL;
      echo '<script type="module" src="' . esc_url($url . '/frontend/instant-wins-vue-init.js') . '"></script>';
    }, 5);
    return;
  }

  // Production mode - load from manifest
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';

  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);

    // Vue entry point
    $instant_wins_entry = 'frontend/instant-wins-vue-init.js';
    $vendor_chunk_name = 'vue-vendor';
    $handle_prefix = 'vue';

    if (isset($manifest[$instant_wins_entry])) {
      $instant_wins_file = $manifest[$instant_wins_entry]['file'];
      $deps = [];

      // Check for vendor chunk imports
      if (isset($manifest[$instant_wins_entry]['imports'])) {
        foreach ($manifest[$instant_wins_entry]['imports'] as $import_key) {
          // Check if this is the vendor chunk (starts with underscore)
          if (
            isset($manifest[$import_key]) &&
            strpos($import_key, '_' . $vendor_chunk_name) !== false
          ) {
            // Enqueue vendor chunk first with automatic versioning
            $vendor_file_path = NERA_DIR . '/dist/' . $manifest[$import_key]['file'];
            $vendor_handle = 'nera-' . $handle_prefix . '-vendor';

            // Register and enqueue as module script
            wp_enqueue_script(
              $vendor_handle,
              NERA_URI . '/dist/' . $manifest[$import_key]['file'],
              [],
              file_exists($vendor_file_path) ? filemtime($vendor_file_path) : NERA_VERSION,
              true, // in footer
            );

            $deps[] = $vendor_handle;
          }
        }
      }

      // Enqueue instant wins app with automatic versioning
      $instant_wins_file_path = NERA_DIR . '/dist/' . $instant_wins_file;
      $instant_wins_handle = 'nera-instant-wins-' . $handle_prefix;

      // Register and enqueue as module script
      wp_enqueue_script(
        $instant_wins_handle,
        NERA_URI . '/dist/' . $instant_wins_file,
        $deps,
        file_exists($instant_wins_file_path) ? filemtime($instant_wins_file_path) : NERA_VERSION,
        true, // in footer
      );
    }
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_instant_wins_vue');

/**
 * Enqueue Winners Vue.js (production build only)
 *
 * Loads the Winners page Vue.js app with Vue vendor chunk.
 * Only loads on the winners page template.
 */
function nera_enqueue_winners_vue()
{
  // Only load on winners page template
  if (!is_page_template('page-templates/winners-template.php')) {
    return;
  }

  if (nera_is_vite_dev_server_running()) {
    add_action('wp_footer', function () {
      $url = NERA_VITE_DEV_SERVER_URL;
      echo '<script type="module" src="' . esc_url($url . '/frontend/winners-vue-init.js') . '"></script>';
    }, 5);
    return;
  }

  // Production mode - load from manifest
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';

  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);

    $winners_entry = 'frontend/winners-vue-init.js';

    if (isset($manifest[$winners_entry])) {
      $winners_file = $manifest[$winners_entry]['file'];
      $deps = [];

      // Check for vue-vendor chunk
      if (isset($manifest[$winners_entry]['imports'])) {
        foreach ($manifest[$winners_entry]['imports'] as $import_key) {
          if (isset($manifest[$import_key]) && strpos($import_key, '_vue-vendor') !== false) {
            $vendor_handle = 'nera-vue-vendor';

            if (!wp_script_is($vendor_handle, 'enqueued')) {
              wp_enqueue_script(
                $vendor_handle,
                NERA_URI . '/dist/' . $manifest[$import_key]['file'],
                [],
                NERA_VERSION,
                true,
              );
            }

            $deps[] = $vendor_handle;
          }
        }
      }

      wp_enqueue_script(
        'nera-winners-vue',
        NERA_URI . '/dist/' . $winners_file,
        $deps,
        NERA_VERSION,
        true,
      );
    }
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_winners_vue');

/**
 * Enqueue Archive Winners Vue.js
 */
function nera_enqueue_archive_winners_vue()
{
  // Only load on archive winners page template
  if (!is_page_template('page-templates/archive-winners-template.php')) {
    return;
  }

  if (nera_is_vite_dev_server_running()) {
    add_action('wp_footer', function () {
      $url = NERA_VITE_DEV_SERVER_URL;
      echo '<script type="module" src="' . esc_url($url . '/frontend/archive-winners-vue-init.js') . '"></script>';
    }, 5);
    return;
  }

  // Production mode - load from manifest (if exists)
  $manifest_path = NERA_DIR . '/dist/.vite/manifest.json';
  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);
    $archive_entry = 'frontend/archive-winners-vue-init.js';

    if (isset($manifest[$archive_entry])) {
      $archive_file = $manifest[$archive_entry]['file'];
      $deps = [];

      // Check for vue-vendor
      if (isset($manifest[$archive_entry]['imports'])) {
        foreach ($manifest[$archive_entry]['imports'] as $import_key) {
          if (isset($manifest[$import_key]) && strpos($import_key, '_vue-vendor') !== false) {
            $vendor_handle = 'nera-vue-vendor';
            if (!wp_script_is($vendor_handle, 'enqueued')) {
              wp_enqueue_script($vendor_handle, NERA_URI . '/dist/' . $manifest[$import_key]['file'], [], NERA_VERSION, true);
            }
            $deps[] = $vendor_handle;
          }
        }
      }

      wp_enqueue_script('nera-archive-winners-vue', NERA_URI . '/dist/' . $archive_file, $deps, NERA_VERSION, true);
    }
  }
}
add_action('wp_enqueue_scripts', 'nera_enqueue_archive_winners_vue');

/**
 * Add type="module" to Vue vendor/app scripts
 */
add_filter(
  'script_loader_tag',
  function ($tag, $handle, $src) {
    // List of handles that need type="module"
    $module_handles = ['nera-vue-vendor', 'nera-instant-wins-vue', 'nera-winners-vue', 'nera-archive-winners-vue'];

    if (in_array($handle, $module_handles)) {
      // Replace the script tag to add type="module"
      $tag = str_replace('<script ', '<script type="module" ', $tag);
    }

    return $tag;
  },
  10,
  3,
);

/**
 * Include required files
 */
// WordPress Customizer
require_once NERA_DIR . '/inc/customizer.php';

// Menu Walker Classes
require_once NERA_DIR . '/inc/menu-walkers.php';

// Custom Competition Shortcodes
require_once get_template_directory() . '/inc/competition-shortcodes.php';

// ACF Single Product Competition Fields
require_once get_template_directory() . '/inc/acf-single-product.php';

// ACF Contact Page Fields
require_once get_template_directory() . '/inc/acf-contact.php';

// ACF Product Listing Fields
require_once get_template_directory() . '/inc/acf-product-listing.php';

// ACF Postal Entry Fields
require_once get_template_directory() . '/inc/acf-postal-entry.php';

// ACF WooCommerce Settings
require_once get_template_directory() . '/inc/acf-woocommerce.php';

// Legal Placeholders for T&C and Privacy Policy
require_once get_template_directory() . '/inc/legal-placeholders.php';

// ACF Winners Page Fields
require_once get_template_directory() . '/inc/acf-winners.php';

// ACF Archive Winners Page Fields
require_once get_template_directory() . '/inc/acf-archive-winners.php';

// ACF Luxora Homepage Fields
require_once get_template_directory() . '/inc/acf-luxora-homepage.php';

/**
 * Fix YouTube live URLs (youtube.com/live/VIDEO_ID) - oEmbed API often fails for live streams.
 * Manually build iframe so embeds work reliably.
 */
add_filter('pre_oembed_result', function ($result, $url, $args) {
  if ($result !== null) {
    return $result;
  }
  if (preg_match('#youtube\.com/live/([a-zA-Z0-9_-]+)#i', $url, $m)) {
    $id = $m[1];
    return sprintf(
      '<iframe width="560" height="315" src="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
      esc_url('https://www.youtube.com/embed/' . $id . '?autoplay=1&mute=1')
    );
  }
  return null;
}, 10, 3);

/**
 * Enqueue scripts and styles.
 */
if (class_exists('WooCommerce')) {
  require_once NERA_DIR . '/inc/woocommerce.php';
}

// REST API for instant wins lazy loading
if (class_exists('WooCommerce')) {
  require_once NERA_DIR . '/inc/api/instant-wins-api.php';
}

// REST API for winners page
require_once NERA_DIR . '/inc/api/winners-api.php';

// REST API for archive winners page
require_once NERA_DIR . '/inc/api/archive-api.php';

// Giveaway plugin customizations
if (class_exists('WooCommerce_Lottery')) {
  require_once NERA_DIR . '/inc/giveaway-custom.php';
}

/**
 * Calculate reading time of a content string
 */
function nera_get_reading_time($content)
{
  $word_count = str_word_count(strip_tags($content));
  $reading_time = ceil($word_count / 200);

  return $reading_time > 0 ? $reading_time : 1;
}

// One-time: manually set one instant win prize as "won" for demo (admin only: ?nera_set_demo_instant_winner=1)

if (class_exists('WooCommerce')) {
  require_once NERA_DIR . '/inc/demo-instant-winner.php';
}

// Custom Elementor widgets
if (did_action('elementor/loaded')) {
  require_once NERA_DIR . '/elementor/widgets-loader.php';
}

/**
 * Add theme support
 */
function nera_theme_support()
{
  // Add support for custom logo
  add_theme_support('custom-logo', [
    'height' => 100,
    'width' => 400,
    'flex-width' => true,
    'flex-height' => true,
  ]);

  // Add support for post thumbnails
  add_theme_support('post-thumbnails');

  // Add support for title tag
  add_theme_support('title-tag');

  // Add support for HTML5
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

  // WooCommerce support
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');

  // Register navigation menus
  register_nav_menus([
    'primary-menu' => __('Primary Menu', 'nera-competitions'),
  ]);
}
add_action('after_setup_theme', 'nera_theme_support');

/**
 * Register widget areas
 */
function nera_widgets_init()
{
  register_sidebar([
    'name' => __('Competition Sidebar', 'nera-competitions'),
    'id' => 'competition-sidebar',
    'description' => __(
      'Widgets in this area will be shown on competition pages.',
      'nera-competitions',
    ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ]);

  // Footer Column 1 - Brand & Socials
  register_sidebar([
    'name' => __('Footer Column 1', 'nera-competitions'),
    'id' => 'footer-1',
    'description' => __(
      'First column of the footer. Typically for brand info and social links.',
      'nera-competitions',
    ),
    'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' =>
      '<h4 class="font-semibold text-text-primary mb-4 text-sm uppercase tracking-wide">',
    'after_title' => '</h4>',
  ]);

  // Footer Column 2 - Links
  register_sidebar([
    'name' => __('Footer Column 2', 'nera-competitions'),
    'id' => 'footer-2',
    'description' => __('Second column of the footer.', 'nera-competitions'),
    'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' =>
      '<h4 class="font-semibold text-text-primary mb-4 text-sm uppercase tracking-wide">',
    'after_title' => '</h4>',
  ]);

  // Footer Column 3 - Links
  register_sidebar([
    'name' => __('Footer Column 3', 'nera-competitions'),
    'id' => 'footer-3',
    'description' => __('Third column of the footer.', 'nera-competitions'),
    'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' =>
      '<h4 class="font-semibold text-text-primary mb-4 text-sm uppercase tracking-wide">',
    'after_title' => '</h4>',
  ]);

  // Footer Column 4 - Links
  register_sidebar([
    'name' => __('Footer Column 4', 'nera-competitions'),
    'id' => 'footer-4',
    'description' => __('Fourth column of the footer.', 'nera-competitions'),
    'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
    'after_widget' => '</div>',
    'before_title' =>
      '<h4 class="font-semibold text-text-primary mb-4 text-sm uppercase tracking-wide">',
    'after_title' => '</h4>',
  ]);
}
add_action('widgets_init', 'nera_widgets_init');

/**
 * Add body class for homepage template
 */
function nera_body_classes($classes)
{
  if (is_page_template('page-templates/homepage-template.php') || is_front_page()) {
    $classes[] = 'nera-homepage-template';
  }
  return $classes;
}
add_filter('body_class', 'nera_body_classes');

/**
 * Add body class for product listing template
 */
function nera_product_listing_body_classes($classes)
{
  if (is_page_template('page-templates/product-listing-template.php')) {
    $classes[] = 'nera-product-listing-template';
  }
  return $classes;
}
add_filter('body_class', 'nera_product_listing_body_classes');

/**
 * AJAX handler for filtering products
 */
function nera_ajax_filter_products()
{
  check_ajax_referer('nera_nonce', 'nonce');

  $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
  $price = isset($_POST['price']) ? sanitize_text_field($_POST['price']) : '';
  $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
  $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'ending-soon';
  $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
  $per_page = isset($_POST['per_page']) ? absint($_POST['per_page']) : 12;

  // Build query args
  $args = [
    'post_type' => 'product',
    'posts_per_page' => $per_page,
    'paged' => $page,
    'post_status' => 'publish',
    'tax_query' => [
      [
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'lottery',
      ],
    ],
  ];

  // Category filter
  if (!empty($category)) {
    $args['tax_query'][] = [
      'taxonomy' => 'product_cat',
      'field' => 'slug',
      'terms' => $category,
    ];
    $args['tax_query']['relation'] = 'AND';
  }

  // Price filter
  if (!empty($price)) {
    $price_range = explode('-', $price);
    if (count($price_range) === 2) {
      $min_price = floatval($price_range[0]);
      $max_price = floatval($price_range[1]);
      $args['meta_query'][] = [
        'key' => '_price',
        'value' => [$min_price, $max_price],
        'type' => 'NUMERIC',
        'compare' => 'BETWEEN',
      ];
    } elseif (strpos($price, '+') !== false) {
      $min_price = floatval(str_replace('+', '', $price));
      $args['meta_query'][] = [
        'key' => '_price',
        'value' => $min_price,
        'type' => 'NUMERIC',
        'compare' => '>=',
      ];
    }
  }

  // Status filter
  if (!empty($status)) {
    $now = current_time('mysql', true);

    switch ($status) {
      case 'ending-soon':
        // Ending within 24 hours
        $args['meta_query'][] = [
          'key' => '_lty_end_date_gmt',
          'value' => [$now, date('Y-m-d H:i:s', strtotime('+24 hours'))],
          'type' => 'DATETIME',
          'compare' => 'BETWEEN',
        ];
        break;

      case 'last-tickets':
        // We'll filter these in PHP after query since it requires calculation
        break;

      case 'new':
        // Created within last 7 days
        $args['date_query'] = [
          [
            'after' => '7 days ago',
            'inclusive' => true,
          ],
        ];
        break;
    }
  }

  // Sorting
  switch ($sort) {
    case 'ending-soon':
      $args['meta_key'] = '_lty_end_date_gmt';
      $args['orderby'] = 'meta_value';
      $args['order'] = 'ASC';
      break;

    case 'newest':
      $args['orderby'] = 'date';
      $args['order'] = 'DESC';
      break;

    case 'price-low':
      $args['meta_key'] = '_price';
      $args['orderby'] = 'meta_value_num';
      $args['order'] = 'ASC';
      break;

    case 'price-high':
      $args['meta_key'] = '_price';
      $args['orderby'] = 'meta_value_num';
      $args['order'] = 'DESC';
      break;

    case 'popularity':
      $args['meta_key'] = 'total_sales';
      $args['orderby'] = 'meta_value_num';
      $args['order'] = 'DESC';
      break;
  }

  $query = new WP_Query($args);

  // Filter for last-tickets status (requires post-query filtering)
  $filtered_posts = [];
  if ($status === 'last-tickets' && $query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      global $product;

      $max_tickets = get_post_meta(get_the_ID(), '_lty_maximum_tickets', true);
      $sold_tickets = method_exists($product, 'get_purchased_ticket_count')
        ? $product->get_purchased_ticket_count()
        : 0;
      $remaining = $max_tickets ? max(0, $max_tickets - $sold_tickets) : 0;

      // Only include if 50 or fewer tickets remaining
      if ($remaining > 0 && $remaining <= 50) {
        $filtered_posts[] = get_the_ID();
      }
    }
    wp_reset_postdata();

    // Re-query with filtered IDs
    if (!empty($filtered_posts)) {
      $args['post__in'] = $filtered_posts;
      unset($args['meta_query']);
      $query = new WP_Query($args);
    } else {
      // No products match
      $query = new WP_Query(['post__in' => [0]]);
    }
  }

  // Build HTML output
  ob_start();

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      global $product;

      get_template_part('template-parts/product-listing/product-card', null, [
        'product' => $product,
      ]);
    }
  } else {
    ?>
    <div class="col-span-full text-center py-16">
      <div class="max-w-md mx-auto">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="1.5">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <h3 class="text-xl font-bold text-text-primary mb-2">
          <?php _e('No competitions found', 'nera-competitions'); ?>
        </h3>
        <p class="text-text-secondary">
          <?php _e(
            'Try adjusting your filters or check back soon!',
            'nera-competitions',
          ); ?>
        </p>
      </div>
    </div>
    <?php
  }

  $html = ob_get_clean();
  wp_reset_postdata();

  // Calculate pagination info
  $total = $query->found_posts;
  $total_pages = $query->max_num_pages;
  $showing = min($page * $per_page, $total);
  $has_more = $page < $total_pages;

  wp_send_json_success([
    'html' => $html,
    'total' => $total,
    'showing' => $showing,
    'page' => $page,
    'total_pages' => $total_pages,
    'has_more' => $has_more,
    'next_page' => $has_more ? $page + 1 : null,
  ]);
}
add_action('wp_ajax_nera_filter_products', 'nera_ajax_filter_products');
add_action('wp_ajax_nopriv_nera_filter_products', 'nera_ajax_filter_products');

/**
 * Add header cart count fragments for AJAX cart updates
 */
function nera_add_header_cart_count_fragments($fragments)
{
  $cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

  ob_start();
  ?>
  <span class="nera-header-cart-count-desktop-wrapper absolute -top-1 -right-1">
    <?php if ($cart_count > 0): ?>
      <span
        class="bg-earthy-terracotta-dark text-earthy-bronze text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
        <?php echo esc_html($cart_count); ?>
      </span>
    <?php endif; ?>
  </span>
  <?php
  $fragments['span.nera-header-cart-count-desktop-wrapper'] = ob_get_clean();

  ob_start();
  ?>
  <span class="nera-header-cart-count-mobile-wrapper">
    <?php if ($cart_count > 0): ?>
      <span
        class="bg-earthy-terracotta-dark text-earthy-bronze text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
        <?php echo esc_html($cart_count); ?>
      </span>
    <?php endif; ?>
  </span>
  <?php
  $fragments['span.nera-header-cart-count-mobile-wrapper'] = ob_get_clean();

  ob_start();
  ?>
  <span class="nera-header-cart-count-mobile-nav-wrapper absolute -top-1 -right-1">
    <?php if ($cart_count > 0): ?>
      <span
        class="bg-earthy-terracotta-dark text-earthy-bronze text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
        <?php echo esc_html($cart_count); ?>
      </span>
    <?php endif; ?>
  </span>
  <?php
  $fragments['span.nera-header-cart-count-mobile-nav-wrapper'] = ob_get_clean();

  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'nera_add_header_cart_count_fragments');

/**
 * AJAX add to cart handler for WooCommerce
 * Ensures proper AJAX response for add to cart requests
 */
function nera_ajax_add_to_cart()
{
  $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
  $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

  if (!$product_id) {
    wp_send_json(['error' => true, 'message' => __('Invalid product.', 'nera-competitions')]);
  }

  $product = wc_get_product($product_id);

  if (!$product) {
    wp_send_json(['error' => true, 'message' => __('Product not found.', 'nera-competitions')]);
  }

  // Add to cart
  $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);

  if ($cart_item_key) {
    // Ensure session cookie is initialized for guest users.
    // Without this, server-level caches (e.g. SiteGround Dynamic Cache) won't see
    // the woocommerce_session cookie and may serve a stale empty cart page.
    if (!is_user_logged_in() && WC()->session && !WC()->session->has_session()) {
      WC()->session->set_customer_session_cookie(true);
    }

    // Fire the cart cookies action so woocommerce_items_in_cart cookie is set.
    // SiteGround Dynamic Cache (and similar Nginx caches) bypass caching when this
    // cookie is present, ensuring the cart page is served fresh rather than from cache.
    do_action('woocommerce_set_cart_cookies', true);

    // Flush session to DB before sending the JSON response so the session data
    // is available when the browser navigates to the cart page.
    if (WC()->session) {
      WC()->session->save_data();
    }

    // Get cart fragments for updating mini cart
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    $fragments = [
      'div.widget_shopping_cart_content' =>
        '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
    ];

    // Apply WooCommerce fragments filter
    $fragments = apply_filters('woocommerce_add_to_cart_fragments', $fragments);

    wp_send_json([
      'error' => false,
      'message' => get_field('add_to_cart_success_message', 'option') ?: __('Tickets added to cart.', 'nera-competitions'),
      'cart_hash' => WC()->cart->get_cart_hash(),
      'cart_quantity' => WC()->cart->get_cart_contents_count(),
      'fragments' => $fragments,
    ]);
  } else {
    wp_send_json(['error' => true, 'message' => __('Could not add to cart.', 'nera-competitions')]);
  }
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'nera_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'nera_ajax_add_to_cart');

/**
 * Send no-cache headers for the cart page
 *
 * WooCommerce already sends Cache-Control no-cache headers, but server-level caches
 * like SiteGround Dynamic Cache can bypass them. This adds a secondary layer including
 * the SiteGround-specific X-SG-No-Cache header and the WooCommerce cart/checkout/account
 * page exclusion header, ensuring the cart page is never served stale from cache.
 */
function nera_cart_no_cache_headers()
{
  if (function_exists('is_cart') && is_cart()) {
    nocache_headers();
    header('X-SG-No-Cache: 1');
  }
}
add_action('send_headers', 'nera_cart_no_cache_headers');

/**
 * Populate Winners Page with Sample Data
 *
 * Temporary function to add sample winners data for testing.
 * Can be removed after populating real winner data.
 */
function nera_populate_sample_winners_data()
{
  // Find the Winners page
  $winners_page = get_posts([
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/winners-template.php',
    'posts_per_page' => 1,
  ]);

  if (empty($winners_page)) {
    return [
      'success' => false,
      'message' => 'Winners page not found. Please create a page using the Winners template first.',
    ];
  }

  $page_id = $winners_page[0]->ID;

  // Sample winners data from screenshot
  $sample_winners = [
    [
      'name' => 'Donald Yeoman',
      'prize' => 'Target Omni Ultimate Home Dart Bundle',
      'date' => '2026-01-05',
      'quote' => "I can't believe I won! This is absolutely amazing, thank you so much!",
      'category' => 'live-draw',
    ],
    [
      'name' => 'Stephen Lamb',
      'prize' => '75" LG QNED EVO AI MiniLED 4K Smart TV',
      'date' => '2026-01-01',
      'quote' =>
        "What an incredible prize! I've always wanted a TV like this. Dreams do come true!",
      'category' => 'live-draw',
    ],
    [
      'name' => 'Stephanie Scott',
      'prize' => 'Free Entry - Bambu Lab P1S Combo',
      'date' => '2026-02-02',
      'quote' => 'Free entry and I actually won! This has made my entire year!',
      'category' => 'instant-win',
    ],
    [
      'name' => 'Marvin Chaplin',
      'prize' => 'Pokémon TCG Prismatic Evolutions Bundle',
      'date' => '2026-01-29',
      'quote' => 'My kids are going to be so excited! Best competition ever!',
      'category' => 'live-draw',
    ],
  ];

  // Update ACF fields
  if (function_exists('update_field')) {
    // Set hero section if empty
    if (!get_field('winners_heading', $page_id)) {
      update_field('winners_heading', 'Recent Winners', $page_id);
    }
    if (!get_field('winners_subheading', $page_id)) {
      update_field('winners_subheading', 'Our Lucky Winners', $page_id);
    }
    if (!get_field('winners_description', $page_id)) {
      update_field(
        'winners_description',
        'Congratulations to all our amazing winners! See who\'s been lucky recently and get inspired for your next entry.',
        $page_id,
      );
    }

    // Set display settings
    update_field('winners_per_page', 12, $page_id);
    update_field('winners_show_filters', 1, $page_id);
    update_field('winners_show_quotes', 1, $page_id);

    // Populate winners list
    update_field('winners_list', $sample_winners, $page_id);

    return [
      'success' => true,
      'message' => 'Sample winners data added successfully! You can now view the Winners page.',
      'page_id' => $page_id,
    ];
  } else {
    return [
      'success' => false,
      'message' =>
        'ACF Pro plugin is not active. Please ensure ACF Pro is installed and activated.',
    ];
  }
}

/**
 * Handle sample data population action
 */
function nera_handle_populate_sample_winners()
{
  // Check nonce and capabilities
  if (!isset($_GET['nera_populate_winners']) || !isset($_GET['_wpnonce'])) {
    return;
  }

  if (!wp_verify_nonce($_GET['_wpnonce'], 'nera_populate_winners_sample')) {
    wp_die('Security check failed');
  }

  if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to perform this action');
  }

  $result = nera_populate_sample_winners_data();

  // Redirect with result message
  $redirect_url = admin_url('admin.php?page=nera-winners-sample');

  if ($result['success']) {
    $redirect_url = add_query_arg(
      [
        'nera_sample_success' => '1',
        'page_id' => $result['page_id'],
      ],
      admin_url('edit.php?post_type=page'),
    );
  } else {
    $redirect_url = add_query_arg(
      'nera_sample_error',
      urlencode($result['message']),
      admin_url('index.php'),
    );
  }

  wp_redirect($redirect_url);
  exit();
}
add_action('admin_init', 'nera_handle_populate_sample_winners');

/**
 * Display admin notice with button to populate sample winners
 */
function nera_sample_winners_admin_notice()
{
  // Only show to administrators
  if (!current_user_can('manage_options')) {
    return;
  }

  // Check if Winners page exists
  $winners_page = get_posts([
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/winners-template.php',
    'posts_per_page' => 1,
  ]);

  if (empty($winners_page)) {
    return;
  }

  $page_id = $winners_page[0]->ID;
  $existing_winners = get_field('winners_list', $page_id);

  // Show success message
  if (isset($_GET['nera_sample_success'])) {

    $page_id = isset($_GET['page_id']) ? absint($_GET['page_id']) : 0;
    $view_url = $page_id ? get_permalink($page_id) : '';
    ?>
    <div class="notice notice-success is-dismissible">
      <p>
        <strong>Success!</strong> Sample winners data has been added to your Winners page.
        <?php if ($view_url): ?>
          <a href="<?php echo esc_url(
            $view_url,
          ); ?>" target="_blank" class="button button-small" style="margin-left: 10px;">View Winners Page</a>
        <?php endif; ?>
      </p>
    </div>
    <?php return;
  }

  // Show error message
  if (isset($_GET['nera_sample_error'])) { ?>
    <div class="notice notice-error is-dismissible">
      <p><strong>Error:</strong> <?php echo esc_html(
        urldecode($_GET['nera_sample_error']),
      ); ?></p>
    </div>
    <?php return;
  }

  // Don't show populate button if winners already exist
  if (!empty($existing_winners) && is_array($existing_winners) && count($existing_winners) > 0) {
    return;
  }

  // Show populate button
  $populate_url = wp_nonce_url(
    add_query_arg('nera_populate_winners', '1', admin_url('admin.php')),
    'nera_populate_winners_sample',
  );
  ?>
  <div class="notice notice-info">
    <p>
      <strong>Winners Page Sample Data</strong><br>
      Your Winners page is empty. Would you like to add sample winner data for testing?
    </p>
    <p>
      <a href="<?php echo esc_url(
        $populate_url,
      ); ?>" class="button button-primary">Add Sample Winners Data</a>
      <a href="<?php echo esc_url(
        get_edit_post_link($page_id),
      ); ?>" class="button" style="margin-left: 10px;">Edit Winners Page</a>
    </p>
  </div>
  <?php
}
add_action('admin_notices', 'nera_sample_winners_admin_notice');

// ACF Header Fields (Theme Settings > Header)
require_once get_template_directory() . '/inc/acf-header.php';

// ACE Footer Fields
require_once get_template_directory() . '/inc/acf-footer.php';
