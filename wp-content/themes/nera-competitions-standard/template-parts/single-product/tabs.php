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
$effective_draw_gmt = function_exists('nera_get_effective_draw_date_gmt')
  ? nera_get_effective_draw_date_gmt($product_id)
  : $end_date_gmt;
$has_instant_wins = false;

$show_entry_list_tab = get_field('show_entry_list_tab', $product_id);
if ($show_entry_list_tab === null) {
  $show_entry_list_tab = true;
}

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
    <?php if ($show_entry_list_tab): ?>
    <button class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-ink-soft hover:text-ink transition-colors"
      data-tab="entry-list">
      <?php _e('Entry List', 'nera-competitions'); ?>
    </button>
    <?php endif; ?>
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

  <?php if ($show_entry_list_tab): ?>
  <div class="tab-panel mt-6 hidden" data-tab-panel="entry-list">
    <?php do_action( 'lty_lottery_entry_list_content', $product ); ?>
  </div>
  <?php endif; ?>

  <div class="tab-panel mt-6 hidden" data-tab-panel="draw-info">
    <?php
    $draw_live_details = function_exists('get_field') ? get_field('draw_live_details', $product_id) : '';
    $has_draw_live_rich =
      $draw_live_details &&
      trim(wp_strip_all_tags((string) $draw_live_details)) !== '';
    $draw_date =
      function_exists('nera_format_draw_date') && $effective_draw_gmt
        ? nera_format_draw_date($effective_draw_gmt)
        : '';
    if ($has_draw_live_rich || $draw_date): ?>
      <p class="text-ink-soft">
        <span class="text-ink-soft"><?php esc_html_e('The draw will take place on ', 'nera-competitions'); ?></span>
        <?php if ($has_draw_live_rich): ?>
          <span class="text-ink max-w-none inline-block align-top"><?php echo wp_kses_post($draw_live_details); ?></span>
        <?php else: ?>
          <strong class="text-ink"><?php echo esc_html($draw_date); ?></strong>.
        <?php endif; ?>
      </p>
    <?php endif; ?>

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