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
       class="lg:hidden inline-flex items-center text-sm font-medium text-ink-56 hover:text-ink transition-colors mb-4">
      <span class="material-symbols-outlined text-base mr-1">arrow_back</span>
      <?php esc_html_e('Back to Dashboard', 'nera-competitions-standard'); ?>
    </a>

    <h2 class="text-2xl sm:text-3xl font-bold text-ink flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined text-sage text-3xl sm:text-4xl">person</span>
      <?php esc_html_e('Account details', 'woocommerce'); ?>
    </h2>
    <p class="text-ink-56">
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
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 mb-6">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ink-20">
        <div class="w-10 h-10 bg-gradient-to-br from-forest to-sage rounded-lg flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-xl">badge</span>
        </div>
        <h3 class="text-xl font-bold text-ink">Personal Information</h3>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <p class="woocommerce-form-row woocommerce-form-row--first form-row ">
          <label for="account_first_name" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="account_first_name" 
                 id="account_first_name" 
                 autocomplete="given-name" 
                 value="<?php echo esc_attr($user->first_name); ?>" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--last form-row">
          <label for="account_last_name" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="account_last_name" 
                 id="account_last_name" 
                 autocomplete="family-name" 
                 value="<?php echo esc_attr($user->last_name); ?>" />
        </p>
      </div>

      <div class="mt-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="account_display_name" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="text" 
                 class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="account_display_name" 
                 id="account_display_name" 
                 value="<?php echo esc_attr($user->display_name); ?>" />
          <span class="text-sm text-ink-50 mt-1 block">
            <em><?php esc_html_e(
              'This will be how your name will be displayed in the account section and in reviews',
              'woocommerce',
            ); ?></em>
          </span>
        </p>
      </div>

      <div class="mt-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="account_email" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;
            <span class="required text-red-500">*</span>
          </label>
          <input type="email" 
                 class="woocommerce-Input woocommerce-Input--email input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="account_email" 
                 id="account_email" 
                 autocomplete="email" 
                 value="<?php echo esc_attr($user->user_email); ?>" />
        </p>
      </div>
    </div>

    <!-- Password Change Card -->
    <div class="bg-mint-wash rounded-2xl border border-ink-20 p-6 mb-6">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-ink-20">
        <div class="w-10 h-10 bg-gradient-to-br from-forest to-sage rounded-lg flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-xl">lock</span>
        </div>
        <h3 class="text-xl font-bold text-ink">Password change</h3>
      </div>

      <p class="text-sm text-ink-56 mb-6">
        <?php esc_html_e(
          'Leave blank to keep your current password',
          'nera-competitions-standard',
        ); ?>
      </p>

      <div class="space-y-6">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_current" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="password_current" 
                 id="password_current" 
                 autocomplete="off" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_1" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
                 name="password_1" 
                 id="password_1" 
                 autocomplete="off" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
          <label for="password_2" class="block text-sm font-semibold text-ink mb-2">
            <?php esc_html_e('Confirm new password', 'woocommerce'); ?>
          </label>
          <input type="password" 
                 class="woocommerce-Input woocommerce-Input--password input-text w-full px-4 py-3 bg-off-white text-ink border-2 border-ink-20 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage-20 transition-all placeholder:text-ink-40" 
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
                  class="flex items-center justify-center text-base gap-2 w-full sm:w-auto bg-forest text-mint px-4 py-4 rounded-xl font-bold shadow-lg hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed" 
                  name="save_account_details" 
                  value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>">
            <span class="material-symbols-outlined text-xl">save</span>
            <?php esc_html_e('Save changes', 'woocommerce'); ?>
          </button>
          <input type="hidden" name="action" value="save_account_details" />
      
      <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
         class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-off-white border-2 border-ink-20 text-ink-80 font-semibold rounded-xl hover:border-ink-40 hover:text-ink transition-all w-full sm:w-auto">
        <span class="material-symbols-outlined text-xl">cancel</span>
        <?php esc_html_e('Cancel', 'nera-competitions-standard'); ?>
      </a>
    </div>

    <?php do_action('woocommerce_edit_account_form_end'); ?>
  </form>

  <!-- Danger zone: permanent account deletion (separate POST from Save changes) -->
  <div
    class="mt-10 rounded-2xl border border-ink-20 border-l-4 border-l-red-600 bg-off-white p-6 sm:p-7 shadow-sm ring-1 ring-black/5"
  >
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between sm:gap-8">
      <div class="flex items-start gap-4 min-w-0 flex-1">
        <div
          class="w-10 h-10 shrink-0 rounded-lg bg-red-100 flex items-center justify-center"
          aria-hidden="true"
        >
          <span class="material-symbols-outlined text-red-600 text-xl">warning</span>
        </div>
        <div class="min-w-0">
          <h3 class="text-lg font-bold text-ink mt-0">
            <?php esc_html_e('Danger zone', 'nera-competitions-standard'); ?>
          </h3>
          <p class="text-sm text-ink-70 mt-1.5 leading-relaxed max-w-prose">
            <?php esc_html_e(
              'Permanently delete your account and associated data. This cannot be undone.',
              'nera-competitions-standard',
            ); ?>
          </p>
        </div>
      </div>

      <button
        type="button"
        id="nera-deactivate-account-open"
        class="inline-flex items-center justify-center gap-2 px-5 py-3.5 w-full sm:w-auto shrink-0 rounded-xl font-semibold bg-red-600 text-white shadow-md hover:bg-red-700 hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 active:shadow-md transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
      >
        <span class="material-symbols-outlined text-xl" aria-hidden="true">person_off</span>
        <?php esc_html_e('Deactivate account', 'nera-competitions-standard'); ?>
      </button>
    </div>

    <form
      id="nera-deactivate-account-form"
      class="hidden"
      method="post"
      action="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"
    >
      <?php wp_nonce_field('nera_deactivate_account', 'nera-deactivate-account-nonce'); ?>
      <input type="hidden" name="action" value="nera_deactivate_account" />
      <input
        type="hidden"
        name="nera_deactivate_user_id"
        value="<?php echo esc_attr((string) get_current_user_id()); ?>"
      />
    </form>

    <dialog
      id="nera-deactivate-account-dialog"
      class="fixed left-1/2 top-1/2 z-100 max-h-[min(90vh,32rem)] w-[calc(100%-2rem)] max-w-md -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-2xl border border-ink-20 bg-white p-6 sm:p-7 shadow-2xl ring-1 ring-black/5 backdrop:bg-black/30"
    >
      <h4 class="text-lg font-bold text-ink mb-2">
        <?php esc_html_e('Delete your account permanently?', 'nera-competitions-standard'); ?>
      </h4>
      <p class="text-sm text-ink-70 mb-6 leading-relaxed">
        <?php esc_html_e(
          'You will be logged out and your user account will be removed. This action cannot be reversed.',
          'nera-competitions-standard',
        ); ?>
      </p>
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
        <button
          type="button"
          id="nera-deactivate-account-cancel"
          class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl font-semibold bg-off-white border-2 border-ink-20 text-ink shadow-sm hover:border-ink-40 hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 transition-all duration-200"
        >
          <?php esc_html_e('Cancel', 'nera-competitions-standard'); ?>
        </button>
        <button
          type="submit"
          form="nera-deactivate-account-form"
          class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl font-semibold bg-red-600 text-white shadow-md hover:bg-red-700 hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 active:shadow-md transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
        >
          <?php esc_html_e('Yes, delete my account', 'nera-competitions-standard'); ?>
        </button>
      </div>
    </dialog>
  </div>

  <script>
    (function () {
      var openBtn = document.getElementById('nera-deactivate-account-open');
      var dialog = document.getElementById('nera-deactivate-account-dialog');
      var cancelBtn = document.getElementById('nera-deactivate-account-cancel');
      if (openBtn && dialog) {
        openBtn.addEventListener('click', function () {
          if (typeof dialog.showModal === 'function') {
            dialog.showModal();
          }
        });
      }
      if (cancelBtn && dialog) {
        cancelBtn.addEventListener('click', function () {
          if (typeof dialog.close === 'function') {
            dialog.close();
          }
        });
      }
    })();
  </script>

</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>
