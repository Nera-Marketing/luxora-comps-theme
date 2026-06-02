<?php
/**
 * Luxora Homepage — Free Entry Banner
 *
 * Sage CTA banner with ACF fields.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$title = get_field('luxora_free_title') ?: __('Free entry is always available.', 'nera-competitions');
$body = get_field('luxora_free_body') ?: __('You never need to pay to enter a Luxora Draw. A free postal entry route is provided on every competition in compliance with UK law.', 'nera-competitions');
$cta_text = get_field('luxora_free_cta_text') ?: __('View Free Entry Details', 'nera-competitions');
$cta_url = get_field('luxora_free_cta_url') ?: home_url('/#how-it-works');
?>

<div class="free-bar bg-sage py-8 px-4 sm:py-10 sm:px-6 md:px-10 lg:py-[38px] lg:px-[60px] flex flex-col gap-6 items-stretch md:flex-row md:items-center md:justify-between md:gap-10">
  <div class="free-bar-text text-center md:text-left">
    <div class="font-heading text-[1.1rem] md:text-[1.25rem] font-normal text-white mb-1.5 free-bar-title"><?php echo esc_html($title); ?></div>
    <?php if ($body): ?>
      <p class="text-[0.73rem] text-[rgba(255,255,255,0.6)] leading-[1.7] max-w-none md:max-w-[520px] font-light free-bar-body"><?php echo esc_html($body); ?></p>
    <?php endif; ?>
  </div>
  <a href="<?php echo esc_url($cta_url); ?>" class="w-full text-center md:w-auto md:shrink-0 inline-block py-[13px] px-9 bg-white text-forest text-[0.67rem] tracking-[0.18em] uppercase font-medium no-underline transition-colors duration-300 rounded-none hover:bg-mint btn-white"><?php echo esc_html($cta_text); ?></a>
</div>
