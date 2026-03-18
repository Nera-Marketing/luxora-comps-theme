<?php
/**
 * Product Info Card Template Part
 *
 * Main product information container with all interactive elements.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

$product = isset($args['product']) ? $args['product'] : null;
$lottery_data = isset($args['lottery_data']) ? $args['lottery_data'] : [];
$countdown = isset($args['countdown']) ? $args['countdown'] : [];

if (!$product) {
  return;
}

$product_id = $product->get_id();
?>

<div class="bg-[#1e1c18] rounded-3xl border border-[rgba(216,181,130,0.2)] p-6 lg:p-8 space-y-6">

  <!-- Product Title -->
  <div>
    <h1 class="text-2xl lg:text-3xl font-bold text-ink leading-tight">
      <?php echo esc_html($product->get_name()); ?>
    </h1>
    <?php if ($product->get_short_description()): ?>
      <p class="mt-2 text-[rgba(216,181,130,0.56)] text-sm">
        <?php echo wp_kses_post($product->get_short_description()); ?>
      </p>
    <?php endif; ?>
  </div>

  <!-- Price Display -->
  <?php get_template_part('template-parts/single-product/price-display', null, [
    'product' => $product,
  ]); ?>

  <!-- Countdown Timer -->
  <?php get_template_part('template-parts/single-product/countdown-timer', null, [
    'product' => $product,
    'countdown' => $countdown,
  ]); ?>

  <!-- Progress Bar -->
  <?php get_template_part('template-parts/single-product/progress-bar', null, [
    'product' => $product,
    'lottery_data' => $lottery_data,
  ]); ?>

  <!-- Competition Info Icons -->
  <?php get_template_part('template-parts/single-product/competition-icons', null, [
    'product' => $product,
    'lottery_data' => $lottery_data,
  ]); ?>

  <!-- Q&A Skill Question (from Lottery plugin) -->
  <?php // Hook for the lottery plugin Q&A

do_action('woocommerce_lottery_before_add_to_cart_button'); ?>

  <!-- Quantity Selector -->
  <?php get_template_part('template-parts/single-product/quantity-selector', null, [
    'product' => $product,
    'lottery_data' => $lottery_data,
  ]); ?>

  <!-- Add to Cart Button -->
  <?php get_template_part('template-parts/single-product/add-to-cart', null, [
    'product' => $product,
    'lottery_data' => $lottery_data,
  ]); ?>

  <!-- Trust Elements -->
  <?php get_template_part('template-parts/single-product/trust-elements', null, [
    'product' => $product,
  ]); ?>

</div>
