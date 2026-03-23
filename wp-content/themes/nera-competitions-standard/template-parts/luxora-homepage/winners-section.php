<?php
/**
 * Luxora Homepage — Winners Section
 *
 * 4-column winners grid from luxora_winners_list ACF repeater.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$label = get_field('luxora_winners_label') ?: __('Recent Winners', 'nera-competitions');
$title = get_field('luxora_winners_title') ?: __('People are', 'nera-competitions');
$title_em = get_field('luxora_winners_title_em') ?: __('winning.', 'nera-competitions');
$list = get_field('luxora_winners_list') ?: [];
?>

<section class="py-12 px-4 sm:py-16 sm:px-6 md:py-20 md:px-10 lg:py-24 lg:px-[60px] bg-mint-wash winners-section">
  <div class="sec-label text-[0.58rem] tracking-[0.32em] uppercase text-sage font-normal mb-3 flex items-center gap-3"><?php echo esc_html($label); ?></div>
  <h2 class="font-heading text-[clamp(2rem,3.5vw,2.9rem)] font-normal text-ink leading-[1.15] tracking-[-0.01em] sec-title"><?php echo esc_html($title); ?> <em class="italic text-sage"><?php echo esc_html($title_em); ?></em></h2>
  <?php if (is_array($list) && !empty($list)): ?>
    <div class="mt-12 grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-0.5 lg:grid-cols-4 winners-grid">
      <?php foreach ($list as $winner): ?>
        <div class="winner-card bg-white p-4 sm:py-[26px] sm:px-[22px] border border-[rgba(61,74,58,0.14)] rounded-none">
          <?php if (!empty($winner['prize'])): ?>
            <div class="font-heading text-[0.95rem] font-normal text-ink mb-1 leading-[1.3] winner-prize"><?php echo esc_html($winner['prize']); ?></div>
          <?php endif; ?>
          <?php if (!empty($winner['location'])): ?>
            <div class="text-[0.62rem] tracking-[0.08em] text-ink-soft mb-3.5 winner-loc"><?php echo esc_html($winner['location']); ?></div>
          <?php endif; ?>
          <?php if (!empty($winner['quote'])): ?>
            <div class="font-heading italic text-[0.82rem] text-ink-mid leading-[1.7] winner-quote"><?php echo esc_html($winner['quote']); ?></div>
          <?php endif; ?>
          <?php if (!empty($winner['date'])): ?>
            <div class="mt-3.5 text-[0.56rem] tracking-[0.16em] uppercase text-sage winner-date"><?php echo esc_html($winner['date']); ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="mt-12 grid grid-cols-1 gap-2 sm:grid-cols-2 sm:gap-0.5 lg:grid-cols-4 winners-grid">
      <div class="winner-card bg-white p-4 sm:py-[26px] sm:px-[22px] border border-[rgba(61,74,58,0.14)] rounded-none">
        <div class="font-heading text-[0.95rem] font-normal text-ink mb-1 leading-[1.3] winner-prize"><?php esc_html_e('Apple MacBook Air M3', 'nera-competitions'); ?></div>
        <div class="text-[0.62rem] tracking-[0.08em] text-ink-soft mb-3.5 winner-loc"><?php esc_html_e('James T. — Manchester', 'nera-competitions'); ?></div>
        <div class="font-heading italic text-[0.82rem] text-ink-mid leading-[1.7] winner-quote"><?php esc_html_e('"Genuinely didn\'t believe it at first. The whole process was seamless from entry to delivery."', 'nera-competitions'); ?></div>
        <div class="mt-3.5 text-[0.56rem] tracking-[0.16em] uppercase text-sage winner-date"><?php esc_html_e('February 2025', 'nera-competitions'); ?></div>
      </div>
      <div class="winner-card bg-white p-4 sm:py-[26px] sm:px-[22px] border border-[rgba(61,74,58,0.14)] rounded-none">
        <div class="font-heading text-[0.95rem] font-normal text-ink mb-1 leading-[1.3] winner-prize"><?php esc_html_e('£500 Cash Prize', 'nera-competitions'); ?></div>
        <div class="text-[0.62rem] tracking-[0.08em] text-ink-soft mb-3.5 winner-loc"><?php esc_html_e('Sophie L. — London', 'nera-competitions'); ?></div>
        <div class="font-heading italic text-[0.82rem] text-ink-mid leading-[1.7] winner-quote"><?php esc_html_e('"In my account within 24 hours. No fuss, no drama. Will absolutely enter again."', 'nera-competitions'); ?></div>
        <div class="mt-3.5 text-[0.56rem] tracking-[0.16em] uppercase text-sage winner-date"><?php esc_html_e('February 2025', 'nera-competitions'); ?></div>
      </div>
      <div class="winner-card bg-white p-4 sm:py-[26px] sm:px-[22px] border border-[rgba(61,74,58,0.14)] rounded-none">
        <div class="font-heading text-[0.95rem] font-normal text-ink mb-1 leading-[1.3] winner-prize"><?php esc_html_e('Charlotte Tilbury Bundle', 'nera-competitions'); ?></div>
        <div class="text-[0.62rem] tracking-[0.08em] text-ink-soft mb-3.5 winner-loc"><?php esc_html_e('Priya M. — Birmingham', 'nera-competitions'); ?></div>
        <div class="font-heading italic text-[0.82rem] text-ink-mid leading-[1.7] winner-quote"><?php esc_html_e('"The packaging alone felt premium. Everything arrived perfectly, exactly as described."', 'nera-competitions'); ?></div>
        <div class="mt-3.5 text-[0.56rem] tracking-[0.16em] uppercase text-sage winner-date"><?php esc_html_e('January 2025', 'nera-competitions'); ?></div>
      </div>
      <div class="winner-card bg-white p-4 sm:py-[26px] sm:px-[22px] border border-[rgba(61,74,58,0.14)] rounded-none">
        <div class="font-heading text-[0.95rem] font-normal text-ink mb-1 leading-[1.3] winner-prize"><?php esc_html_e('KitchenAid Artisan Mixer', 'nera-competitions'); ?></div>
        <div class="text-[0.62rem] tracking-[0.08em] text-ink-soft mb-3.5 winner-loc"><?php esc_html_e('David R. — Edinburgh', 'nera-competitions'); ?></div>
        <div class="font-heading italic text-[0.82rem] text-ink-mid leading-[1.7] winner-quote"><?php esc_html_e('"The draw was live streamed and completely transparent. My wife had wanted one for years."', 'nera-competitions'); ?></div>
        <div class="mt-3.5 text-[0.56rem] tracking-[0.16em] uppercase text-sage winner-date"><?php esc_html_e('January 2025', 'nera-competitions'); ?></div>
      </div>
    </div>
  <?php endif; ?>
</section>
