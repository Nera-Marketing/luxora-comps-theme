<?php
/**
 * My Account Dashboard
 *
 * Custom dashboard with modern card-based design
 * Shows welcome message, stats, quick actions, and recent orders
 *
 * @package Nera Competitions Standard
 */

defined('ABSPATH') || exit();

$user = wp_get_current_user();
$customer_orders = wc_get_orders([
  'customer' => get_current_user_id(),
  'limit' => 5,
  'orderby' => 'date',
  'order' => 'DESC',
]);

// Get wallet balance if available (use 'edit' for raw number - 'view' returns HTML)
$wallet_balance = 0;
if (function_exists('woo_wallet') && is_object(woo_wallet()) && is_object(woo_wallet()->wallet)) {
  $wallet_balance = (float) woo_wallet()->wallet->get_wallet_balance(get_current_user_id(), 'edit');
}

$total_orders = count(
  wc_get_orders([
    'customer' => get_current_user_id(),
    'limit' => -1,
  ]),
);
?>

<div class="nera-account-dashboard">
  
  <!-- Welcome Hero Card -->
  <div class="bg-gradient-to-br from-forest via-sage to-forest rounded-2xl p-5 sm:p-8 mb-8 relative overflow-hidden">
    <!-- Decorative background pattern -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
    </div>
    
    <div class="relative z-10">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 backdrop-blur-sm rounded-xl sm:rounded-2xl flex-shrink-0 flex items-center justify-center border border-white/30">
          <span class="material-symbols-outlined text-white text-2xl sm:text-4xl">person</span>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl sm:text-3xl font-bold text-white mb-0.5 sm:mb-1 leading-tight">
            Welcome back, <?php echo esc_html($user->display_name); ?>!
          </h1>
          <p class="text-white/75 text-sm sm:text-lg">
             <?php printf(
               esc_html__(
                 'Ready to win big? Check out our latest competitions!',
                 'nera-competitions-standard',
               ),
             ); ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Total Orders Card -->
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-forest to-sage rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-2xl">receipt_long</span>
        </div>
        <span class="text-3xl font-bold text-ink"><?php echo esc_html($total_orders); ?></span>
      </div>
      <h3 class="text-sm font-semibold text-ink-56 uppercase tracking-wide">Total Orders</h3>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" 
         class="mt-3 inline-flex items-center text-sm font-medium text-sage hover:text-ink transition-colors">
        View all
        <span class="material-symbols-outlined text-base ml-1">arrow_forward</span>
      </a>
    </div>

    <!-- Wallet Balance Card -->
    <?php if (function_exists('woo_wallet')): ?>
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-forest to-sage rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-2xl">account_balance_wallet</span>
        </div>
        <span class="text-3xl font-bold text-ink"><?php echo wc_price(
          $wallet_balance,
        ); ?></span>
      </div>
      <h3 class="text-sm font-semibold text-ink-56 uppercase tracking-wide">Wallet Balance</h3>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('woo-wallet')); ?>" 
         class="mt-3 inline-flex items-center text-sm font-medium text-sage hover:text-ink transition-colors">
        Manage wallet
        <span class="material-symbols-outlined text-base ml-1">arrow_forward</span>
      </a>
    </div>
    <?php endif; ?>

    <!-- Account Status Card -->
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-forest to-sage rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-2xl">verified_user</span>
        </div>
        <span class="text-sm font-semibold text-emerald-400 bg-[rgba(34,197,94,0.2)] border border-emerald-500/30 px-3 py-1 rounded-full">Active</span>
      </div>
      <h3 class="text-sm font-semibold text-ink-56 uppercase tracking-wide">Account Status</h3>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" 
         class="mt-3 inline-flex items-center text-sm font-medium text-sage hover:text-ink transition-colors">
        Edit details
        <span class="material-symbols-outlined text-base ml-1">arrow_forward</span>
      </a>
    </div>

  </div>

  <!-- Quick Actions Card -->
  <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 mb-8">
    <h2 class="text-xl font-bold text-ink mb-4 flex items-center gap-2">
      <span class="material-symbols-outlined text-sage">bolt</span>
      Quick Actions
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
         class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-sm hover:shadow-md">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span>Competitions</span>
      </a>

      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" 
         class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-off-white border-2 border-ink-20 text-ink-80 font-semibold rounded-xl hover:border-ink-40 hover:text-ink transition-all">
        <span class="material-symbols-outlined">receipt_long</span>
        <span>My Orders</span>
      </a>

      <?php if (function_exists('woo_wallet')): ?>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('woo-wallet')); ?>" 
         class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-off-white border-2 border-ink-20 text-ink-80 font-semibold rounded-xl hover:border-ink-40 hover:text-ink transition-all">
        <span class="material-symbols-outlined">account_balance_wallet</span>
        <span>My Wallet</span>
      </a>
      <?php endif; ?>

      <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" 
         class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-off-white border-2 border-ink-20 text-ink-80 font-semibold rounded-xl hover:border-ink-40 hover:text-ink transition-all">
        <span class="material-symbols-outlined">settings</span>
        <span>Settings</span>
      </a>

    </div>
  </div>

  <!-- Recent Orders Card -->
  <?php if (!empty($customer_orders)): ?>
  <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6">
    <div class="flex items-center justify-between mb-6 gap-3">
      <h2 class="text-xl font-bold text-ink flex items-center gap-2 shrink-0">
        <span class="material-symbols-outlined text-sage">schedule</span>
        Recent Orders
      </h2>
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" 
         class="text-sm font-medium text-sage hover:text-ink transition-colors whitespace-nowrap">
        View all
      </a>
    </div>

    <div class="space-y-4">
      <?php foreach ($customer_orders as $order):

        $order_id = $order->get_id();
        $order_status = $order->get_status();
        $order_date = $order->get_date_created();
        $order_total = $order->get_total();
        $item_count = $order->get_item_count();

        // Status badge colors (Earthy dark-theme variants)
        $status_colors = [
          'completed' => 'bg-[rgba(34,197,94,0.2)] text-emerald-400 border-emerald-500/30',
          'processing' => 'bg-[rgba(59,130,246,0.2)] text-blue-400 border-blue-500/30',
          'pending' => 'bg-[rgba(234,179,8,0.2)] text-amber-400 border-amber-500/30',
          'on-hold' => 'bg-[rgba(249,115,22,0.2)] text-orange-400 border-orange-500/30',
          'cancelled' => 'bg-[rgba(239,68,68,0.2)] text-red-400 border-red-500/30',
          'refunded' => 'bg-[rgba(156,163,175,0.2)] text-gray-400 border-gray-500/30',
          'failed' => 'bg-[rgba(239,68,68,0.2)] text-red-400 border-red-500/30',
        ];
        $status_class = isset($status_colors[$order_status])
          ? $status_colors[$order_status]
          : 'bg-[rgba(156,163,175,0.2)] text-gray-400 border-gray-500/30';
        ?>
      <a href="<?php echo esc_url($order->get_view_order_url()); ?>"
         class="flex items-center gap-3 p-4 bg-off-white rounded-xl hover:bg-mint-soft transition-colors group border border-ink-8">
        <div class="w-10 h-10 bg-sage-20 rounded-lg flex items-center justify-center flex-shrink-0">
          <span class="material-symbols-outlined text-sage text-xl">receipt</span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap mb-0.5">
            <h3 class="font-semibold text-ink whitespace-nowrap">
              Order #<?php echo esc_html($order_id); ?>
            </h3>
            <span class="px-2 py-0.5 text-xs font-medium rounded-full border <?php echo esc_attr(
              $status_class,
            ); ?>">
              <?php echo esc_html(wc_get_order_status_name($order_status)); ?>
            </span>
          </div>
          <p class="text-sm text-ink-56 truncate">
            <?php echo esc_html($item_count); ?> item<?php echo $item_count > 1 ? 's' : ''; ?> •
            <?php echo esc_html($order_date->date_i18n(get_option('date_format'))); ?>
          </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
          <p class="text-base font-bold text-ink"><?php echo wp_kses_post(
            $order->get_formatted_order_total(),
          ); ?></p>
          <span class="material-symbols-outlined text-ink-40 text-lg group-hover:text-sage transition-colors">chevron_right</span>
        </div>
      </a>
      <?php
      endforeach; ?>
    </div>
  </div>
  <?php else: ?>
  <div class="bg-mint-wash rounded-2xl border border-ink-20 p-8 text-center">
    <div class="w-20 h-20 bg-off-white rounded-2xl flex items-center justify-center mx-auto mb-4 border border-ink-10">
      <span class="material-symbols-outlined text-ink-40 text-4xl">shopping_cart</span>
    </div>
    <h3 class="text-xl font-bold text-ink mb-2">No orders yet</h3>
    <p class="text-ink-56 mb-6">Start browsing our exciting competitions and place your first order!</p>
    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-sm hover:shadow-md no-underline">
      <span class="material-symbols-outlined">shopping_bag</span>
      <span>Browse Competitions</span>
    </a>
  </div>
  <?php endif; ?>

</div>

<?php
/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

