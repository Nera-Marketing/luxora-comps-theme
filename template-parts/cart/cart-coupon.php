<?php
/**
 * Cart Coupon Template Part
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
} ?>

<div class="bg-white rounded-2xl border border-border p-4 mb-6 shadow-sm"
  x-data="{ open: <?php echo WC()->cart->has_discount() ? 'true' : 'false'; ?> }">
  <button type="button"
    class="flex w-full items-center justify-between text-ink font-semibold hover:text-sage transition-colors"
    @click="open = !open">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-sage">local_offer</span>
      <span>
        <?php _e('Have a coupon code?', 'nera-competitions'); ?>
      </span>
    </div>
    <span class="material-symbols-outlined transition-transform duration-300"
      :class="{ 'rotate-180': open }">expand_more</span>
  </button>

  <div x-show="open" x-collapse class="mt-4 pt-4 space-y-4 border-t border-border">

    <form class="flex gap-2 coupon-form" action="<?php echo esc_url(
      wc_get_cart_url(),
    ); ?>" method="post">
      <div class="relative flex-grow">
        <input type="text" name="coupon_code"
          class="w-full h-12 pl-4 pr-4 rounded-xl border-2 border-border bg-off-white text-ink placeholder:text-ink-soft focus:border-sage focus:ring-2 focus:ring-sage/20 outline-none transition-all text-sm font-medium"
          placeholder="<?php esc_attr_e(
            'Enter discount code',
            'nera-competitions',
          ); ?>" id="coupon_code" value="" />
      </div>
      <button type="submit"
        class="h-12 px-6 rounded-xl bg-forest text-white font-bold text-sm hover:opacity-90 transition-colors flex-shrink-0 w-full md:w-auto"
        name="apply_coupon" value="<?php esc_attr_e('Apply', 'nera-competitions'); ?>">
        <?php _e('Apply', 'nera-competitions'); ?>
      </button>
    </form>

    <!-- Display Applied Coupons (if any) -->
    <?php if (WC()->cart->has_discount()): ?>
      <div class="mt-4 flex flex-wrap gap-2">
        <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
          <div
            class="inline-flex items-center gap-2 bg-sage/15 text-ink px-3 py-1.5 rounded-lg border border-border text-sm font-medium">
            <span>
              <?php echo esc_html($code); ?>
            </span>
            <a href="<?php echo esc_url(
              wc_get_cart_url() . '?remove_coupon=' . urlencode($code),
            ); ?>"
              class="flex items-center justify-center w-5 h-5 rounded-full bg-border hover:bg-sage/30 text-ink transition-colors"
              aria-label="<?php esc_attr_e('Remove coupon', 'nera-competitions'); ?>">
              <span class="material-symbols-outlined !text-xs !font-bold">close</span>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>