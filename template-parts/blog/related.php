<?php
/**
 * Related Posts Section
 *
 * @package Nera_Competitions
 */

$categories = get_the_category();
if (!$categories)
  return;

$args = array(
  'post_type' => 'post',
  'posts_per_page' => 3,
  'post__not_in' => array(get_the_ID()),
  'category__in' => array($categories[0]->term_id),
  'orderby' => 'rand'
);

$related_query = new WP_Query($args);

if ($related_query->have_posts()):
  ?>

  <section class="py-24 border-t border-border">
    <div class="max-w-7xl mx-auto px-6 lg:px-20">
      <div class="flex items-end justify-between mb-12" data-aos="fade-up">
        <div>
          <span class="inline-block text-xs font-bold text-sage uppercase tracking-[0.2em] mb-4">
            <?php _e('More to explore', 'nera-competitions'); ?>
          </span>
          <h3 class="text-4xl lg:text-5xl font-heading font-bold text-ink">
            <?php _e('Related Articles', 'nera-competitions'); ?>
          </h3>
        </div>
        <a href="<?php echo get_post_type_archive_link('post'); ?>"
          class="hidden md:flex items-center gap-2 group/all font-bold text-sm text-ink hover:text-sage transition-colors">
          <span>
            <?php _e('View Journal', 'nera-competitions'); ?>
          </span>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            class="transition-transform duration-300 group-hover/all:translate-x-1">
            <path d="M5 12h14M12 5l7 7-7 7" />
          </svg>
        </a>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $count = 0;
        while ($related_query->have_posts()):
          $related_query->the_post();
          get_template_part('template-parts/blog/card', null, array(
            'delay' => $count * 100
          ));
          $count++;
        endwhile;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>

<?php endif; ?>