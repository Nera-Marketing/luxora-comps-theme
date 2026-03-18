<?php
/**
 * Template Name: Luxora Homepage
 * Template Post Type: page
 *
 * Luxora Draws homepage — premium competition landing with all 7 sections.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

// Luxora fonts + CSS
add_action('wp_head', function () {
  echo '<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500&family=Dancing+Script:wght@600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">' . "\n";
  $css_path = get_template_directory() . '/assets/css/luxora-homepage.css';
  $url = get_template_directory_uri() . '/assets/css/luxora-homepage.css';
  $ver = file_exists($css_path) ? filemtime($css_path) : '';
  echo '<link rel="stylesheet" href="' . esc_url($url) . '?v=' . esc_attr($ver) . '" />' . "\n";
}, 5);

get_header();
?>

<main id="main" class="luxora-homepage" role="main">

  <?php
  get_template_part('template-parts/luxora-homepage/hero-section');
  get_template_part('template-parts/luxora-homepage/marquee-banner');
  get_template_part('template-parts/luxora-homepage/competitions-section');
  get_template_part('template-parts/luxora-homepage/why-section');
  get_template_part('template-parts/luxora-homepage/winners-section');
  get_template_part('template-parts/luxora-homepage/how-it-works-section');
  get_template_part('template-parts/luxora-homepage/free-entry-banner');
  ?>

</main>

<?php get_footer(); ?>
