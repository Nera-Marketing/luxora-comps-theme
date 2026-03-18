<?php
/**
 * Winners Grid Template Part (Vue.js Hybrid)
 *
 * Displays winners in a filterable, paginated grid layout using Vue.js
 * with server-rendered initial content for SEO.
 *
 * Hybrid Strategy:
 * 1. Server-renders first page of winners for SEO
 * 2. Vue.js mounts and takes over for interactivity
 * 3. Additional data loaded via REST API
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Get ACF field values
$winners_list = get_field('winners_list');
$per_page = get_field('winners_per_page') ?: 12;
$show_filters = get_field('winners_show_filters');
$show_quotes = get_field('winners_show_quotes');

// Exit if no winners
if (!$winners_list || empty($winners_list)) { ?>
    <section class="py-16 px-5 sm:px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 mx-auto mb-4 text-ink-soft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 15l3.5-3.5L12 8" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <h3 class="text-xl font-bold text-ink mb-2">
                        <?php _e('No Winners Yet', 'nera-competitions'); ?>
                    </h3>
                    <p class="text-ink-soft">
                        <?php _e(
                          'Check back soon to see our lucky winners!',
                          'nera-competitions',
                        ); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <?php return;}

// Get first page of winners for server-rendered HTML (SEO)
$initial_winners = array_slice($winners_list, 0, $per_page);
?>

<section class="py-16 px-5 sm:px-6">
    <!-- Vue Mount Point -->
    <div id="winners-root"
         data-page-id="<?php echo esc_attr(get_the_ID()); ?>"
         data-per-page="<?php echo esc_attr($per_page); ?>"
         data-show-quotes="<?php echo esc_attr($show_quotes ? '1' : '0'); ?>">

        <!-- Server-rendered initial content for SEO -->
        <div class="winners-ssr-content">
            <div class="container mx-auto max-w-7xl">

                <?php if ($show_filters): ?>
                <!-- Filter Tabs (Static for SEO) -->
                <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
                    <button type="button" class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm bg-forest text-mint shadow-lg">
                        <span><?php _e('All Winners', 'nera-competitions'); ?></span>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Winners Grid (Server-rendered for SEO) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($initial_winners as $index => $winner):

                      $name = $winner['name'] ?? '';
                      $prize = $winner['prize'] ?? '';
                      $date = $winner['date'] ?? '';
                      $image = $winner['image'] ?? null;
                      $quote = $winner['quote'] ?? '';
                      $category = $winner['category'] ?? 'live-draw';

                      // Skip if missing required fields
                      if (empty($name) || empty($prize)) {
                        continue;
                      }

                      // Get image URL
                      $image_url = '';
                      if ($image && is_array($image)) {
                        $image_url = $image['sizes']['large'] ?? ($image['url'] ?? '');
                      }

                      // Category badge
                      $category_label =
                        $category === 'instant-win'
                          ? __('Instant Win', 'nera-competitions')
                          : __('Live Draw', 'nera-competitions');
                      $category_class = 'bg-ink/80 text-mint';
                      ?>

                    <!-- Winner Card (Server-rendered) -->
                    <article class="group bg-white rounded-3xl overflow-hidden transition-all duration-300 border border-border">
                        <!-- Prize Image -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4 z-10 <?php echo esc_attr(
                              $category_class,
                            ); ?> text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                <?php echo esc_html($category_label); ?>
                            </div>

                            <?php if ($image_url): ?>
                                <div
                                    class="w-full h-full bg-center bg-no-repeat bg-cover transform group-hover:scale-110 transition-transform duration-700"
                                    style="background-image: url('<?php echo esc_url(
                                      $image_url,
                                    ); ?>');"
                                ></div>
                            <?php else: ?>
                                <!-- Placeholder if no image -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[rgba(155,80,57,0.15)] to-[rgba(216,181,130,0.05)]">
                                    <svg class="w-16 h-16 text-[rgba(216,181,130,0.2)]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M12 15l3.5-3.5L12 8" />
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6">
                            <!-- Winner Name -->
                            <h3 class="text-lg font-bold text-ink mb-2">
                                <?php echo esc_html($name); ?>
                            </h3>

                            <!-- Prize -->
                            <p class="text-sage font-semibold mb-3">
                                <?php echo esc_html($prize); ?>
                            </p>

                            <!-- Date -->
                            <?php if ($date): ?>
                                <div class="flex items-center gap-2 text-sm text-ink-soft mb-4">
                                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                    <span><?php echo esc_html($date); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- Quote -->
                            <?php if ($show_quotes && !empty($quote)): ?>
                                <div class="pt-4 border-t border-[rgba(216,181,130,0.2)]">
                                    <div class="relative">
                                        <svg class="absolute -top-1 -left-1 w-6 h-6 text-[rgba(216,181,130,0.2)]" fill="currentColor" viewBox="0 0 32 32">
                                            <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"/>
                                        </svg>
                                        <p class="text-sm text-ink-soft italic pl-6 line-clamp-3">
                                            <?php echo esc_html($quote); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>

                    <?php
                    endforeach; ?>
                </div>

                <!-- Noscript message -->
                <noscript>
                    <div class="text-center mt-8 p-4 bg-white rounded-lg border border-border">
                        <p class="text-ink-soft">
                            <?php _e(
                              'Enable JavaScript to load more winners and use filters.',
                              'nera-competitions',
                            ); ?>
                        </p>
                    </div>
                </noscript>

            </div>
        </div>

    </div>
</section>
