<?php
/**
 * Competition Icons Template Part
 *
 * Info icons row showing key competition details.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

$product = isset($args['product']) ? $args['product'] : null;
$lottery_data = isset($args['lottery_data']) ? $args['lottery_data'] : [];

if (!$product) {
  return;
}

$product_id = $product->get_id();

// ACF settings
$show_tickets = get_field('show_tickets_available', $product_id);
$show_max_per_user = get_field('show_max_per_user', $product_id);
$show_draw_date = get_field('show_draw_date', $product_id);
$custom_icons = get_field('custom_info_icons', $product_id);

// Default to showing if not set
if ($show_tickets === null) {
  $show_tickets = true;
}
if ($show_max_per_user === null) {
  $show_max_per_user = true;
}
if ($show_draw_date === null) {
  $show_draw_date = true;
}

// Get lottery data
$remaining = $lottery_data['remainingTickets'] ?? 0;
$max_per_order = $lottery_data['maxPerOrder'] ?? 0;
$max_per_user = $lottery_data['maxPerUser'] ?? 0;
$end_date_gmt = get_post_meta($product_id, '_lty_end_date_gmt', true);
$effective_draw_gmt = function_exists('nera_get_effective_draw_date_gmt')
  ? nera_get_effective_draw_date_gmt($product_id)
  : $end_date_gmt;
$has_live_draw_override =
  function_exists('nera_has_actual_draw_date_override') &&
  nera_has_actual_draw_date_override($product_id);

$icons = [];

// Tickets Available
if ($show_tickets && $remaining > 0) {
  $icons[] = [
    'icon' => 'confirmation_number',
    'label' => __('Available', 'nera-competitions'),
    'value' => number_format($remaining),
  ];
}

// Max Per Order (primary display)
if ($show_max_per_user && $max_per_order > 0) {
  $icons[] = [
    'icon' => 'shopping_cart',
    'label' => __('Max Per Order', 'nera-competitions'),
    'value' => number_format($max_per_order),
  ];
}

// Draw date (optional “live” override vs LFW end date)
if ($show_draw_date && $effective_draw_gmt) {
  $icons[] = [
    'icon' => 'event',
    'label' => $has_live_draw_override
      ? __('Live draw', 'nera-competitions')
      : __('Draw Date', 'nera-competitions'),
    'value' => nera_format_draw_date($effective_draw_gmt),
  ];
}

// Custom Icons
if (!empty($custom_icons)) {
  foreach ($custom_icons as $custom) {
    if (!empty($custom['icon']) && !empty($custom['value'])) {
      $icons[] = [
        'icon' => $custom['icon'],
        'label' => $custom['label'] ?? '',
        'value' => $custom['value'],
      ];
    }
  }
}

if (empty($icons)) {
  return;
}
?>

<div class="competition-icons">
  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
    <?php foreach ($icons as $item): ?>
      <div class="icon-item flex items-center gap-3 bg-gray-50 rounded-xl p-3">
        <div class="icon-wrapper flex-shrink-0 w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
          <span class="material-symbols-outlined text-primary text-xl">
            <?php echo esc_html($item['icon']); ?>
          </span>
        </div>
        <div class="icon-content min-w-0">
          <?php if (!empty($item['label'])): ?>
            <span class="block text-xs text-text-secondary truncate">
              <?php echo esc_html($item['label']); ?>
            </span>
          <?php endif; ?>
          <span class="block text-sm font-bold text-text-primary truncate">
            <?php echo esc_html($item['value']); ?>
          </span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
