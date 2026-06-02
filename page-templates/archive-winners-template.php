<?php
/**
 * Template Name: Nera Archive Winners
 * Template Post Type: page
 *
 * Archive Winners page template featuring finished lotteries and downloadable entry lists.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

get_header();
?>

<main id="main" class="nera-archive-winners-page" role="main">
  <?php
  // Hero section for Archive Winners
  get_template_part('template-parts/winners/archive-hero');

  // Archive grid with Vue.js integration
  get_template_part('template-parts/winners/archive-grid');
  ?>
</main>

<?php get_footer();
