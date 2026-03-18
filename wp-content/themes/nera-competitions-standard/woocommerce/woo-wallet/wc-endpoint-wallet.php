<?php
/**
 * My Wallet Page Template Override
 * Custom styled wallet dashboard matching theme design system
 *
 * @package Nera_Competitions
 */

defined('ABSPATH') || exit();

// Get current user and balance (use 'edit' for raw number - 'view' returns HTML)
$user_id = get_current_user_id();
$balance = (float) woo_wallet()->wallet->get_wallet_balance($user_id, 'edit');

// Get current action
$current_action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
?>

<div class="nera-wallet-dashboard">

  <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"
     class="lg:hidden inline-flex items-center text-sm font-medium text-earthy-bronze-56 hover:text-earthy-bronze transition-colors mb-4">
    <span class="material-symbols-outlined text-base mr-1">arrow_back</span>
    <?php esc_html_e('Back to Dashboard', 'nera-competitions'); ?>
  </a>

  <!-- Wallet Balance Card -->
  <div class="bg-gradient-to-br from-earthy-terracotta-dark via-earthy-terracotta to-earthy-terracotta-dark rounded-2xl shadow-xl p-8 mb-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
    
    <div class="relative z-10">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-3xl">account_balance_wallet</span>
          </div>
          <div>
            <h2 class="text-white text-xl font-bold">
              <?php esc_html_e('My Wallet', 'nera-competitions'); ?>
            </h2>
            <p class="text-white/80 text-sm">
              <?php esc_html_e('Your available credit balance', 'nera-competitions'); ?>
            </p>
          </div>
        </div>
      </div>
      
      <div class="text-center py-6">
        <p class="text-white/80 text-sm font-medium mb-2">
          <?php esc_html_e('Available Balance', 'nera-competitions'); ?>
        </p>
        <p class="text-white text-5xl font-bold tracking-tight">
          <?php echo wc_price($balance); ?>
        </p>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
        <?php if (apply_filters('woo_wallet_is_enable_top_up', true)): ?>
        <a href="<?php echo esc_url(
          add_query_arg('action', 'add', wc_get_account_endpoint_url('woo-wallet')),
        ); ?>" 
           class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl py-3 px-4 text-white font-semibold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
          <span class="material-symbols-outlined">add_circle</span>
          <?php esc_html_e('Top Up', 'nera-competitions'); ?>
        </a>
        <?php endif; ?>
        
        <a href="<?php echo esc_url(remove_query_arg('action')); ?>" 
           class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl py-3 px-4 text-white font-semibold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
          <span class="material-symbols-outlined">receipt_long</span>
          <?php esc_html_e('Transactions', 'nera-competitions'); ?>
        </a>
        
        <?php if (is_enable_wallet_transfer()): ?>
        <a href="<?php echo esc_url(
          add_query_arg('action', 'transfer', wc_get_account_endpoint_url('woo-wallet')),
        ); ?>" 
           class="flex items-center justify-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl py-3 px-4 text-white font-semibold transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
          <span class="material-symbols-outlined">send</span>
          <?php esc_html_e('Transfer', 'nera-competitions'); ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Content Area -->
  <div class="bg-earthy-surface rounded-2xl border border-earthy-bronze-20 p-6 md:p-8">
    <?php // Display appropriate content based on action

