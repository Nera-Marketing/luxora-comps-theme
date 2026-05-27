<?php
/**
 * Lost Password Confirmation
 *
 * Shown after the user submits the lost-password form.
 * Overrides woocommerce/myaccount/lost-password-confirmation.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
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
				<span class="material-symbols-outlined text-base">mark_email_read</span>
				<?php esc_html_e('Email Sent', 'woocommerce'); ?>
			</span>
		</div>

		<h1 class="font-heading text-4xl lg:text-5xl font-bold text-off-white mb-4">
			<?php esc_html_e('Check Your Inbox', 'woocommerce'); ?>
		</h1>

		<p class="text-lg text-mint leading-relaxed max-w-2xl mx-auto">
			<?php esc_html_e("We've sent a password reset link to your email address", 'woocommerce'); ?>
		</p>
	</div>

	<!-- Card -->
	<div class="relative flex-1 flex flex-col items-center max-w-2xl mx-auto w-full px-4 pb-8 lg:pb-10">

		<?php do_action('woocommerce_before_lost_password_confirmation_message'); ?>

		<div class="bg-off-white rounded-2xl border-t-4 border-sage/60 overflow-hidden w-full shadow-[0_25px_50px_-12px_rgba(61,74,58,0.3)]">
			<div class="p-6 lg:p-8 text-center">

				<!-- Icon -->
				<div class="flex justify-center mb-5">
					<div class="w-16 h-16 rounded-full bg-sage/10 border border-sage/30 flex items-center justify-center">
						<span class="material-symbols-outlined text-3xl text-sage">mail</span>
					</div>
				</div>

				<!-- Confirmation message -->
				<p class="text-sm text-ink-soft leading-relaxed mb-6">
					<?php echo esc_html(apply_filters(
						'woocommerce_lost_password_confirmation_message',
						esc_html__("A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.", 'woocommerce'),
					)); ?>
				</p>

				<!-- Back to login -->
				<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
					class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-forest to-sage text-white font-semibold rounded-xl hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-sm hover:shadow-lg">
					<span class="material-symbols-outlined text-xl">login</span>
					<?php esc_html_e('Back to Login', 'woocommerce'); ?>
				</a>

			</div>
		</div>

		<?php do_action('woocommerce_after_lost_password_confirmation_message'); ?>

	</div>

</div>
