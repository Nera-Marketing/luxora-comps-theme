<?php
/**
 * Archive Winners Page - Hero Section
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

// Get ACF field values with fallbacks
$heading = function_exists('get_field')
  ? get_field('archive_winners_heading')
  : __('Draw Results & Winners', 'nera-competitions');
$subheading = function_exists('get_field')
  ? get_field('archive_winners_subheading')
  : __('Competition Archive', 'nera-competitions');
$description = function_exists('get_field')
  ? get_field('archive_winners_description')
  : __('Browse our completed draws, view entry lists, and catch up on any results you might have missed.', 'nera-competitions');

// Ensure defaults if fields are empty
$heading = $heading ?: __('Draw Results & Winners', 'nera-competitions');
$subheading = $subheading ?: __('Competition Archive', 'nera-competitions');
$description = $description ?: __('Browse our completed draws, view entry lists, and catch up on any results you might have missed.', 'nera-competitions');
?>

<section class="product-listing-hero" role="banner">
  <div class="max-w-[1200px] mx-auto px-4 lg:px-8 text-center">
    <div class="product-listing-hero-inner text-center">
      <p class="m-0 mb-4 text-[0.58rem] tracking-[0.28em] uppercase text-mint flex items-center justify-center gap-[10px] font-normal opacity-90" data-aos="fade-up">
        <span class="lux-dot" aria-hidden="true"></span>
        <?php echo esc_html($subheading); ?>
      </p>
      <h1 class="hero-title" data-aos="fade-up" data-aos-delay="50">
        <?php echo esc_html($heading); ?>
      </h1>
      <p class="hero-tagline" data-aos="fade-up" data-aos-delay="100">
        <?php echo esc_html($description); ?>
      </p>
    </div>
  </div>
</section>