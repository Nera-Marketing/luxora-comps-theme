<?php
/**
 * Quick Guide - How To Enter Section
 *
 * Editorial-style 3-step guide matching LiveLifePrizes Concept B: Earthy & Editorial.
 * Left-aligned layout with tag, large faded step numbers, and per-step CTA links.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Quick guide steps
$steps = get_field('guide_steps');

if (empty($steps)) {
  $steps = [
    [
      'number' => '01',
      'title' => __('Create a Free Account', 'nera-competitions'),
      'description' => __(
        "Sign up in seconds — you'll need an account to enter any of our prize draws.",
        'nera-competitions',
      ),
      'step_link' => [
        'url' => home_url('/my-account/'),
        'title' => __('Create account', 'nera-competitions'),
        'target' => '',
      ],
    ],
    [
      'number' => '02',
      'title' => __('Pick Your Prizes', 'nera-competitions'),
      'description' => __(
        'Browse live competitions and answer the question correctly to qualify your entry.',
        'nera-competitions',
      ),
      'step_link' => [
        'url' => home_url('/shop/'),
        'title' => __('Browse prizes', 'nera-competitions'),
        'target' => '',
      ],
    ],
    [
      'number' => '03',
      'title' => __('Watch the Live Draw', 'nera-competitions'),
      'description' => __(
        'Secure your tickets and join us on socials for the fully transparent live draw.',
        'nera-competitions',
      ),
      'step_link' => [
        'url' => '#',
        'title' => __('More info', 'nera-competitions'),
        'target' => '',
      ],
    ],
  ];
}

$guide_tag = get_field('guide_tag') ?: __('Simple & Fair', 'nera-competitions');
$guide_title = get_field('guide_title') ?: __('How To Enter', 'nera-competitions');
$guide_subtitle =
  get_field('guide_subtitle') ?:
  __('Win your dream prizes in just three simple steps', 'nera-competitions');
?>

<section class="how-to-enter-section py-16 lg:py-24 bg-[#1a1815] relative" id="quick-guide"
  data-aos="fade-up">
  <!-- Optional radial gradient overlay -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_50%,rgba(155,80,57,0.06),transparent_70%)]"></div>
  </div>

  <div class="how-to-enter-inner max-w-[1200px] mx-auto px-6 lg:px-8 relative z-10">

    <!-- Section Header (left-aligned) -->
    <div class="text-left mb-12 lg:mb-16">
      <?php if ($guide_tag): ?>
        <span class="how-to-enter-tag inline-block px-4 py-1.5 mb-4 text-[11px] font-medium uppercase tracking-[2px] rounded-[20px] bg-[rgba(155,80,57,0.2)] text-[#c4704e]">
          <?php echo esc_html($guide_tag); ?>
        </span>
      <?php endif; ?>
      <h2 class="font-heading text-3xl lg:text-4xl xl:text-[44px] font-normal text-[#d8b582] mb-2">
        <?php echo esc_html($guide_title); ?>
      </h2>
      <p class="text-lg text-[rgba(216,181,130,0.56)]">
        <?php echo esc_html($guide_subtitle); ?>
      </p>
    </div>

    <!-- Steps Grid (flat columns with dividers) -->
    <div class="how-to-enter-steps grid grid-cols-1 md:grid-cols-3">
      <?php foreach ($steps as $index => $step): ?>
        <div class="how-step how-step-<?php echo (int) $index +
          1; ?> py-8 px-0 lg:px-10 border-b border-[rgba(216,181,130,0.06)] last:border-b-0 md:border-b-0 md:border-r md:last:border-r-0">
          <div class="how-step-num font-heading text-[72px] font-normal leading-none mb-5 text-[rgba(216,181,130,0.06)]">
            <?php echo esc_html($step['number']); ?>
          </div>
          <h3 class="text-lg font-medium text-[#d8b582] mb-3 tracking-[0.3px]">
            <?php echo esc_html($step['title']); ?>
          </h3>
          <p class="text-sm text-[rgba(216,181,130,0.56)] leading-relaxed">
            <?php echo esc_html($step['description']); ?>
          </p>
          <?php
          $link = $step['step_link'] ?? [];
          $link_url = $link['url'] ?? '';
          $link_title = $link['title'] ?? '';
          $link_target = !empty($link['target']) ? $link['target'] : '';
          if ($link_url): ?>
            <a href="<?php echo esc_url($link_url); ?>"
              class="how-step-link inline-block mt-3 text-[13px] font-normal !text-[#c4704e] hover:text-[#d8b582] transition-colors"
              <?php echo $link_target
                ? ' target="' . esc_attr($link_target) . '" rel="noopener"'
                : ''; ?>>
              <?php echo esc_html($link_title ?: $link_url); ?> &rarr;
            </a>
          <?php endif;
          ?>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
