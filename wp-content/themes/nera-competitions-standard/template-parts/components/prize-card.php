<?php
/**
 * Prize Card Component
 *
 * Reusable prize card with image, countdown, progress bar, and CTA.
 * Used in: homepage hero (luxora-homepage/hero-section.php),
 *           competitions grid (product-listing/product-grid.php).
 *
 * @package Nera_Competitions
 * @param array $args {
 *   @type WC_Product|WP_Post|int $product       Product to display.
 *   @type string                 $badge_label   Top-left badge text ('' = no badge).
 *   @type bool                   $animate       Add fade-up animation class (hero only).
 *   @type string                 $extra_classes Additional CSS classes on the wrapper.
 *   @type string                 $x_show        Alpine.js x-show expression (filtered grids).
 * }
 */

if (!defined('ABSPATH')) {
  exit();
}

// --- Resolve product to WC_Product ---
$raw = $args['product'] ?? null;

if ($raw instanceof WC_Product) {
  $wc = $raw;
} elseif ($raw instanceof WP_Post) {
  $wc = wc_get_product($raw->ID);
} elseif (is_numeric($raw)) {
  $wc = wc_get_product((int) $raw);
} else {
  $wc = function_exists('wc_get_product') ? wc_get_product(get_the_ID()) : null;
}

$badge_label   = isset($args['badge_label']) ? (string) $args['badge_label'] : '';
$animate       = !empty($args['animate']);
$extra_classes = isset($args['extra_classes']) ? (string) $args['extra_classes'] : '';
$x_show        = isset($args['x_show']) ? (string) $args['x_show'] : '';
$card_index    = isset($args['card_index']) ? (int) $args['card_index'] : 0;
/** Advanced filter grid: AOS must not target .prize-card (inline transition delays break hover). */
$filter_grid   = $x_show !== '';

$animate_class = $animate
  ? '[animation:luxora-fadeUp_0.85s_ease_0.3s_forwards] opacity-0'
  : '';

