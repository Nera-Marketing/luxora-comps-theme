<?php
/**
 * Product Listing Hero Section Template Part
 *
 * Centered hero with page title and tagline.
 * LUXORA brand — enhanced visibility, Playfair Display typography.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Get page title and excerpt context
$post_id = isset($args['post_id']) && $args['post_id'] ? $args['post_id'] : get_the_ID();

// Get page title and excerpt
$page_title = get_the_title($post_id);
$page_tagline = get_the_excerpt($post_id);

// Fallback tagline if no excerpt is set
if (empty($page_tagline)) {
  $page_tagline = __(
    'Enter to win amazing prizes with our exclusive competitions.',
    'nera-competitions',
  );
}
?>

<section class="product-listing-hero" role="banner">
  <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
    <div class="product-listing-hero-inner text-center max-w-2xl mx-auto">
      <p class="hero-eyebrow" data-aos="fade-up">
        <span class="lux-dot" aria-hidden="true"></span>
        <?php esc_html_e('All Competitions', 'nera-competitions'); ?>
      </p>
      <h1 class="hero-title" data-aos="fade-up" data-aos-delay="50">
        <?php echo esc_html($page_title); ?>
      </h1>
      <p class="hero-tagline" data-aos="fade-up" data-aos-delay="100">
        <?php echo esc_html($page_tagline); ?>
      </p>
    </div>
  </div>
</section>
