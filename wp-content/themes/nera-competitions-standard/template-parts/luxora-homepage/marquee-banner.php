<?php
/**
 * Luxora Homepage — Marquee Banner
 *
 * Scrolling marquee with ACF repeater items.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$items = get_field('luxora_marquee_items');
if (!is_array($items) || empty($items)) {
  $items = [
    ['text' => 'Tech Bundles'],
    ['text' => 'Cash Prizes'],
    ['text' => 'Kitchen Appliances'],
    ['text' => 'Beauty Collections'],
    ['text' => 'Lifestyle Packages'],
    ['text' => 'Luxury Watches'],
    ['text' => 'Free Entry Always Available'],
  ];
}

// Double the items for seamless loop (animation translates -50%)
$all = array_merge($items, $items);
?>

<div class="bg-forest overflow-hidden py-4 marquee">
  <div class="marquee-track flex gap-16 whitespace-nowrap [animation:luxora-scrollLeft_24s_linear_infinite]">
    <?php foreach ($all as $item): ?>
      <span class="marquee-item font-heading text-[0.82rem] italic text-[rgba(200,230,192,0.5)] tracking-[0.04em] flex items-center gap-16 after:content-['·'] after:not-italic after:text-[rgba(200,230,192,0.2)] after:text-base"><?php echo esc_html($item['text'] ?? ''); ?></span>
    <?php endforeach; ?>
  </div>
</div>
