<?php
/**
 * Checkout coupon form (Theme Override)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined('ABSPATH') || exit();

if (!wc_coupons_enabled()) {
  return;
}
?>

<div class="bg-earthy-surface rounded-2xl border border-earthy-bronze-20 shadow-sm p-4 lg:p-6 mb-6" x-data="{ open: false }">

  <button type="button"
    class="flex w-full items-center justify-between text-earthy-bronze font-semibold hover:text-earthy-terracotta transition-colors"
    @click="open = !open">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-earthy-terracotta">local_offer</span>
      <span><?php esc_html_e('Have a coupon code?', 'nera-competitions'); ?></span>
    </div>
    <span class="material-symbols-outlined transition-transform duration-300"
      :class="{ 'rotate-180': open }">expand_more</span>
  </button>

  <div x-show="open" 
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 -translate-y-2"
       class="mt-4 pt-4 border-t border-earthy-bronze-20 bg-earthy-bronze-3 -mx-4 lg:-mx-6 px-4 lg:px-6 pb-4 rounded-b-xl">

    <form class="checkout_coupon woocommerce-form-coupon !block !p-0 !border-0" method="post" action="<?php echo esc_url(
      wc_get_checkout_url(),
    ); ?>">

      <div class="flex flex-col gap-2">
        <div class="flex gap-2">
          <div class="flex-1 relative">
            <label for="coupon_code" class="sr-only"><?php esc_html_e(
              'Coupon:',
              'woocommerce',
            ); ?></label>
            <input type="text"
              name="coupon_code"
              id="coupon_code"
              class="w-full h-12 pl-4 pr-4 rounded-xl border-2 border-earthy-bronze-20 bg-earthy-bg text-earthy-bronze text-sm font-medium focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 outline-none transition-all placeholder:text-earthy-bronze-40"
              placeholder="<?php esc_attr_e('Enter your discount code', 'nera-competitions'); ?>"
              value="" />
          </div>
          <button type="submit"
            class="h-12 px-6 rounded-xl bg-gradient-to-r from-earthy-terracotta-dark to-earthy-terracotta text-white font-bold text-sm hover:opacity-90 active:scale-95 transition-all duration-200 shrink-0 flex items-center gap-2"
            name="apply_coupon"
            value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
            <span><?php esc_html_e('Apply', 'nera-competitions'); ?></span>
            <span class="material-symbols-outlined text-lg">check_circle</span>
          </button>
        </div>
      </div>

    </form>

    <!-- Display Applied Coupons (if any) -->
    <?php
    $applied_coupons = WC()->cart->get_applied_coupons();
    if (!empty($applied_coupons)): ?>
      <div class="mt-4 space-y-2" id="checkout-applied-coupons">
        <p class="text-xs font-semibold text-earthy-bronze-56 uppercase tracking-wide mb-2"><?php esc_html_e(
          'Applied Coupons:',
          'nera-competitions',
        ); ?></p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($applied_coupons as $code): ?>
            <div class="inline-flex items-center gap-2 bg-earthy-terracotta-15 text-earthy-bronze px-3 py-1.5 rounded-lg border border-earthy-bronze-20 text-sm font-medium">
              <span class="material-symbols-outlined text-base">local_offer</span>
              <span><?php echo esc_html($code); ?></span>
              <a href="#"
                data-coupon="<?php echo esc_attr($code); ?>"
                class="remove-coupon flex items-center justify-center w-5 h-5 rounded-full bg-earthy-bronze-20 hover:bg-earthy-terracotta-hover text-earthy-bronze transition-colors"
                aria-label="<?php esc_attr_e('Remove coupon', 'nera-competitions'); ?>"
                role="button">
                <span class="material-symbols-outlined !text-xs">close</span>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif;
    ?>

  </div>

</div>
