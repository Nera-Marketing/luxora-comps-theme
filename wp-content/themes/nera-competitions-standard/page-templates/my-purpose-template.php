<?php
/**
 * Template Name: My Purpose
 * Template Post Type: page
 *
 * A dedicated personal page for JJ's story.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();

// Get ACF fields
$title = get_field('my_purpose_title') ?: get_the_title();
$hero_image = get_field('my_purpose_hero_image');
$narrative = get_field('my_purpose_narrative');
$health_title = get_field('health_journey_title') ?: __('My Health Journey', 'nera-competitions');
$health_content = get_field('health_journey_content');
$autism_title = get_field('autism_diagnosis_title') ?: __('Autism Diagnosis', 'nera-competitions');
$autism_content = get_field('autism_diagnosis_content');

// Community CTA fields
$cta_heading = get_field('community_cta_heading') ?: __('Join Our Community', 'nera-competitions');
$cta_description = get_field('community_cta_description') ?: __('Be a part of a transparent, supportive, and exciting journey where everyone has a chance to change their life.', 'nera-competitions');
$cta_primary_text = get_field('community_cta_primary_btn_text') ?: __('Explore Competitions', 'nera-competitions');
$cta_primary_url = get_field('community_cta_primary_btn_url') ?: home_url('/shop/');
$cta_secondary_text = get_field('community_cta_secondary_btn_text') ?: __('Get in Touch', 'nera-competitions');
$cta_secondary_url = get_field('community_cta_secondary_btn_url') ?: home_url('/contact/');
?>

<main id="main" class="my-purpose-page bg-off-white text-ink font-body" role="main">

  <!-- Hero Section -->
  <section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden py-20"
    style="background: var(--color-forest); border-bottom: 1px solid rgba(200,230,192,0.15);">
    <!-- Background glow blobs -->
    <div class="absolute inset-0 z-0 pointer-events-none">
      <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full blur-[120px]"
        style="background: rgba(200,230,192,0.12);"></div>
      <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full blur-[120px]"
        style="background: rgba(107,140,107,0.2);"></div>
    </div>
    <!-- Radial glow overlay -->
    <div class="absolute inset-0 pointer-events-none"
      style="background: radial-gradient(ellipse at top right, rgba(107,140,107,0.3) 0%, transparent_60%);"></div>

    <div class="max-w-7xl mx-auto px-4 lg:px-20 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div data-aos="fade-up">
          <span
            class="inline-block text-mint uppercase tracking-[3px] text-xs font-semibold mb-6 pb-1"
            style="border-bottom: 1px solid rgba(200,230,192,0.3);">
            <?php _e('The Story Behind Nera', 'nera-competitions'); ?>
          </span>
          <h1 class="font-heading text-5xl lg:text-7xl leading-[1.1] mb-8 text-white">
            <?php echo esc_html($title); ?>
          </h1>
          <div class="w-20 h-1 bg-mint mb-8"></div>
          <p class="text-xl leading-relaxed font-light italic max-w-xl"
            style="color: rgba(200,230,192,0.75);">
            <?php _e('Building a community rooted in resilience, transparency, and the pursuit of a better life for everyone.', 'nera-competitions'); ?>
          </p>
        </div>

        <div class="relative" data-aos="fade-left" data-aos-delay="200">
          <?php if ($hero_image): ?>
            <?php
            $img_url = is_array($hero_image) ? $hero_image['url'] : $hero_image;
            $img_alt = is_array($hero_image) ? $hero_image['alt'] : '';
            ?>
            <div class="aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl relative">
              <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>"
                class="w-full !h-full object-cover">
              <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-2xl"></div>
            </div>
          <?php else: ?>
            <!-- Placeholder for JJ's photo -->
            <div
              class="aspect-[4/5] rounded-2xl flex items-center justify-center relative shadow-2xl overflow-hidden group"
              style="background: rgba(200,230,192,0.08); border: 1px solid rgba(200,230,192,0.15);">
              <div class="absolute inset-0 bg-gradient-to-tr from-sage/20 to-transparent"></div>
              <span class="text-sm italic group-hover:scale-110 transition-transform duration-700"
                style="color: rgba(200,230,192,0.3);">
                <?php _e('JJ\'s Portrait', 'nera-competitions'); ?>
              </span>
            </div>
          <?php endif; ?>

          <!-- Decorative frame -->
          <div
            class="absolute -bottom-6 -right-6 w-full h-full rounded-2xl -z-10 hidden lg:block"
            style="border: 1px solid rgba(200,230,192,0.2);">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Narrative Section -->
  <section class="py-24 border-y border-border relative">
    <div class="max-w-4xl mx-auto px-4 lg:px-20">
      <div
        class="rich-text max-w-none text-ink-soft [&_blockquote]:border-sage [&_blockquote]:text-ink [&_blockquote]:italic [&_strong]:text-ink [&_h1]:text-ink [&_h2]:text-ink [&_h3]:text-ink"
        data-aos="fade-up">
        <?php if ($narrative): ?>
          <?php echo $narrative; ?>
        <?php else: ?>
          <p class="text-center italic opacity-50">
            <?php _e('JJ\'s story is coming soon...', 'nera-competitions'); ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Health Journey & Autism Diagnosis -->
  <section class="py-24 bg-off-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 lg:px-20">
      <div class="grid md:grid-cols-2 gap-16">

        <!-- Health Journey -->
        <div
          class="bg-white p-10 lg:p-16 rounded-3xl border border-border shadow-xl transition-all duration-500 hover:border-sage/40 hover:shadow-sage/5 group"
          data-aos="fade-right">
          <div class="flex items-center gap-4 mb-8">
            <div
              class="w-12 h-12 rounded-full bg-sage/10 flex items-center justify-center text-sage group-hover:bg-sage group-hover:text-white transition-all duration-500">
              <span class="material-symbols-outlined">health_and_safety</span>
            </div>
            <h2 class="font-heading text-3xl text-ink">
              <?php echo esc_html($health_title); ?>
            </h2>
          </div>
          <div class="text-ink-soft leading-relaxed rich-text max-w-none">
            <?php if ($health_content): ?>
              <?php echo $health_content; ?>
            <?php else: ?>
              <p>
                <?php _e('Details about the health journey will be shared here, focusing on the path to healing and strength.', 'nera-competitions'); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Autism Diagnosis -->
        <div
          class="bg-white p-10 lg:p-16 rounded-3xl border border-border shadow-xl transition-all duration-500 hover:border-sage/40 hover:shadow-sage/5 group"
          data-aos="fade-left">
          <div class="flex items-center gap-4 mb-8">
            <div
              class="w-12 h-12 rounded-full bg-sage/10 flex items-center justify-center text-sage group-hover:bg-sage group-hover:text-white transition-all duration-500">
              <span class="material-symbols-outlined">psychology</span>
            </div>
            <h2 class="font-heading text-3xl text-ink">
              <?php echo esc_html($autism_title); ?>
            </h2>
          </div>
          <div class="text-ink-soft leading-relaxed rich-text max-w-none">
            <?php if ($autism_content): ?>
              <?php echo $autism_content; ?>
            <?php else: ?>
              <p>
                <?php _e('Insights into the autism diagnosis journey, building understanding and community support.', 'nera-competitions'); ?>
              </p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Community Call to Action -->
  <section class="py-24 text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-sage/5 to-transparent"></div>
    <div class="max-w-3xl mx-auto px-4 lg:px-20 relative z-10" data-aos="zoom-in">
      <h2 class="font-heading text-4xl lg:text-5xl mb-8"><?php echo esc_html($cta_heading); ?></h2>
      <p class="text-xl text-ink-soft mb-10 leading-relaxed">
        <?php echo esc_html($cta_description); ?>
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="<?php echo esc_url($cta_primary_url); ?>"
          class="px-8 py-4 bg-forest text-white font-semibold rounded-2xl hover:bg-sage transition-colors shadow-lg shadow-forest/20">
          <?php echo esc_html($cta_primary_text); ?>
        </a>
        <a href="<?php echo esc_url($cta_secondary_url); ?>"
          class="px-8 py-4 border border-forest/30 text-ink font-semibold rounded-2xl hover:bg-sage/10 transition-colors">
          <?php echo esc_html($cta_secondary_text); ?>
        </a>
      </div>
    </div>
  </section>

</main>

<style>
  .my-purpose-page .rich-text p {
    margin-bottom: 1.5em;
  }
</style>

<?php get_footer(); ?>