if (!$wc) {
  // --- Placeholder card (no product) ---
  ?>
  <div class="prize-card bg-white border border-[rgba(61,74,58,0.1)] relative rounded-none
              group transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(61,74,58,0.12)]
              <?php echo esc_attr($animate_class . ' ' . $extra_classes); ?>"
       data-aos="fade-up" data-aos-duration="500"
       data-aos-delay="<?php echo esc_attr(min($card_index * 80, 400)); ?>">
    <a href="<?php echo esc_url(function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/')); ?>" class="absolute inset-0 z-0" aria-label="<?php esc_attr_e('View shop', 'nera-competitions'); ?>"></a>
    <div class="prize-card-img h-[280px] flex items-center justify-center relative overflow-hidden [background:linear-gradient(145deg,#d0e8c8,#b8d8b0,#c4e0bc)]">
      <svg width="200" height="210" viewBox="0 0 220 220" fill="none" aria-hidden="true">
        <rect x="88" y="10" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
        <rect x="93" y="15" width="34" height="44" rx="7" fill="#2e3a2c"/>
        <circle cx="110" cy="110" r="60" fill="url(#pc-wcase)" stroke="#3d4a3a" stroke-width="2"/>
        <circle cx="110" cy="110" r="47" fill="url(#pc-wdial)"/>
        <line x1="110" y1="110" x2="88" y2="86" stroke="rgba(200,230,192,0.95)" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="110" y1="110" x2="130" y2="76" stroke="rgba(200,230,192,0.9)" stroke-width="1.8" stroke-linecap="round"/>
        <circle cx="110" cy="110" r="3.5" fill="#6b8c6b"/>
        <defs>
          <radialGradient id="pc-wcase" cx="38%" cy="30%"><stop offset="0%" stop-color="#566054"/><stop offset="60%" stop-color="#3a4238"/><stop offset="100%" stop-color="#2a3028"/></radialGradient>
          <radialGradient id="pc-wdial" cx="40%" cy="35%"><stop offset="0%" stop-color="#3c4a3a"/><stop offset="100%" stop-color="#252e24"/></radialGradient>
        </defs>
      </svg>
      <?php if ($badge_label): ?>
        <div class="absolute top-[18px] left-[18px] text-[0.54rem] tracking-[0.2em] uppercase py-1.5 px-3 bg-forest text-mint font-normal z-[2] rounded-none"><?php echo esc_html($badge_label); ?></div>
      <?php endif; ?>
    </div>
    <div class="p-6 pt-6 pb-7 px-[26px] prize-card-body">
      <div class="text-[0.58rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 prize-cat"><?php esc_html_e('Lifestyle', 'nera-competitions'); ?></div>
      <div class="font-heading text-[1.4rem] font-normal text-ink leading-[1.25] mb-2 prize-name"><?php esc_html_e('Emporio Armani Chronograph Watch', 'nera-competitions'); ?></div>
      <div class="text-[0.7rem] text-ink-soft mb-6 prize-val-row"><?php esc_html_e('Retail value', 'nera-competitions'); ?> <strong class="text-forest font-medium">£395</strong> &nbsp;·&nbsp; <?php esc_html_e('Cash alternative available', 'nera-competitions'); ?></div>
      <div class="flex items-start mb-[22px] countdown" data-end-ts="<?php echo esc_attr(time() + 2 * 86400 + 14 * 3600 + 37 * 60 + 8); ?>">
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="d">02</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Days', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="h">14</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Hours', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="m">37</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Mins', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="s">08</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Secs', 'nera-competitions'); ?></span></div>
      </div>
      <div class="flex justify-between mb-2 ticket-labels">
        <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium">682</strong> <?php esc_html_e('tickets sold', 'nera-competitions'); ?></span>
        <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium">318</strong> <?php esc_html_e('remaining', 'nera-competitions'); ?></span>
      </div>
      <div class="h-0.5 bg-mint mb-[22px] overflow-hidden prog-track"><div class="h-full bg-forest transition-[width_1.2s_ease] prog-fill" style="width:68%"></div></div>
      <a href="<?php echo esc_url(function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/')); ?>" class="block w-full py-[15px] text-center bg-forest text-mint text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline border-none cursor-pointer transition-colors duration-300 rounded-none hover:bg-[#2e3a2c] hover:text-white enter-btn relative z-10"><?php esc_html_e('Enter from £1.99 per ticket', 'nera-competitions'); ?></a>
      <div class="text-center mt-2.5 text-[0.6rem] text-ink-soft tracking-[0.04em] cash-note"><?php esc_html_e('Prefer cash?', 'nera-competitions'); ?> <a href="<?php echo esc_url(function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/')); ?>" class="text-sage underline underline-offset-[2px] relative z-10"><?php esc_html_e('£316 alternative available', 'nera-competitions'); ?></a></div>
    </div>
  </div>
  <?php
  // Output countdown JS (once per page)
  if (!defined('PRIZE_CARD_COUNTDOWN_JS_LOADED')) {
    define('PRIZE_CARD_COUNTDOWN_JS_LOADED', true);
    ?>
    <script>
    (function(){
      function initPrizeCardCountdowns(){
        document.querySelectorAll('.prize-card .countdown[data-end-ts]').forEach(function(el){
          var ts = parseInt(el.getAttribute('data-end-ts'), 10) * 1000;
          function tick(){
            var diff = Math.max(0, ts - Date.now());
            var d = Math.floor(diff/86400000);
            var h = Math.floor(diff%86400000/3600000);
            var m = Math.floor(diff%3600000/60000);
            var s = Math.floor(diff%60000/1000);
            var units = { d:d, h:h, m:m, s:s };
            el.querySelectorAll('[data-unit]').forEach(function(span){
              var u = span.getAttribute('data-unit');
              if (u in units) span.textContent = String(units[u]).padStart(2,'0');
            });
          }
          tick();
          setInterval(tick, 1000);
        });
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPrizeCardCountdowns);
      } else {
        initPrizeCardCountdowns();
      }
    })();
    </script>
    <?php
  }
  return;
}

// --- Live product card ---
$product_id   = $wc->get_id();
$image_id     = $wc->get_image_id();
$price        = $wc->get_price();
$max_tickets  = get_post_meta($product_id, '_lty_maximum_tickets', true);
$sold_tickets = method_exists($wc, 'get_purchased_ticket_count') ? $wc->get_purchased_ticket_count() : 0;
$remaining    = $max_tickets ? max(0, (int) $max_tickets - (int) $sold_tickets) : 0;
$progress     = $max_tickets ? min(100, round(($sold_tickets / $max_tickets) * 100)) : 0;
$retail       = get_post_meta($product_id, '_lty_cash_alternative', true);
$end_date_gmt = get_post_meta($product_id, '_lty_end_date_gmt', true);
$countdown_ts = 0;
if ($end_date_gmt) {
  $ts = strtotime($end_date_gmt . ' UTC');
  if ($ts > time()) {
    $countdown_ts = $ts;
  }
}
$terms          = get_the_terms($product_id, 'product_cat');
$category       = $terms && !is_wp_error($terms) ? $terms[0]->name : __('Lifestyle', 'nera-competitions');
$category_slugs = ($terms && !is_wp_error($terms)) ? array_map(fn($t) => $t->slug, $terms) : [];

// Filter/sort data attributes (for Alpine.js filtered grids)
$end_ts_for_sort = $end_date_gmt ? strtotime($end_date_gmt) : 9999999999;
$data_attrs = sprintf(
  'data-price="%s" data-end-date="%s" data-posted-date="%s" data-popularity="%s" data-categories="%s"',
  esc_attr($price),
  esc_attr($end_ts_for_sort),
  esc_attr(get_the_date('U', $product_id)),
  esc_attr(get_post_meta($product_id, 'total_sales', true) ?: '0'),
  esc_attr(wp_json_encode($category_slugs))
);

