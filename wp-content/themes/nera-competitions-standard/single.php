<?php
/**
 * The template for displaying single posts
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();
?>

<main id="primary" class="site-main bg-earthy-bg min-h-screen !max-w-none !mx-0 !pt-0">

  <?php while (have_posts()):
    the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <!-- Premium Post Header -->
      <header class="relative min-h-[60vh] flex items-center py-16 lg:py-24 overflow-hidden">
        <?php if (has_post_thumbnail()): ?>
          <div class="absolute inset-0 z-0">
            <?php the_post_thumbnail('full', [
              'class' => 'w-full h-full object-cover opacity-30',
            ]); ?>
          </div>
        <?php else: ?>
          <div class="absolute inset-0 bg-earthy-surface opacity-50 z-0"></div>
        <?php endif; ?>

        <div class="max-w-7xl mx-auto px-6 lg:px-20 relative z-10 w-full">
          <div class="max-w-4xl" data-aos="fade-up">
            <div class="flex flex-wrap items-center gap-4 mb-8">
              <?php
              $categories = get_the_category();
              foreach ($categories as $category) {
                echo '<a href="' .
                  esc_url(get_category_link($category->term_id)) .
                  '" class="px-3 py-1 rounded-full bg-earthy-terracotta/20 border border-earthy-terracotta/30 text-[10px] font-bold uppercase tracking-widest text-earthy-terracotta hover:bg-earthy-terracotta hover:text-white transition-all">' .
                  esc_html($category->name) .
                  '</a>';
              }
              ?>
              <span class="w-1.5 h-1.5 rounded-full bg-earthy-bronze-20"></span>
              <span class="text-xs font-bold text-earthy-bronze-40 uppercase tracking-widest">
                <?php echo sprintf(
                  __('%s min read', 'nera-competitions'),
                  nera_get_reading_time(get_the_content()),
                ); ?>
              </span>
            </div>

            <?php the_title(
              '<h1 class="text-5xl md:text-7xl font-heading font-bold text-earthy-bronze mb-8 leading-[1.1]">',
              '</h1>',
            ); ?>

            <div class="flex items-center gap-6 text-earthy-bronze-56">
              <div class="flex items-center gap-3">
                <?php echo get_avatar(get_the_author_meta('ID'), 48, '', '', [
                  'class' => 'rounded-full border border-earthy-bronze-20',
                ]); ?>
                <div class="flex flex-col">
                  <span
                    class="text-xs font-bold text-earthy-terracotta uppercase tracking-wider mb-0.5"><?php _e(
                      'Written by',
                      'nera-competitions',
                    ); ?></span>
                  <span class="text-base font-semibold text-earthy-bronze"><?php the_author(); ?></span>
                </div>
              </div>
              <div class="h-10 w-px bg-earthy-bronze-10"></div>
              <div class="flex flex-col">
                <span
                  class="text-xs font-bold text-earthy-terracotta uppercase tracking-wider mb-0.5"><?php _e(
                    'Published',
                    'nera-competitions',
                  ); ?></span>
                <time class="text-base font-semibold text-earthy-bronze"
                  datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                  <?php echo get_the_date(); ?>
                </time>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <div class="max-w-7xl mx-auto px-6 lg:px-20 pb-24 pt-0 lg:pt-24">
        <?php get_template_part('template-parts/blog/content'); ?>

        <!-- Post Navigation -->
        <nav class="grid md:grid-cols-2 gap-6 mt-24">
          <?php
          $prev_post = get_previous_post();
          $next_post = get_next_post();
          ?>

          <div class="nav-prev">
            <?php if ($prev_post): ?>
              <a href="<?php echo get_permalink($prev_post); ?>"
                class="group flex flex-col h-full p-8 rounded-3xl bg-earthy-surface border border-earthy-bronze-10 hover:border-earthy-terracotta transition-all duration-300">
                <span
                  class="text-xs font-bold text-earthy-terracotta uppercase tracking-widest mb-4 flex items-center gap-2">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                  </svg>
                  <?php _e('Previous Article', 'nera-competitions'); ?>
                </span>
                <h4
                  class="text-xl font-heading font-bold text-earthy-bronze group-hover:text-earthy-terracotta transition-colors line-clamp-2">
                  <?php echo get_the_title($prev_post); ?>
                </h4>
              </a>
            <?php endif; ?>
          </div>

          <div class="nav-next text-right">
            <?php if ($next_post): ?>
              <a href="<?php echo get_permalink($next_post); ?>"
                class="group flex flex-col h-full p-8 rounded-3xl bg-earthy-surface border border-earthy-bronze-10 hover:border-earthy-terracotta transition-all duration-300">
                <span
                  class="text-xs font-bold text-earthy-terracotta uppercase tracking-widest mb-4 flex items-center justify-end gap-2">
                  <?php _e('Next Article', 'nera-competitions'); ?>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                  </svg>
                </span>
                <h4
                  class="text-xl font-heading font-bold text-earthy-bronze group-hover:text-earthy-terracotta transition-colors line-clamp-2">
                  <?php echo get_the_title($next_post); ?>
                </h4>
              </a>
            <?php endif; ?>
          </div>
        </nav>
      </div>

    </article>

    <!-- Related Posts Section -->
    <?php get_template_part('template-parts/blog/related'); ?>

  <?php
  endwhile; ?>

</main>


<?php get_footer();
