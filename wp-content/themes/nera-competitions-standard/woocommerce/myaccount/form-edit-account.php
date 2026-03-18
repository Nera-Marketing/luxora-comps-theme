<?php
/**
 * Edit account form
 *
 * @package Nera Competitions Standard
 */

defined('ABSPATH') || exit();

do_action('woocommerce_before_edit_account_form');
?>

<div class="nera-edit-account">
  
  <!-- Page Header -->
  <div class="mb-8">
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"
       class="lg:hidden inline-flex items-center text-sm font-medium text-earthy-bronze-56 hover:text-earthy-bronze transition-colors mb-4">
      <span class="material-symbols-outlined text-base mr-1">arrow_back</span>
      <?php esc_html_e('Back to Dashboard', 'nera-competitions-standard'); ?>
    </a>

    <h2 class="text-2xl sm:text-3xl font-bold text-earthy-bronze flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined text-earthy-terracotta text-3xl sm:text-4xl">person</span>
      <?php esc_html_e('Account details', 'woocommerce'); ?>
    </h2>
    <p class="text-earthy-bronze-56">
      <?php esc_html_e(
        'Update your account information and password',
        'nera-competitions-standard',
      ); ?>
    </p>
  </div>

  <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action(
    'woocommerce_edit_account_form_tag',
  ); ?>>

    <?php do_action('woocommerce_edit_account_form_start'); ?>

    <!-- Personal Information Card -->
    <div class="bg-earthy-surface rounded-2xl border border-earthy-bronze-20 p-6 mb-6">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-earthy-bronze-20">
        <div class="w-10 h-10 bg-gradient-to-br from-earthy-terracotta-dark to-earthy-terracotta rounded-lg flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-xl">badge</span>
        </div>
        <h3 class="text-xl font-bold text-earthy-bronze">Personal Information</h3>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <p class="woocommerce-form-row woocommerce-form-row--first form-row ">
          <label for="account_first_name" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="account_first_name" 
                 id="account_first_name" 
                 autocomplete="given-name" 
                 value="<?php echo esc_attr($user->first_name); ?>" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--last form-row">
          <label for="account_last_name" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="account_last_name" 
                 id="account_last_name" 
                 autocomplete="family-name" 
                 value="<?php echo esc_attr($user->last_name); ?>" />
        </p>
      </div>

      <div class="mt-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="account_display_name" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="account_display_name" 
                 id="account_display_name" 
                 value="<?php echo esc_attr($user->display_name); ?>" />
          <span class="text-sm text-earthy-bronze-50 mt-1 block">
            <em><?php esc_html_e(
              'This will be how your name will be displayed in the account section and in reviews',
              'woocommerce',
            ); ?></em>
          </span>
        </p>
      </div>

      <div class="mt-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="account_email" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="email" 
                 class="woocommerce-Input woocommerce-Input--email input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="account_email" 
                 id="account_email" 
                 autocomplete="email" 
                 value="<?php echo esc_attr($user->user_email); ?>" />
        </p>
      </div>
    </div>

    <!-- Password Change Card -->
    <div class="bg-earthy-surface rounded-2xl border border-earthy-bronze-20 p-6 mb-6">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-earthy-bronze-20">
        <div class="w-10 h-10 bg-gradient-to-br from-earthy-terracotta-dark to-earthy-terracotta rounded-lg flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-xl">lock</span>
        </div>
        <h3 class="text-xl font-bold text-earthy-bronze">Password change</h3>
      </div>

      <p class="text-sm text-earthy-bronze-56 mb-6">
        <?php esc_html_e(
          'Leave blank to keep your current password',
          'nera-competitions-standard',
        ); ?>
      </p>

      <div class="space-y-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_current" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="password_current" 
                 id="password_current" 
                 autocomplete="off" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_1" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="password_1" 
                 id="password_1" 
                 autocomplete="off" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_2" class="block text-sm font-semibold text-earthy-bronze mb-2">
            <?php esc_html_e('Confirm new password', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-earthy-bg text-earthy-bronze border-2 border-earthy-bronze-20 rounded-xl focus:border-earthy-terracotta focus:ring-2 focus:ring-earthy-terracotta-20 transition-all placeholder:text-earthy-bronze-40" 
                 name="password_2" 
                 id="password_2" 
                 autocomplete="off" />
        </p>
      </div>
    </div>

    <?php do_action('woocommerce_edit_account_form'); ?>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3">
          <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
          <button type="submit" 
                  class="woocommerce-Button button !inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-earthy-terracotta-dark to-earthy-terracotta text-earthy-bronze font-semibold rounded-xl hover:opacity-90 transition-all shadow-sm hover:shadow-md w-full sm:w-auto" 
                  name="save_account_details" 
                  value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>">
            <span class="material-symbols-outlined text-xl">save</span>
            <?php esc_html_e('Save changes', 'woocommerce'); ?>
          </button>
          <input type="hidden" name="action" value="save_account_details" />
      
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
         class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-earthy-bg border-2 border-earthy-bronze-20 text-earthy-bronze-80 font-semibold rounded-xl hover:border-earthy-bronze-40 hover:text-earthy-bronze transition-all w-full sm:w-auto">
        <span class="material-symbols-outlined text-xl">cancel</span>
        <?php esc_html_e('Cancel', 'nera-competitions-standard'); ?>
      </a>
    </div>

    <?php do_action('woocommerce_edit_account_form_end'); ?>
  </form>

</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
