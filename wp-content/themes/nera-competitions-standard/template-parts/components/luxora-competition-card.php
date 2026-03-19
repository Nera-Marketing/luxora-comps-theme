<?php
/**
 * Luxora Competition Card Component
 *
 * Editorial card matching the homepage competition card design.
 * Reusable for the all-competitions listing with filter/sort support.
 *
 * @package Nera_Competitions
 * @param array $args {
 *   @type string $x_show     AlpineJS x-show expression for filtering
 *   @type int    $card_index Card index for cycling mint gradient backgrounds
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

global $product;

if ( ! $product && isset( $args['product'] ) ) {
	$product = $args['product'];
} elseif ( ! $product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$card_index   = isset( $args['card_index'] ) ? (int) $args['card_index'] : 0;
$product_id   = $product->get_id();
$image_id     = $product->get_image_id();
$price        = $product->get_price();
$max_tickets  = get_post_meta( $product_id, '_lty_maximum_tickets', true );
$sold_tickets = method_exists( $product, 'get_purchased_ticket_count' ) ? $product->get_purchased_ticket_count() : 0;
$remaining    = $max_tickets ? max( 0, (int) $max_tickets - (int) $sold_tickets ) : 0;
$progress     = $max_tickets ? min( 100, round( ( $sold_tickets / $max_tickets ) * 100 ) ) : 0;
$retail       = get_post_meta( $product_id, '_lty_cash_alternative', true );
$end_date_gmt = get_post_meta( $product_id, '_lty_end_date_gmt', true );
$is_live      = $max_tickets && $remaining > 0 && ( $end_date_gmt ? strtotime( $end_date_gmt ) > time() : true );

$terms          = get_the_terms( $product_id, 'product_cat' );
$category       = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : __( 'Lifestyle', 'nera-competitions' );
$category_slugs = $terms && ! is_wp_error( $terms ) ? array_map( fn( $t ) => $t->slug, $terms ) : [];

// Category image fallback
$cat_image_url = '';
if ( ! $image_id && $terms && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $term ) {
		$cat_thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		if ( $cat_thumb_id ) {
			$cat_image_url = wp_get_attachment_image_url( $cat_thumb_id, 'large' );
			break;
		}
	}
}

$bg_gradients = [
	'linear-gradient(145deg,#c4debb,#b0cca6,#a4c09a)',
	'linear-gradient(145deg,#cee8c6,#bcd8b4,#aecaa6)',
	'linear-gradient(145deg,#d6ecce,#c4dcc0,#b8d2b2)',
	'linear-gradient(145deg,#bcd6b4,#accaa4,#9ebc96)',
	'linear-gradient(145deg,#c0dab8,#aecaaa,#a0bc9c)',
];
$bg_grad = $bg_gradients[ $card_index % count( $bg_gradients ) ];

// Alpine.js x-show + data attributes for filter/sort
$x_show_attr = ! empty( $args['x_show'] ) ? 'x-show="' . esc_attr( $args['x_show'] ) . '"' : '';
$data_attrs  = sprintf(
	'data-price="%s" data-end-date="%s" data-posted-date="%s" data-popularity="%s" data-categories="%s"',
	esc_attr( $price ),
	esc_attr( $end_date_gmt ? strtotime( $end_date_gmt ) : '9999999999' ),
	esc_attr( get_the_date( 'U' ) ),
	esc_attr( get_post_meta( $product_id, 'total_sales', true ) ?: '0' ),
	esc_attr( json_encode( $category_slugs ) )
);
?>

<a href="<?php the_permalink(); ?>" <?php echo $data_attrs; ?> <?php echo $x_show_attr; ?>
	x-transition:enter="transition ease-out duration-300"
	x-transition:enter-start="opacity-0 scale-95"
	x-transition:enter-end="opacity-100 scale-100"
	class="group bg-white no-underline text-inherit block overflow-hidden transition-transform duration-[0.4s] ease relative border border-[rgba(61,74,58,0.14)] rounded-none hover:-translate-y-1 comp-card">

	<!-- Image Area -->
	<div class="h-[230px] overflow-hidden relative cc-img">
		<div class="w-full h-full transition-transform duration-[0.6s] ease flex items-center justify-center group-hover:scale-[1.03] cc-img-inner"
			style="background: <?php echo esc_attr( $bg_grad ); ?>;<?php
			if ( $image_id ) {
				$img_url = wp_get_attachment_image_url( $image_id, 'large' );
				echo 'background-image:url(\'' . esc_url( $img_url ) . '\');background-size:cover;background-position:center;';
			}
			?>">
			<?php if ( ! $image_id && $cat_image_url ) : ?>
				<img src="<?php echo esc_url( $cat_image_url ); ?>" alt="" class="max-w-full max-h-full object-contain" />
			<?php elseif ( ! $image_id && ! $cat_image_url ) : ?>
				<svg width="140" height="110" viewBox="0 0 140 110" fill="none" aria-hidden="true">
					<rect x="15" y="8" width="110" height="70" rx="7" fill="rgba(30,42,30,0.35)" stroke="rgba(61,74,58,0.5)" stroke-width="1.2"/>
					<rect x="22" y="15" width="96" height="56" rx="4" fill="rgba(20,30,20,0.5)"/>
					<path d="M5 80 L135 80 L138 90 L2 90 Z" fill="rgba(30,42,30,0.3)" stroke="rgba(61,74,58,0.35)" stroke-width="0.8"/>
					<rect x="58" y="76" width="24" height="4" rx="2" fill="rgba(20,30,20,0.6)"/>
				</svg>
			<?php endif; ?>
		</div>

		<!-- Category pill (top-left) -->
		<div class="absolute top-4 left-4 text-[0.52rem] tracking-[0.2em] uppercase py-1 px-[11px] font-normal bg-[rgba(248,251,246,0.92)] text-forest rounded-none cc-pill">
			<?php echo esc_html( $category ); ?>
		</div>

		<!-- Live badge (top-right) -->
		<?php if ( $is_live ) : ?>
			<div class="absolute top-4 right-4 text-[0.52rem] tracking-[0.16em] uppercase py-1 px-[11px] font-normal bg-forest text-mint flex items-center gap-1 rounded-none cc-live">
				<span class="lux-dot"></span>
				<?php esc_html_e( 'Live', 'nera-competitions' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<!-- Body -->
	<div class="p-5 pt-5 pb-[26px] px-[22px] border-t border-[rgba(61,74,58,0.14)] cc-body">
		<div class="text-[0.56rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 cc-brand"><?php echo esc_html( $category ); ?></div>
		<div class="font-heading text-[1.05rem] font-normal text-ink leading-[1.3] mb-3.5 cc-name"><?php the_title(); ?></div>

		<div class="flex items-center justify-between mb-[11px] cc-meta">
			<div class="font-heading text-base font-normal text-forest cc-price">
				<?php echo $price ? wp_kses_post( wc_price( $price ) ) : '—'; ?>
				<span class="text-[0.58rem] text-ink-soft font-light ml-0.5">/ <?php esc_html_e( 'ticket', 'nera-competitions' ); ?></span>
			</div>
			<?php if ( $retail ) : ?>
				<div class="text-[0.63rem] text-ink-soft cc-worth">
					<?php esc_html_e( 'Worth', 'nera-competitions' ); ?>
					<strong class="text-ink-mid font-medium"><?php echo wp_kses_post( wc_price( $retail ) ); ?></strong>
				</div>
			<?php endif; ?>
		</div>

		<div class="h-0.5 bg-mint mb-1.5 overflow-hidden cc-bar">
			<div class="h-full bg-forest cc-bar-fill" style="width:<?php echo esc_attr( $progress ); ?>%"></div>
		</div>
		<div class="flex justify-between text-[0.56rem] text-ink-soft">
			<span><?php echo esc_html( number_format_i18n( $sold_tickets ) ); ?> <?php esc_html_e( 'sold', 'nera-competitions' ); ?></span>
			<span><?php echo esc_html( number_format_i18n( $remaining ) ); ?> <?php esc_html_e( 'remaining', 'nera-competitions' ); ?></span>
		</div>
	</div>

</a>
