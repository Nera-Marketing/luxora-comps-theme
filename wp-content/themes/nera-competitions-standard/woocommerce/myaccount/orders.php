<?php
/**
 * Orders
 *
 * Shows orders on the account page with modern card-based design.
 *
 * @package Nera Competitions Standard
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_account_orders', $has_orders);
?>

<div class="nera-account-orders">
  
  <!-- Page Header -->
  <div class="mb-8">
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"
       class="lg:hidden inline-flex items-center text-sm font-medium text-ink-56 hover:text-ink transition-colors mb-4">
      <span class="material-symbols-outlined text-base mr-1">arrow_back</span>
      <?php esc_html_e('Back to Dashboard', 'nera-competitions-standard'); ?>
    </a>

    <h2 class="text-2xl sm:text-3xl font-bold text-ink flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined text-sage text-3xl sm:text-4xl">receipt_long</span>
      <?php esc_html_e('Orders', 'woocommerce'); ?>
    </h2>
    <p class="text-ink-56">
      <?php esc_html_e('View and manage your competition orders', 'nera-competitions-standard'); ?>
    </p>
  </div>

  <?php if ($has_orders): ?>

    <div class="space-y-4">
      <?php foreach ($customer_orders->orders as $customer_order):

        $order = wc_get_order($customer_order);
        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
        $order_id = $order->get_id();
        $order_status = $order->get_status();
        $order_date = $order->get_date_created();
        $order_total = $order->get_total();

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

        // Status icon mapping
        $status_icons = [
          'completed' => 'check_circle',
          'processing' => 'hourglass_top',
          'pending' => 'schedule',
          'on-hold' => 'pause_circle',
          'cancelled' => 'cancel',
          'refunded' => 'sync',
          'failed' => 'error',
        ];
        $status_icon = isset($status_icons[$order_status]) ? $status_icons[$order_status] : 'info';
        ?>

      <!-- Order Card -->
      <div class="bg-mint-soft rounded-2xl border border-ink-20 hover:shadow-md transition-all overflow-hidden">
        
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-ink-4 to-transparent px-6 py-4 border-b border-ink-20">
          <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-off-white rounded-lg flex items-center justify-center border border-ink-20">
                <span class="material-symbols-outlined text-sage text-xl">receipt</span>
              </div>
              <div>
                <h3 class="font-bold text-ink">
                  <a href="<?php echo esc_url($order->get_view_order_url()); ?>" 
                     class="hover:text-sage transition-colors">
                    <?php printf(
                      esc_html__('Order #%1$s', 'woocommerce'),
                      esc_html($order->get_order_number()),
                    ); ?>
                  </a>
                </h3>
                <p class="text-sm text-ink-56">
                  <?php echo esc_html($order_date->date_i18n(get_option('date_format'))); ?>
                </p>
              </div>
            </div>
            
            <span class="px-3 py-1.5 text-sm font-semibold rounded-full border inline-flex items-center gap-1.5 <?php echo esc_attr(
              $status_class,
            ); ?>">
              <span class="material-symbols-outlined text-base"><?php echo esc_html(
                $status_icon,
              ); ?></span>
              <?php echo esc_html(wc_get_order_status_name($order_status)); ?>
            </span>
          </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
          
          <!-- Order Items Preview -->
          <div class="mb-4">
            <h4 class="text-sm font-semibold text-ink-56 uppercase tracking-wide mb-3">Items</h4>
            <div class="space-y-2">
              <?php foreach ($order->get_items() as $item_id => $item):

                $product = $item->get_product();
                if (!$product) {
                  continue;
                }

                $product_name = $item->get_name();
                $quantity = $item->get_quantity();
                $total = $order->get_formatted_line_subtotal($item);
                $thumbnail = $product->get_image('thumbnail');
                ?>
              <div class="flex items-center gap-3 p-3 bg-off-white rounded-xl border border-ink-8">
                <div class="w-12 h-12 flex-shrink-0 bg-mint-soft rounded-lg overflow-hidden border border-ink-20">
                  <?php echo wp_kses_post($thumbnail); ?>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-ink truncate"><?php echo esc_html(
                    $product_name,
                  ); ?></p>
                  <p class="text-sm text-ink-56">
                    Qty: <?php echo esc_html($quantity); ?> 
                    <?php if ($quantity > 1): ?>
                      • <?php echo wp_kses_post($total); ?>
                    <?php endif; ?>
                  </p>
                </div>
                <?php if ($quantity === 1): ?>
                <div class="text-right flex-shrink-0">
                  <p class="font-semibold text-ink"><?php echo wp_kses_post($total); ?></p>
                </div>
                <?php endif; ?>
              </div>
              <?php
              endforeach; ?>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-ink-20 gap-3">
            <div class="flex items-center gap-4 text-sm">
              <div>
                <span class="text-ink-56">Items:</span>
                <span class="font-semibold text-ink ml-1"><?php echo esc_html(
                  $item_count,
                ); ?></span>
              </div>
              <div>
                <span class="text-ink-56">Total:</span>
                <span class="font-bold text-ink ml-1 text-lg"><?php echo wp_kses_post(
                  $order->get_formatted_order_total(),
                ); ?></span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center flex-wrap gap-2">
              <?php
              $actions = wc_get_account_orders_actions($order);

              if (!empty($actions)) {
                foreach ($actions as $key => $action):

                  $action_classes =
                    'inline-flex items-center gap-1.5 px-4 py-2 rounded-lg font-medium transition-all text-sm';

                  if ($key === 'pay') {
                    $action_classes .=
                      ' bg-gradient-to-r from-forest to-sage text-ink hover:opacity-90 shadow-sm hover:shadow-md';
                  } elseif ($key === 'view') {
                    $action_classes .=
                      ' bg-off-white border-2 border-ink-20 text-ink-80 hover:border-ink-40 hover:text-ink';
                  } else {
                    $action_classes .= ' bg-off-white border border-ink-20 text-ink-80 hover:border-ink-30';
                  }

                  $action_icon = [
                    'pay' => 'payment',
                    'view' => 'visibility',
                    'cancel' => 'cancel',
                  ];
                  $icon = isset($action_icon[$key]) ? $action_icon[$key] : 'arrow_forward';
                  ?>
                  <a href="<?php echo esc_url($action['url']); ?>" 
                     class="<?php echo esc_attr($action_classes); ?>">
                    <span class="material-symbols-outlined text-base"><?php echo esc_html(
                      $icon,
                    ); ?></span>
                    <?php echo esc_html($action['name']); ?>
                  </a>
                <?php
                endforeach;
              }
              ?>
            </div>
          </div>

        </div>

      </div>

      <?php
      endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php do_action('woocommerce_before_account_orders_pagination'); ?>

    <?php if (1 < $customer_orders->max_num_pages): ?>
      <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination mt-8 flex items-center justify-center gap-3">
        <?php if (1 !== $current_page): ?>
          <a class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-off-white border-2 border-ink-20 text-ink-80 font-semibold rounded-xl hover:border-ink-40 hover:text-ink transition-all no-underline" 
             href="<?php echo esc_url(
               wc_get_endpoint_url('orders', $current_page - 1),
             ); ?>">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            <?php esc_html_e('Previous', 'woocommerce'); ?>
          </a>
        <?php endif; ?>

        <?php if (
          intval($current_page) !== intval($customer_orders->max_num_pages)
        ): ?>
          <a class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-sm hover:shadow-md no-underline" 
             href="<?php echo esc_url(
               wc_get_endpoint_url('orders', $current_page + 1),
             ); ?>">
            <?php esc_html_e('Next', 'woocommerce'); ?>
            <span class="material-symbols-outlined text-base">arrow_forward</span>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  <?php else: ?>
    
    <!-- Empty State -->
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-12 text-center">
      <div class="w-24 h-24 bg-off-white rounded-2xl flex items-center justify-center mx-auto mb-6 border border-ink-10">
        <span class="material-symbols-outlined text-ink-40 text-5xl">shopping_cart</span>
      </div>
      <h3 class="text-2xl font-bold text-ink mb-3">No orders yet</h3>
      <p class="text-ink-56 mb-8 max-w-md mx-auto">
        You haven't placed any orders yet. Browse our exciting competitions and get started!
      </p>
      <a href="<?php echo esc_url(
        apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
      ); ?>" 
         class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-sm hover:shadow-md no-underline">
        <span class="material-symbols-outlined">shopping_bag</span>
        <span>Browse Competitions</span>
      </a>
    </div>

  <?php endif; ?>

</div>

<?php do_action('woocommerce_after_account_orders', $has_orders); ?>
