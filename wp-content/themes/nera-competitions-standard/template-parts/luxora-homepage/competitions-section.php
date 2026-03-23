<?php
/**
 * Luxora Homepage — Competitions Section
 *
 * 6-card WooCommerce lottery grid using WP_Query + nera_active_lottery_meta_query().
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$label = get_field('luxora_comp_label') ?: __('Live Now', 'nera-competitions');
$title = get_field('luxora_comp_title') ?: __('Current', 'nera-competitions');
$title_em = get_field('luxora_comp_title_em') ?: __('Competitions', 'nera-competitions');
$see_all_text = get_field('luxora_comp_see_all_text') ?: __('View all competitions', 'nera-competitions');
$see_all_url = get_field('luxora_comp_see_all_url') ?: (function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/'));
$count = (int) (get_field('luxora_comp_count') ?: 6);

$competitions_args = [
  'post_type' => 'product',
  'posts_per_page' => max(1, min(12, $count)),
  'post_status' => 'publish',
  'tax_query' => [
    [
      'taxonomy' => 'product_type',
      'field' => 'slug',
      'terms' => 'lottery',
    ],
  ],
  'meta_key' => '_lty_end_date_gmt',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => function_exists('nera_active_lottery_meta_query') ? nera_active_lottery_meta_query() : [],
];

$competitions = new WP_Query($competitions_args);

if (!$competitions->have_posts()) {
  $competitions_args = [
    'post_type' => 'product',
    'posts_per_page' => max(1, min(12, $count)),
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
  ];
  $competitions = new WP_Query($competitions_args);
}

$bg_gradients = [
  'bg-[linear-gradient(145deg,#c4debb,#b0cca6,#a4c09a)]',
  'bg-[linear-gradient(145deg,#cee8c6,#bcd8b4,#aecaa6)]',
  'bg-[linear-gradient(145deg,#d6ecce,#c4dcc0,#b8d2b2)]',
  'bg-[linear-gradient(145deg,#bcd6b4,#accaa4,#9ebc96)]',
  'bg-[linear-gradient(145deg,#c0dab8,#aecaaa,#a0bc9c)]',
];
$bg_gradients_inline = [
  'linear-gradient(145deg,#c4debb,#b0cca6,#a4c09a)',
  'linear-gradient(145deg,#cee8c6,#bcd8b4,#aecaa6)',
  'linear-gradient(145deg,#d6ecce,#c4dcc0,#b8d2b2)',
  'linear-gradient(145deg,#bcd6b4,#accaa4,#9ebc96)',
  'linear-gradient(145deg,#c0dab8,#aecaaa,#a0bc9c)',
];
?>

<section class="py-12 px-4 sm:py-16 sm:px-6 md:py-20 md:px-10 lg:py-24 lg:px-[60px] comp-section">
  <div class="flex flex-col gap-5 items-stretch mb-8 md:flex-row md:items-end md:justify-between md:mb-12 comp-header">
    <div>
      <div class="sec-label text-[0.58rem] tracking-[0.32em] uppercase text-sage font-normal mb-3 flex items-center gap-3"><?php echo esc_html($label); ?></div>
      <h2 class="font-heading text-[clamp(2rem,3.5vw,2.9rem)] font-normal text-ink leading-[1.15] tracking-[-0.01em] sec-title"><?php echo esc_html($title); ?> <em class="italic text-sage"><?php echo esc_html($title_em); ?></em></h2>
    </div>
    <a href="<?php echo esc_url($see_all_url); ?>" class="text-[0.63rem] tracking-[0.16em] uppercase text-ink-soft no-underline font-normal border-b border-[rgba(61,74,58,0.14)] pb-0.5 transition-colors duration-[0.25s] hover:text-forest hover:border-forest see-all self-start shrink-0 md:self-auto"><?php echo esc_html($see_all_text); ?> →</a>
  </div>

  <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-0.5 lg:grid-cols-3 comp-grid">
    <?php if ($competitions->have_posts()): ?>
      <?php
      $idx = 0;
      while ($competitions->have_posts()):
        $competitions->the_post();
        $product_id = get_the_ID();
        $product = function_exists('wc_get_product') ? wc_get_product($product_id) : null;
        if (!$product) continue;

        $image_id = $product->get_image_id();
        $price = $product->get_price();
        $max_tickets = get_post_meta($product_id, '_lty_maximum_tickets', true);
        $sold_tickets = (method_exists($product, 'get_purchased_ticket_count')) ? $product->get_purchased_ticket_count() : 0;
        $remaining = $max_tickets ? max(0, (int) $max_tickets - (int) $sold_tickets) : 0;
        $progress = $max_tickets ? min(100, round(($sold_tickets / $max_tickets) * 100)) : 0;
        $retail = get_post_meta($product_id, '_lty_cash_alternative', true);
        $end_date_gmt = get_post_meta($product_id, '_lty_end_date_gmt', true);
        $is_live = $max_tickets && $remaining > 0 && ($end_date_gmt ? strtotime($end_date_gmt) > time() : true);
        $terms = get_the_terms($product_id, 'product_cat');
        $category = $terms && !is_wp_error($terms) ? $terms[0]->name : __('Lifestyle', 'nera-competitions');
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
        $bg_grad = $bg_gradients[$idx % count($bg_gradients)];
        $bg_grad_inline = $bg_gradients_inline[$idx % count($bg_gradients_inline)];
        ?>
        <a href="<?php the_permalink(); ?>" class="group bg-white no-underline text-inherit block overflow-hidden transition-transform duration-[0.4s] ease relative border border-[rgba(61,74,58,0.14)] rounded-none comp-card">
          <div class="h-[200px] sm:h-[220px] lg:h-[230px] overflow-hidden relative cc-img">
            <div class="w-full h-full bg-contain bg-center bg-no-repeat transition-transform duration-[0.6s] ease flex items-center justify-center group-hover:scale-[1.05] cc-img-inner <?php echo esc_attr($bg_grad); ?>" <?php
              if ($image_id) {
                $img_url = wp_get_attachment_image_url($image_id, 'large');
                echo 'style="background-image:url(\'' . esc_url($img_url) . '\');"';
              }
            ?>>
              <?php if ($cat_image_url): ?>
                <img src="<?php echo esc_url($cat_image_url); ?>" alt="" class="max-w-full max-h-full object-contain" />
              <?php elseif (!$image_id && !$cat_image_url): ?>
                <svg width="140" height="110" viewBox="0 0 140 110" fill="none" aria-hidden="true">
                  <rect x="15" y="8" width="110" height="70" rx="7" fill="rgba(30,42,30,0.35)" stroke="rgba(61,74,58,0.5)" stroke-width="1.2"/>
                  <rect x="22" y="15" width="96" height="56" rx="4" fill="rgba(20,30,20,0.5)"/>
                  <path d="M5 80 L135 80 L138 90 L2 90 Z" fill="rgba(30,42,30,0.3)" stroke="rgba(61,74,58,0.35)" stroke-width="0.8"/>
                  <rect x="58" y="76" width="24" height="4" rx="2" fill="rgba(20,30,20,0.6)"/>
                </svg>
              <?php endif; ?>
            </div>
            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 text-[0.52rem] tracking-[0.2em] uppercase py-1 px-[11px] font-normal bg-[rgba(248,251,246,0.92)] text-forest rounded-none cc-pill"><?php echo esc_html($category); ?></div>
            <?php if ($is_live): ?>
              <div class="absolute top-3 right-3 sm:top-4 sm:right-4 text-[0.52rem] tracking-[0.16em] uppercase py-1 px-[11px] font-normal bg-forest text-mint flex items-center gap-1 rounded-none cc-live"><span class="dot"></span> <?php esc_html_e('Live', 'nera-competitions'); ?></div>
            <?php endif; ?>
          </div>
          <div class="p-4 pt-4 pb-[22px] px-4 border-t border-[rgba(61,74,58,0.14)] sm:p-5 sm:pt-5 sm:pb-[26px] sm:px-[22px] cc-body">
            <div class="text-[0.56rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 cc-brand"><?php echo esc_html($category); ?></div>
            <div class="font-heading text-[1.05rem] font-normal text-ink leading-[1.3] mb-3.5 cc-name"><?php the_title(); ?></div>
            <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between mb-[11px] cc-meta">
              <div class="font-heading text-base font-normal text-forest cc-price"><?php echo $price ? wp_kses_post(wc_price($price)) : '—'; ?> <span class="font-['Jost',sans-serif] text-[0.58rem] text-ink-soft font-light ml-0.5">/ <?php esc_html_e('ticket', 'nera-competitions'); ?></span></div>
              <?php if ($retail): ?>
                <div class="text-[0.63rem] text-ink-soft cc-worth"><?php echo esc_html__('Worth', 'nera-competitions'); ?> <strong class="text-ink-mid font-medium"><?php echo wp_kses_post(wc_price($retail)); ?></strong></div>
              <?php endif; ?>
            </div>
            <div class="h-0.5 bg-mint mb-1.5 overflow-hidden cc-bar"><div class="h-full bg-forest cc-bar-fill" style="width:<?php echo esc_attr($progress); ?>%"></div></div>
            <div class="flex justify-between text-[0.56rem] text-ink-soft cc-bar-labels"><span><?php echo esc_html(number_format_i18n($sold_tickets)); ?> <?php esc_html_e('sold', 'nera-competitions'); ?></span><span><?php echo esc_html(number_format_i18n($remaining)); ?> <?php esc_html_e('left', 'nera-competitions'); ?></span></div>
          </div>
        </a>
        <?php
        $idx++;
      endwhile;
      wp_reset_postdata();
      ?>
    <?php else: ?>
      <?php for ($i = 0; $i < min(6, $count); $i++): ?>
        <div class="bg-white block overflow-hidden relative border border-[rgba(61,74,58,0.14)] rounded-none" style="pointer-events:none">
          <div class="h-[200px] sm:h-[220px] lg:h-[230px] overflow-hidden relative cc-img">
            <div class="w-full h-full flex items-center justify-center cc-img-inner <?php echo esc_attr($bg_gradients[$i]); ?>">
              <svg width="140" height="110" viewBox="0 0 140 110" fill="none" aria-hidden="true">
                <rect x="15" y="8" width="110" height="70" rx="7" fill="rgba(30,42,30,0.35)" stroke="rgba(61,74,58,0.5)" stroke-width="1.2"/>
                <rect x="22" y="15" width="96" height="56" rx="4" fill="rgba(20,30,20,0.5)"/>
                <path d="M5 80 L135 80 L138 90 L2 90 Z" fill="rgba(30,42,30,0.3)" stroke="rgba(61,74,58,0.35)" stroke-width="0.8"/>
                <rect x="58" y="76" width="24" height="4" rx="2" fill="rgba(20,30,20,0.6)"/>
              </svg>
            </div>
            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 text-[0.52rem] tracking-[0.2em] uppercase py-1 px-[11px] font-normal bg-[rgba(248,251,246,0.92)] text-forest rounded-none"><?php esc_html_e('Coming Soon', 'nera-competitions'); ?></div>
          </div>
          <div class="p-4 pt-4 pb-[22px] px-4 border-t border-[rgba(61,74,58,0.14)] sm:p-5 sm:pt-5 sm:pb-[26px] sm:px-[22px]">
            <div class="text-[0.56rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5">—</div>
            <div class="font-heading text-[1.05rem] font-normal text-ink leading-[1.3] mb-3.5"><?php esc_html_e('New competitions soon', 'nera-competitions'); ?></div>
            <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between mb-[11px]">
              <div class="font-heading text-base font-normal text-forest">— <span class="font-['Jost',sans-serif] text-[0.58rem] text-ink-soft font-light ml-0.5">/ <?php esc_html_e('ticket', 'nera-competitions'); ?></span></div>
            </div>
            <div class="h-0.5 bg-mint mb-1.5 overflow-hidden"><div class="h-full bg-forest" style="width:0%"></div></div>
            <div class="flex justify-between text-[0.56rem] text-ink-soft"><span>0 <?php esc_html_e('sold', 'nera-competitions'); ?></span><span>0 <?php esc_html_e('left', 'nera-competitions'); ?></span></div>
          </div>
        </div>
      <?php endfor; ?>
    <?php endif; ?>
  </div>
</section>
