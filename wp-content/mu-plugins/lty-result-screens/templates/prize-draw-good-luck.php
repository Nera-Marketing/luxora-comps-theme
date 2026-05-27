<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$draw_heading   = get_field( 'lty_rs_draw_heading', 'option' ) ?: __( "You're in the draw!", 'lty-result-screens' );
$draw_subtext   = get_field( 'lty_rs_draw_subtext', 'option' ) ?: __( "Your entry is confirmed \xe2\x80\x94 fingers crossed!", 'lty-result-screens' );
$draw_good_luck = get_field( 'lty_rs_draw_good_luck', 'option' ) ?: __( 'Good luck!', 'lty-result-screens' );
$draw_button    = get_field( 'lty_rs_draw_button', 'option' ) ?: __( 'Got it!', 'lty-result-screens' );

$draw_date = '';

if ( function_exists( 'nera_get_effective_draw_date_gmt' ) && function_exists( 'nera_format_draw_date' ) ) {
	$effective = nera_get_effective_draw_date_gmt( (int) $product->get_id() );
	$draw_date = $effective ? nera_format_draw_date( $effective ) : '';
} else {
	$end_date = $product->get_lty_end_date();
	if ( $end_date ) {
		$timestamp = is_numeric( $end_date )
			? (int) $end_date
			: strtotime( $end_date );

		if ( $timestamp ) {
			$draw_date = date_i18n( get_option( 'date_format' ), $timestamp );
		}
	}
}
?>
<div class="lty-rs-overlay lty-rs-overlay--scrim ltyrs-flex ltyrs-items-center ltyrs-justify-center ltyrs-p-4 ltyrs-backdrop-blur-md"
     role="dialog" aria-modal="true" aria-labelledby="lty-rs-draw-heading">

	<div class="ltyrs-relative ltyrs-w-full ltyrs-max-w-[520px] ltyrs-max-h-[90vh] ltyrs-overflow-y-auto ltyrs-overflow-x-hidden ltyrs-rounded-[var(--lty-rs-card-radius)] ltyrs-bg-[var(--lty-rs-draw-bg)] ltyrs-text-[var(--color-ink,#1e2a1e)] ltyrs-text-center ltyrs-px-7 ltyrs-py-8 ltyrs-shadow-2xl ltyrs-border-t-4 ltyrs-border-[var(--lty-rs-draw-accent)] ltyrs-animate-rs-enter">

		<div class="ltyrs-block ltyrs-text-5xl ltyrs-mb-2" aria-hidden="true">
			<!-- &#127881; -->
			<img src="https://luxoradraws.co.uk/wp-content/uploads/2026/04/luxora-goodluck.jpeg" alt="thumbnails" />
		</div>

		<h2 id="lty-rs-draw-heading" class="ltyrs-text-[clamp(1.5rem,4vw,2rem)] ltyrs-font-extrabold ltyrs-tracking-tight ltyrs-text-[var(--lty-rs-draw-accent)] ltyrs-mb-2 ltyrs-leading-tight">
			<?php echo esc_html( $draw_heading ); ?>
		</h2>

		<p class="ltyrs-text-base ltyrs-text-[var(--color-ink-mid,#4a5a48)] ltyrs-leading-relaxed ltyrs-mb-3">
			<?php echo esc_html( $draw_subtext ); ?>
		</p>

		<?php if ( $draw_date ) : ?>
			<p class="ltyrs-inline-flex ltyrs-items-center ltyrs-gap-1.5 ltyrs-bg-[var(--color-ink-8,rgba(30,42,30,0.08))] ltyrs-border ltyrs-border-[var(--color-border,rgba(61,74,58,0.14))] ltyrs-rounded-full ltyrs-px-3.5 ltyrs-py-1.5 ltyrs-text-sm ltyrs-font-semibold ltyrs-text-[var(--lty-rs-draw-accent)] ltyrs-mb-2">
				<span aria-hidden="true">&#128197;</span>
				<?php
				printf(
					/* translators: %s: formatted draw date */
					esc_html__( 'Draw: %s', 'lty-result-screens' ),
					esc_html( $draw_date )
				);
				?>
			</p>
		<?php endif; ?>

		<p class="ltyrs-text-lg ltyrs-font-bold ltyrs-text-[var(--lty-rs-draw-accent)] ltyrs-mb-5">
			<?php echo esc_html( $draw_good_luck ); ?>
			<!-- <span aria-hidden="true">&#129310;</span> -->
		</p>

		<button class="lty-rs-btn lty-rs-btn-draw" data-lty-rs-dismiss>
			<?php echo esc_html( $draw_button ); ?>
		</button>

	</div>
</div>
