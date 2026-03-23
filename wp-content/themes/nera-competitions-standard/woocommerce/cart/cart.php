<?php
/**
 * Cart Page
 *
 * @package Nera_Competitions
 */

defined('ABSPATH') || exit();
// Cart content only - page.php provides header/footer so footer stays outside article
$cart_empty = WC()->cart->is_empty();
$hero_tagline = $cart_empty
  ? __('Your cart is currently empty', 'nera-competitions')
  : sprintf(
    _n(
      '%d item in your cart',
      '%d items in your cart',
      WC()->cart->get_cart_contents_count(),
      'nera-competitions',
    ),
    WC()->cart->get_cart_contents_count(),
  );

$hero_args = [
  'title' => __('Shopping Cart', 'nera-competitions'),
  'tagline' => $hero_tagline,
  'eyebrow' => __('Your Cart', 'nera-competitions'),
];
?>
<?php if ($cart_empty): ?>
<div class="flex flex-1 flex-col min-h-0 w-full">
  <div class="shrink-0">
    <?php get_template_part('template-parts/cart/cart-hero', null, $hero_args); ?>
  </div>
  <div
    class="flex-1 flex flex-col items-center justify-center min-h-0 bg-forest relative overflow-hidden px-4 py-8">
    <!-- Ambient mesh blobs -->
    <div
      class="absolute top-[-20%] right-[-10%] w-[500px] h-[500px] rounded-full bg-sage/20 blur-[120px] pointer-events-none"></div>
    <div
      class="absolute bottom-[-20%] left-[-10%] w-[400px] h-[400px] rounded-full bg-mint/10 blur-[100px] pointer-events-none"></div>
    <?php do_action('woocommerce_before_cart'); ?>
    <div class="relative z-10 w-full flex justify-center">
      <?php get_template_part('template-parts/cart/cart-empty'); ?>
    </div>
    <?php do_action('woocommerce_after_cart'); ?>
  </div>
</div>
<?php else: ?>
  <?php get_template_part('template-parts/cart/cart-hero', null, $hero_args); ?>
<div class="py-12 lg:py-20 bg-off-white">
  <div class="container mx-auto px-4">

    <!-- Messages -->
    <?php do_action('woocommerce_before_cart'); ?>

      <!-- Cart Layout -->
      <form class="woocommerce-cart-form block" action="<?php echo esc_url(
        wc_get_cart_url(),
      ); ?>" method="post">

        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-start">

          <!-- Cart Items (Left Column) -->
          <div class="lg:col-span-8 space-y-4">

            <div class="bg-white rounded-2xl border border-border p-6 mb-6">
              <h2 class="text-xl font-bold text-ink mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-sage">list_alt</span>
                <?php _e('Your Selections', 'nera-competitions'); ?>
              </h2>

              <!-- Loop through cart items -->
              <div class="space-y-4 divide-y divide-border -mx-6 px-6 md:divide-y-0 md:mx-0 md:px-0">
              <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters(
                  'woocommerce_cart_item_product',
                  $cart_item['data'],
                  $cart_item,
                  $cart_item_key,
                );
                if (
                  $_product &&
                  $_product->exists() &&
                  $cart_item['quantity'] > 0 &&
                  apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
                ) {
                  get_template_part('template-parts/cart/cart-item', null, [
                    'cart_item_key' => $cart_item_key,
                    'cart_item' => $cart_item,
                    'product' => $_product,
                  ]);
                }
              } ?>
              </div>

              <!-- Update Cart Button (Visible for manual updates) -->
              <div class="flex justify-end mt-6">
                <button type="submit"
                  class="px-6 py-2.5 rounded-xl border border-border font-bold text-ink hover:bg-mint/5 hover:border-border transition-all"
                  name="update_cart" value="<?php esc_attr_e(
                    'Update cart',
                    'nera-competitions',
                  ); ?>">
                  <?php _e('Update Cart', 'nera-competitions'); ?>
                </button>
              </div>
              <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </div>

            <!-- Coupon Section (Mobile specific placement can be handled here or sidebar) -->
            <div class="lg:hidden">
              <?php get_template_part('template-parts/cart/cart-coupon'); ?>
            </div>

          </div>

          <!-- Sidebar (Right Column) -->
          <div class="lg:col-span-4 mt-8 lg:mt-0">

            <!-- Coupon Section (Desktop) -->
            <div class="hidden lg:block">
              <?php get_template_part('template-parts/cart/cart-coupon'); ?>
            </div>

            <?php do_action('woocommerce_before_cart_collaterals'); ?>

            <!-- Cart Totals -->
            <div class="cart-collaterals">
              <?php get_template_part('template-parts/cart/cart-totals'); ?>
            </div>

          </div>

        </div>
      </form>

    <?php do_action('woocommerce_after_cart'); ?>

  </div>
</div>
<?php endif; ?>

<script>
  // Disable inputs in hidden cart-item block so only visible block's values submit
  function neraCartUpdateInputStates() {
    const isDesktop = window.matchMedia('(min-width: 768px)').matches;
    document.querySelectorAll('.cart-item-mobile input').forEach((i) => (i.disabled = isDesktop));
    document.querySelectorAll('.cart-item-desktop input').forEach((i) => (i.disabled = !isDesktop));
  }
  document.addEventListener('DOMContentLoaded', () => {
    neraCartUpdateInputStates();
    window.addEventListener('resize', neraCartUpdateInputStates);
    if (typeof NeraCart === 'undefined' && <?php echo WC()->cart->is_empty()
      ? 'false'
      : 'true'; ?>) {
      console.warn('NeraCart logic not loaded. Interactive features may be limited.');
    }
  });
</script>