switch ($current_action) {
      case 'add':
        // Top-up form
        if (apply_filters('woo_wallet_is_enable_top_up', true)) {
          echo '<div class="nera-wallet-topup">';
          echo '<h3 class="text-2xl font-bold text-earthy-bronze mb-6 flex items-center gap-2">';
          echo '<span class="material-symbols-outlined text-earthy-terracotta">add_circle</span>';
          echo esc_html__('Top Up Wallet', 'nera-competitions');
          echo '</h3>';
          woo_wallet_add_content();
          echo '</div>';
        }
        break;

      case 'transfer':
        // Transfer form
        if (is_enable_wallet_transfer()) {
          echo '<div class="nera-wallet-transfer">';
          echo '<h3 class="text-2xl font-bold text-earthy-bronze mb-6 flex items-center gap-2">';
          echo '<span class="material-symbols-outlined text-earthy-terracotta">send</span>';
          echo esc_html__('Transfer Credit', 'nera-competitions');
          echo '</h3>';
          woo_wallet_transfer_content();
          echo '</div>';
        }
        break;

      default:
        // Transaction history (default view)
        echo '<div class="nera-wallet-transactions">';
        echo '<h3 class="text-2xl font-bold text-earthy-bronze mb-6 flex items-center gap-2">';
        echo '<span class="material-symbols-outlined text-earthy-terracotta">receipt_long</span>';
        echo esc_html__('Transaction History', 'nera-competitions');
        echo '</h3>';
        woo_wallet_transactions_content();
        echo '</div>';
        break;
    } ?>
  </div>

  <!-- How Wallet Works (Info Box) -->
  <div class="mt-8 bg-earthy-bg rounded-2xl border border-earthy-bronze-20 p-6">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-12 h-12 rounded-full bg-earthy-terracotta-20 flex items-center justify-center">
        <span class="material-symbols-outlined text-earthy-terracotta text-2xl">help</span>
      </div>
      <div class="flex-1">
        <h4 class="text-lg font-bold text-earthy-bronze mb-3">
          <?php esc_html_e('How Your Wallet Works', 'nera-competitions'); ?>
        </h4>
        <div class="space-y-2 text-sm text-earthy-bronze-56">
          <div class="flex items-start gap-2">
            <span class="material-symbols-outlined text-emerald-400 text-lg mt-0.5">check_circle</span>
            <p><?php esc_html_e(
              'Earn credit by winning instant win prizes on competitions',
              'nera-competitions',
            ); ?></p>
          </div>
          <div class="flex items-start gap-2">
            <span class="material-symbols-outlined text-emerald-400 text-lg mt-0.5">check_circle</span>
            <p><?php esc_html_e(
              'Use your credit to pay for competition entries at checkout',
              'nera-competitions',
            ); ?></p>
          </div>
          <div class="flex items-start gap-2">
            <span class="material-symbols-outlined text-emerald-400 text-lg mt-0.5">check_circle</span>
            <p><?php esc_html_e(
              'Your balance carries over - no expiration on wallet credit',
              'nera-competitions',
            ); ?></p>
          </div>
          <?php if (apply_filters('woo_wallet_is_enable_top_up', true)): ?>
          <div class="flex items-start gap-2">
            <span class="material-symbols-outlined text-emerald-400 text-lg mt-0.5">check_circle</span>
            <p><?php esc_html_e(
              'Top up your wallet anytime to have credit ready for quick entries',
              'nera-competitions',
            ); ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</div>

<style>
/* Custom styling for wallet forms and tables */
.nera-wallet-dashboard .woocommerce-form-row {
  margin-bottom: 1.5rem;
}

.nera-wallet-dashboard .woocommerce-form-row label {
  display: block;
  font-weight: 600;
  color: var(--color-earthy-bronze);
  margin-bottom: 0.5rem;
}

.nera-wallet-dashboard .woocommerce-Input,
.nera-wallet-dashboard input[type="text"],
.nera-wallet-dashboard input[type="email"],
.nera-wallet-dashboard input[type="number"],
.nera-wallet-dashboard select {
  width: 100%;
  padding: 0.75rem 1rem;
  background: var(--color-earthy-bg);
  color: var(--color-earthy-bronze);
  border: 2px solid var(--color-earthy-bronze-20);
  border-radius: 0.75rem;
  font-size: 0.875rem;
  transition: all 0.3s ease;
}

.nera-wallet-dashboard .woocommerce-Input:focus,
.nera-wallet-dashboard input:focus,
.nera-wallet-dashboard select:focus {
  outline: none;
  border-color: var(--color-earthy-terracotta);
  box-shadow: 0 0 0 3px var(--color-earthy-terracotta-20);
}

.nera-wallet-dashboard .woocommerce-Button,
.nera-wallet-dashboard button[type="submit"] {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 2rem;
  background: linear-gradient(135deg, var(--color-earthy-terracotta-dark) 0%, var(--color-earthy-terracotta) 100%);
  color: var(--color-earthy-bronze);
  font-weight: 600;
  border: none;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: var(--shadow-primary);
}

.nera-wallet-dashboard .woocommerce-Button:hover,
.nera-wallet-dashboard button[type="submit"]:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-primary-hover);
}

.nera-wallet-dashboard table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1.5rem;
}

.nera-wallet-dashboard table th {
  background: var(--color-earthy-bg);
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: var(--color-earthy-bronze);
  border-bottom: 2px solid var(--color-earthy-bronze-20);
}

.nera-wallet-dashboard table td {
  padding: 1rem;
  border-bottom: 1px solid var(--color-earthy-bronze-10);
  color: var(--color-earthy-bronze-56);
}

.nera-wallet-dashboard table tr:hover {
  background: var(--color-earthy-bronze-4);
}

/* Transaction type badges (Earthy dark-theme variants) */
.nera-wallet-dashboard .transaction-type-credit {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.75rem;
  background: rgba(34, 197, 94, 0.2);
  color: #34d399;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.nera-wallet-dashboard .transaction-type-debit {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.75rem;
  background: rgba(239, 68, 68, 0.2);
  color: #f87171;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .nera-wallet-dashboard table {
    font-size: 0.875rem;
  }
  
  .nera-wallet-dashboard table th,
  .nera-wallet-dashboard table td {
    padding: 0.75rem 0.5rem;
  }
}
</style>
