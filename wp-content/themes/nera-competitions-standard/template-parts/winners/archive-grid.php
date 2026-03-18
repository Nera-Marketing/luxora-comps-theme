<?php
/**
 * Archive Winners Page - Main Grid
 *
 * This component initializes a Vue.js instance to fetch and display 
 * finished competitions from the REST API.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}
?>

<section id="archive-winners-app" class="pb-20 lg:pb-32 px-5 sm:px-6 lg:px-8 max-w-7xl mx-auto" v-cloak
  data-rest-url="<?php echo esc_url(get_rest_url(null, 'nera/v1/archive')); ?>"
  data-wp-nonce="<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>"
  data-pdf-nonce="<?php echo esc_attr(wp_create_nonce('lty-lottery-entry-list-pdf')); ?>">
  <!-- Filter Bar -->
  <div
    class="flex flex-col md:flex-row md:items-center justify-between mb-12 space-y-4 md:space-y-0 bg-off-white/80 p-6 rounded-2xl border border-border backdrop-blur-sm">
    <div class="flex flex-col space-y-1">
      <h3 class="text-ink font-semibold text-lg">Filter draws</h3>
      <p class="text-sm text-ink-soft">View past results and entry lists</p>
    </div>

    <div class="flex flex-wrap gap-3">
      <!-- Search -->
      <div class="relative min-w-[240px]">
        <input type="text" v-model="searchQuery" placeholder="Search prize or winner..."
          class="w-full bg-off-white border border-border text-ink rounded-xl px-10 py-3 text-sm focus:outline-none focus:border-forest transition-colors">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-soft" fill="none"
          stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <!-- Category Filter (Optional logic) -->
      <select v-model="selectedCategory"
        class="bg-off-white border border-border text-ink rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-forest transition-colors appearance-none pr-10 relative">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat.slug" :value="cat.slug">{{ cat.name }}</option>
      </select>
    </div>
  </div>

  <!-- Loading State -->
  <div v-if="loading" class="flex flex-col items-center justify-center py-24 space-y-4">
    <div class="w-12 h-12 border-4 border-sage/20 border-t-sage rounded-full animate-spin"></div>
    <p class="text-ink/60 font-medium">Loading archive...</p>
  </div>

  <!-- Empty State -->
  <div v-if="!loading && filteredItems.length === 0"
    class="text-center py-24 bg-off-white/30 rounded-3xl border border-dashed border-border">
    <div class="bg-sage/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
      <svg class="w-10 h-10 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
        </path>
      </svg>
    </div>
    <h3 class="text-2xl font-bold text-ink mb-2">No archived draws found</h3>
    <p class="text-ink-soft">Try adjusting your search or filters.</p>
  </div>

  <!-- Results Grid -->
  <div v-if="!loading && filteredItems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <div v-for="item in filteredItems" :key="item.id"
      class="group bg-white rounded-2xl overflow-hidden border border-border hover:border-sage/50 transition-all duration-300 flex flex-col h-full shadow-lg hover:shadow-sage/10">
      <!-- Image Header -->
      <div class="relative aspect-video overflow-hidden">
        <img :src="item.image" :alt="item.title"
          class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent opacity-60"></div>

        <!-- Winner Badge -->
        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
          <span
            class="bg-sage text-white text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full shadow-lg">
            Completed Draw
          </span>
          <span class="text-white/60 text-xs font-medium">
            {{ item.end_date_formatted }}
          </span>
        </div>
      </div>

      <!-- Content Body -->
      <div class="p-6 flex flex-col flex-grow">
        <h3
          class="text-xl font-bold text-ink mb-4 line-clamp-2 leading-snug group-hover:text-white transition-colors">
          {{ item.title }}
        </h3>

        <!-- Winner Info Box -->
        <div class="bg-off-white rounded-xl p-4 mb-6 border border-border">
          <div class="flex items-center space-x-3 mb-1">
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-br from-sage to-forest flex items-center justify-center text-white font-bold text-sm shadow-inner">
              {{ item.winner_name ? item.winner_name.charAt(0) : '?' }}
            </div>
            <div>
              <p class="text-[10px] uppercase tracking-wider text-white/30 font-bold mb-0.5">Winner Identified</p>
              <p class="text-ink font-bold leading-tight">{{ item.winner_name || 'Processing...' }}</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-auto grid grid-cols-2 gap-3">
          <a :href="item.entry_list_url" target="_blank"
            class="flex items-center justify-center space-x-2 bg-transparent border border-border hover:border-sage hover:text-sage text-ink py-2.5 rounded-lg text-xs font-bold transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
            <span>Entry List</span>
          </a>

          <a v-if="item.draw_video_url" :href="item.draw_video_url" target="_blank"
            class="flex items-center justify-center space-x-2 bg-sage/10 hover:bg-sage text-sage hover:text-white py-2.5 rounded-lg text-xs font-bold transition-all border border-transparent">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                clip-rule="evenodd"></path>
            </svg>
            <span>Watch Draw</span>
          </a>
          <div v-else
            class="flex items-center justify-center text-border py-2.5 rounded-lg text-xs font-bold border border-border">
            No Video
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pagination (Placeholder) -->
  <div v-if="totalPages > 1" class="mt-16 flex justify-center space-x-2">
    <button @click="prevPage" :disabled="currentPage === 1"
      class="px-4 py-2 bg-white border border-border text-ink rounded-lg disabled:opacity-30">
      Previous
    </button>
    <span class="flex items-center px-4 text-ink/60 text-sm font-medium">Page {{ currentPage }} of {{ totalPages
      }}</span>
    <button @click="nextPage" :disabled="currentPage === totalPages"
      class="px-4 py-2 bg-white border border-border text-ink rounded-lg disabled:opacity-30">
      Next
    </button>
  </div>
</section>

<!-- Vue integration script will be loaded here -->
<?php
// We will enqueue the script in functions.php or here for simplicity
?>