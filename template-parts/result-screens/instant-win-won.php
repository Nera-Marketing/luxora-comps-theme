<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$confetti_colors = array( '#6b8c6b', '#3d4a3a', '#c8e6c0', '#dff0d8', '#f77f00', '#3d523d', '#8faa8f', '#5c6b58' );

$win_heading    = get_field( 'lty_rs_win_heading', 'option' ) ?: __( "You've won!", 'lty-result-screens' );
$win_email_note = get_field( 'lty_rs_win_email_note', 'option' ) ?: __( 'A confirmation email is on its way to you.', 'lty-result-screens' );
$win_button     = get_field( 'lty_rs_win_button', 'option' ) ?: __( 'Claim my prize!', 'lty-result-screens' );
?>
<div class="lty-rs-overlay lty-rs-overlay--scrim ltyrs-flex ltyrs-items-center ltyrs-justify-center ltyrs-p-4 ltyrs-backdrop-blur-md"
     role="dialog" aria-modal="true" aria-labelledby="lty-rs-win-heading">

	<div class="ltyrs-relative ltyrs-w-full ltyrs-max-w-[520px] ltyrs-max-h-[90vh] ltyrs-overflow-y-auto ltyrs-overflow-x-hidden ltyrs-rounded-[var(--lty-rs-card-radius)] ltyrs-bg-[var(--color-off-white,#f8fbf6)] ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-text-center ltyrs-px-7 ltyrs-py-8 ltyrs-shadow-2xl ltyrs-border-t-4 ltyrs-border-[var(--lty-rs-win-accent)] ltyrs-animate-rs-enter">

		<!-- Confetti -->
		<div class="ltyrs-absolute ltyrs-inset-0 ltyrs-pointer-events-none ltyrs-overflow-hidden ltyrs-rounded-[var(--lty-rs-card-radius)] ltyrs-z-0" aria-hidden="true">
			<?php for ( $i = 0; $i < 28; $i++ ) : ?>
				<?php
				$left     = rand( 2, 98 );
				$delay    = rand( 0, 18 ) / 10;
				$duration = rand( 12, 26 ) / 10;
				$size     = rand( 6, 11 );
				$color    = $confetti_colors[ array_rand( $confetti_colors ) ];
				?>
				<span class="lty-rs-confetti-piece" style="left:<?php echo esc_attr( $left ); ?>%;animation-delay:<?php echo esc_attr( $delay ); ?>s;animation-duration:<?php echo esc_attr( $duration ); ?>s;background:<?php echo esc_attr( $color ); ?>;width:<?php echo esc_attr( $size ); ?>px;height:<?php echo esc_attr( $size ); ?>px"></span>
			<?php endfor; ?>
		</div>

		<!-- Content (above confetti) -->
		<div class="ltyrs-relative ltyrs-z-10">

			<?php lty_rs_screen_icon( 'lty_rs_win_icon', '&#127942;', 'ltyrs-animate-rs-bounce-icon' ); ?>

			<h2 id="lty-rs-win-heading" class="lty-rs-win-heading">
				<?php echo esc_html( $win_heading ); ?>
			</h2>

			<?php foreach ( $log_ids as $log_id ) : ?>
				<?php $log = lty_get_instant_winner_log( $log_id ); ?>
				<?php if ( ! $log || ! $log->get_id() ) : continue; endif; ?>

				<div class="ltyrs-rounded-2xl ltyrs-p-4 ltyrs-mb-4 ltyrs-bg-[var(--lty-rs-win-bg)] ltyrs-border ltyrs-border-[var(--color-border,rgba(61,74,58,0.14))]">

					<?php if ( $log->get_image_url() ) : ?>
						<div class="ltyrs-flex ltyrs-w-full ltyrs-justify-center ltyrs-mb-3">
							<img src="<?php echo esc_url( $log->get_image_url() ); ?>"
								alt="<?php esc_attr_e( 'Prize image', 'lty-result-screens' ); ?>"
								class="ltyrs-max-w-[110px] ltyrs-h-auto ltyrs-rounded-xl ltyrs-block ltyrs-shadow-md" />
						</div>
					<?php endif; ?>

					<?php
					$prize_label   = '';
					$wallet_amount = null;
					if ( 'product' === $log->get_prize_type() ) {
						$prize_label = $log->get_gift_product_name( false );
					} elseif ( in_array( $log->get_prize_type(), array( 'wallet', 'woo_wallet' ), true ) ) {
						$wallet_amount = floatval( $log->get_prize_amount() );
					} elseif ( $log->get_prize_message() ) {
						$prize_label = $log->get_prize_message();
					}
					?>

					<?php if ( null !== $wallet_amount ) : ?>
						<div class="lty-rs-wallet-prize">
							<span class="lty-rs-wallet-prize__icon" aria-hidden="true">&#128176;</span>
							<span class="lty-rs-wallet-prize__amount"><?php echo wp_kses_post( wc_price( $wallet_amount ) ); ?></span>
							<span class="lty-rs-wallet-prize__label"><?php esc_html_e( 'added to your wallet', 'lty-result-screens' ); ?></span>
						</div>
					<?php elseif ( $prize_label ) : ?>
						<p class="ltyrs-text-lg ltyrs-font-bold ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-mb-1.5 ltyrs-leading-snug">
							<?php echo esc_html( $prize_label ); ?>
						</p>
					<?php endif; ?>

					<?php if ( 'coupon' === $log->get_prize_type() && $log->get_coupon_code() ) : ?>
						<p class="ltyrs-text-xs ltyrs-font-semibold ltyrs-uppercase ltyrs-tracking-widest ltyrs-text-[var(--color-ink-soft,#7a8f78)] ltyrs-mt-3 ltyrs-mb-1.5">
							<?php esc_html_e( 'Your coupon code:', 'lty-result-screens' ); ?>
						</p>
						<button class="lty-rs-copy-code ltyrs-group ltyrs-relative ltyrs-font-mono ltyrs-text-xl ltyrs-font-bold ltyrs-tracking-[0.12em] ltyrs-border-2 ltyrs-border-dashed ltyrs-border-[var(--lty-rs-win-accent)] ltyrs-rounded-lg ltyrs-px-4 ltyrs-py-2 ltyrs-inline-flex ltyrs-items-center ltyrs-gap-2 ltyrs-cursor-pointer ltyrs-transition-colors ltyrs-duration-150 hover:ltyrs-bg-[var(--lty-rs-win-bg)]"
							data-code="<?php echo esc_attr( $log->get_coupon_code() ); ?>"
							title="<?php esc_attr_e( 'Click to copy', 'lty-result-screens' ); ?>">
							<span class="lty-rs-copy-code__text"><?php echo esc_html( $log->get_coupon_code() ); ?></span>
							<span class="lty-rs-copy-code__icon ltyrs-text-base ltyrs-opacity-50 ltyrs-transition-opacity group-hover:ltyrs-opacity-100" aria-hidden="true">&#128203;</span>
							<span class="lty-rs-copy-code__confirm ltyrs-hidden ltyrs-absolute ltyrs--top-8 ltyrs-left-1/2 ltyrs--translate-x-1/2 ltyrs-text-xs ltyrs-rounded ltyrs-px-2 ltyrs-py-1 ltyrs-whitespace-nowrap">
								<?php esc_html_e( 'Copied!', 'lty-result-screens' ); ?>
							</span>
						</button>
					<?php endif; ?>

				</div>

			<?php endforeach; ?>

			<p class="ltyrs-text-sm ltyrs-text-[var(--color-ink-soft,#7a8f78)] ltyrs-mt-3 ltyrs-mb-4 ltyrs-leading-relaxed">
				<?php echo esc_html( $win_email_note ); ?>
			</p>

			<button class="lty-rs-btn lty-rs-btn-win" data-lty-rs-dismiss>
				<?php echo esc_html( $win_button ); ?>
			</button>

		</div>
	</div>
</div>
