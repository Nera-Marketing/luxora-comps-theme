<template>
  <div class="relative">
    <!-- Filter bar -->
    <div class="mb-16">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-off-white/80 backdrop-blur-sm p-8 rounded-2xl border border-border shadow-lg">
        <div class="flex flex-col space-y-1">
          <h3 class="text-ink font-heading font-bold text-xl tracking-tight">Search Archive</h3>
          <p class="text-sm text-ink-soft font-medium">Find specific results or download entry lists</p>
        </div>

        <div class="flex flex-wrap gap-4 w-full md:w-auto">
          <!-- Search Input -->
          <div class="relative w-full md:min-w-[400px]">
            <input
              type="text"
              v-model="searchInput"
              placeholder="Search by prize name or winner..."
              class="w-full bg-white border border-border text-ink rounded-2xl pl-12 pr-4 py-4 text-sm focus:outline-none focus:border-forest focus:ring-4 focus:ring-forest/5 transition-all duration-300 placeholder:text-ink/30"
              @input="handleSearch"
            >
            <div class="absolute left-4 top-1/2 -translate-y-1/2 flex items-center justify-center">
              <svg class="w-5 h-5 text-ink-soft transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>

            <!-- Clear button -->
            <button
              v-if="searchInput"
              @click="searchInput = ''; handleSearch()"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-ink-soft hover:text-forest p-1 transition-colors"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex flex-col items-center justify-center py-24 space-y-4">
      <div class="w-12 h-12 border-4 border-sage/20 border-t-sage rounded-full animate-spin"></div>
      <p class="text-ink/60 font-medium">Loading archive...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="isError" class="text-center py-24 bg-off-white/30 rounded-3xl border border-dashed border-red-500/20">
      <div class="bg-red-500/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
        <span class="material-symbols-outlined text-4xl text-red-500">error</span>
      </div>
      <h3 class="text-2xl font-bold text-ink mb-2">Failed to load archive</h3>
      <p class="text-ink-soft mb-6">We encountered an error while fetching the draw results.</p>
      <button @click="refetch" class="bg-forest text-white px-8 py-3 rounded-xl font-bold hover:bg-ink transition-colors">
        Try Again
      </button>
    </div>

    <!-- Empty State -->
    <div v-else-if="items.length === 0" class="text-center py-24 bg-off-white/30 rounded-3xl border border-dashed border-border">
      <div class="bg-sage/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
      </div>
      <h3 class="text-2xl font-bold text-ink mb-2">No archived draws found</h3>
      <p class="text-ink-soft">Try adjusting your search query.</p>
    </div>

    <!-- Results Grid -->
    <div v-else :class="{ 'opacity-50 pointer-events-none': isFetching }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-opacity duration-300">
      <div
        v-for="item in items"
        :key="item.id"
        class="group bg-off-white rounded-2xl overflow-hidden border border-border hover:border-sage/50 transition-all duration-500 flex flex-col h-full shadow-lg hover:shadow-sage/10"
      >
        <!-- Image Header -->
        <div class="relative aspect-[16/10] overflow-hidden">
          <img :src="item.image || 'https://placehold.co/600x400/3d4a3a/c8e6c0?text=Nera+Competitions'" :alt="item.title" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000">

          <!-- Overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-ink via-transparent to-transparent opacity-60"></div>

          <!-- Status Badge (Top Left) -->
          <div class="absolute top-4 left-4">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-sage text-white text-[10px] uppercase tracking-widest font-bold shadow-lg">
              <span class="w-1.5 h-1.5 rounded-full bg-white mr-2 animate-pulse"></span>
              Completed
            </span>
          </div>

          <!-- Date Badge (Top Right) -->
          <div class="absolute top-4 right-4">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-black/40 backdrop-blur-md text-white/80 text-[10px] font-medium border border-white/10 uppercase tracking-tighter">
              {{ item.end_date_formatted }}
            </span>
          </div>
        </div>

        <!-- Content Body -->
        <div class="p-6 md:p-8 flex flex-col flex-grow">
          <!-- Title -->
          <h3 class="font-heading text-2xl font-bold text-ink mb-6 line-clamp-2 leading-[1.3] group-hover:text-forest transition-colors">
            {{ item.title }}
          </h3>

          <!-- Winner Spotlight Section -->
          <div class="relative mb-8 p-0.5 rounded-2xl bg-gradient-to-br from-sage/20 to-transparent">
            <div class="bg-white rounded-[15px] p-4 flex items-center space-x-4">
              <!-- Avatar with Ring -->
              <div class="relative shrink-0">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sage to-forest flex items-center justify-center text-white font-bold text-xl shadow-inner border-2 border-white">
                  {{ item.winner_name ? item.winner_name.charAt(0) : '?' }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-sage rounded-full flex items-center justify-center border-2 border-white">
                  <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                  </svg>
                </div>
              </div>

              <!-- Info -->
              <div class="overflow-hidden">
                <p class="text-[9px] uppercase tracking-[0.2em] text-ink-soft font-bold mb-0.5">Lucky Winner</p>
                <p class="text-ink font-bold text-lg leading-tight truncate tracking-tight">
                  {{ item.winner_name || 'Verification Pending' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Action Grid -->
          <div class="mt-auto grid grid-cols-2 gap-4">
            <!-- Entry List -->
            <a
              :href="item.entry_list_url"
              target="_blank"
              class="group/btn flex items-center justify-center space-x-2 bg-transparent border border-border hover:border-sage text-ink hover:text-sage py-3.5 rounded-xl text-xs font-bold transition-all duration-300"
            >
              <svg class="w-4 h-4 transition-transform group-hover/btn:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>Entry List</span>
            </a>

            <!-- Video -->
            <a
              v-if="item.draw_video_url"
              :href="item.draw_video_url"
              target="_blank"
              class="group/btn flex items-center justify-center space-x-2 bg-sage/10 hover:bg-sage text-sage hover:text-white py-3.5 rounded-xl text-xs font-bold transition-all duration-300 border border-transparent"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
              </svg>
              <span>Watch Draw</span>
            </a>

            <!-- Disabled Content (No Video) -->
            <div v-else class="flex items-center justify-center space-x-2 bg-transparent text-border py-3.5 rounded-xl text-xs font-bold border border-border cursor-not-allowed">
              <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
              <span>No Video</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1 && !isLoading" class="mt-20 flex flex-col md:flex-row items-center justify-center gap-8 border-t border-border pt-12">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="flex items-center space-x-3 px-8 py-4 bg-off-white border border-border text-ink rounded-2xl font-bold transition-all duration-300 hover:border-sage disabled:opacity-20 disabled:cursor-not-allowed group/prev shadow-lg"
      >
        <svg class="w-5 h-5 transition-transform group-hover/prev:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span>Previous Results</span>
      </button>

      <div class="flex items-center space-x-2">
        <span class="w-8 h-[1px] bg-border"></span>
        <div class="px-6 py-2 rounded-full bg-sage/5 border border-sage/10 text-ink/60 text-sm font-bold tracking-widest uppercase">
          Page <span class="text-forest">{{ currentPage }}</span> <span class="mx-1">/</span> {{ totalPages }}
        </div>
        <span class="w-8 h-[1px] bg-border"></span>
      </div>

      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="flex items-center space-x-3 px-8 py-4 bg-off-white border border-border text-ink rounded-2xl font-bold transition-all duration-300 hover:border-sage disabled:opacity-20 disabled:cursor-not-allowed group/next shadow-lg"
      >
        <span>Next Results</span>
        <svg class="w-5 h-5 transition-transform group-hover/next:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useQuery } from '@tanstack/vue-query';

// State
const container = document.getElementById('archive-winners-app');
const restUrl = container?.dataset.restUrl || '/wp-json/nera/v1/archive';
const wpNonce = container?.dataset.wpNonce || '';
const pdfNonce = container?.dataset.pdfNonce || '';

const searchInput = ref('');
const searchQuery = ref('');
const currentPage = ref(1);
const perPage = ref(12);

// Debounce search
let searchTimer = null;
const handleSearch = () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    searchQuery.value = searchInput.value;
    currentPage.value = 1; // Reset to page 1 on search
  }, 400);
};

// Fetch function
const fetchArchive = async ({ queryKey }) => {
  const [_, search, page, perPage] = queryKey;
  const params = new URLSearchParams({
    search,
    page: page.toString(),
    per_page: perPage.toString(),
    pdf_nonce: pdfNonce // Pass the session-correct PDF nonce to the API
  });
  
  const headers = {};
  if (wpNonce) {
    headers['X-WP-Nonce'] = wpNonce; // Authenticate REST request 
  }

  const response = await fetch(`${restUrl}?${params}`, { headers });
  if (!response.ok) throw new Error('Network response was not ok');
  const result = await response.json();
  return result.data;
};

// Query
const { data, isLoading, isError, isFetching, refetch } = useQuery({
  queryKey: ['archive-winners', searchQuery, currentPage, perPage, pdfNonce], // Add nonce to key
  queryFn: fetchArchive,
  staleTime: 60000, // 1 minute
});

// Computed
const items = computed(() => data.value?.items || []);
const pagination = computed(() => data.value?.pagination || {});
const totalPages = computed(() => pagination.value.total_pages || 1);

// Methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};
</script>

<style scoped>
/* No specific styles needed as we use Tailwind, 
   but added for any component-specific overrides if needed */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
