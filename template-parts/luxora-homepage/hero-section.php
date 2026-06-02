<?php
/**
 * Luxora Homepage — Hero Section
 *
 * Two-column hero: left (forest) with brand statement, right (mint) with featured prize card.
 * Countdown reads _lty_end_date_gmt from the ACF-selected product.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$eyebrow = get_field('luxora_hero_eyebrow') ?: 'Premium Competitions';
$tagline = get_field('luxora_hero_tagline') ?: 'Every draw, a chance worth taking.';
$body = get_field('luxora_hero_body') ?: 'Curated competitions for people who appreciate quality. Transparent draws, genuine prizes, and a cash alternative on every entry.';
$cta_text = get_field('luxora_hero_cta_text') ?: 'View Competitions';
$cta_url = get_field('luxora_hero_cta_url') ?: (function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/'));
$sec_text = get_field('luxora_hero_secondary_text') ?: 'How it works';
$sec_url = get_field('luxora_hero_secondary_url') ?: home_url('/#how-it-works');
$show_trust_stats = get_field('luxora_hero_show_trust_stats');
if ($show_trust_stats === null || $show_trust_stats === '') {
  $show_trust_stats = true;
}
$trust = get_field('luxora_hero_trust') ?: [['num' => '1,240+', 'label' => 'Verified Winners'], ['num' => '£48k', 'label' => 'Prizes Awarded'], ['num' => '4.9 ★', 'label' => 'Trustpilot']];
$featured = get_field('luxora_hero_featured_product');

$product = null;
$end_date_gmt = '';
$countdown_ts = 0;

if ($featured && is_object($featured)) {
  $product = $featured;
  $end_date_gmt = get_post_meta($product->ID, '_lty_end_date_gmt', true);
  if ($end_date_gmt) {
    $countdown_ts = strtotime($end_date_gmt . ' UTC');
    if ($countdown_ts <= time()) {
      $countdown_ts = 0;
    }
  }
}
?>

<section class="lg:min-h-screen grid grid-cols-1 lg:grid-cols-2 hero">
  <div class="hero-left bg-forest p-8 sm:p-12 md:p-16 lg:p-[80px_60px] flex flex-col justify-center relative overflow-hidden">
    <div class="text-[0.6rem] tracking-[0.32em] uppercase text-mint font-normal mb-7 flex items-center gap-[14px] opacity-70 before:content-[''] before:block before:w-[28px] before:h-px before:bg-mint/50 [animation-delay:0.1s]"><?php echo esc_html($eyebrow); ?></div>

    <div class="mb-9 hero-logo-display">
      <h1 class="font-heading !font-black tracking-[0.1em] uppercase text-white leading-[0.95] block text-[clamp(3.8rem,7vw,6.5rem)]">Luxora</h1>
      <span class="font-draws font-semibold leading-none block text-mint -mt-[0.05em] pl-3 text-[clamp(2rem,3.8vw,3.4rem)]">draws</span>
    </div>

    <p class="font-heading text-[clamp(1rem,1.8vw,1.3rem)] italic font-normal text-[rgba(200,230,192,0.75)] mb-5 leading-[1.5] w-full max-w-[360px] hero-tagline"><?php echo esc_html($tagline); ?></p>
    <p class="text-[0.85rem] leading-[1.9] text-[rgba(200,230,192,0.55)] w-full max-w-[380px] mb-8 lg:mb-12 font-light hero-body"><?php echo esc_html($body); ?></p>

    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-8 hero-actions">
      <a href="<?php echo esc_url($cta_url); ?>" class="inline-block py-3.5 px-10 bg-mint text-forest text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline transition-colors duration-300 rounded-none hover:bg-white hover:text-forest"><?php echo esc_html($cta_text); ?></a>
      <a href="<?php echo esc_url($sec_url); ?>" class="text-[0.68rem] tracking-[0.15em] uppercase text-[rgba(200,230,192,0.6)] no-underline font-normal border-b border-[rgba(200,230,192,0.25)] pb-0.5 transition-colors duration-[0.25s] hover:!text-white hover:border-mint"><?php echo esc_html($sec_text); ?></a>
    </div>

    <?php if ($show_trust_stats && is_array($trust) && !empty($trust)): ?>
      <div class="mt-14 pt-8 border-t border-[rgba(200,230,192,0.12)] flex flex-wrap gap-6 sm:gap-9 hero-trust">
        <?php foreach ($trust as $item): ?>
          <div class="flex flex-col trust-item">
            <span class="font-heading text-[1.6rem] font-normal text-white leading-none trust-num"><?php echo esc_html($item['num'] ?? ''); ?></span>
            <span class="text-[0.58rem] tracking-[0.18em] uppercase text-[rgba(200,230,192,0.45)] mt-1 trust-label"><?php echo esc_html($item['label'] ?? ''); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="hero-right bg-mint-soft relative overflow-hidden flex flex-col justify-center py-10 px-6 sm:py-12 sm:px-10 lg:py-[60px] lg:px-[52px]">
    <div class="hero-right-bg-logo absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center pointer-events-none select-none opacity-[0.06] whitespace-nowrap" aria-hidden="true">
      <span class="font-heading text-[9rem] text-forest font-black tracking-[0.1em] uppercase block leading-none">Luxora</span>
      <span class="font-draws text-[5rem] text-forest block pl-5 -mt-[0.1em]">draws</span>
    </div>

    <?php
    get_template_part('template-parts/components/prize-card', null, [
      'product'       => $featured,
      'badge_label'   => __('Featured Draw', 'nera-competitions'),
      'animate'       => true,
      'extra_classes' => 'max-w-[400px] mx-auto w-full z-[2]',
    ]);
    ?>
  </div>
</section>

