<?php
/**
 * Advanced Filter Section Template Part
 *
 * Standalone section: multi-select category dropdown, price filter,
 * sort dropdown, and a competition cards grid. Category selection is
 * reflected in the URL (?product_cat=slug1,slug2); when present, the
 * initial grid is filtered server-side. Price and sort remain client-side.
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

// Category color mapping (Earthy palette)
$category_colors = [
  'cars' => 'var(--color-sage)',
  'cash' => 'var(--color-mint)',
  'luxury' => 'var(--color-forest)',
  'electronics' => 'var(--color-sage)',
  'travel' => 'var(--color-mint)',
  'tech' => 'var(--color-forest)',
  'gadgets' => 'var(--color-sage)',
  'watches' => 'var(--color-mint)',
  'lifestyle' => 'var(--color-forest)',
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

$allowed_cat_slugs = array_keys($cat_names);

// URL ?product_cat=slug1,slug2 — validated slugs only (OR semantics via tax IN).
$url_category_slugs = [];
if (isset($_GET['product_cat'])) {
  $raw_segments = array_filter(
    array_map('trim', explode(',', (string) wp_unslash($_GET['product_cat']))),
  );
  foreach ($raw_segments as $seg) {
    $slug = sanitize_title($seg);
    if (
      $slug !== ''
      && in_array($slug, $allowed_cat_slugs, true)
      && !in_array($slug, $url_category_slugs, true)
    ) {
      $url_category_slugs[] = $slug;
    }
  }
}

$filter_posts_per_page = !empty($url_category_slugs) ? 48 : 9;

if (!empty($url_category_slugs)) {
  $filter_tax_query = [
    'relation' => 'AND',
    [
      'taxonomy' => 'product_type',
      'field' => 'slug',
      'terms' => 'lottery',
    ],
    [
      'taxonomy' => 'product_cat',
      'field' => 'slug',
      'terms' => $url_category_slugs,
      'operator' => 'IN',
    ],
  ];
} else {
  $filter_tax_query = [
    [
      'taxonomy' => 'product_type',
      'field' => 'slug',
      'terms' => 'lottery',
    ],
  ];
}

// Query competitions – 9 products by default; when URL categories set, filter and allow more results.
$filter_competitions_args = [
  'post_type' => 'product',
  'posts_per_page' => $filter_posts_per_page,
  'post_status' => 'publish',
  'tax_query' => $filter_tax_query,
  'meta_key' => '_lty_end_date_gmt',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => nera_active_lottery_meta_query(),
];

$competitions = new WP_Query($filter_competitions_args);
?>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('advancedFilterSection', () => ({
      selectedCategories: <?php echo wp_json_encode($url_category_slugs); ?>,
      priceRange: '',
      sortBy: 'ending-soon',
      categoryDropdownOpen: false,
      categorySearchTerm: '',
      categoryNames: <?php echo wp_json_encode($cat_names); ?>,
      categoryOptions: <?php echo wp_json_encode($cat_options); ?>,
      categoryColors: <?php echo wp_json_encode($category_colors); ?>,
      serverCategoryFilterActive: <?php echo wp_json_encode(!empty($url_category_slugs)); ?>,
      initialUrlCategorySlugs: <?php echo wp_json_encode($url_category_slugs); ?>,

      init() {
        this.$watch('sortBy', () => this.sortGrid());
        this.$watch(
          'selectedCategories',
          () => {
            this.syncUrl();
            if (!this.serverCategoryFilterActive) return;
            if (this.categorySlugsEqual(this.selectedCategories, this.initialUrlCategorySlugs)) return;
            window.location.assign(window.location.href);
          },
          { deep: true },
        );
        window.addEventListener('popstate', () => this.applyUrlToCategories());
        this.syncUrl();
      },

      categorySlugsEqual(a, b) {
        const aa = [...a].map(String).sort();
        const bb = [...b].map(String).sort();
        if (aa.length !== bb.length) return false;
        return aa.every((v, i) => v === bb[i]);
      },

      syncUrl() {
        const url = new URL(window.location.href);
        const slugs = this.selectedCategories.filter(Boolean);
        if (slugs.length === 0) {
          url.searchParams.delete('product_cat');
        } else {
          url.searchParams.set('product_cat', slugs.join(','));
        }
        history.replaceState({}, '', url.toString());
      },

      applyUrlToCategories() {
        const params = new URLSearchParams(window.location.search);
        const raw = params.get('product_cat');
        const slugByLower = new Map(
          this.categoryOptions.map(o => [o.slug.toLowerCase(), o.slug]),
        );
        const next = [];
        if (raw) {
          raw.split(',').forEach(part => {
            const key = String(part).trim().toLowerCase();
            if (key === '') return;
            const slug = slugByLower.get(key);
            if (slug && !next.includes(slug)) next.push(slug);
          });
        }
        this.selectedCategories = next;
        this.$nextTick(() => this.sortGrid());
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
        return [...document.querySelectorAll('#advanced-filter-grid [data-price]')].some(c =>
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
        let cards = Array.from(grid.querySelectorAll('[data-price]'));
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

<section class="py-12 bg-[#f8fbf6]" id="advanced-filter-competitions" x-data="advancedFilterSection">

  <div class="max-w-[1400px] mx-auto px-4 lg:px-10">

    <!-- Section Header -->
    <div class="mb-10 text-center" data-aos="fade-up" data-aos-duration="600">

      <!-- Divider + reactive result count -->
      <div class="flex items-center justify-center gap-4 max-w-xl mx-auto mt-3">
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-[rgba(61,74,58,0.14)] to-transparent"></div>
        <p class="text-sm text-ink-soft font-medium whitespace-nowrap">
          <span x-text="[...document.querySelectorAll('#advanced-filter-grid [data-price]')].filter(c => categoryMatch(c.dataset.categories) && priceMatch(c.dataset.price)).length"></span>
          <?php _e('competitions available', 'nera-competitions'); ?>
        </p>
        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-[rgba(61,74,58,0.14)] to-transparent"></div>
      </div>

    </div>

    <!-- Filter Bar -->
    <div class="relative z-10" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3 mb-6
                  bg-white border border-[rgba(61,74,58,0.16)] rounded-2xl
                  shadow-[0_4px_32px_rgba(61,74,58,0.10),0_1px_4px_rgba(61,74,58,0.06),inset_0_1px_0_rgba(255,255,255,0.8)]
                  p-3 sm:p-4
                  transition-shadow duration-300
                  hover:shadow-[0_8px_40px_rgba(61,74,58,0.13),0_2px_8px_rgba(61,74,58,0.08)]">

        <!-- Filter Icon Label -->
        <div class="hidden sm:flex items-center gap-2 pl-1 pr-3 border-r border-[rgba(61,74,58,0.1)] mr-1 shrink-0">
          <svg class="w-4 h-4 text-sage" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
          </svg>
          <span class="text-[0.6rem] font-bold uppercase tracking-[0.18em] text-ink-soft"><?php _e('Filters', 'nera-competitions'); ?></span>
          <!-- Active filter count badge -->
          <span x-show="hasActiveFilters()"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-50"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-50"
                x-text="selectedCategories.length + (priceRange !== '' ? 1 : 0) + (sortBy !== 'ending-soon' ? 1 : 0)"
                class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-forest text-mint text-[0.6rem] font-bold leading-none">
          </span>
        </div>

        <!-- Category Combobox (multi-select) -->
        <div class="relative" @click.outside="categoryDropdownOpen = false">

          <!-- Trigger -->
          <div @click="categoryDropdownOpen = !categoryDropdownOpen"
            :class="categoryDropdownOpen ? 'border-sage ring-2 ring-forest/10' : 'border-[rgba(61,74,58,0.18)] hover:border-sage'"
            class="relative min-h-[44px] min-w-[200px] flex flex-wrap items-center gap-1.5 px-3.5 py-2 pr-9
                   bg-white border rounded-xl cursor-pointer
                   transition-all duration-300
                   hover:-translate-y-px hover:shadow-[0_2px_8px_rgba(61,74,58,0.10)]">

            <!-- Selected Chips -->
            <template x-for="slug in selectedCategories.slice(0, 3)" :key="slug">
              <span x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-75"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-75"
                class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-white
                       shadow-[0_1px_3px_rgba(0,0,0,.2)] border border-white/20"
                :style="{ backgroundColor: categoryColors[slug] || 'var(--color-forest)' }">
                <span class="w-1.5 h-1.5 rounded-full bg-white/50 shrink-0"></span>
                <span x-text="categoryNames[slug]" class="text-[0.62rem] font-semibold uppercase tracking-[0.08em]"></span>
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
              class="inline-flex items-center px-2.5 py-[3px] rounded-full text-xs font-medium bg-mint/20 text-forest">
              +<span x-text="selectedCategories.length - 3"></span> more
            </span>

            <!-- Search Input -->
            <input type="text" x-model="categorySearchTerm" @click.stop="categoryDropdownOpen = true"
              @keydown.escape="categoryDropdownOpen = false"
              :placeholder="selectedCategories.length === 0 ? '<?php echo esc_js(
                __('Select categories…', 'nera-competitions'),
              ); ?>' : '<?php echo esc_js(__('Search…', 'nera-competitions')); ?>'"
              class="flex-1 min-w-[60px] bg-transparent border-none outline-none text-sm font-medium text-ink placeholder-[#7a8f78] cursor-text">

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
            class="absolute z-50 top-full mt-2 w-full min-w-[240px] bg-white border border-[rgba(61,74,58,0.14)] rounded-xl shadow-[0_8px_32px_rgba(61,74,58,0.12),0_2px_8px_rgba(61,74,58,0.08)] overflow-hidden">
            <ul class="max-h-52 overflow-y-auto py-1.5" role="listbox">
              <template x-for="option in filteredCategories()" :key="option.slug">
                <li @click="toggleCategory(option.slug)"
                  :class="selectedCategories.includes(option.slug) ? 'bg-sage/15 border-l-2 border-l-sage' : 'hover:bg-mint/10 border-l-2 border-l-transparent'"
                  class="flex items-center gap-2.5 px-3 py-2.5 text-sm cursor-pointer transition-all duration-150" role="option"
                  :aria-selected="selectedCategories.includes(option.slug)">
               
                  <!-- Checkbox indicator -->
                  <span class="flex items-center justify-center w-4 h-4 rounded border transition-all duration-200"
                    :class="selectedCategories.includes(option.slug) ? 'bg-forest border-forest scale-110' : 'border-[rgba(61,74,58,0.25)] bg-white'">
                    <template x-if="selectedCategories.includes(option.slug)">
                      <svg class="w-2.5 h-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                      </svg>
                    </template>
                  </span>
                  <!-- Option label -->
                  <span
                    :class="selectedCategories.includes(option.slug) ? 'text-forest font-semibold uppercase tracking-[0.06em] text-xs' : 'text-ink text-sm'"
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
        <div class="relative group transition-all duration-300 hover:-translate-y-px hover:shadow-[0_2px_8px_rgba(61,74,58,0.10)] rounded-xl">
          <!-- Prefix icon -->
          <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-sage group-focus-within:text-forest transition-colors duration-200">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9.5 9a3 3 0 0 1 5 1c0 2-3 3-3 3"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
          </span>
          <select x-model="priceRange"
            class="appearance-none bg-white border border-[rgba(61,74,58,0.18)] rounded-xl
                   h-[44px] pl-9 pr-10 text-sm font-medium text-forest
                   cursor-pointer
                   hover:border-sage
                   focus:border-sage focus:ring-2 focus:ring-forest/10 focus:outline-none
                   transition-colors duration-300">
            <option value=""><?php _e('All Prices', 'nera-competitions'); ?></option>
            <option value="0-5"><?php _e('Under £5', 'nera-competitions'); ?></option>
            <option value="5-10"><?php _e('£5 – £10', 'nera-competitions'); ?></option>
            <option value="10-25"><?php _e('£10 – £25', 'nera-competitions'); ?></option>
            <option value="25+"><?php _e('£25+', 'nera-competitions'); ?></option>
          </select>
          <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-ink-soft">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </span>
        </div>

        <!-- Sort Dropdown -->
        <div class="relative group transition-all duration-300 hover:-translate-y-px hover:shadow-[0_2px_8px_rgba(61,74,58,0.10)] rounded-xl">
          <!-- Prefix icon -->
          <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-sage group-focus-within:text-forest transition-colors duration-200">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <path d="M3 6h18M7 12h10M11 18h2"/>
            </svg>
          </span>
          <select x-model="sortBy"
            class="appearance-none bg-white border border-[rgba(61,74,58,0.18)] rounded-xl
                   h-[44px] pl-9 pr-10 text-sm font-medium text-forest
                   cursor-pointer
                   hover:border-sage
                   focus:border-sage focus:ring-2 focus:ring-forest/10 focus:outline-none
                   transition-colors duration-300">
            <option value="ending-soon"><?php _e('Ending Soon', 'nera-competitions'); ?></option>
            <option value="newest"><?php _e('Newest First', 'nera-competitions'); ?></option>
            <option value="price-low"><?php _e('Price: Low to High', 'nera-competitions'); ?></option>
            <option value="price-high"><?php _e('Price: High to Low', 'nera-competitions'); ?></option>
            <option value="popularity"><?php _e('Most Popular', 'nera-competitions'); ?></option>
          </select>
          <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-ink-soft">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </span>
        </div>

        <!-- Clear Filters Button -->
        <button type="button" x-show="hasActiveFilters()" @click="clearFilters()"
          class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-ink-soft hover:text-forest hover:bg-[rgba(61,74,58,0.06)] rounded-lg border border-transparent hover:border-[rgba(61,74,58,0.14)] transition-all duration-200">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
          <?php _e('Clear', 'nera-competitions'); ?>
        </button>

      </div>

      <!-- Active Filters Tag Bar -->
      <div x-show="hasActiveFilters()"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 -translate-y-2"
           x-transition:enter-end="opacity-100 translate-y-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0"
           x-transition:leave-end="opacity-0 -translate-y-2"
           class="flex flex-wrap items-center gap-2 mb-6 px-1">

        <span class="text-[0.6rem] font-bold uppercase tracking-[0.18em] text-ink-soft mr-1">
          <?php _e('Active:', 'nera-competitions'); ?>
        </span>

        <!-- Category filter pills -->
        <template x-for="slug in selectedCategories" :key="'af-' + slug">
          <button type="button" @click="toggleCategory(slug)"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 scale-90"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-150"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-90"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                         text-[0.62rem] font-semibold uppercase tracking-[0.1em]
                         text-white border border-white/20
                         shadow-[0_1px_4px_rgba(0,0,0,0.15)]
                         hover:opacity-80 hover:-translate-y-px
                         transition-all duration-150"
                  :style="{ backgroundColor: categoryColors[slug] || 'var(--color-forest)' }">
            <span x-text="categoryNames[slug]"></span>
            <svg class="w-2.5 h-2.5 opacity-70" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3.5" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </template>

        <!-- Price filter pill -->
        <template x-if="priceRange !== ''">
          <button type="button" @click="priceRange = ''"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 scale-90"
                  x-transition:enter-end="opacity-100 scale-100"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                         text-[0.62rem] font-semibold uppercase tracking-[0.1em]
                         bg-forest/10 text-forest border border-forest/20
                         hover:bg-forest/15 hover:-translate-y-px
                         transition-all duration-150">
            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9.5 9a3 3 0 0 1 5 1c0 2-3 3-3 3"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <span x-text="priceRange === '0-5' ? 'Under £5' : priceRange === '5-10' ? '£5–£10' : priceRange === '10-25' ? '£10–£25' : '£25+'"></span>
            <svg class="w-2.5 h-2.5 opacity-70" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3.5" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </template>

        <!-- Sort pill (only when not default) -->
        <template x-if="sortBy !== 'ending-soon'">
          <button type="button" @click="sortBy = 'ending-soon'; $nextTick(() => sortGrid())"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 scale-90"
                  x-transition:enter-end="opacity-100 scale-100"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                         text-[0.62rem] font-semibold uppercase tracking-[0.1em]
                         bg-sage/10 text-sage border border-sage/20
                         hover:bg-sage/15 hover:-translate-y-px
                         transition-all duration-150">
            <span x-text="sortBy === 'newest' ? 'Newest' : sortBy === 'price-low' ? 'Price ↑' : sortBy === 'price-high' ? 'Price ↓' : 'Popular'"></span>
            <svg class="w-2.5 h-2.5 opacity-70" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="3.5" stroke-linecap="round">
              <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
          </button>
        </template>

        <!-- Clear All -->
        <button type="button" @click="clearFilters()"
                class="ml-auto text-[0.6rem] font-semibold uppercase tracking-[0.12em]
                       text-ink-soft hover:text-forest underline underline-offset-2
                       transition-colors duration-150">
          <?php _e('Clear All', 'nera-competitions'); ?>
        </button>

      </div>
    </div>

    <!-- Results Count Bar -->
    <div class="flex items-center justify-between mb-5 px-1" data-aos="fade-up" data-aos-duration="400" data-aos-delay="200">
      <p class="text-xs text-ink-soft font-medium">
        <?php _e('Showing', 'nera-competitions'); ?>
        <strong class="text-forest font-semibold" x-text="[...document.querySelectorAll('#advanced-filter-grid [data-price]')].filter(c => categoryMatch(c.dataset.categories) && priceMatch(c.dataset.price)).length"></strong>
        <?php _e('of', 'nera-competitions'); ?>
        <strong class="text-forest font-semibold"><?php echo (int) $competitions->found_posts; ?></strong>
        <?php _e('competitions', 'nera-competitions'); ?>
      </p>
    </div>

    <!-- Competitions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6"
         id="advanced-filter-grid"
         data-aos="fade-up" data-aos-duration="600" data-aos-delay="150">

      <?php if ($competitions->have_posts()): ?>
        <?php
        $card_index = 0;
        while ($competitions->have_posts()):
          $competitions->the_post(); ?>
          <?php
          $card_args = [
            'product'    => wc_get_product(get_the_ID()),
            'badge_label' => '',
            'x_show'     => 'categoryMatch($el.dataset.categories) && priceMatch($el.dataset.price)',
            'card_index' => $card_index,
          ];
          get_template_part('template-parts/components/prize-card', null, $card_args);
          $card_index++;
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
            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-forest hover:text-ink bg-mint/20 hover:bg-mint/30 rounded-lg border border-[rgba(61,74,58,0.18)] transition-all duration-200">
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
