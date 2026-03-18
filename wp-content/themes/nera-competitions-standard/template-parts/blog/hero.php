<?php
/**
 * Blog Hero Section
 *
 * @package Nera_Competitions
 */

$title = $args['title'] ?? get_the_title(get_option('page_for_posts', true));
$description = $args['description'] ?? '';
$is_single = $args['is_single'] ?? false;
?>

<section class="relative pt-18 pb-18 lg:pt-32 lg:pb-32 overflow-hidden bg-off-white border-b border-border">
  <!-- Background Decor -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-[10%] -right-[5%] w-[40%] h-[60%] bg-sage/5 blur-[120px] rounded-full"></div>
    <div class="absolute -bottom-[10%] -left-[5%] w-[30%] h-[50%] bg-ink/5 blur-[100px] rounded-full"></div>
  </div>

  <div class="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 text-center">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
      <?php if (!$is_single): ?>
        <span
          class="inline-block px-4 py-1.5 rounded-full border border-border text-ink text-xs font-semibold tracking-widest uppercase mb-6">
          <?php _e('The Journal', 'nera-competitions'); ?>
        </span>
      <?php endif; ?>

      <h1 class="text-5xl lg:text-7xl font-heading font-bold text-ink mb-8 leading-[1.1]">
        <?php echo esc_html($title); ?>
      </h1>

      <?php if ($description): ?>
        <p class="text-lg lg:text-xl text-border6 font-body max-w-2xl leading-relaxed">
          <?php echo esc_html($description); ?>
        </p>
      <?php endif; ?>
    </div>
  </div>
</section>