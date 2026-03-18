<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();
?>

<main id="primary" class="site-main bg-[#0c0b09] min-h-screen<?php
  $is_cart = function_exists('is_cart') && is_cart();
  $is_checkout = function_exists('is_checkout') && is_checkout();
  if ($is_cart || $is_checkout) {
    echo ' !py-0';
  }
  if ($is_cart) {
    echo ' flex flex-col items-center justify-center';
  }
?>">

    <?php while (have_posts()):
      the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php if (has_post_thumbnail() && !is_front_page()): ?>
                <div class="w-full aspect-[21/9] overflow-hidden">
                    <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                </div>
            <?php endif; ?>

            <?php if (
              !is_front_page() &&
              !(function_exists('is_checkout') && is_checkout()) &&
              !(function_exists('is_cart') && is_cart())
            ): ?>
                <header class="mb-8">
                    <?php the_title(
                      '<h1 class="text-4xl md:text-5xl font-bold text-[#d8b582]">',
                      '</h1>',
                    ); ?>
                </header>
            <?php endif; ?>

            <div class="rich-text max-w-none">
                <?php the_content(); ?>
            </div>

            <?php wp_link_pages([
              'before' =>
                '<div class="page-links mt-8 py-4 border-t border-[rgba(216,181,130,0.2)]"><span class="text-[#d8b582] font-semibold mr-4">' .
                esc_html__('Pages:', 'nera-competitions') .
                '</span>',
              'after' => '</div>',
              'link_before' =>
                '<span class="px-3 py-1 bg-[rgba(216,181,130,0.1)] rounded hover:bg-[rgba(216,181,130,0.2)] transition-colors text-[#d8b582]">',
              'link_after' => '</span>',
            ]); ?>

            </article>
  
              <?php if (comments_open() || get_comments_number()) {
                echo '<div class="container mx-auto px-4 pb-12"><div class="max-w-4xl mx-auto bg-[#1e1c18] rounded-2xl p-8 border border-[rgba(216,181,130,0.2)]">';
                comments_template();
                echo '</div></div>';
              } ?>

    <?php
    endwhile; ?>

</main>

<?php get_footer();
