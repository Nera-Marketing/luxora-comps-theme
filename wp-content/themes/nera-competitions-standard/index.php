<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();

// Get the blog page title and description
$blog_id = get_option('page_for_posts');
$title = $blog_id ? get_the_title($blog_id) : __('The Journal', 'nera-competitions');
$description = $blog_id ? get_post_field('post_content', $blog_id) : __('Insights, updates, and stories from the world of premium competitions.', 'nera-competitions');

if (is_search()) {
  $title = sprintf(__('Search Results: %s', 'nera-competitions'), get_search_query());
  $description = '';
}
?>

<main id="primary" class="site-main bg-earthy-bg min-h-screen !max-w-none !mx-0 !pt-0">

  <!-- Hero Section -->
  <?php get_template_part('template-parts/blog/hero', null, array(
    'title' => $title,
    'description' => $description
  )); ?>

  <div class="max-w-7xl mx-auto px-6 lg:px-20 pb-24 mt-12 lg:mt-20 relative z-20">

    <?php if (have_posts()): ?>

      <div class="grid lg:grid-cols-3 gap-10">
        <?php
        $counter = 0;
        while (have_posts()):
          the_post();

          // Show a featured card for the first post on the first page (only if not searching)
          $is_featured = ($counter === 0 && !is_paged() && !is_search());

          get_template_part('template-parts/blog/card', null, array(
            'is_featured' => $is_featured,
            'delay' => $counter * 100
          ));

          $counter++;
        endwhile;
        ?>
      </div>

      <!-- Enhanced Pagination -->
      <nav class="mt-20 flex justify-center gap-4" data-aos="fade-up">
        <?php
        echo paginate_links(array(
          'prev_text' => '<span class="flex items-center gap-2 px-6 py-3 rounded-xl bg-earthy-surface border border-earthy-bronze-20 text-earthy-bronze hover:border-earthy-terracotta transition-all">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                        ' . __('Previous', 'nera-competitions') . '
                                    </span>',
          'next_text' => '<span class="flex items-center gap-2 px-6 py-3 rounded-xl bg-earthy-surface border border-earthy-bronze-20 text-earthy-bronze hover:border-earthy-terracotta transition-all">
                                        ' . __('Next', 'nera-competitions') . '
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </span>',
          'type' => 'plain',
          'end_size' => 1,
          'mid_size' => 2,
        ));
        ?>
      </nav>

    <?php else: ?>

      <div class="text-center py-32 bg-earthy-surface border border-earthy-bronze-10 rounded-[40px]" data-aos="fade-up">
        <div class="max-w-md mx-auto px-6">
          <div class="w-20 h-20 bg-earthy-bronze-5 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-earthy-bronze-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="1.5">
              <path
                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
          </div>
          <h2 class="text-3xl font-heading font-bold text-earthy-bronze mb-4">
            <?php _e('Silence in the Journal', 'nera-competitions'); ?>
          </h2>
          <p class="text-earthy-bronze-56 mb-10">
            <?php _e('We couldn\'t find any articles matching your request. Perhaps try a different search or explore our latest stories.', 'nera-competitions'); ?>
          </p>
          <a href="<?php echo home_url('/'); ?>"
            class="inline-flex items-center gap-2 bg-earthy-terracotta text-white px-8 py-4 rounded-2xl font-bold hover:brightness-110 transition-all">
            <?php _e('Return to Home', 'nera-competitions'); ?>
          </a>
        </div>
      </div>

    <?php endif; ?>

  </div>
</main>


<?php get_footer();
