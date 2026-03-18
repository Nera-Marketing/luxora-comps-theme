<?php
/**
 * Advanced Filter Section Template Part
 *
 * Standalone section: multi-select category dropdown, price filter,
 * sort dropdown, and a competition cards grid. All filtering / sorting
 * is client-side via Alpine.js.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

// Get all active product categories
$categories = get_terms([
  'taxonomy' => 'product_cat',
  'hide_empty' => true,
  'exclude' => get_option('default_product_cat'),
]);

// Query competitions – 9 products, ordered by end date ASC (default sort)
$filter_competitions_args = [
  'post_type' => 'product',
  'posts_per_page' => 9,
  'post_status' => 'publish',
  'tax_query' => [
    [
      'taxonomy' => 'product_type',
      'field' => 'slug',
      'terms' => 'lottery',
    ],
  ],
  'meta_key' => '_lty_end_date_gmt',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => nera_active_lottery_meta_query(),
];

$competitions = new WP_Query($filter_competitions_args);

// Category color mapping (Earthy palette)
$category_colors = [
  'cars' => 'var(--color-sage)',
  'cash' => 'var(--color-mint)',
  'luxury' => 'forest',
  'electronics' => 'var(--color-sage)',
  'travel' => 'var(--color-mint)',
  'tech' => 'forest',
  'gadgets' => 'var(--color-sage)',
  'watches' => 'var(--color-mint)',
  'lifestyle' => 'forest',
];

// Prepare category data for the Alpine combobox
$cat_names = [];
$cat_options = [];
if (!empty($categories) && !is_wp_error($categories)) {
  foreach ($categories as $cat) {
    $cat_names[$cat->slug] = $cat->name;
    $cat_options[] = ['slug' => $cat->slug, 'name' => $cat->name];
  }
}
?>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('advancedFilterSection', () => ({
      selectedCategories: [],
      priceRange: '',
      sortBy: 'ending-soon',
      categoryDropdownOpen: false,
      categorySearchTerm: '',
      categoryNames: <?php echo wp_json_encode($cat_names); ?>,
      categoryOptions: <?php echo wp_json_encode($cat_options); ?>,
      categoryColors: <?php echo wp_json_encode($category_colors); ?>,

      init() {
        this.$watch('sortBy', () => this.sortGrid());
      },

      filteredCategories() {
        if (this.categorySearchTerm === '') return this.categoryOptions;
        let term = this.categorySearchTerm.toLowerCase();
        return this.categoryOptions.filter(o => o.name.toLowerCase().includes(term));
      },

      toggleCategory(slug) {
        let idx = this.selectedCategories.indexOf(slug);
        idx > -1 ? this.selectedCategories.splice(idx, 1) : this.selectedCategories.push(slug);
      },

      categoryMatch(categoriesJson) {
        if (this.selectedCategories.length === 0) return true;
        return this.selectedCategories.some(c => JSON.parse(categoriesJson).includes(c));
      },

      priceMatch(priceStr) {
        if (this.priceRange === '') return true;
        let p = parseFloat(priceStr);
        if (this.priceRange === '0-5') return p < 5;
        if (this.priceRange === '5-10') return p >= 5 && p < 10;
        if (this.priceRange === '10-25') return p >= 10 && p < 25;
        if (this.priceRange === '25+') return p >= 25;
        return true;
      },

      hasActiveFilters() {
        return this.selectedCategories.length > 0 || this.priceRange !== '' || this.sortBy !== 'ending-soon';
      },

      hasMatchingCards() {
        return [...document.querySelectorAll('#advanced-filter-grid article[data-price]')].some(c =>
          this.categoryMatch(c.dataset.categories) && this.priceMatch(c.dataset.price)
        );
      },

      selectAllCategories() {
        if (this.selectedCategories.length === this.categoryOptions.length) {
          this.selectedCategories = [];
        } else {
          this.selectedCategories = this.categoryOptions.map(o => o.slug);
        }
      },

      clearFilters() {
        this.selectedCategories = [];
        this.priceRange = '';
        this.sortBy = 'ending-soon';
        this.categorySearchTerm = '';
        this.$nextTick(() => this.sortGrid());
      },

      sortGrid() {
        let grid = document.getElementById('advanced-filter-grid');
        if (!grid) return;
        let cards = Array.from(grid.querySelectorAll('article[data-price]'));
        cards.sort((a, b) => {
          switch (this.sortBy) {
            case 'price-low': return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-high': return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'newest': return Number(b.dataset.postedDate) - Number(a.dataset.postedDate);
            case 'popularity': return Number(b.dataset.popularity) - Number(a.dataset.popularity);
            default: return Number(a.dataset.endDate) - Number(b.dataset.endDate);
          }
        });
        cards.forEach(c => grid.appendChild(c));
      }
    }));
  });
</script>

<section class="py-12 bg-forest" id="advanced-filter-competitions" x-data="advancedFilterSection">

  <div class="max-w-[1200px] mx-auto px-4">

    <!-- Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-2.5 mb-10
                bg-off-white border border-border rounded-2xl p-3 sm:p-3.5">

      <!-- Category Combobox (multi-select) -->
      <div class="relative" @click.outside="categoryDropdownOpen = false">
        <!-- <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1.5 pl-0.5"> -->
        <!-- <?php _e('Categories', 'nera-competitions'); ?> -->
        <!-- </label> -->

        <!-- Trigger -->
        <div @click="categoryDropdownOpen = !categoryDropdownOpen"
          :class="categoryDropdownOpen ? 'border-border ring-2 ring-forest/20 bg-forest' : 'border-border hover:border-border bg-forest'"
          class="relative min-h-[42px] flex flex-wrap items-center gap-1.5 px-3 py-1.5 pr-8
                 border rounded-xl cursor-pointer transition-all duration-150">

          <!-- Selected Chips -->
          <template x-for="slug in selectedCategories.slice(0, 3)" :key="slug">
            <span x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-75"
              x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-150 ease-in"
              x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75" class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-xs font-semibold text-white
                         shadow-[0_1px_3px_rgba(0,0,0,.2)] border border-white/20"
              :style="{ backgroundColor: categoryColors[slug] || 'forest' }">
              <span x-text="categoryNames[slug]"></span>
              <button type="button" @click.stop="toggleCategory(slug)"
                class="opacity-50 hover:opacity-100 transition-opacity ml-0.5 -mr-0.5">
                <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"
                  stroke-linecap="round">
                  <path d="M18 6L6 18M6 6l12 12" />
                </svg>
              </button>
            </span>
          </template>

          <!-- +N more badge -->
          <span x-show="selectedCategories.length > 3"
            class="inline-flex items-center px-2.5 py-[3px] rounded-full text-xs font-semibold bg-mint/10 text-ink-soft">
            +<span x-text="selectedCategories.length - 3"></span> more
          </span>

          <!-- Search Input -->
          <input type="text" x-model="categorySearchTerm" @click.stop="categoryDropdownOpen = true"
            @keydown.escape="categoryDropdownOpen = false"
            :placeholder="selectedCategories.length === 0 ? '<?php echo esc_js(
              __('Select categories…', 'nera-competitions'),
            ); ?>' : '<?php echo esc_js(__('Search…', 'nera-competitions')); ?>'"
            class="flex-1 min-w-[60px] bg-transparent border-none outline-none text-sm font-medium text-ink placeholder-ink-soft cursor-text">

          <!-- Chevron -->
          <span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-ink-soft">
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': categoryDropdownOpen }"
              xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </span>
        </div>

        <!-- Dropdown -->
        <div x-show="categoryDropdownOpen" x-transition:enter="transition ease-out duration-150"
          x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
          x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
          x-transition:leave-end="opacity-0 -translate-y-1"
          class="absolute z-30 top-full mt-1.5 w-full min-w-[220px] bg-off-white border border-border rounded-xl shadow-lg overflow-hidden">
          <ul class="max-h-52 overflow-y-auto py-1.5" role="listbox">
            <template x-for="option in filteredCategories()" :key="option.slug">
              <li @click="toggleCategory(option.slug)"
                :class="selectedCategories.includes(option.slug) ? 'bg-sage/20' : 'hover:bg-mint/10'"
                class="flex items-center gap-2.5 px-3 py-2 text-sm cursor-pointer transition-colors" role="option"
                :aria-selected="selectedCategories.includes(option.slug)">
                <!-- Checkbox indicator -->
                <span class="flex items-center justify-center w-4 h-4 rounded border transition-colors"
                  :class="selectedCategories.includes(option.slug) ? 'bg-forest border-forest' : 'border-border bg-forest'">
                  <template x-if="selectedCategories.includes(option.slug)">
                    <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                      stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                  </template>
                </span>
                <!-- Option label -->
                <span
                  :class="selectedCategories.includes(option.slug) ? 'text-sage font-medium' : 'text-ink'"
                  x-text="option.name"></span>
              </li>
            </template>
            <!-- No search results -->
            <template x-if="filteredCategories().length === 0">
              <li class="px-3 py-2.5 text-sm text-ink-soft text-center">
                <?php _e('No matching categories', 'nera-competitions'); ?>
              </li>
            </template>
          </ul>
        </div>
      </div>

      <!-- Price Dropdown -->
      <div class="relative">
        <select x-model="priceRange"
          class="appearance-none bg-forest border border-border rounded-xl px-4 py-2.5 pr-10 text-sm font-medium text-ink
                 cursor-pointer hover:border-border focus:border-border focus:ring-2 focus:ring-forest/20 focus:outline-none transition-all">
          <option value=""><?php _e('All Prices', 'nera-competitions'); ?></option>
          <option value="0-5"><?php _e('Under £5', 'nera-competitions'); ?></option>
          <option value="5-10"><?php _e('£5 – £10', 'nera-competitions'); ?></option>
          <option value="10-25"><?php _e('£10 – £25', 'nera-competitions'); ?></option>
          <option value="25+"><?php _e('£25+', 'nera-competitions'); ?></option>
        </select>
        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
          <svg class="w-4 h-4 text-ink-soft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
              clip-rule="evenodd" />
          </svg>
        </span>
      </div>

      <!-- Sort Dropdown -->
      <div class="relative">
        <select x-model="sortBy"
          class="appearance-none bg-forest border border-border rounded-xl px-4 py-2.5 pr-10 text-sm font-medium text-ink
                 cursor-pointer hover:border-border focus:border-border focus:ring-2 focus:ring-forest/20 focus:outline-none transition-all">
          <option value="ending-soon"><?php _e('Ending Soon', 'nera-competitions'); ?></option>
          <option value="newest"><?php _e('Newest First', 'nera-competitions'); ?></option>
          <option value="price-low"><?php _e('Price: Low to High', 'nera-competitions'); ?></option>
          <option value="price-high"><?php _e(
            'Price: High to Low',
            'nera-competitions',
          ); ?></option>
          <option value="popularity"><?php _e('Most Popular', 'nera-competitions'); ?></option>
        </select>
        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
          <svg class="w-4 h-4 text-ink-soft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
              clip-rule="evenodd" />
          </svg>
        </span>
      </div>

      <!-- Clear Filters Button -->
      <button type="button" x-show="hasActiveFilters()" @click="clearFilters()"
        class="inline-flex items-center gap-1.5 px-3 py-2.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        <?php _e('Clear', 'nera-competitions'); ?>
      </button>

    </div>

    <!-- Competitions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="advanced-filter-grid">

      <?php if ($competitions->have_posts()): ?>
        <?php while ($competitions->have_posts()):
          $competitions->the_post(); ?>
          <?php
          // Pass arguments to the reusable component
          $card_args = [
            'x_show' => 'categoryMatch($el.dataset.categories) && priceMatch($el.dataset.price)',
            'category_colors' => $category_colors,
          ];
          get_template_part('template-parts/components/competition-card', null, $card_args);
          ?>
        <?php
        endwhile; ?>

        <!-- No results after filtering -->
        <div class="col-span-full text-center py-16"
          x-show="(selectedCategories.length > 0 || priceRange !== '') && !hasMatchingCards()">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-off-white mb-5">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
              class="text-ink-soft">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-ink mb-2">No competitions match your filters</h3>
          <p class="text-ink-soft mb-4">Try adjusting your filters to see more results.</p>
          <button type="button" @click="clearFilters()"
            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg border border-red-200 transition-all">
            Clear All Filters
          </button>
        </div>

      <?php else: ?>
        <!-- Empty State (no competitions in DB) -->
        <div class="col-span-full text-center py-20">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-off-white mb-6">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
              class="text-ink-soft">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
              <circle cx="8.5" cy="8.5" r="1.5" />
              <polyline points="21 15 16 10 5 21" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-ink mb-2">No competitions found</h3>
          <p class="text-ink-soft">Check back soon for new amazing prizes!</p>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>

  </div>
</section>