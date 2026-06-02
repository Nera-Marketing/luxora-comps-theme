<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading    = get_field( 'lty_rs_no_win_heading', 'option' ) ?: __( 'Thanks for entering!', 'lty-result-screens' );
$message    = get_field( 'lty_rs_no_win_message', 'option' ) ?: __( "Not this time \xe2\x80\x94 but every entry brings you closer. There are always more competitions to enter!", 'lty-result-screens' );
$btn_text   = get_field( 'lty_rs_no_win_button', 'option' ) ?: __( 'Browse more competitions', 'lty-result-screens' );
$browse_url = get_field( 'lty_rs_browse_url', 'option' ) ?: get_permalink( wc_get_page_id( 'shop' ) );
?>
<div class="lty-rs-overlay lty-rs-overlay--scrim ltyrs-flex ltyrs-items-center ltyrs-justify-center ltyrs-p-4 ltyrs-backdrop-blur-md"
     role="dialog" aria-modal="true" aria-labelledby="lty-rs-no-win-heading">

	<div class="ltyrs-relative ltyrs-w-full ltyrs-max-w-[520px] ltyrs-max-h-[90vh] ltyrs-overflow-y-auto ltyrs-overflow-x-hidden ltyrs-rounded-[var(--lty-rs-card-radius)] ltyrs-bg-[var(--lty-rs-no-win-bg)] ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-text-center ltyrs-px-7 ltyrs-py-8 ltyrs-shadow-2xl ltyrs-border-t-4 ltyrs-border-[var(--lty-rs-no-win-accent)] ltyrs-animate-rs-enter">

		<!-- X close button -->
		<button class="lty-rs-close-x"
			data-lty-rs-dismiss
			aria-label="<?php esc_attr_e( 'Close', 'lty-result-screens' ); ?>">
			&#10005;
		</button>

		<?php lty_rs_screen_icon( 'lty_rs_no_win_icon', '&#127808;' ); ?>

		<h2 id="lty-rs-no-win-heading" class="ltyrs-text-[clamp(1.5rem,4vw,2rem)] ltyrs-font-extrabold ltyrs-tracking-tight ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-mb-2 ltyrs-leading-tight">
			<?php echo esc_html( $heading ); ?>
		</h2>

		<p class="ltyrs-text-[1.0625rem] ltyrs-text-[var(--color-ink-mid,#4a5a48)] ltyrs-leading-relaxed ltyrs-mb-5">
			<?php echo esc_html( $message ); ?>
		</p>

		<a href="<?php echo esc_url( $browse_url ); ?>" class="lty-rs-btn lty-rs-btn-no-win" data-lty-rs-dismiss>
			<?php echo esc_html( $btn_text ); ?>
		</a>

	</div>
</div>
