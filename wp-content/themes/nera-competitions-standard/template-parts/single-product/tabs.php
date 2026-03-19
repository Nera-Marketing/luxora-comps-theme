<?php
/**
 * Tabs template part for Single Competition
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$product = $args['product'] ?? null;
$specifications = $args['specifications'] ?? [];
$end_date_gmt = $args['end_date_gmt'] ?? '';

if (!$product) {
  return;
}

$product_id = $product->get_id();
$has_instant_wins = false;

if (
  function_exists('lty_is_lottery_product') &&
  lty_is_lottery_product($product) &&
  method_exists($product, 'is_instant_winner') &&
  $product->is_instant_winner()
) {
  $has_instant_wins = true;
}
?>

<!-- Tabs Section -->
<div class="mt-8" data-product-tabs>
  <!-- Tab Navigation -->
  <div class="flex border-b border-border">
    <button class="tab-btn px-6 py-3 text-sm font-semibold text-ink border-b-2 border-forest -mb-px"
      data-tab="prize-details">
      <?php _e('Prize Details', 'nera-competitions'); ?>
    </button>
    <button class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-ink-soft hover:text-ink transition-colors"
      data-tab="entry-list">
      <?php _e('Entry List', 'nera-competitions'); ?>
    </button>
    <button class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-ink-soft hover:text-ink transition-colors"
      data-tab="draw-info">
      <?php _e('Draw Information', 'nera-competitions'); ?>
    </button>
  </div>

  <!-- Tab Content -->
  <div class="tab-panel mt-6" data-tab-panel="prize-details">
    <!-- Specifications Grid -->
    <?php if (!empty($specifications)): ?>
      <div class="mb-6">
        <h3 class="text-lg font-bold text-ink mb-4">
          <?php _e('Specifications', 'nera-competitions'); ?>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 sm:gap-x-8 gap-y-3">
          <?php foreach ($specifications as $spec): ?>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-1 gap-0.5 sm:gap-0">
              <span class="text-ink-soft">
                <?php echo esc_html($spec['label']); ?>
              </span>
              <span class="font-semibold text-ink">
                <?php echo esc_html($spec['value']); ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Product Description -->
    <div class="max-w-none text-ink-soft leading-relaxed">
      <?php echo wp_kses_post($product->get_description()); ?>
    </div>
  </div>

  <div class="tab-panel mt-6 hidden" data-tab-panel="entry-list">
    <p class="text-ink-soft">
      <?php _e('Entry list will be displayed here after purchase.', 'nera-competitions'); ?>
    </p>
  </div>

  <div class="tab-panel mt-6 hidden" data-tab-panel="draw-info">
    <?php
    $draw_date = nera_format_draw_date($end_date_gmt);
    if ($draw_date): ?>
      <p class="text-ink-soft">
        <?php printf(
          __('The draw will take place on %s.', 'nera-competitions'),
          '<strong>' . esc_html($draw_date) . '</strong>',
        ); ?>
      </p>
    <?php endif;
    ?>

    <?php if (function_exists('get_field')): ?>
      <?php $competition_rules = get_field('competition_rules', $product_id); ?>
      <?php if ($competition_rules): ?>
        <div class="mt-4 max-w-none text-ink-soft">
          <?php echo wp_kses_post($competition_rules); ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>