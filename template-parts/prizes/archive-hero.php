<?php
/**
 * Archive Prizes Page - Hero Section
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit();
}

// Get ACF field values with fallbacks
$heading = function_exists('get_field')
  ? get_field('archive_prizes_heading')
  : __('All Prizes', 'nera-competitions');
$subheading = function_exists('get_field')
  ? get_field('archive_prizes_subheading')
  : __('Prize Archive', 'nera-competitions');
$description = function_exists('get_field')
  ? get_field('archive_prizes_description')
  : __('Browse all the incredible prizes up for grabs across our competitions.', 'nera-competitions');

$heading    = $heading    ?: __('All Prizes', 'nera-competitions');
$subheading = $subheading ?: __('Prize Archive', 'nera-competitions');
$description = $description ?: __('Browse all the incredible prizes up for grabs across our competitions.', 'nera-competitions');
?>

<div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 text-center pt-12 lg:pt-20 pb-8 lg:pb-12">

  <!-- Badge Pill -->
  <div class="flex justify-center mb-6" data-aos="fade-up">
    <span class="inline-flex items-center px-4 py-2 rounded-full bg-sage/20 text-sage text-sm font-semibold border border-border">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5 3h14M5 3a2 2 0 00-2 2v1a4 4 0 004 4h6a4 4 0 004-4V5a2 2 0 00-2-2M5 3l1 9m13-9l-1 9m-5 4v3m0 0H9m3 0h3"></path>
      </svg>
      <?php echo esc_html($subheading); ?>
    </span>
  </div>

  <!-- Main Heading -->
  <h1 class="font-heading text-4xl lg:text-5xl xl:text-6xl font-bold text-ink mb-6 tracking-tight"
    data-aos="fade-up" data-aos-delay="100">
    <?php echo esc_html($heading); ?>
  </h1>

  <!-- Description -->
  <p class="text-lg lg:text-xl text-ink-soft leading-relaxed max-w-2xl mx-auto"
    data-aos="fade-up" data-aos-delay="200">
    <?php echo esc_html($description); ?>
  </p>

</div>
