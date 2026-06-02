<?php
/**
 * Blog Post Content
 *
 * @package Nera_Competitions
 */

$is_single = is_singular('post');
?>

<div class="blog-content-wrapper mb-16" data-aos="fade-up">
  <div class="prose prose-lg max-w-none 
                prose-headings:font-heading prose-headings:font-bold prose-headings:text-ink
                prose-p:font-body prose-p:leading-relaxed
                prose-a:text-sage prose-a:no-underline hover:prose-a:text-ink transition-colors
                prose-strong:text-ink prose-strong:font-bold
                prose-blockquote:border-l-4 prose-blockquote:border-sage prose-blockquote:bg-white prose-blockquote:py-2 prose-blockquote:px-8 prose-blockquote:italic prose-blockquote:rounded-r-xl prose-blockquote:text-ink/80
                prose-img:rounded-3xl prose-img:border prose-img:border-border
                prose-li:text-ink-soft
                selection:bg-sage/30">
    <?php the_content(); ?>
  </div>

  <?php if ($is_single): ?>
    <footer class="mt-16 pt-12 border-t border-border flex flex-wrap items-center justify-between gap-8">
      <!-- Author Card -->
      <div class="flex items-center gap-4">
        <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', [
          'class' => 'rounded-full border-2 border-sage/20 p-1'
        ]); ?>
        <div>
          <span class="block text-xs font-bold text-sage uppercase tracking-widest mb-1">
            <?php _e('Published By', 'nera-competitions'); ?>
          </span>
          <h4 class="text-xl font-heading font-bold text-ink">
            <?php the_author(); ?>
          </h4>
        </div>
      </div>

      <!-- Tags -->
      <?php if (has_tag()): ?>
        <div class="flex flex-wrap gap-2">
          <?php
          $tags = get_the_tags();
          foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="px-4 py-2 rounded-full bg-white border border-border text-xs font-medium text-ink-soft hover:border-sage hover:text-sage transition-all">#' . esc_html($tag->name) . '</a>';
          }
          ?>
        </div>
      <?php endif; ?>
    </footer>
  <?php endif; ?>
</div>