<?php
/**
 * Credibility Section Template Part
 *
 * Trust bar with icon + label blocks — Earthy Editorial style
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

// Default trust items (used when ACF repeater is empty)
$default_items = [
  ['icon' => 'lock', 'label' => __('Secure Payments', 'nera-competitions')],
  ['icon' => 'verified', 'label' => __('UK Compliant', 'nera-competitions')],
  ['icon' => 'visibility', 'label' => __('Transparent Draws', 'nera-competitions')],
  ['icon' => 'emoji_events', 'label' => __('Real Winners', 'nera-competitions')],
  ['icon' => 'headset_mic', 'label' => __('Fast Support', 'nera-competitions')],
];

// Get items from ACF repeater or use defaults
$credibility_items = [];
if (function_exists('have_rows') && have_rows('credibility_items')) {
  while (have_rows('credibility_items')) {
    the_row();
    $credibility_items[] = [
      'icon' => get_sub_field('icon') ?: 'check_circle',
      'label' => get_sub_field('label') ?: '',
    ];
  }
}

if (empty($credibility_items)) {
  $credibility_items = $default_items;
}
?>

<section class="credibility-section trust-bar py-10 px-6 lg:px-20 bg-[#0c0b09] border-t md:border-t-0 border-b border-[rgba(216,181,130,0.06)]" data-aos="fade-up">
  <div class="flex flex-wrap items-center md:justify-center gap-x-12 md:gap-x-16 lg:gap-x-[60px]">
    <?php foreach ($credibility_items as $item):
      if (empty($item['label'])) {
        continue;
      } ?>
      <div class="trust-item flex items-center gap-2.5">
        <span class="inline-flex items-center justify-center w-9 h-9 text-[#c4704e]">
          <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 0;">
            <?php echo esc_html($item['icon']); ?>
          </span>
        </span>
        <span class="text-[12px] text-[rgba(216,181,130,0.56)] tracking-[1px]">
          <?php echo esc_html($item['label']); ?>
        </span>
      </div>
    <?php
    endforeach; ?>
  </div>
</section>
