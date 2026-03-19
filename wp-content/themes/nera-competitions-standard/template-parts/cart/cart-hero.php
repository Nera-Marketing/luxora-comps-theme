<?php
/**
 * Cart Hero Section Template Part
 *
 * Centered hero matching product-listing hero layout.
 * LUXORA brand — forest green, Playfair Display, mint tagline.
 *
 * @package Nera_Competitions
 */

defined('ABSPATH') || exit();

$title   = isset($args['title']) ? $args['title'] : __('Shopping Cart', 'nera-competitions');
$tagline = isset($args['tagline']) ? $args['tagline'] : __('Your cart is currently empty', 'nera-competitions');
$eyebrow = isset($args['eyebrow']) ? $args['eyebrow'] : __('Your Cart', 'nera-competitions');
?>

<section class="product-listing-hero" role="banner">
  <div class="max-w-[1200px] mx-auto px-4 lg:px-8">
    <div class="product-listing-hero-inner text-center max-w-2xl mx-auto">
      <h1 class="hero-title" data-aos="fade-up" data-aos-delay="50">
        <?php echo esc_html($title); ?>
      </h1>
      <p class="hero-tagline" data-aos="fade-up" data-aos-delay="100">
        <?php echo esc_html($tagline); ?>
      </p>
    </div>
  </div>
</section>
