<?php
/**
 * Welcome Section - Personal Intro from JJ
 *
 * Personal welcome with JJ's photo and intro text.
 * Editable via Home Page Group > Welcome (JJ Intro) in WP admin.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// ACF fields (fallbacks used when empty)
$welcome_badge = get_field('welcome_badge') ?: __('From the Founder', 'nera-competitions');
$welcome_title =
  get_field('welcome_title') ?: __('A Personal Welcome from JJ', 'nera-competitions');
$welcome_intro =
  get_field('welcome_intro') ?:
  __(
    'Intro text from JJ will be placed here. This is a placeholder for the personal introduction.',
    'nera-competitions',
  );
$welcome_image = get_field('welcome_image');
?>

<section class="welcome-section py-16 lg:py-28 bg-[#1a1815] relative overflow-hidden" id="welcome"
  data-aos="fade-up">

  <!-- Optional radial gradient overlay -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_50%,rgba(155,80,57,0.06),transparent_70%)]"></div>
  </div>

  <div class="max-w-6xl mx-auto px-4 lg:px-8 relative z-10">

    <!-- 50/50 Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

      <!-- Text Content Column -->
      <div class="space-y-6 welcome-text-content">

        <!-- Badge -->
        <?php if ($welcome_badge): ?>
          <div
            class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-[rgba(155,80,57,0.2)] border border-[rgba(216,181,130,0.2)]">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-[#c4704e]">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                fill="currentColor" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-widest text-[#c4704e]"><?php echo esc_html(
              $welcome_badge,
            ); ?></span>
          </div>
        <?php endif; ?>

        <!-- Title -->
        <h2 class="font-heading text-4xl lg:text-5xl xl:text-6xl font-black text-[#d8b582] leading-[1.1] tracking-tight">
          <?php echo esc_html($welcome_title); ?>
        </h2>

        <!-- Intro text (placeholder) -->
        <div class="text-lg text-[rgba(216,181,130,0.56)] leading-relaxed max-w-none">
          <?php echo wp_kses_post($welcome_intro); ?>
        </div>

      </div>

      <!-- Photo Column -->
      <div class="relative welcome-image-container">

        <!-- Decorative frame element -->
        <div class="absolute -inset-6 border border-[rgba(216,181,130,0.2)] rounded-3xl transform rotate-2"></div>
        <div class="absolute -inset-4 bg-gradient-to-br from-[rgba(155,80,57,0.1)] to-[rgba(216,181,130,0.05)] rounded-3xl blur-2xl opacity-60"></div>

        <!-- Main image container -->
        <div
          class="relative rounded-2xl overflow-hidden shadow-2xl shadow-[rgba(155,80,57,0.2)] group border-4 border-[#1e1c18]">
          <?php if ($welcome_image && isset($welcome_image['url'])): ?>
            <div class="aspect-[4/5] overflow-hidden relative">
              <img src="<?php echo esc_url($welcome_image['url']); ?>"
                alt="<?php echo esc_attr(
                  $welcome_image['alt'] ?: __('JJ', 'nera-competitions'),
                ); ?>"
                class="absolute inset-0 w-full h-full min-h-full object-cover object-[center_top] transition-transform duration-[800ms] ease-out group-hover:scale-105"
                loading="lazy" />
            </div>
          <?php else: ?>
            <!-- Placeholder for JJ's photo -->
            <div
              class="aspect-[4/5] bg-gradient-to-br from-[#1e1c18] via-[rgba(216,181,130,0.05)] to-[#1a1815] flex items-center justify-center">
              <div class="text-center space-y-4">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5"
                  class="text-[rgba(216,181,130,0.2)] mx-auto">
                  <rect x="3" y="3" width="18" height="18" rx="2" />
                  <circle cx="8.5" cy="8.5" r="1.5" />
                  <polyline points="21 15 16 10 5 21" />
                </svg>
                <p class="text-sm text-[rgba(216,181,130,0.4)] font-medium"><?php _e(
                  "JJ's photo placeholder",
                  'nera-competitions',
                ); ?></p>
              </div>
            </div>
          <?php endif; ?>

          <!-- Subtle gradient overlay -->
          <div
            class="absolute inset-0 bg-gradient-to-t from-[rgba(155,80,57,0.05)] via-transparent to-transparent pointer-events-none">
          </div>
        </div>

      </div>

    </div>

  </div>
</section>
