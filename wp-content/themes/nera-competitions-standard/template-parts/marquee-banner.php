<?php
/**
 * Marquee Banner Template Part
 *
 * Moving text banner at the top of all pages with brand messaging.
 * Content editable via Theme Settings > Marquee Banner.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$enabled = get_field('marquee_enabled', 'option');
$items = get_field('marquee_items', 'option');

// Default client-supplied messaging when repeater is empty
$default_items = [
  'LIVE LIFE PRIZES',
  'PART OF VAN LIFE BUILDS',
  'WIN LIFE ENRICHING PRIZES',
  'FIXED ODDS & TRANSPARENT DRAWS',
  'SMALL BUSINESS, BIG HEART',
  'SUPPORTING WORTHY CAUSES',
  'GOODBYE THINGS, HELLO EXPERIENCES',
  'RECONNECT WITH NATURE',
  'LIVE LIFE FULLY',
];

if (!$enabled) {
  return;
}

$phrases = [];
if (is_array($items) && !empty($items)) {
  foreach ($items as $row) {
    $text = isset($row['text']) ? trim($row['text']) : '';
    if ($text !== '') {
      $phrases[] = $text;
    }
  }
}
if (empty($phrases)) {
  $phrases = $default_items;
}
?>

<div class="marquee-banner overflow-hidden bg-[#0c0b09] border-b border-[rgba(216,181,130,0.06)] py-2.5" role="region" aria-label="<?php esc_attr_e('Brand messaging', 'nera-competitions'); ?>">
  <div class="marquee-track flex shrink-0 gap-x-12 items-center">
    <?php
    // Output items twice for seamless infinite scroll
    $phrases_doubled = array_merge($phrases, $phrases);
    $count = count($phrases_doubled);
    foreach ($phrases_doubled as $i => $phrase):
      $is_duplicate = $i >= count($phrases);
    ?>
      <span class="marquee-item text-[12px] font-medium uppercase tracking-[2px] text-[rgba(216,181,130,0.7)] shrink-0"<?php echo $is_duplicate ? ' aria-hidden="true"' : ''; ?>>
        <?php echo esc_html($phrase); ?>
      </span>
      <?php if ($i < $count - 1): ?>
        <span class="marquee-sep text-[rgba(216,181,130,0.2)] shrink-0" aria-hidden="true">&#8226;</span>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>
