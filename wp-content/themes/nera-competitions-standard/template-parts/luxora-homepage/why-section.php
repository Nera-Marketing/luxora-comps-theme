<?php
/**
 * Luxora Homepage — Why Luxora Section
 *
 * Forest green two-column layout: left = visual (main + accent images, pullquote);
 * right = label + title + items repeater (max 4).
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$label = get_field('luxora_why_label') ?: __('Why Luxora', 'nera-competitions');
$title = get_field('luxora_why_title') ?: __('Different by', 'nera-competitions');
$title_em = get_field('luxora_why_title_em') ?: __('design.', 'nera-competitions');
$pullquote = get_field('luxora_why_pullquote') ?: __('"The most refined competition platform I\'ve used."', 'nera-competitions');
$img_main = get_field('luxora_why_image_main');
$img_accent = get_field('luxora_why_image_accent');
$items = get_field('luxora_why_items') ?: [];
?>

<section class="bg-forest text-white py-24 px-[60px] grid grid-cols-2 gap-20 items-center why-section">
  <div class="relative aspect-[3/4] overflow-hidden why-visual">
    <div class="w-full h-[72%] [background:linear-gradient(145deg,#4a5a46,#3a4838,#425244)] relative overflow-hidden flex items-center justify-center why-img-main" <?php
      if ($img_main && is_array($img_main) && !empty($img_main['url'])) {
        echo 'style="background-image:url(\'' . esc_url($img_main['url']) . '\');background-size:cover;background-position:center"';
      }
    ?>>
      <?php if (!$img_main || !is_array($img_main) || empty($img_main['url'])): ?>
        <div class="opacity-30 text-center why-img-main-inner">
          <span class="font-heading text-2xl font-black tracking-[0.12em] uppercase text-mint block">Luxora</span>
          <span class="font-draws text-[1.4rem] text-mint block pl-2.5 -mt-[0.1em]">draws</span>
        </div>
      <?php endif; ?>
    </div>
    <div class="absolute bottom-0 right-0 w-[55%] h-[36%] bg-sage border-4 border-forest flex items-center justify-center why-img-accent" <?php
      if ($img_accent && is_array($img_accent) && !empty($img_accent['url'])) {
        echo 'style="background-image:url(\'' . esc_url($img_accent['url']) . '\');background-size:cover;background-position:center"';
      }
    ?>>
    </div>
    <?php if ($pullquote): ?>
      <div class="absolute bottom-[34%] left-0 bg-mint p-[18px_20px] max-w-[195px] why-pullquote">
        <div class="font-heading italic text-[0.88rem] text-forest leading-[1.5] why-pullquote-text"><?php echo esc_html($pullquote); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div>
    <div class="why-content-label text-[0.58rem] tracking-[0.32em] uppercase text-mint font-normal mb-3 flex items-center gap-3 opacity-70"><?php echo esc_html($label); ?></div>
    <h2 class="font-heading text-[clamp(1.9rem,3vw,2.7rem)] font-normal text-white leading-[1.15] mb-10 why-title"><?php echo esc_html($title); ?><br><em class="italic text-mint"><?php echo esc_html($title_em); ?></em></h2>
    <?php if (is_array($items) && !empty($items)): ?>
      <div class="flex flex-col why-items">
        <?php foreach (array_slice($items, 0, 4) as $n => $item): ?>
          <div class="flex gap-[22px] py-[26px] border-b border-[rgba(200,230,192,0.08)] first:pt-0 why-item">
            <span class="why-n font-heading text-[0.68rem] text-[rgba(200,230,192,0.25)] tracking-[0.1em] min-w-[22px] pt-0.5"><?php echo esc_html(str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <div>
              <?php if (!empty($item['title'])): ?>
                <div class="font-heading text-[0.98rem] font-normal text-white mb-1.5 why-item-title"><?php echo esc_html($item['title']); ?></div>
              <?php endif; ?>
              <?php if (!empty($item['text'])): ?>
                <p class="text-[0.73rem] text-[rgba(200,230,192,0.45)] leading-[1.8] font-light why-item-text"><?php echo esc_html($item['text']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
