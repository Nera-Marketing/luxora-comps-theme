<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pending_heading = get_field( 'lty_rs_pending_heading', 'option' ) ?: __( "Confirming your entry\xe2\x80\xa6", 'lty-result-screens' );
$pending_subtext = get_field( 'lty_rs_pending_subtext', 'option' ) ?: __( "Hang tight \xe2\x80\x94 your result will appear here in a moment.", 'lty-result-screens' );
?>
<div class="lty-rs-overlay lty-rs-overlay--scrim ltyrs-flex ltyrs-items-center ltyrs-justify-center ltyrs-p-4 ltyrs-backdrop-blur-md"
     role="dialog" aria-modal="true" aria-labelledby="lty-rs-pending-heading"
     data-lty-rs-pending>

	<div class="ltyrs-relative ltyrs-w-full ltyrs-max-w-[520px] ltyrs-max-h-[90vh] ltyrs-overflow-y-auto ltyrs-overflow-x-hidden ltyrs-rounded-[var(--lty-rs-card-radius)] ltyrs-bg-[var(--color-off-white,#f8fbf6)] ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-text-center ltyrs-px-7 ltyrs-py-8 ltyrs-shadow-2xl ltyrs-border-t-4 ltyrs-border-[var(--lty-rs-no-win-accent)] ltyrs-animate-rs-enter">

		<!-- X close button -->
		<button class="lty-rs-close-x"
			data-lty-rs-dismiss
			aria-label="<?php esc_attr_e( 'Close', 'lty-result-screens' ); ?>">
			&#10005;
		</button>

		<!-- Spinner -->
		<div class="lty-rs-pending-spinner" aria-hidden="true"></div>

		<!-- Live region so screen readers announce the result when it swaps in -->
		<div aria-live="polite" aria-atomic="true">
			<h2 id="lty-rs-pending-heading" class="ltyrs-text-[clamp(1.5rem,4vw,2rem)] ltyrs-font-extrabold ltyrs-tracking-tight ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-mb-2 ltyrs-leading-tight">
				<?php echo esc_html( $pending_heading ); ?>
			</h2>

			<p class="ltyrs-text-[1.0625rem] ltyrs-text-[var(--color-ink-mid,#4a5a48)] ltyrs-leading-relaxed ltyrs-mb-5">
				<?php echo esc_html( $pending_subtext ); ?>
			</p>
		</div>

		<button class="lty-rs-btn lty-rs-btn-no-win" data-lty-rs-dismiss>
			<?php esc_html_e( 'Close', 'lty-result-screens' ); ?>
		</button>

	</div>
</div>