// Alpine x-show + transition attrs (on wrapper when filter_grid — see below)
$alpine_attrs = '';
if ($x_show) {
  $alpine_attrs = 'x-show="' . esc_attr($x_show) . '" '
    . 'x-transition:enter="transition ease-out duration-300" '
    . 'x-transition:enter-start="opacity-0 scale-95" '
    . 'x-transition:enter-end="opacity-100 scale-100"';
}

$prize_card_surface = 'prize-card bg-white border border-[rgba(61,74,58,0.1)] relative rounded-none group ';
if ($filter_grid) {
  $prize_card_surface .= trim($animate_class . ' ' . $extra_classes) . ' h-full';
} else {
  $prize_card_surface .= 'transition-all hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(61,74,58,0.12)] '
    . trim($animate_class . ' ' . $extra_classes);
}
$prize_card_surface = trim($prize_card_surface);

// Category image fallback
$cat_image_url = '';
if (!$image_id && $terms && !is_wp_error($terms)) {
  foreach ($terms as $term) {
    $cat_thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    if ($cat_thumb_id) {
      $cat_image_url = wp_get_attachment_image_url($cat_thumb_id, 'large');
      break;
    }
  }
}
?>

<?php if ($filter_grid) : ?>
<div class="prize-card-aos-wrap h-full min-h-0 flex flex-col"
  <?php echo $data_attrs; ?>
  <?php echo $alpine_attrs; ?>
  data-aos="fade-up" data-aos-duration="500"
  data-aos-delay="<?php echo esc_attr(min($card_index * 80, 400)); ?>">
<?php endif; ?>

