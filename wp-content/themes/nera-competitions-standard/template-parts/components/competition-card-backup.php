<?php
/**
 * Competition Card Component Template Part
 *
 * Reusable card for displaying competition products.
 * Handles display logic, badges, progress bars, and countdowns.
 *
 * @package Nera_Competitions
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $x_show AlpineJS expression for visibility toggling.
 *     @type string $extra_attributes Additional HTML attributes for the article tag.
 *     @type array  $category_colors Associative array of category slugs to hex colors.
 * }
 */

if (!defined('ABSPATH')) {
  exit();
}

global $product;

// If we don't have a product global, try to get it from args or current post
if (!$product && isset($args['product'])) {
  $product = $args['product'];
} elseif (!$product) {
  $product = wc_get_product(get_the_ID());
}

if (!$product) {
  return;
}

$product_id = $product->get_id();
$image_id = $product->get_image_id();
$price = $product->get_price();

// Default Category Colors (Earthy Editorial — terracotta/bronze variants)
$category_colors = isset($args['category_colors'])
  ? $args['category_colors']
  : [
    'cars' => '#c4704e',
    'cash' => '#d8b582',
    'luxury' => '#9b5039',
    'electronics' => '#c4704e',
    'travel' => '#d8b582',
    'tech' => '#9b5039',
    'gadgets' => '#c4704e',
    'watches' => '#d8b582',
    'lifestyle' => '#9b5039',
  ];

// Lottery-specific data
$max_tickets = get_post_meta($product_id, '_lty_maximum_tickets', true);
$sold_tickets = method_exists($product, 'get_purchased_ticket_count')
  ? $product->get_purchased_ticket_count()
  : 0;

$progress = $max_tickets ? min(100, round(($sold_tickets / $max_tickets) * 100)) : 0;
$remaining = $max_tickets ? $max_tickets - $sold_tickets : 0;

// End date / countdown
$end_date_gmt = get_post_meta($product_id, '_lty_end_date_gmt', true);
$days_left = 0;
$hours_left = 0;
$mins_left = 0;

if ($end_date_gmt) {
  $end_timestamp = strtotime($end_date_gmt);
  $now = time();
  $diff = $end_timestamp - $now;

  if ($diff > 0) {
    $days_left = floor($diff / 86400);
    $hours_left = floor(($diff % 86400) / 3600);
    $mins_left = floor(($diff % 3600) / 60);
  }
}

// Status badge
$badge_text = '';
$badge_class = 'bg-gradient-to-r from-red-600 to-red-700';
$is_urgent = false;

if ($remaining > 0 && $remaining <= 50) {
  $badge_text = sprintf(__('Last %d Tickets', 'nera-competitions'), $remaining);
  $is_urgent = true;
} elseif ($days_left <= 1 && ($days_left > 0 || $hours_left > 0)) {
  $badge_text = __('Ending Soon', 'nera-competitions');
  $is_urgent = true;
} elseif ($progress >= 90) {
  $badge_text = __('Almost Gone', 'nera-competitions');
  $badge_class = 'bg-gradient-to-r from-orange-500 to-orange-600';
}

// Product categories
$product_categories = wp_get_post_terms($product_id, 'product_cat');
$category_slugs = array_map(function ($cat) {
  return $cat->slug;
}, $product_categories);

$primary_category = !empty($product_categories) ? $product_categories[0]->slug : '';
$base_accent_color = isset($category_colors[$primary_category])
  ? $category_colors[$primary_category]
  : '#9b5039';

// Parsing Args
$x_show_attr = !empty($args['x_show']) ? 'x-show="' . esc_attr($args['x_show']) . '"' : '';
$extra_attrs = !empty($args['extra_attributes']) ? $args['extra_attributes'] : '';

// Data attributes for JS filtering
$data_attributes = sprintf(
  'data-price="%s" data-end-date="%s" data-posted-date="%s" data-popularity="%s" data-categories="%s"',
  esc_attr($price),
  esc_attr($end_date_gmt ? strtotime($end_date_gmt) : '9999999999'),
  esc_attr(get_the_date('U')),
  esc_attr(get_post_meta($product_id, 'total_sales', true) ?: '0'),
  esc_attr(json_encode($category_slugs)),
);
?>

