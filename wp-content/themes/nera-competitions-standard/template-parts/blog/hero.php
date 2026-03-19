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

<section class="relative pt-18 pb-18 lg:pt-32 lg:pb-32 overflow-hidden"
  style="background: var(--color-forest); border-bottom: 1px solid rgba(200,230,192,0.15);">
  <!-- Background Decor -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-[10%] -right-[5%] w-[40%] h-[60%] rounded-full blur-[120px]"
      style="background: rgba(200,230,192,0.12);"></div>
    <div class="absolute -bottom-[10%] -left-[5%] w-[30%] h-[50%] rounded-full blur-[100px]"
      style="background: rgba(107,140,107,0.2);"></div>
  </div>
  <!-- Radial glow overlay -->
  <div class="absolute inset-0 pointer-events-none"
    style="background: radial-gradient(ellipse at top right, rgba(107,140,107,0.3) 0%, transparent 60%);"></div>

  <div class="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 text-center">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
      <?php if (!$is_single): ?>
        <span
          class="inline-block px-4 py-1.5 rounded-full text-mint text-xs font-semibold tracking-widest uppercase mb-6"
          style="border: 1px solid rgba(200,230,192,0.3);">
          <?php _e('The Journal', 'nera-competitions'); ?>
        </span>
      <?php endif; ?>

      <h1 class="text-5xl lg:text-7xl font-heading font-bold text-white mb-8 leading-[1.1]">
        <?php echo esc_html($title); ?>
      </h1>

      <?php if ($description): ?>
        <p class="text-lg lg:text-xl font-body max-w-2xl leading-relaxed"
          style="color: rgba(200,230,192,0.7);">
          <?php echo esc_html($description); ?>
        </p>
      <?php endif; ?>
    </div>
  </div>
</section>