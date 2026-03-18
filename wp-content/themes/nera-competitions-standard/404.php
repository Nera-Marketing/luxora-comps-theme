<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

get_header();
?>

<main id="primary" class="site-main bg-[#0c0b09] min-h-screen flex items-center">
    <div class="container mx-auto px-4 py-16">

        <div class="max-w-2xl mx-auto text-center">

            <div class="mb-8">
                <span class="text-9xl font-bold text-[rgba(155,80,57,0.2)]">404</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-[#d8b582] mb-4">
                <?php esc_html_e('Page Not Found', 'nera-competitions'); ?>
            </h1>

            <p class="text-xl text-[rgba(216,181,130,0.56)] mb-8">
                <?php esc_html_e(
                  'Oops! The page you\'re looking for doesn\'t exist or has been moved.',
                  'nera-competitions',
                ); ?>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="<?php echo esc_url(
                  home_url('/'),
                ); ?>" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-[#9b5039] to-[#c4704e] text-[#d8b582] font-semibold rounded-lg hover:opacity-90 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <?php esc_html_e('Back to Home', 'nera-competitions'); ?>
                </a>

                <button onclick="history.back()" class="inline-flex items-center justify-center px-8 py-4 bg-[#1e1c18] text-[#d8b582] font-semibold rounded-lg border border-[rgba(216,181,130,0.2)] hover:bg-[rgba(216,181,130,0.05)] transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <?php esc_html_e('Go Back', 'nera-competitions'); ?>
                </button>
            </div>

            <div class="bg-[#1e1c18] rounded-2xl p-8 border border-[rgba(216,181,130,0.2)]">
                <h2 class="text-xl font-bold text-[#d8b582] mb-4">
                    <?php esc_html_e('Try searching instead', 'nera-competitions'); ?>
                </h2>

                <form role="search" method="get" class="flex gap-2" action="<?php echo esc_url(
                  home_url('/'),
                ); ?>">
                    <input
                        type="search"
                        class="flex-1 px-4 py-3 border border-[rgba(216,181,130,0.2)] bg-[#0c0b09] text-[#d8b582] rounded-lg focus:outline-none focus:ring-2 focus:ring-[rgba(216,181,130,0.2)] focus:border-transparent"
                        placeholder="<?php esc_attr_e('Search...', 'nera-competitions'); ?>"
                        name="s"
                    >
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#9b5039] to-[#c4704e] text-[#d8b582] font-semibold rounded-lg hover:opacity-90 transition-colors">
                        <?php esc_html_e('Search', 'nera-competitions'); ?>
                    </button>
                </form>
            </div>

        </div>

    </div>
</main>

<?php get_footer();
