<?php
/**
 * Template Name: How It Works Page
 * Template Post Type: page
 *
 * A comprehensive guide to the draw process, entries, and fairness.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();

// Get ACF fields for the page
$hero_title = get_field('hiw_hero_title') ?: get_the_title();
$hero_subtitle = get_field('hiw_hero_subtitle') ?: __('Win your dream prizes in just 4 simple steps', 'nera-competitions');
$hero_badge = get_field('hiw_hero_badge') ?: __('Simple & Fair', 'nera-competitions');

// Draw Process Section
$draw_title = get_field('hiw_draw_title') ?: __('The Draw Process', 'nera-competitions');
$draw_content = get_field('hiw_draw_content');
$draw_image = get_field('hiw_draw_image');
$draw_image_url = '';
$draw_image_alt = '';
if (is_array($draw_image) && !empty($draw_image['url'])) {
  $draw_image_url = $draw_image['url'];
  $draw_image_alt = isset($draw_image['alt']) ? $draw_image['alt'] : '';
} elseif (is_string($draw_image) && $draw_image !== '') {
  $draw_image_url = $draw_image;
  $draw_image_alt = '';
}

// Postal Entry Section
$postal_title = get_field('hiw_postal_title') ?: __('Free Postal Entry Route', 'nera-competitions');
$acf_postal_steps = get_field('hiw_postal_steps');
$postal_note = get_field('hiw_postal_note');

$default_postal_steps = [
  [
    'number' => '1',
    'title' => __('Include Your Details', 'nera-competitions'),
    'icon' => 'contact_page',
    'text' => __('Send your name, address, date of birth, contact phone number, and the name of the competition you wish to enter.', 'nera-competitions'),
  ],
  [
    'number' => '2',
    'title' => __('Send Your Postcard', 'nera-competitions'),
    'icon' => 'mail',
    'text' => __('Send your entry on an unenclosed postcard via first or second class post to our registered business address.', 'nera-competitions'),
  ],
  [
    'number' => '3',
    'title' => __('We Process It', 'nera-competitions'),
    'icon' => 'verified_user',
    'text' => __('Once received, your entry will be processed and included in the draw just like a paid entry.', 'nera-competitions'),
  ],
];

$postal_steps = [];
if (!empty($acf_postal_steps) && is_array($acf_postal_steps)) {
  foreach ($acf_postal_steps as $i => $step) {
    if (!empty($step['text'])) {
      $default = isset($default_postal_steps[$i]) ? $default_postal_steps[$i] : $default_postal_steps[0];
      $postal_steps[] = [
        'number' => !empty($step['number']) ? $step['number'] : ($i + 1),
        'title' => !empty($step['title']) ? $step['title'] : $default['title'],
        'icon' => !empty($step['icon']) ? $step['icon'] : $default['icon'],
        'text' => $step['text'],
      ];
    }
  }
}
if (empty($postal_steps)) {
  $postal_steps = $default_postal_steps;
}

// Transparency Section
$trans_title = get_field('hiw_transparency_title') ?: __('Transparency & Fairness', 'nera-competitions');
$acf_trans_features = get_field('hiw_transparency_features');

$default_trans_features = [
  [
    'icon' => 'verified_user',
    'title' => __('Fully Insured', 'nera-competitions'),
    'description' => __('We are a legally registered UK business, fully insured and compliant with all regulations.', 'nera-competitions')
  ],
  [
    'icon' => 'diversity_3',
    'title' => __('Community Focused', 'nera-competitions'),
    'description' => __('Our mission is to support our community and provide life-changing opportunities for everyone.', 'nera-competitions')
  ],
  [
    'icon' => 'shield_with_heart',
    'title' => __('Secure & Safe', 'nera-competitions'),
    'description' => __('We use industry-standard security protocols to ensure your data and entries are always protected.', 'nera-competitions')
  ]
];

$trans_features = [];
if (!empty($acf_trans_features) && is_array($acf_trans_features)) {
  foreach ($acf_trans_features as $i => $feature) {
    $default = isset($default_trans_features[$i]) ? $default_trans_features[$i] : $default_trans_features[0];
    $trans_features[] = [
      'icon' => !empty($feature['icon']) ? $feature['icon'] : $default['icon'],
      'title' => !empty($feature['title']) ? $feature['title'] : $default['title'],
      'description' => !empty($feature['description']) ? $feature['description'] : $default['description'],
    ];
  }
} else {
  $trans_features = $default_trans_features;
}
?>

<main id="main" class="how-it-works-page font-body" role="main">

  <!-- Step-by-Step Entry Guide Section (Integrated from template part) -->
  <?php get_template_part('template-parts/how-it-works', null, [
    'title' => $hero_title,
    'subtitle' => $hero_subtitle,
  ]); ?>

  <!-- Detailed Draw Process Section -->
  <section class="py-20 lg:py-32 relative overflow-hidden separator-top">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div data-aos="fade-right">
          <span
            class="inline-block bg-sage/20 text-sage py-1.5 px-4 rounded-[20px] text-[11px] tracking-[2px] uppercase mb-6 font-medium">
            <?php _e('Fair & Transparent', 'nera-competitions'); ?>
          </span>
          <h2 class="font-heading text-4xl lg:text-5xl text-ink mb-8 leading-tight">
            <?php echo esc_html($draw_title); ?>
          </h2>
          <div class="max-w-none text-ink-soft leading-relaxed">
            <?php if ($draw_content): ?>
              <?php echo $draw_content; ?>
            <?php else: ?>
              <p>
                <?php _e('Our draws are conducted with absolute transparency. We use the <strong>Google Random Number Generator</strong> to ensure every entry has an equal and fair chance of winning.', 'nera-competitions'); ?>
              </p>
              <p>
                <?php _e('Join us live on our social media channels for every draw! We broadcast the entire process in real-time, announcing winners as they happen and celebrating with our community.', 'nera-competitions'); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
        <div class="relative" data-aos="fade-left">
          <?php if (!empty($draw_image_url)): ?>
            <div class="rounded-3xl overflow-hidden shadow-2xl border border-border">
              <img src="<?php echo esc_url($draw_image_url); ?>" alt="<?php echo esc_attr($draw_image_alt); ?>"
                class="w-full h-auto">
            </div>
          <?php else: ?>
            <div
              class="aspect-video bg-off-white rounded-3xl border border-border flex items-center justify-center">
              <div class="text-center p-8">
                <div
                  class="w-20 h-20 bg-sage/20 rounded-full flex items-center justify-center mx-auto mb-6">
                  <span class="material-symbols-outlined text-sage scale-150">videocam</span>
                </div>
                <h3 class="text-xl font-bold mb-2">
                  <?php _e('Live Draw Streams', 'nera-competitions'); ?>
                </h3>
                <p class="text-sm text-ink-soft">
                  <?php _e('Watch us live on Facebook and Instagram', 'nera-competitions'); ?>
                </p>
              </div>
            </div>
          <?php endif; ?>
          <!-- Decorative element -->
          <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-forest/20 blur-3xl rounded-full -z-10"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Free Postal Entry Section -->
  <section class="py-20 lg:py-32 bg-forest relative">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 relative z-10">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="font-heading text-4xl lg:text-5xl text-white mb-6">
          <?php echo esc_html($postal_title); ?>
        </h2>
        <p class="text-white/80 text-lg">
          <?php _e('We offer a free entry route via post for all of our competitions.', 'nera-competitions'); ?>
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
        <?php
        $delay = 0;
        foreach ($postal_steps as $step):
          $icon = isset($step['icon']) ? $step['icon'] : 'mail';
          $title = isset($step['title']) ? $step['title'] : '';
          ?>
        <div
          class="postal-entry-card relative group bg-white/95 backdrop-blur-sm border border-border rounded-3xl p-8 overflow-hidden hover:border-sage/40 group-hover:-translate-y-2"
          data-aos="fade-up"
          data-aos-delay="<?php echo esc_attr($delay); ?>">
          <!-- Shine Effect -->
          <div
            class="postal-entry-shine absolute inset-0 opacity-0 group-hover:opacity-100 overflow-hidden rounded-3xl pointer-events-none z-0">
            <div class="postal-entry-shine-inner absolute inset-0 bg-linear-to-r from-transparent via-white/25 to-transparent -translate-x-full group-hover:translate-x-full"></div>
          </div>
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-6">
              <div class="relative">
                <div
                  class="postal-entry-icon w-14 h-14 rounded-2xl bg-sage/15 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-sage/25">
                  <span class="material-symbols-outlined text-sage scale-150"><?php echo esc_html($icon); ?></span>
                </div>
                <div
                  class="absolute inset-0 w-14 h-14 rounded-2xl opacity-0 group-hover:opacity-100 bg-sage/15 animate-ping-slow pointer-events-none"></div>
              </div>
              <div
                class="postal-entry-badge w-10 h-10 rounded-full flex items-center justify-center font-heading font-bold text-sm shrink-0 rotate-12 group-hover:rotate-0"
                style="background: linear-gradient(135deg, var(--color-forest), var(--color-sage)); color: var(--color-mint);">
                <?php echo esc_html($step['number']); ?>
              </div>
            </div>
            <?php if ($title): ?>
              <h3 class="text-lg font-bold text-ink mb-3">
                <?php echo esc_html($title); ?>
              </h3>
            <?php endif; ?>
            <p class="text-ink-soft leading-relaxed text-sm">
              <?php echo esc_html($step['text']); ?>
            </p>
          </div>
        </div>
        <?php
          $delay += 100;
        endforeach;
        ?>
      </div>

      <div
        class="mt-12 p-6 bg-white/90 backdrop-blur-sm border border-sage/30 rounded-2xl flex items-center gap-4"
        data-aos="fade-up"
        data-aos-delay="<?php echo esc_attr($delay); ?>">
        <span class="material-symbols-outlined text-sage shrink-0 scale-125">info</span>
        <span class="text-sm text-ink-soft italic">
          <?php echo esc_html($postal_note ?: __('Please note: One entry per postcard. Entries must be received before the competition closes.', 'nera-competitions')); ?>
        </span>
      </div>
    </div>
  </section>

  <!-- Fairness & Trust Section -->
  <section class="py-20 lg:py-32 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 relative z-10">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="font-heading text-4xl lg:text-5xl text-ink mb-6">
          <?php echo esc_html($trans_title); ?>
        </h2>
        <p class="text-ink-soft text-lg max-w-2xl mx-auto">
          <?php _e('We pride ourselves on being a registered UK business that operates with full integrity and a passion for giving back.', 'nera-competitions'); ?>
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8">
        <?php foreach ($trans_features as $feature): ?>
          <div
            class="bg-off-white border border-border p-8 rounded-3xl hover:border-forest/40 transition-all duration-500"
            data-aos="fade-up">
            <div class="text-sage mb-6">
              <span class="material-symbols-outlined scale-150"><?php echo esc_html($feature['icon']); ?></span>
            </div>
            <h3 class="text-xl font-bold mb-4">
              <?php echo esc_html($feature['title']); ?>
            </h3>
            <p class="text-sm text-ink-soft leading-relaxed">
              <?php echo esc_html($feature['description']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>

<style>
  .how-it-works-page strong {
    color: var(--color-sage);
  }

  .separator-top {
    border-top: 1px solid rgba(216, 181, 130, 0.05);
  }

  /* Postal entry cards – glow pulse on hover */
  @keyframes postal-card-glow-pulse {
    0%,
    100% {
      box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.08),
        0 0 24px 0 rgba(107, 140, 107, 0.12),
        0 0 40px 0 rgba(107, 140, 107, 0.06);
    }
    50% {
      box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.08),
        0 0 32px 4px rgba(107, 140, 107, 0.18),
        0 0 56px 8px rgba(107, 140, 107, 0.08);
    }
  }

  /* Postal entry cards – UI Animation Skill compliant */
  @media (hover: hover) and (pointer: fine) {
    .postal-entry-card {
      transition:
        transform 250ms cubic-bezier(0.22, 1, 0.36, 1),
        box-shadow 250ms cubic-bezier(0.22, 1, 0.36, 1),
        border-color 200ms ease;
    }

    .postal-entry-card:hover {
      animation: postal-card-glow-pulse 2.2s ease-in-out infinite;
    }

    .postal-entry-card:active {
      transform: scale(0.98);
    }

    .postal-entry-shine {
      transition: opacity 300ms ease;
    }

    .postal-entry-shine-inner {
      transition: transform 600ms cubic-bezier(0.22, 1, 0.36, 1);
    }

    .postal-entry-icon {
      transition:
        transform 220ms cubic-bezier(0.22, 1, 0.36, 1),
        background-color 200ms ease;
    }

    .postal-entry-badge {
      transition: transform 250ms cubic-bezier(0.22, 1, 0.36, 1);
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .postal-entry-card,
    .postal-entry-card * {
      transition: none !important;
      animation: none !important;
    }

    .postal-entry-card:hover {
      transform: none;
      animation: none;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
    }
  }
</style>

<?php get_footer(); ?>