<article class="group transition-all duration-400 ease-out" <?php echo $data_attributes; ?>
  <?php echo $extra_attrs; ?>
  <?php echo $x_show_attr; ?>
  x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
  x-transition:enter-end="opacity-100 scale-100">

  <!-- Card Inner Wrapper (Earthy prize-card styling) -->
  <div
    class="prize-card bg-mint-wash rounded-[20px] overflow-hidden border border-ink-4 h-full flex flex-col transition-all duration-400 hover:-translate-y-1 hover:border-ink-12">

    <!-- Image Container -->
    <div class="prize-img relative aspect-[4/3] overflow-hidden flex items-center justify-center"
      style="background: linear-gradient(135deg, var(--color-sage-15), var(--color-ink-5));">

      <!-- Status Badge (Top Left) — Earthy style -->
      <div class="prize-status absolute top-4 left-4 z-10 flex items-center gap-1.5 bg-off-white/70 backdrop-blur-sm py-1.5 px-3.5 rounded-[20px] text-[11px] tracking-[1px] uppercase text-ink <?php echo $is_urgent ? 'animate-pulse' : ''; ?>">
        <?php if ($remaining > 0 && $progress < 90 && !$badge_text): ?>
          <span class="status-dot w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
        <?php endif; ?>
        <?php echo esc_html($badge_text ?: __('Live Now', 'nera-competitions')); ?>
      </div>

      <!-- Category Badges (Top Right) — Earthy style -->
      <?php if (!empty($product_categories)): ?>
        <div class="absolute top-4 right-4 z-10 flex items-center gap-1.5">
          <div class="px-3 py-1.5 text-xs font-medium rounded-full bg-sage-20 text-sage border border-ink-20">
            <?php echo esc_html($product_categories[0]->name); ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Product Image -->
      <a href="<?php the_permalink(); ?>" class="block w-full h-full">
        <?php if ($image_id): ?>
          <?php $image_url = wp_get_attachment_image_url($image_id, 'large'); ?>
          <div
            class="w-full h-full bg-center bg-no-repeat bg-cover transition-transform duration-500 ease-out group-hover:scale-110"
            style="background-image: url('<?php echo esc_url($image_url); ?>');">
          </div>
        <?php else: ?>
          <div class="w-full h-full flex flex-col items-center justify-center gap-2">
            <span class="font-heading italic text-ink-20 text-sm"><?php the_title(); ?></span>
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
              class="text-ink-20">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
        <?php endif; ?>
      </a>

      <!-- Price Badge (Bottom Right) — Earthy -->
      <div class="absolute bottom-4 right-4 z-10 px-4 py-2.5 bg-off-white/70 backdrop-blur-md rounded-xl border border-ink-20">
        <div class="text-sm font-bold leading-none mb-0.5 text-ink"><?php echo wc_price($price); ?></div>
        <div class="text-[10px] font-medium text-ink-56 uppercase tracking-[1px]">per entry</div>
      </div>
    </div>

    <!-- Card Content (prize-body) -->
    <div class="prize-body p-6 flex-1 flex flex-col">

      <!-- Title -->
      <h3 class="font-heading text-[22px] font-normal text-ink mb-2 leading-tight">
        <a href="<?php the_permalink(); ?>" class="hover:text-sage transition-colors">
          <?php the_title(); ?>
        </a>
      </h3>

      <!-- Progress Section (prize-stats, prize-bar) -->
      <div class="space-y-3 mb-auto">
        <?php if ($max_tickets): ?>
          <div class="prize-stats flex gap-5 mb-4">
            <div class="prize-stat flex flex-col">
              <label class="text-[10px] text-ink-20 uppercase tracking-[1px] mb-1"><?php _e('Price', 'nera-competitions'); ?></label>
              <span class="text-[15px] font-medium text-ink"><?php echo wc_price($price); ?></span>
            </div>
            <div class="prize-stat flex flex-col">
              <label class="text-[10px] text-ink-20 uppercase tracking-[1px] mb-1"><?php _e('Sold', 'nera-competitions'); ?></label>
              <span class="text-[15px] font-medium text-ink"><?php echo esc_html($progress); ?>%</span>
            </div>
          </div>

          <div class="prize-bar h-1 w-full rounded-sm overflow-hidden bg-ink-8">
            <div class="prize-bar-fill h-full rounded-sm transition-all duration-500"
              style="width: <?php echo esc_attr($progress); ?>%; background: linear-gradient(90deg, var(--color-forest), var(--color-ink));">
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Footer: Countdown & CTA -->
      <div class="flex items-center justify-between pt-5 mt-4 border-t border-ink-6">

        <!-- Countdown Timer -->
        <?php if ($end_date_gmt): ?>
          <div class="flex items-center gap-1.5 text-ink-56">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span class="text-xs font-bold uppercase tabular-nums">
              <?php printf('%dd : %02dh : %02dm', $days_left, $hours_left, $mins_left); ?>
            </span>
          </div>
        <?php else: ?>
          <div></div>
        <?php endif; ?>

        <!-- CTA Button -->
        <a href="<?php the_permalink(); ?>"
          class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded text-xs font-extrabold uppercase tracking-wide transition-all duration-300 hover:scale-105"
          style="background: linear-gradient(135deg, var(--color-forest) 0%, var(--color-sage) 100%); color: var(--color-ink);">
          <span>ENTER NOW</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            class="transition-transform group-hover:translate-x-0.5">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </a>
      </div>

    </div>
  </div>
</article>