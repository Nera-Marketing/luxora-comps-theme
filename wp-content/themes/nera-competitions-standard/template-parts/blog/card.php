<?php
/**
 * Blog Post Card
 *
 * @package Nera_Competitions
 */

$is_featured = $args['is_featured'] ?? false;
$classes = $is_featured ? 'lg:col-span-3 grid lg:grid-cols-2' : 'flex flex-col';
?>

<article <?php post_class('group relative overflow-hidden rounded-3xl bg-white border border-border hover:border-ink-30 transition-all duration-500 ' . $classes); ?> data-aos="fade-up"
  data-aos-delay="<?php echo esc_attr($args['delay'] ?? 0); ?>">

  <!-- Image Wrapper -->
  <div class="relative overflow-hidden <?php echo $is_featured ? 'aspect-[4/3] lg:aspect-auto' : 'aspect-[16/10]'; ?>">
    <?php if (has_post_thumbnail()): ?>
      <a href="<?php the_permalink(); ?>" class="block w-full h-full">
        <?php the_post_thumbnail('large', [
          'class' => 'w-full !h-full object-cover transition-transform duration-700 group-hover:scale-110'
        ]); ?>
      </a>
    <?php else: ?>
      <div class="w-full h-full bg-white-alt flex items-center justify-center">
        <span class="text-border font-heading italic text-xl">Nera Journal</span>
      </div>
    <?php endif; ?>

    <!-- Category Tag -->
    <div class="absolute top-6 left-6 flex gap-2">
      <?php
      $categories = get_the_category();
      if ($categories): ?>
        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>"
          class="px-3 py-1 rounded-full bg-off-white/80 backdrop-blur-md border border-border text-[10px] font-bold uppercase tracking-widest text-ink hover:bg-sage hover:text-white transition-colors">
          <?php echo esc_html($categories[0]->name); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Content -->
  <div class="p-8 lg:p-10 flex flex-col justify-center">
    <div class="flex items-center gap-4 mb-4 text-xs font-medium text-ink-40 tracking-wide uppercase">
      <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
        <?php echo get_the_date(); ?>
      </time>
      <span class="w-1 h-1 rounded-full bg-border"></span>
      <span>
        <?php echo sprintf(__('%s min read', 'nera-competitions'), nera_get_reading_time(get_the_content())); ?>
      </span>
    </div>

    <h2
      class="<?php echo $is_featured ? 'text-3xl lg:text-5xl' : 'text-2xl'; ?> font-heading font-bold text-ink mb-4 leading-tight group-hover:text-sage transition-colors">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>

    <div
      class="text-ink-56 font-body <?php echo $is_featured ? 'text-lg line-clamp-4' : 'text-sm line-clamp-3'; ?> mb-8 leading-relaxed">
      <?php echo wp_trim_words(get_the_excerpt(), $is_featured ? 40 : 25); ?>
    </div>

    <div class="mt-auto">
      <a href="<?php the_permalink(); ?>"
        class="inline-flex items-center gap-2 group/btn font-bold text-sm text-ink hover:text-sage transition-colors">
        <span>
          <?php _e('Read Article', 'nera-competitions'); ?>
        </span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          class="transition-transform duration-300 group-hover/btn:translate-x-1">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>
</article>