<?php
/**
 * Account Creation Prompt Template Part
 *
 * Featured card prompting logged-out users to create a free account.
 * Editable via Home Page Group > Account Prompt.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (is_user_logged_in()) {
  return;
}

$enabled = get_field('account_prompt_enabled');
if ($enabled === false || $enabled === 0) {
  return;
}

$copy =
  get_field('account_prompt_copy') ?:
  __(
    'New here? Create your free account — it takes seconds to start entering.',
    'nera-competitions',
  );
$cta_text = get_field('account_prompt_cta_text') ?: __('Create account', 'nera-competitions');
$cta_url_raw = get_field('account_prompt_cta_url');
$cta_url = $cta_url_raw ? esc_url($cta_url_raw) : esc_url(home_url('/my-account/'));
$badge = get_field('account_prompt_badge');
$icon = get_field('account_prompt_icon') ?: 'person_add';
?>

<section class="account-prompt-section py-10 lg:py-12 px-6 lg:px-20 relative overflow-visible" data-aos="fade-up" role="region" aria-label="<?php esc_attr_e('Account creation', 'nera-competitions'); ?>">

  <div class="max-w-4xl mx-auto relative">
    <!-- Decorative blur orbs -->
    <div class="absolute -top-16 -right-16 w-48 h-48 bg-[rgba(155,80,57,0.08)] rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-[rgba(216,181,130,0.05)] rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <!-- Contained card -->
    <div class="account-prompt-card relative overflow-hidden rounded-2xl lg:rounded-3xl bg-[#1a1815] border border-[rgba(216,181,130,0.15)] backdrop-blur-sm group">

      <!-- Top gradient strip -->
      <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[rgba(155,80,57,0.4)] to-transparent" aria-hidden="true"></div>

      <div class="relative z-10 px-8 py-8 lg:px-12 lg:py-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <!-- Badge + icon + copy -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
          <?php if ($badge): ?>
            <span class="inline-block w-fit px-4 py-1.5 text-[11px] font-medium uppercase tracking-[2px] rounded-full bg-[rgba(155,80,57,0.2)] text-[#c4704e] shrink-0">
              <?php echo esc_html($badge); ?>
            </span>
          <?php endif; ?>
          <div class="flex items-center gap-3">
            <!-- Icon with glow -->
            <div class="relative shrink-0 icon-wrapper">
              <div class="absolute inset-0 w-12 h-12 rounded-xl bg-gradient-to-tr from-[rgba(155,80,57,0.2)] to-[rgba(216,181,130,0.1)] blur-xl opacity-60 animate-pulse-slow" aria-hidden="true"></div>
              <span class="relative inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[rgba(155,80,57,0.2)] text-[#c4704e] group-hover:scale-110 transition-transform duration-300">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 0;">
                  <?php echo esc_html($icon); ?>
                </span>
              </span>
            </div>
            <p class="text-base lg:text-lg font-medium text-[rgba(216,181,130,0.9)] tracking-[0.3px] !m-0 leading-relaxed">
              <?php echo esc_html($copy); ?>
            </p>
          </div>
        </div>

        <!-- Premium CTA Button -->
        <a href="<?php echo $cta_url; ?>"
          class="account-prompt-cta group/btn relative inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-[14px] tracking-[1px] overflow-hidden transition-all duration-300 hover:-translate-y-0.5 shrink-0 w-fit"
          style="background: linear-gradient(135deg, #9b5039 0%, #c4704e 100%); color: #d8b582; box-shadow: 0 10px 20px -10px rgba(155,80,57,0.3);">
          <!-- Shimmer on hover -->
          <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:animate-shimmer pointer-events-none" aria-hidden="true"></span>
          <span class="relative z-10"><?php echo esc_html($cta_text); ?></span>
          <svg class="relative z-10 w-5 h-5 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </a>
      </div>
    </div>
  </div>
</section>
