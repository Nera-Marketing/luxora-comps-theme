<?php
/**
 * Lost Password Form
 *
 * Styled override matching the login form card design.
 * Overrides the WooCommerce default myaccount/form-lost-password.php template.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined('ABSPATH') || exit;
?>

<style>
body.woocommerce-account:not(.logged-in) article {
  padding: 0;
}
body.woocommerce-account:not(.logged-in) article > header {
  display: none;
}
body.woocommerce-account:not(.logged-in) .woocommerce-MyAccount-content {
  padding: 0;
}
</style>

<div class="relative w-full">

	<!-- Hero Section -->
	<div class="relative shrink-0 max-w-4xl mx-auto px-4 lg:px-8 text-center pt-8 lg:pt-10 pb-6">
		<div class="flex justify-center mb-6">
			<span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sage/20 text-mint text-sm font-semibold">
				<span class="material-symbols-outlined text-base">lock_reset</span>
				<?php esc_html_e('Password Reset', 'woocommerce'); ?>
			</span>
		</div>

		<h1 class="font-heading text-4xl lg:text-5xl font-bold text-off-white mb-4">
			<?php esc_html_e('Reset Password', 'woocommerce'); ?>
		</h1>

		<p class="text-lg text-sage leading-relaxed max-w-2xl mx-auto">
			<?php esc_html_e("Enter your username or email and we'll send you a reset link", 'woocommerce'); ?>
		</p>
	</div>

	<!-- Form Container -->
	<div class="relative flex-1 flex flex-col items-center max-w-2xl mx-auto w-full px-4 pb-8 lg:pb-10">

		<?php do_action('woocommerce_before_lost_password_form'); ?>

		<!-- Form Card -->
		<div class="bg-off-white rounded-2xl border-t-4 border-sage/60 overflow-hidden w-full shadow-[0_25px_50px_-12px_rgba(61,74,58,0.3)]">
			<div class="p-6 lg:p-8">

				<form method="post" class="woocommerce-ResetPassword lost_reset_password">

					<!-- Username / Email -->
					<div class="mb-5">
						<label for="user_login" class="block text-sm font-semibold text-ink mb-2">
							<?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;
							<span class="text-red-500" aria-hidden="true">*</span>
							<span class="sr-only"><?php esc_html_e('Required', 'woocommerce'); ?></span>
						</label>
						<input
							class="woocommerce-Input woocommerce-Input--text input-text w-full px-4 py-3 bg-white border border-ink-12 rounded-xl focus:border-sage focus:ring-2 focus:ring-sage/20 transition-all text-ink placeholder:text-ink-40"
							type="text"
							name="user_login"
							id="user_login"
							autocomplete="username"
							placeholder="<?php esc_attr_e('Enter your username or email', 'woocommerce'); ?>"
							required
							aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
					</div>

					<?php do_action('woocommerce_lostpassword_form'); ?>

					<input type="hidden" name="wc_reset_password" value="true" />
					<?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

					<!-- Submit -->
					<div class="mb-4">
						<button
							type="submit"
							class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-forest to-sage text-mint font-semibold rounded-xl hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-sm hover:shadow-lg">
							<span class="material-symbols-outlined text-xl">send</span>
							<?php esc_html_e('Send Reset Link', 'woocommerce'); ?>
						</button>
					</div>

				</form>

				<!-- Back to login -->
				<div class="text-center pt-2 border-t border-border">
					<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
						class="text-sm text-sage hover:text-ink font-semibold transition-colors inline-flex items-center gap-1">
						<span class="material-symbols-outlined text-base">arrow_back</span>
						<?php esc_html_e('Back to login', 'woocommerce'); ?>
					</a>
				</div>

			</div>
		</div>

		<?php do_action('woocommerce_after_lost_password_form'); ?>

	</div>

</div>
