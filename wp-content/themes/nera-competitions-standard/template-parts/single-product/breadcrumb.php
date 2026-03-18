<?php
/**
 * Breadcrumb template part for Single Competition
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

global $product;

if (!$product) {
  return;
}
?>

<!-- Breadcrumb Navigation -->
<nav class="border-b border-border">
  <div class="max-w-7xl mx-auto px-4 lg:px-8 py-4">
    <ol class="flex items-center gap-2 text-sm">
      <li>
        <a href="<?php echo esc_url(home_url('/')); ?>"
          class="text-ink-soft hover:text-sage transition-colors">
          <?php _e('Home', 'nera-competitions'); ?>
        </a>
      </li>
      <li class="text-ink-soft">/</li>
      <li>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
          class="text-sage hover:text-ink transition-colors">
          <?php _e('Live Competitions', 'nera-competitions'); ?>
        </a>
      </li>
      <li class="text-ink-soft">/</li>
      <li class="text-ink font-medium">
        <?php echo esc_html($product->get_name()); ?>
      </li>
    </ol>
  </div>
</nav>