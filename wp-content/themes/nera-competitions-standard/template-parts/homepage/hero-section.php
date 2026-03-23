<?php
/**
 * Hero Section Template Part
 *
 * Main hero banner for the homepage — Earthy Editorial style
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Get hero content from ACF or use defaults
$hero_title = get_field('hero_title') ?: __('Win Your Dream', 'nera-competitions');
$hero_highlight = get_field('hero_highlight') ?: __('Lifestyle.', 'nera-competitions');
$hero_description =
  get_field('hero_description') ?:
  __(
    'Experience the thrill of high-end giveaways with the UK\'s most exclusive prize competition platform. Because you deserve a chance to win.',
    'nera-competitions',
  );
$hero_cta_text = get_field('hero_cta_text') ?: __('View Active Giveaways', 'nera-competitions');
$hero_cta_url = get_field('hero_cta_url') ?: get_permalink(wc_get_page_id('shop'));
$hero_secondary_text =
  get_field('hero_secondary_text') ?: __('Recent Winners', 'nera-competitions');
$hero_secondary_url = get_field('hero_secondary_url') ?: '#winners';

// Hero media (image, video, or video iframe)
$hero_media_type = get_field('hero_media_type') ?: 'image';
$hero_image = get_field('hero_image');
$hero_video = get_field('hero_video');
// Hero video iframe: always build from raw URL (bypass ACF oEmbed - it can fail and wp_kses_post strips iframes)
$hero_video_iframe_raw = get_field('hero_video_iframe', get_queried_object_id(), false);
$hero_video_iframe = '';
if ($hero_video_iframe_raw) {
  if (
    preg_match(
      '#(?:youtube\.com/(?:live|embed)/([a-zA-Z0-9_-]+)|youtube\.com/watch\?v=([a-zA-Z0-9_-]+)|youtu\.be/([a-zA-Z0-9_-]+))#i',
      $hero_video_iframe_raw,
      $m,
    )
  ) {
    $id = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? $m[2] : $m[3]);
    $hero_video_iframe = sprintf(
      '<iframe width="560" height="315" src="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
      esc_url('https://www.youtube.com/embed/' . $id . '?autoplay=1&mute=1'),
    );
  } elseif (
    preg_match(
      '#(?:vimeo\.com/(?:video/)?(\d+)|player\.vimeo\.com/video/(\d+))#i',
      $hero_video_iframe_raw,
      $m,
    )
  ) {
    $id = !empty($m[1]) ? $m[1] : $m[2];
    $hero_video_iframe = sprintf(
      '<iframe width="560" height="315" src="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
      esc_url('https://player.vimeo.com/video/' . $id . '?autoplay=1&muted=1'),
    );
  } else {
    $hero_video_iframe = wp_oembed_get($hero_video_iframe_raw) ?: '';
  }
}

// Last winner
$last_winner_name = get_field('last_winner_name') ?: 'Sarah M.';
$last_winner_prize = get_field('last_winner_prize') ?: 'Won This Prize';
?>

<section
  class="hero-section relative grid grid-cols-1 lg:grid-cols-2 min-h-screen lg:min-h-[calc(100vh-73px)] bg-[#0c0b09] overflow-hidden"
  id="hero" data-aos="fade-up" data-aos-duration="600">

  <!-- Left: Editorial content -->
  <div class="hero-left flex flex-col justify-center px-6 md:px-12 lg:px-20 py-10 md:py-12 lg:py-20 relative order-2 lg:order-1">
    <!-- Vertical divider (bronze-dim) -->
    <div class="hidden lg:block absolute top-[10%] right-0 bottom-[10%] w-px bg-gradient-to-b from-transparent via-[rgba(216,181,130,0.2)] to-transparent"></div>

    <!-- Badge -->
    <span class="inline-block w-fit bg-[rgba(155,80,57,0.2)] text-[#c4704e] rounded-[20px] py-1.5 px-4 text-[11px] tracking-[2px] uppercase mb-6 font-medium">
      <?php _e('Premium Giveaways', 'nera-competitions'); ?>
    </span>

    <!-- Title -->
    <h1 class="font-heading text-3xl sm:text-4xl md:text-5xl lg:text-[56px] font-normal leading-[1.15] text-[#d8b582] mb-6">
      <?php echo esc_html($hero_title); ?>
      <br>
      <em class="italic text-[#c4704e]">
        <?php echo esc_html($hero_highlight); ?>
      </em>
    </h1>

    <!-- Description -->
    <p class="text-base leading-[1.8] text-[rgba(216,181,130,0.56)] w-full max-w-[480px] mb-6 md:mb-10">
      <?php echo esc_html($hero_description); ?>
    </p>

    <!-- CTA Buttons -->
    <div class="flex flex-wrap gap-4 mb-6 md:mb-8">
      <a href="<?php echo esc_url($hero_cta_url); ?>"
        class="hero-primary inline-flex items-center gap-2 px-10 py-4 bg-[#d8b582] text-[#0c0b09] rounded font-medium text-[14px] tracking-[1px] transition-all hover:bg-[#c4704e] hover:text-[#d8b582]">
        <?php echo esc_html($hero_cta_text); ?>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
      <a href="<?php echo esc_url($hero_secondary_url); ?>"
        class="hero-secondary inline-flex items-center gap-2 text-[rgba(216,181,130,0.56)] hover:text-[#d8b582] text-[14px] transition-colors">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
        <?php echo esc_html($hero_secondary_text); ?>
      </a>
    </div>
  </div>

  <!-- Right: Image / Video placeholder area -->
  <div class="hero-right relative overflow-hidden flex items-center justify-center px-6 py-8 md:px-12 md:py-12 lg:px-20 lg:py-20 order-1 lg:order-2"
    style="background: linear-gradient(160deg, rgba(155,80,57,0.15), rgba(216,181,130,0.05), rgba(155,80,57,0.1));">
    <!-- Decorative circles -->
    <div class="absolute w-[280px] h-[280px] sm:w-[400px] sm:h-[400px] md:w-[500px] md:h-[500px] rounded-full border border-[rgba(216,181,130,0.2)] opacity-30"></div>
    <div class="absolute w-[420px] h-[420px] sm:w-[550px] sm:h-[550px] md:w-[700px] md:h-[700px] rounded-full border border-[rgba(216,181,130,0.2)] opacity-15"></div>

    <!-- Main content area -->
    <div class="relative w-full md:w-[80%] z-10">
      <?php if ($hero_media_type === 'image' && $hero_image): ?>
        <div class="aspect-[16/9] rounded-xl overflow-hidden border border-[rgba(216,181,130,0.2)] bg-[rgba(216,181,130,0.05)]">
          <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr(
  $hero_title,
); ?>" class="w-full h-full object-cover">
        </div>
      <?php elseif ($hero_media_type === 'video' && $hero_video): ?>
        <div class="aspect-[16/9] rounded-xl overflow-hidden border border-[rgba(216,181,130,0.2)] bg-[rgba(216,181,130,0.05)]">
          <video src="<?php echo esc_url(
            $hero_video,
          ); ?>" controls playsinline preload="metadata" class="w-full h-full object-cover"></video>
        </div>
      <?php elseif ($hero_media_type === 'video_iframe' && $hero_video_iframe): ?>
        <div class="aspect-[16/9] rounded-xl overflow-hidden border border-[rgba(216,181,130,0.2)] bg-[rgba(216,181,130,0.05)]">
          <div class="relative size-full [&_iframe]:absolute [&_iframe]:inset-0 [&_iframe]:size-full">
            <?php echo $hero_video_iframe; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="hero-video-placeholder aspect-[16/9] rounded-xl flex flex-col items-center justify-center gap-3 border border-[rgba(216,181,130,0.2)] bg-[rgba(216,181,130,0.05)]">
          <div class="play-btn w-[60px] h-[60px] rounded-full border-[1.5px] border-[rgba(216,181,130,0.56)] flex items-center justify-center hover:bg-[#d8b582] hover:border-[#d8b582] transition-all cursor-pointer">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d8b582" stroke-width="1.5"><polygon points="10 8 16 12 10 16 10 8"/></svg>
          </div>
          <span class="text-[12px] text-[rgba(216,181,130,0.2)] tracking-[2px] uppercase"><?php _e(
            'Lifestyle video here',
            'nera-competitions',
          ); ?></span>
        </div>
      <?php endif; ?>

      <?php if ($hero_media_type === 'image'): ?>
      <!-- Winner Badge -->
      <div class="absolute -bottom-3 -right-3 sm:bottom-4 sm:right-4 lg:bottom-8 lg:-right-4 bg-[#1e1c18] rounded-xl sm:rounded-2xl border border-[rgba(216,181,130,0.2)] p-2.5 sm:p-4 flex items-center gap-2 sm:gap-3">
        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-[#d8b582] flex items-center justify-center text-[#0c0b09] font-bold text-sm sm:text-base">
          <?php echo esc_html(substr($last_winner_name, 0, 1)); ?>
        </div>
        <div>
          <span class="block text-xs sm:text-sm font-semibold text-[#d8b582]">
            <?php _e('Last Winner:', 'nera-competitions'); ?>
          </span>
          <span class="block text-[10px] sm:text-xs text-[rgba(216,181,130,0.56)]">
            <?php echo esc_html($last_winner_name); ?>
          </span>
        </div>
        <span class="text-[#4ade80] text-base sm:text-xl">✓</span>
      </div>
      <?php endif; ?>
    </div>
  </div>

</section>
