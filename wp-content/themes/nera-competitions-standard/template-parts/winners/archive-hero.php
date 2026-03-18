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

<div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 text-center pt-12 lg:pt-20 pb-8 lg:pb-12">
  <!-- Badge Pill -->
  <div class="flex justify-center mb-6" data-aos="fade-up">
    <span
      class="inline-flex items-center px-4 py-2 rounded-full bg-sage/20 text-sage text-sm font-semibold border border-border">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
  <p class="text-lg lg:text-xl text-ink-soft leading-relaxed max-w-2xl mx-auto" data-aos="fade-up"
    data-aos-delay="200">
    <?php echo esc_html($description); ?>
  </p>
</div>