<?php
/**
 * Template Name: Nera Archive Prizes
 * Template Post Type: page
 *
 * Archive Prizes page template displaying all available competition prizes.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

get_header();
?>

<main id="main" class="nera-archive-prizes-page" role="main">
  <?php
  get_template_part('template-parts/prizes/archive-hero');
  get_template_part('template-parts/prizes/archive-grid');
  ?>
</main>

<?php get_footer();
