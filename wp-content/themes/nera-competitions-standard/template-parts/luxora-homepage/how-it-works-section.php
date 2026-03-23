<?php
/**
 * Luxora Homepage — How It Works Section
 *
 * 3-step guide from luxora_how_steps ACF repeater.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$label = get_field('luxora_how_label') ?: __('The Process', 'nera-competitions');
$title = get_field('luxora_how_title') ?: __('Simple to enter.', 'nera-competitions');
$title_em = get_field('luxora_how_title_em') ?: __('Honest by nature.', 'nera-competitions');
$steps = get_field('luxora_how_steps') ?: [];
?>

<section class="py-12 px-4 sm:py-16 sm:px-6 md:py-20 md:px-10 lg:py-24 lg:px-[60px] bg-mint-soft how-section" id="how-it-works">
  <div class="sec-label text-[0.58rem] tracking-[0.32em] uppercase text-sage font-normal mb-3 flex items-center gap-3"><?php echo esc_html($label); ?></div>
  <h2 class="font-heading text-[clamp(2rem,3.5vw,2.9rem)] font-normal text-ink leading-[1.15] tracking-[-0.01em] sec-title"><?php echo esc_html($title); ?><br><em class="italic text-sage"><?php echo esc_html($title_em); ?></em></h2>
  <?php if (is_array($steps) && !empty($steps)): ?>
    <div class="mt-12 lg:mt-[60px] grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-0.5 lg:grid-cols-3 how-grid">
      <?php foreach (array_slice($steps, 0, 3) as $n => $step): ?>
        <div class="how-step bg-white p-6 sm:p-8 md:py-11 md:px-8 relative overflow-hidden border border-[rgba(61,74,58,0.14)] rounded-none" data-n="<?php echo esc_attr($n + 1); ?>">
          <div class="how-n text-[0.68rem] tracking-[0.16em] text-sage mb-[18px] font-normal"><?php
            $num = !empty($step['step_num']) ? $step['step_num'] : str_pad((string) ($n + 1), 2, '0', STR_PAD_LEFT);
            echo esc_html__('Step', 'nera-competitions') . ' ' . esc_html($num);
          ?></div>
          <?php if (!empty($step['title'])): ?>
            <div class="font-heading text-[1.25rem] font-normal text-ink mb-3 leading-[1.2] how-title"><?php echo esc_html($step['title']); ?></div>
          <?php endif; ?>
          <?php if (!empty($step['text'])): ?>
            <p class="text-[0.75rem] text-ink-soft leading-[1.85] font-light how-text"><?php echo esc_html($step['text']); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="mt-12 lg:mt-[60px] grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-0.5 lg:grid-cols-3 how-grid">
      <div class="how-step bg-white p-6 sm:p-8 md:py-11 md:px-8 relative overflow-hidden border border-[rgba(61,74,58,0.14)] rounded-none" data-n="1">
        <div class="how-n text-[0.68rem] tracking-[0.16em] text-sage mb-[18px] font-normal"><?php esc_html_e('Step 01', 'nera-competitions'); ?></div>
        <div class="font-heading text-[1.25rem] font-normal text-ink mb-3 leading-[1.2] how-title"><?php esc_html_e('Browse and Select', 'nera-competitions'); ?></div>
        <p class="text-[0.75rem] text-ink-soft leading-[1.85] font-light how-text"><?php esc_html_e('Explore live competitions across tech, lifestyle, beauty and more. Every prize includes full details, retail value, and a cash alternative amount.', 'nera-competitions'); ?></p>
      </div>
      <div class="how-step bg-white p-6 sm:p-8 md:py-11 md:px-8 relative overflow-hidden border border-[rgba(61,74,58,0.14)] rounded-none" data-n="2">
        <div class="how-n text-[0.68rem] tracking-[0.16em] text-sage mb-[18px] font-normal"><?php esc_html_e('Step 02', 'nera-competitions'); ?></div>
        <div class="font-heading text-[1.25rem] font-normal text-ink mb-3 leading-[1.2] how-title"><?php esc_html_e('Choose Your Tickets', 'nera-competitions'); ?></div>
        <p class="text-[0.75rem] text-ink-soft leading-[1.85] font-light how-text"><?php esc_html_e('Select how many tickets you\'d like. Bundle pricing rewards multiple entries. A free postal entry route is available on every competition.', 'nera-competitions'); ?></p>
      </div>
      <div class="how-step bg-white p-6 sm:p-8 md:py-11 md:px-8 relative overflow-hidden border border-[rgba(61,74,58,0.14)] rounded-none" data-n="3">
        <div class="how-n text-[0.68rem] tracking-[0.16em] text-sage mb-[18px] font-normal"><?php esc_html_e('Step 03', 'nera-competitions'); ?></div>
        <div class="font-heading text-[1.25rem] font-normal text-ink mb-3 leading-[1.2] how-title"><?php esc_html_e('Live Draw and Win', 'nera-competitions'); ?></div>
        <p class="text-[0.75rem] text-ink-soft leading-[1.85] font-light how-text"><?php esc_html_e('Draws are conducted live. Winners selected via certified randomisation and contacted directly. Prize or cash — your choice.', 'nera-competitions'); ?></p>
      </div>
    </div>
  <?php endif; ?>
</section>
