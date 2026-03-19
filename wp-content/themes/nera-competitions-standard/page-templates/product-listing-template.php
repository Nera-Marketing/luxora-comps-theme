<?php
/**
 * Template Name: Nera Product Listing
 * Template Post Type: page
 *
 * Product listing page template with filters and AJAX functionality
 * Based on Stitch design "Competition Listings Minimalist Light"
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Load Luxora fonts for the editorial hero + card design
add_action('wp_head', function () {
  echo '<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500&family=Dancing+Script:wght@600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">' . "\n";
}, 5);

// Add body class for page-scoped CSS
add_filter('body_class', function ($classes) {
  $classes[] = 'nera-product-listing-luxora';
  return $classes;
});

get_header();
?>

<main id="main" class="nera-product-listing" role="main">

  <?php
  // 1. Hero Section - Page title and tagline
  get_template_part('template-parts/product-listing/hero-section');

  // 2. Filter Bar - Category, Price, Status, Sort dropdowns
  // get_template_part('template-parts/product-listing/filter-bar');
  get_template_part('template-parts/homepage/categories-filter');

  // 3. Product Grid - Competition cards
  get_template_part('template-parts/homepage/categories-competitions', null, array('show_view_all' => false));

  // 4. Trust Features - 3 trust badges
  get_template_part('template-parts/product-listing/trust-features');
  ?>

</main>

<?php get_footer();
?>