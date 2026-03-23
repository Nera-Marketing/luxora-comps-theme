<?php
/**
 * Cart Empty State Template Part
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
} ?>

<div class="nera-cart-empty-state w-full max-w-lg relative z-10">

  <!-- Glassmorphic Card -->
  <div class="bg-ink/60 backdrop-blur-xl border border-mint/10 rounded-3xl p-10 shadow-2xl relative overflow-hidden">

    <!-- Internal card blobs for glass depth -->
    <div class="absolute -top-20 -right-20 w-48 h-48 bg-sage/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-mint/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col items-center text-center">

      <!-- Icon with glow ring -->
      <div class="relative mb-8 group">
        <div class="absolute inset-0 rounded-full blur-2xl bg-mint/20 scale-110 animate-pulse-slow pointer-events-none"></div>
        <div
          class="relative w-28 h-28 bg-sage-dark rounded-full flex items-center justify-center ring-1 ring-mint/20 shadow-[0_0_40px_rgba(200,230,192,0.2)] animate-float transition-transform duration-500 group-hover:scale-105">
          <span class="material-symbols-outlined !text-4xl text-mint">shopping_cart</span>
        </div>
      </div>

      <!-- Eyebrow label -->
      <p class="text-[0.6rem] tracking-[0.32em] uppercase text-mint/50 font-normal mb-4 flex items-center gap-3">
        <span class="block w-6 h-px bg-mint/20"></span>
        <?php _e('Nothing here yet', 'nera-competitions'); ?>
        <span class="block w-6 h-px bg-mint/20"></span>
      </p>

      <!-- Main Heading -->
      <h2 class="font-heading text-4xl font-bold text-white mb-4 leading-tight">
        <?php _e('Your cart is', 'nera-competitions'); ?>
        <em class="text-mint not-italic"><?php _e(' empty', 'nera-competitions'); ?></em>
      </h2>

      <!-- Subheading -->
      <p class="text-mint/60 text-base text-center max-w-sm mx-auto mb-10 leading-relaxed">
        <?php _e(
          'You haven\'t added any tickets yet. Explore our active competitions and get your chance to win big.',
          'nera-competitions',
        ); ?>
      </p>

      <!-- Call to Action -->
      <a href="<?php echo esc_url(
        apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
      ); ?>"
        class="group inline-flex items-center justify-center gap-2 px-10 py-4 bg-mint text-forest text-[0.75rem] tracking-[0.2em] uppercase font-semibold font-['Jost',sans-serif] no-underline transition-all duration-300 hover:bg-white hover:shadow-[0_0_30px_rgba(200,230,192,0.4)] hover:-translate-y-0.5">
        <span class="material-symbols-outlined !text-base transition-transform group-hover:rotate-12">rocket_launch</span>
        <span><?php _e('Browse Active Competitions', 'nera-competitions'); ?></span>
      </a>

      <!-- Trust badge pills -->
      <div class="flex flex-wrap justify-center gap-3 mt-10 pt-8 border-t border-mint/10 w-full">

        <div class="flex items-center gap-2 px-4 py-2 bg-forest/60 border border-mint/10 rounded-full group hover:-translate-y-0.5 transition-transform duration-200 cursor-default">
          <span class="material-symbols-outlined !text-sm text-mint">verified_user</span>
          <span class="text-xs text-mint/70 font-medium tracking-wide"><?php _e('Guaranteed Draws', 'nera-competitions'); ?></span>
        </div>

        <div class="flex items-center gap-2 px-4 py-2 bg-forest/60 border border-mint/10 rounded-full group hover:-translate-y-0.5 transition-transform duration-200 cursor-default">
          <span class="material-symbols-outlined !text-sm text-mint">lock</span>
          <span class="text-xs text-mint/70 font-medium tracking-wide"><?php _e('256-bit SSL', 'nera-competitions'); ?></span>
        </div>

        <div class="flex items-center gap-2 px-4 py-2 bg-forest/60 border border-mint/10 rounded-full group hover:-translate-y-0.5 transition-transform duration-200 cursor-default">
          <span class="material-symbols-outlined !text-sm text-mint">card_giftcard</span>
          <span class="text-xs text-mint/70 font-medium tracking-wide"><?php _e('Instant Prizes', 'nera-competitions'); ?></span>
        </div>

      </div>
    </div>
  </div>
</div>