<div class="<?php echo esc_attr($prize_card_surface); ?>"
  <?php if (!$filter_grid) : ?>
  <?php echo $data_attrs; ?>
  <?php echo $alpine_attrs; ?>
  data-aos="fade-up" data-aos-duration="500"
  data-aos-delay="<?php echo esc_attr(min($card_index * 80, 400)); ?>"
  <?php endif; ?>>
  <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="absolute inset-0 z-0" aria-label="<?php echo esc_attr(sprintf(__('View %s', 'nera-competitions'), get_the_title($product_id))); ?>"></a>
  <div class="prize-card-img h-[280px] flex items-center justify-center relative overflow-hidden" style="<?php echo $image_id ? '' : 'background:linear-gradient(145deg,#d0e8c8,#b8d8b0,#c4e0bc);'; ?>">
    <?php if ($image_id): ?>
      <img src="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'large')); ?>" alt="<?php echo esc_attr(get_the_title($product_id)); ?>" class="absolute inset-0 w-full !h-full object-contain" />
    <?php elseif ($cat_image_url): ?>
      <img src="<?php echo esc_url($cat_image_url); ?>" alt="" class="max-w-full object-contain" />
    <?php else: ?>
      <svg width="200" height="210" viewBox="0 0 220 220" fill="none" aria-hidden="true">
        <rect x="88" y="10" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
        <rect x="93" y="15" width="34" height="44" rx="7" fill="#2e3a2c"/>
        <rect x="97" y="22" width="26" height="2" rx="1" fill="rgba(200,230,192,0.15)"/>
        <rect x="97" y="27" width="26" height="2" rx="1" fill="rgba(200,230,192,0.08)"/>
        <rect x="97" y="32" width="26" height="2" rx="1" fill="rgba(200,230,192,0.08)"/>
        <rect x="88" y="158" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
        <rect x="93" y="162" width="34" height="44" rx="7" fill="#2e3a2c"/>
        <circle cx="110" cy="110" r="60" fill="url(#pc-wcase-<?php echo esc_attr($product_id); ?>)" stroke="#3d4a3a" stroke-width="2"/>
        <circle cx="110" cy="110" r="47" fill="url(#pc-wdial-<?php echo esc_attr($product_id); ?>)"/>
        <line x1="110" y1="110" x2="88" y2="86" stroke="rgba(200,230,192,0.95)" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="110" y1="110" x2="130" y2="76" stroke="rgba(200,230,192,0.9)" stroke-width="1.8" stroke-linecap="round"/>
        <circle cx="110" cy="110" r="3.5" fill="#6b8c6b"/>
        <defs>
          <radialGradient id="pc-wcase-<?php echo esc_attr($product_id); ?>" cx="38%" cy="30%"><stop offset="0%" stop-color="#566054"/><stop offset="60%" stop-color="#3a4238"/><stop offset="100%" stop-color="#2a3028"/></radialGradient>
          <radialGradient id="pc-wdial-<?php echo esc_attr($product_id); ?>" cx="40%" cy="35%"><stop offset="0%" stop-color="#3c4a3a"/><stop offset="100%" stop-color="#252e24"/></radialGradient>
        </defs>
      </svg>
    <?php endif; ?>

    <?php if ($badge_label): ?>
      <div class="absolute top-[18px] left-[18px] text-[0.54rem] tracking-[0.2em] uppercase py-1.5 px-3 bg-forest text-mint font-normal z-[2] rounded-none"><?php echo esc_html($badge_label); ?></div>
    <?php endif; ?>
  </div>

  <div class="p-6 pt-6 pb-7 px-[26px] prize-card-body">
    <div class="text-[0.58rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 prize-cat"><?php echo esc_html($category); ?></div>
    <div class="font-heading text-[1.4rem] font-normal text-ink leading-[1.25] mb-2 prize-name"><?php echo esc_html(get_the_title($product_id)); ?></div>

    <div class="text-[0.7rem] text-ink-soft mb-6 prize-val-row"><?php
      echo esc_html__('Retail value', 'nera-competitions');
      if ($retail) {
        echo ' <strong class="text-forest font-medium">' . wp_kses_post(wc_price($retail)) . '</strong>';
      }
      echo ' &nbsp;·&nbsp; ' . esc_html__('Cash alternative available', 'nera-competitions');
    ?></div>

    <?php if ($countdown_ts > 0): ?>
      <div class="flex items-start mb-[22px] countdown" data-end-ts="<?php echo esc_attr($countdown_ts); ?>">
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="d">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Days', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="h">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Hours', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="m">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Mins', 'nera-competitions'); ?></span></div>
        <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
        <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" data-unit="s">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Secs', 'nera-competitions'); ?></span></div>
      </div>
    <?php endif; ?>

    <div class="flex justify-between mb-2 ticket-labels">
      <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium"><?php echo esc_html(number_format_i18n($sold_tickets)); ?></strong> <?php esc_html_e('tickets sold', 'nera-competitions'); ?></span>
      <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium"><?php echo esc_html(number_format_i18n($remaining)); ?></strong> <?php esc_html_e('remaining', 'nera-competitions'); ?></span>
    </div>
    <div class="h-0.5 bg-mint mb-[22px] overflow-hidden prog-track"><div class="h-full bg-forest transition-[width_1.2s_ease] prog-fill" style="width:<?php echo esc_attr($progress); ?>%"></div></div>

    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="block w-full py-[15px] text-center bg-forest text-mint text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline border-none cursor-pointer transition-colors duration-300 rounded-none hover:bg-[#2e3a2c] hover:text-white enter-btn relative z-10"><?php
      printf(
        /* translators: %s: ticket price */
        esc_html__('Enter from %s per ticket', 'nera-competitions'),
        $price ? wp_kses_post(wc_price($price)) : '—'
      );
    ?></a>

    <div class="text-center mt-2.5 text-[0.6rem] text-ink-soft tracking-[0.04em] cash-note"><?php esc_html_e('Prefer cash?', 'nera-competitions'); ?> <?php
      if ($retail) {
        echo '<a href="' . esc_url(get_permalink($product_id)) . '" class="text-sage underline underline-offset-[2px] relative z-10">' . wp_kses_post(wc_price($retail)) . ' ' . esc_html__('alternative available', 'nera-competitions') . '</a>';
      } else {
        esc_html_e('Cash alternative available', 'nera-competitions');
      }
    ?></div>
  </div>
</div>
<?php if ($filter_grid) : ?>
</div>
<?php endif; ?>

<?php
// Output countdown JS once per page
if (!defined('PRIZE_CARD_COUNTDOWN_JS_LOADED')) {
  define('PRIZE_CARD_COUNTDOWN_JS_LOADED', true);
  ?>
  <script>
  (function(){
    function initPrizeCardCountdowns(){
      document.querySelectorAll('.prize-card .countdown[data-end-ts]').forEach(function(el){
        var ts = parseInt(el.getAttribute('data-end-ts'), 10) * 1000;
        function tick(){
          var diff = Math.max(0, ts - Date.now());
          var d = Math.floor(diff/86400000);
          var h = Math.floor(diff%86400000/3600000);
          var m = Math.floor(diff%3600000/60000);
          var s = Math.floor(diff%60000/1000);
          var units = { d:d, h:h, m:m, s:s };
          el.querySelectorAll('[data-unit]').forEach(function(span){
            var u = span.getAttribute('data-unit');
            if (u in units) span.textContent = String(units[u]).padStart(2,'0');
          });
        }
        tick();
        setInterval(tick, 1000);
      });
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initPrizeCardCountdowns);
    } else {
      initPrizeCardCountdowns();
    }
  })();
  </script>
  <?php
}
