<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Nera_Competitions
 * @version 8.1.0
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit(); ?>

<!-- Page Header -->
<div class="w-full bg-off-white border-b border-ink-20 py-10 lg:py-16 mb-0">
  <div class="container mx-auto px-4">
    <div class="flex items-center gap-4 mb-8">
      <div class="w-12 h-12 rounded-xl bg-sage-20 flex items-center justify-center text-sage">
        <span class="material-symbols-outlined text-2xl">check_circle</span>
      </div>
      <div>
        <h1 class="text-3xl font-bold text-ink mb-1">
          <?php esc_html_e('Order received', 'woocommerce'); ?>
        </h1>
        <p class="text-ink-56 text-sm">
          <?php esc_html_e('Your order has been successfully placed.', 'nera-competitions'); ?>
        </p>
      </div>
    </div>

    <!-- Progress Indicator - Complete -->
    <?php $steps = [
      [
        'label' => __('Cart', 'nera-competitions'),
        'icon' => 'shopping_cart',
        'status' => 'complete',
      ],
      ['label' => __('Checkout', 'nera-competitions'), 'icon' => 'payment', 'status' => 'complete'],
      [
        'label' => __('Complete', 'nera-competitions'),
        'icon' => 'check_circle',
        'status' => 'active',
      ],
    ]; ?>
    <div class="flex items-center justify-center max-w-md mx-auto">
      <?php foreach ($steps as $i => $step): ?>
        <div class="flex items-center <?php echo $i < count($steps) - 1 ? 'flex-1' : ''; ?>">
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mb-1.5 transition-all duration-300
              <?php if ($step['status'] === 'complete') {
                echo 'bg-forest text-white';
              } elseif ($step['status'] === 'active') {
                echo 'bg-sage text-white shadow-primary';
              } else {
                echo 'bg-ink-15 text-ink-40';
              } ?>">
              <span class="material-symbols-outlined text-xl"><?php echo esc_html(
                $step['icon'],
              ); ?></span>
            </div>
            <span class="text-xs font-semibold whitespace-nowrap
              <?php echo $step['status'] === 'active'
                ? 'text-sage'
                : ($step['status'] === 'complete'
                  ? 'text-ink'
                  : 'text-ink-40'); ?>">
              <?php echo esc_html($step['label']); ?>
            </span>
          </div>

          <?php if ($i < count($steps) - 1): ?>
            <div class="h-px flex-1 mx-4 bg-forest"></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="woocommerce-order py-12 lg:py-20 bg-off-white">
  <div class="container mx-auto px-4">

    <?php if ($order):
      do_action('woocommerce_before_thankyou', $order->get_id()); ?>

      <?php if ($order->has_status('failed')): ?>

        <div class="max-w-2xl mx-auto bg-mint-wash rounded-2xl border border-ink-20 p-8">
          <p class="text-danger font-semibold mb-6">
            <?php esc_html_e(
              'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.',
              'woocommerce',
            ); ?>
          </p>
          <div class="flex flex-wrap gap-3">
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-primary hover:shadow-primary-hover">
              <span class="material-symbols-outlined text-xl">payment</span>
              <?php esc_html_e('Pay', 'woocommerce'); ?>
            </a>
            <?php if (is_user_logged_in()): ?>
              <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                 class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border-2 border-ink-40 text-ink font-semibold rounded-xl hover:border-sage hover:text-sage transition-all">
                <span class="material-symbols-outlined text-xl">person</span>
                <?php esc_html_e('My account', 'woocommerce'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>

      <?php else: ?>

        <?php wc_get_template('checkout/order-received.php', ['order' => $order]); ?>

        <!-- Order Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
          <div class="bg-mint-wash rounded-2xl border border-ink-20 p-4 lg:p-5">
            <p class="text-xs font-semibold text-ink-56 uppercase tracking-wide mb-1"><?php esc_html_e(
              'Order number',
              'woocommerce',
            ); ?></p>
            <p class="text-lg font-bold text-ink"><?php echo esc_html(
              $order->get_order_number(),
            ); ?></p>
          </div>
          <div class="bg-mint-wash rounded-2xl border border-ink-20 p-4 lg:p-5">
            <p class="text-xs font-semibold text-ink-56 uppercase tracking-wide mb-1"><?php esc_html_e(
              'Date',
              'woocommerce',
            ); ?></p>
            <p class="text-lg font-bold text-ink"><?php echo esc_html(
              wc_format_datetime($order->get_date_created()),
            ); ?></p>
          </div>
          <?php if (
            is_user_logged_in() &&
            $order->get_user_id() === get_current_user_id() &&
            $order->get_billing_email()
          ): ?>
            <div class="bg-mint-wash rounded-2xl border border-ink-20 p-4 lg:p-5">
              <p class="text-xs font-semibold text-ink-56 uppercase tracking-wide mb-1"><?php esc_html_e(
                'Email',
                'woocommerce',
              ); ?></p>
              <p class="text-sm font-bold text-ink break-all"><?php echo esc_html(
                $order->get_billing_email(),
              ); ?></p>
            </div>
          <?php endif; ?>
          <div class="bg-mint-wash rounded-2xl border border-ink-20 p-4 lg:p-5">
            <p class="text-xs font-semibold text-ink-56 uppercase tracking-wide mb-1"><?php esc_html_e(
              'Total',
              'woocommerce',
            ); ?></p>
            <p class="text-lg font-bold text-ink"><?php echo wp_kses_post(
              $order->get_formatted_order_total(),
            ); ?></p>
          </div>
          <?php if ($order->get_payment_method_title()): ?>
            <div class="bg-mint-wash rounded-2xl border border-ink-20 p-4 lg:p-5">
              <p class="text-xs font-semibold text-ink-56 uppercase tracking-wide mb-1"><?php esc_html_e(
                'Payment method',
                'woocommerce',
              ); ?></p>
              <p class="text-sm font-bold text-ink"><?php echo wp_kses_post(
                $order->get_payment_method_title(),
              ); ?></p>
            </div>
          <?php endif; ?>
        </div>

        <?php do_action(
          'woocommerce_thankyou_' . $order->get_payment_method(),
          $order->get_id(),
        ); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

      <?php endif; ?>

    <?php
    else:
       ?>

      <?php wc_get_template('checkout/order-received.php', ['order' => false]); ?>

    <?php
    endif; ?>

  </div>
</div>
