<?php
/**
 * Brand Statement Section
 *
 * Three-image fan layout with headline and body copy.
 * Showcases Luxora's brand identity using portrait visual assets.
 *
 * ACF Fields: bs_label, bs_heading, bs_heading_em, bs_body,
 *             bs_cta_text, bs_cta_url, bs_image_1, bs_image_2, bs_image_3
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

// ── Content fields ──────────────────────────────────────────────────────────
$label      = get_field('bs_label')      ?: __('Our Promise', 'nera-competitions');
$heading    = get_field('bs_heading')    ?: __('Get on a', 'nera-competitions');
$heading_em = get_field('bs_heading_em') ?: __('winning streak', 'nera-competitions');
$body       = get_field('bs_body')       ?: __('Capped tickets. Fair draws. Real winners. Every competition on Luxora gives you a genuine shot at winning something extraordinary — no bots, no bulk buyers, just you.', 'nera-competitions');
$cta_text   = get_field('bs_cta_text')   ?: __('Enter a Competition', 'nera-competitions');
$cta_link   = get_field('bs_cta_url');
$cta_url    = !empty($cta_link['url'])    ? $cta_link['url']    : '/competitions';
$cta_target = !empty($cta_link['target']) ? $cta_link['target'] : '';

// ── Image fields — fall back to reference assets ────────────────────────────
$theme_uri = get_stylesheet_directory_uri();

$img_1_data = get_field('bs_image_1');
$img_1_url  = !empty($img_1_data['url']) ? $img_1_data['url'] : $theme_uri . '/reference/idea-1.jpeg';
$img_1_alt  = !empty($img_1_data['alt']) ? $img_1_data['alt'] : __('Get on a winning streak', 'nera-competitions');

$img_2_data = get_field('bs_image_2');
$img_2_url  = !empty($img_2_data['url']) ? $img_2_data['url'] : $theme_uri . '/reference/idea-2.jpeg';
$img_2_alt  = !empty($img_2_data['alt']) ? $img_2_data['alt'] : __('Capped tickets, real winners', 'nera-competitions');

$img_3_data = get_field('bs_image_3');
$img_3_url  = !empty($img_3_data['url']) ? $img_3_data['url'] : $theme_uri . '/reference/idea-3.jpeg';
$img_3_alt  = !empty($img_3_data['alt']) ? $img_3_data['alt'] : __('Will you win?', 'nera-competitions');
?>

<section class="overflow-hidden bg-[#f8fbf6] py-20 lg:py-28 brand-statement-section">
  <div class="max-w-[1200px] mx-auto px-6 md:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20 items-center">

    <!-- ── Text column ──────────────────────────────────────────────── -->
    <div data-aos="fade-up">

      <!-- Eyebrow label -->
      <p class="flex items-center gap-2.5 text-[0.75rem] font-semibold tracking-[0.15em] uppercase text-[#6b8c6b] mb-5">
        <span class="block w-7 h-0.5 bg-[#6b8c6b] rounded-full shrink-0" aria-hidden="true"></span>
        <?php echo esc_html($label); ?>
      </p>

      <!-- Heading -->
      <h2 class="font-heading text-[clamp(2.25rem,5vw,3.5rem)] font-bold leading-[1.15] text-[#1e2a1e] mb-6">
        <?php echo esc_html($heading); ?><br>
        <em class="italic font-normal text-[#3d6e3d]"><?php echo esc_html($heading_em); ?></em>
      </h2>

      <!-- Body text -->
      <p class="text-[1.0625rem] leading-[1.75] text-[#3d4a3a] max-w-[44ch] mb-10">
        <?php echo esc_html($body); ?>
      </p>

      <!-- CTA — matches prize-card "Enter from" button -->
      <?php if ($cta_url && $cta_text): ?>
        <a href="<?php echo esc_url($cta_url); ?>"
           <?php if ($cta_target): ?>target="<?php echo esc_attr($cta_target); ?>" rel="noopener noreferrer"<?php endif; ?>
           class="inline-flex items-center gap-2.5 py-[15px] px-8 bg-forest !text-mint-soft text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline cursor-pointer transition-colors duration-300 rounded-none hover:bg-[#2e3a2c] hover:!text-white">
          <?php echo esc_html($cta_text); ?>
          <span aria-hidden="true">&rarr;</span>
        </a>
      <?php endif; ?>

    </div><!-- /.text column -->

    <!-- ── Image fan cluster (images first on mobile) ───────────────── -->
    <div class="relative h-[480px] sm:h-[540px] lg:h-[580px] flex items-center justify-center order-first lg:order-last"
         data-aos="fade-left" data-aos-delay="150"
         x-data="{ active: 2 }">

      <!-- Image 1 — behind left, rotated -->
      <div class="absolute w-[58%] max-w-[260px] sm:max-w-[300px] cursor-pointer transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
           :class="active === 1 ? 'z-[10]' : 'z-[1] opacity-80'"
           :style="active === 1 ? 'transform:rotate(0deg) scale(1.05)' : 'transform:rotate(-6deg) translateX(-28%) translateY(2%)'"
           @click="active = 1">
        <div class="group aspect-[4/5] rounded-[1.25rem] overflow-hidden shadow-[0_20px_50px_rgba(30,42,30,0.18)] transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-2 hover:shadow-[0_28px_60px_rgba(30,42,30,0.25)]">
          <img
            src="<?php echo esc_url($img_1_url); ?>"
            alt="<?php echo esc_attr($img_1_alt); ?>"
            class="w-full h-full object-cover block transition-transform duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            loading="lazy"
            decoding="async"
          >
        </div>
      </div>

      <!-- Image 3 — behind right, rotated -->
      <div class="absolute w-[58%] max-w-[260px] sm:max-w-[300px] cursor-pointer transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
           :class="active === 3 ? 'z-[10]' : 'z-[2] opacity-80'"
           :style="active === 3 ? 'transform:rotate(0deg) scale(1.05)' : 'transform:rotate(6deg) translateX(28%) translateY(2%)'"
           @click="active = 3">
        <div class="group aspect-[4/5] rounded-[1.25rem] overflow-hidden shadow-[0_20px_50px_rgba(30,42,30,0.18)] transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-2 hover:shadow-[0_28px_60px_rgba(30,42,30,0.25)]">
          <img
            src="<?php echo esc_url($img_3_url); ?>"
            alt="<?php echo esc_attr($img_3_alt); ?>"
            class="w-full h-full object-cover block transition-transform duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            loading="lazy"
            decoding="async"
          >
        </div>
      </div>

      <!-- Image 2 — front centre, no rotation (rendered last = highest stacking) -->
      <div class="absolute w-[58%] max-w-[260px] sm:max-w-[300px] cursor-pointer transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
           :class="active === 2 ? 'z-[10]' : 'z-[3] opacity-80'"
           :style="active === 2 ? 'transform:rotate(0deg) scale(1.05)' : active === 1 ? 'transform:translateX(28%) translateY(2%)' : 'transform:translateX(-28%) translateY(2%)'"
           @click="active = 2">
        <div class="group aspect-[4/5] rounded-[1.25rem] overflow-hidden shadow-[0_24px_56px_rgba(30,42,30,0.22)] transition-all duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-2 hover:shadow-[0_32px_70px_rgba(30,42,30,0.28)]">
          <img
            src="<?php echo esc_url($img_2_url); ?>"
            alt="<?php echo esc_attr($img_2_alt); ?>"
            class="w-full h-full object-cover block transition-transform duration-[450ms] ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            loading="lazy"
            decoding="async"
          >
        </div>
      </div>

    </div><!-- /.image fan cluster -->

  </div>
</section>
