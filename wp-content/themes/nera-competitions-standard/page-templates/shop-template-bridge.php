<?php
/**
 * Shop Page Template Bridge V2
 *
 * Uses the Product Listing Template V2 logic (Active Grid, Descriptions, HIW)
 * but ensures correct context for the Shop page.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();

// Get the Shop Page ID for content context
$shop_page_id = wc_get_page_id('shop');
?>

<main id="main" class="nera-product-listing nera-shop-page bg-[#0c0b09]" role="main">

  <?php
  // 1. Hero Section - Pass Shop Page ID to get title/tagline from the Shop page settings
  get_template_part('template-parts/product-listing/hero-section', null, [
    'post_id' => $shop_page_id,
  ]);

  // 2. Filter Bar - Category, Price, Status, Sort dropdowns
  get_template_part('template-parts/homepage/categories-filter');

  // 3. Product Grid - The active competition grid
  // get_template_part('template-parts/homepage/categories-competitions', null, array('show_view_all' => false));
  
  // 4. Trust Features - Pass Shop Page ID to get ACF fields from the Shop page
  get_template_part('template-parts/product-listing/trust-features', null, [
    'post_id' => $shop_page_id,
  ]);
  ?>

</main>

<?php get_footer();
