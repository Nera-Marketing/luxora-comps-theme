<?php
/**
 * Archive Prizes Page - Grid Section
 *
 * Queries WooCommerce products and displays them using the prize-card component.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit();
}

$prizes_query = new WP_Query([
  'post_type'      => 'product',
  'post_status'    => 'publish',
  'posts_per_page' => 12,
  'orderby'        => 'date',
  'order'          => 'DESC',
]);
?>

<section class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 pb-20 lg:pb-32">

  <?php if ($prizes_query->have_posts()): ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php while ($prizes_query->have_posts()):
        $prizes_query->the_post();
        $product = wc_get_product(get_the_ID());
        if (!$product) {
          continue;
        }
        get_template_part('template-parts/components/prize-card', null, [
          'product' => $product,
        ]);
      endwhile;
      wp_reset_postdata(); ?>
    </div>

  <?php else: ?>

    <div class="text-center py-24 bg-off-white/50 rounded-3xl border border-dashed border-border">
      <div class="bg-sage/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 3h14M5 3a2 2 0 00-2 2v1a4 4 0 004 4h6a4 4 0 004-4V5a2 2 0 00-2-2M5 3l1 9m13-9l-1 9m-5 4v3m0 0H9m3 0h3"></path>
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-ink mb-2"><?php esc_html_e('No prizes found', 'nera-competitions'); ?></h3>
      <p class="text-ink-soft"><?php esc_html_e('Check back soon for upcoming competitions.', 'nera-competitions'); ?></p>
    </div>

    <?php wp_reset_postdata(); ?>

  <?php endif; ?>

</section>
