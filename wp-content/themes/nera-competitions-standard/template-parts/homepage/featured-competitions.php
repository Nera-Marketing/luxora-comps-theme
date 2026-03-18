<?php
/**
 * Featured Competitions Section Template Part
 *
 * Current Prizes grid layout (matches LiveLifePrizes Concept B Earthy Editorial)
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Get competitions - Query for lottery product type
$competitions_args = [
  'post_type' => 'product',
  'posts_per_page' => 3,
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
  'meta_query' => nera_active_lottery_meta_query(),
];

$competitions = new WP_Query($competitions_args);

// Fallback to regular products if no lottery products
if (!$competitions->have_posts()) {
  $competitions_args = [
    'post_type' => 'product',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
  ];
  $competitions = new WP_Query($competitions_args);
}

$view_all_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/');
?>

<section class="prizes" id="ending-soon" data-aos="fade-up">
  <div class="prizes-header">
    <div class="prizes-header-left">
      <span class="tag"><?php _e('Open Draws', 'nera-competitions'); ?></span>
      <h2>
        <?php echo esc_html(
          get_field('featured_title') ?: __('Current Prizes', 'nera-competitions'),
        ); ?>
      </h2>
    </div>
    <a href="<?php echo esc_url($view_all_url); ?>"><?php _e('View all prizes', 'nera-competitions'); ?> →</a>
  </div>
  <div class="prizes-grid">
    <?php if ($competitions->have_posts()): ?>
      <?php
      $index = 0;
      while ($competitions->have_posts()):
        $competitions->the_post();
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);
        if (!$product) continue;
        $image_id = $product->get_image_id();
        $price = $product->get_price();

        // Get lottery specific data
        $max_tickets = get_post_meta($product_id, '_lty_maximum_tickets', true);
        $sold_tickets = method_exists($product, 'get_purchased_ticket_count')
          ? $product->get_purchased_ticket_count()
          : 0;

        // Calculate progress
        $progress = $max_tickets ? min(100, round(($sold_tickets / $max_tickets) * 100)) : 0;
        $remaining = $max_tickets ? $max_tickets - $sold_tickets : 0;

        // Get end date
        $end_date_gmt = get_post_meta($product_id, '_lty_end_date_gmt', true);
        $is_live = $max_tickets && $remaining > 0 && ($end_date_gmt ? strtotime($end_date_gmt) > time() : true);

        // Caption: product category or truncated title
        $terms = get_the_terms($product_id, 'product_cat');
        $caption = $terms && !is_wp_error($terms)
          ? $terms[0]->name
          : wp_trim_words(get_the_title(), 4);

        // Description: short description or excerpt
        $description = $product->get_short_description() ?: get_the_excerpt();
        $description = $description ? wp_trim_words($description, 20) : '';

        $card_class = 'prize-card';
        if ($index === 0) {
          $card_class .= ' featured';
        }
        ?>
        <a href="<?php the_permalink(); ?>" class="<?php echo esc_attr($card_class); ?>">
          <div class="prize-img" <?php
          if ($image_id):
            $image_url = wp_get_attachment_image_url($image_id, 'large');
            ?>style="background-image: url('<?php echo esc_url($image_url); ?>'); background-size: cover; background-position: center;"<?php
          endif;
          ?>>
            <div class="prize-status">
              <?php if ($is_live): ?>
                <span class="status-dot"></span> <?php _e('Live Now', 'nera-competitions'); ?>
              <?php else: ?>
                <?php _e('Coming Soon', 'nera-competitions'); ?>
              <?php endif; ?>
            </div>
            <span><?php echo esc_html($caption); ?></span>
          </div>
          <div class="prize-body">
            <h3><?php the_title(); ?></h3>
            <?php if ($description): ?>
              <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            <div class="prize-stats">
              <div class="prize-stat">
                <label><?php _e('Price', 'nera-competitions'); ?></label>
                <span class="prize-stat-value"><?php echo wc_price($price); ?></span>
              </div>
              <div class="prize-stat">
                <label><?php echo $index === 0 ? __('Max entries', 'nera-competitions') : __('Max', 'nera-competitions'); ?></label>
                <span class="prize-stat-value"><?php echo $max_tickets ? number_format_i18n($max_tickets) : '—'; ?></span>
              </div>
              <?php if ($is_live && $max_tickets): ?>
                <div class="prize-stat">
                  <label><?php _e('Sold', 'nera-competitions'); ?></label>
                  <span class="prize-stat-value"><?php echo esc_html($progress); ?>%</span>
                </div>
              <?php endif; ?>
            </div>
            <?php if ($is_live && $max_tickets): ?>
              <div class="prize-bar"><div class="prize-bar-fill" style="width:<?php echo esc_attr($progress); ?>%"></div></div>
            <?php endif; ?>
          </div>
        </a>
        <?php
        $index++;
      endwhile;
      wp_reset_postdata();
      ?>
    <?php else: ?>
      <!-- Placeholder cards when no competitions -->
      <div class="prize-card featured">
        <div class="prize-img">
          <div class="prize-status"><span class="status-dot"></span> <?php _e('Live Now', 'nera-competitions'); ?></div>
          <span><?php _e('Prize', 'nera-competitions'); ?></span>
        </div>
        <div class="prize-body">
          <h3><?php _e('Coming Soon', 'nera-competitions'); ?></h3>
          <p><?php _e('New competitions will appear here soon.', 'nera-competitions'); ?></p>
          <div class="prize-stats">
            <div class="prize-stat"><label><?php _e('Price', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
            <div class="prize-stat"><label><?php _e('Max entries', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
            <div class="prize-stat"><label><?php _e('Sold', 'nera-competitions'); ?></label><span class="prize-stat-value">0%</span></div>
          </div>
          <div class="prize-bar"><div class="prize-bar-fill" style="width:0%"></div></div>
        </div>
      </div>
      <div class="prize-card">
        <div class="prize-img">
          <div class="prize-status"><?php _e('Coming Soon', 'nera-competitions'); ?></div>
          <span><?php _e('Prize', 'nera-competitions'); ?></span>
        </div>
        <div class="prize-body">
          <h3><?php _e('Coming Soon', 'nera-competitions'); ?></h3>
          <p><?php _e('Check back for new competitions.', 'nera-competitions'); ?></p>
          <div class="prize-stats">
            <div class="prize-stat"><label><?php _e('Price', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
            <div class="prize-stat"><label><?php _e('Max', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
          </div>
        </div>
      </div>
      <div class="prize-card">
        <div class="prize-img">
          <div class="prize-status"><?php _e('Coming Soon', 'nera-competitions'); ?></div>
          <span><?php _e('Prize', 'nera-competitions'); ?></span>
        </div>
        <div class="prize-body">
          <h3><?php _e('Coming Soon', 'nera-competitions'); ?></h3>
          <p><?php _e('Check back for new competitions.', 'nera-competitions'); ?></p>
          <div class="prize-stats">
            <div class="prize-stat"><label><?php _e('Price', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
            <div class="prize-stat"><label><?php _e('Max', 'nera-competitions'); ?></label><span class="prize-stat-value">—</span></div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
