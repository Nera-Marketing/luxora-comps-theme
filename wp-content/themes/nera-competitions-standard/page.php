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

$is_cart = function_exists('is_cart') && is_cart();
$is_checkout = function_exists('is_checkout') && is_checkout();
$is_account = function_exists('is_account_page') && is_account_page();
$is_account_logged_out = $is_account && !is_user_logged_in();
$is_empty_cart = $is_cart && function_exists('WC') && WC()->cart && WC()->cart->is_empty();
?>

<main id="primary" class="site-main <?php
if ($is_account_logged_out) {
  echo ' mx-auto min-h-[calc(100vh-120px)] flex flex-col relative overflow-hidden';
} elseif ($is_account) {
  echo 'bg-forest mx-auto min-h-screen';
} elseif ($is_cart || $is_checkout) {
  echo 'min-h-screen';
  if ($is_empty_cart) {
    echo ' flex flex-col';
  }
} else {
  echo 'bg-off-white min-h-screen';
}
if ($is_cart || $is_checkout) {
  echo ' !py-0';
}
?>">

    <?php while (have_posts()):
      the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(
  $is_account_logged_out
    ? ['flex', 'flex-1', 'flex-col', 'justify-center', 'min-h-0', 'w-full']
    : ($is_empty_cart
      ? ['flex', 'flex-1', 'flex-col', 'min-h-0', 'w-full']
      : []),
); ?>>

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
                      '<h1 class="text-4xl md:text-5xl font-bold text-ink">',
                      '</h1>',
                    ); ?>
                </header>
            <?php endif; ?>

            <div class="max-w-none<?php echo $is_empty_cart
              ? ' flex flex-1 flex-col min-h-0 w-full'
              : ''; ?>">
                <?php the_content(); ?>
            </div>

            <?php wp_link_pages([
              'before' =>
                '<div class="page-links mt-8 py-4 border-t border-border"><span class="text-ink font-semibold mr-4">' .
                esc_html__('Pages:', 'nera-competitions') .
                '</span>',
              'after' => '</div>',
              'link_before' =>
                '<span class="px-3 py-1 bg-mint/10 rounded hover:bg-mint/10 transition-colors text-ink">',
              'link_after' => '</span>',
            ]); ?>

            </article>
  
              <?php if (comments_open() || get_comments_number()) {
                echo '<div class="container mx-auto px-4 pb-12"><div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 border border-border">';
                comments_template();
                echo '</div></div>';
              } ?>

    <?php
    endwhile; ?>

</main>

<?php get_footer();
