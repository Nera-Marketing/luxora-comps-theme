<template>
  <section>
    <div class="container mx-auto max-w-7xl">
      <!-- Screen reader announcements -->
      <div role="status" aria-live="polite" aria-atomic="true" class="sr-only">
        <span v-if="showLoadingOverlay">Loading winners...</span>
        <span v-else-if="winners.length > 0"
          >Showing {{ winners.length }} of {{ pagination.total_items }} winners</span
        >
      </div>

      <!-- Filter Tabs - Static from mount, no skeleton swap -->
      <FilterTabs
        v-if="!error"
        :active-filter="activeFilter"
        :filters="staticFilters"
        :disabled="showLoadingOverlay"
        :on-filter-change="handleFilterChange"
      />

      <!-- Initial Loading State: Skeleton Cards Only -->
      <div v-if="initialLoading" class="space-y-8">
        <!-- Skeleton Winner Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
          <div
            v-for="i in 12"
            :key="i"
            class="skeleton-card bg-mint-wash rounded-3xl overflow-hidden border border-ink-20"
            :style="{ animationDelay: `${i * 60}ms` }"
          >
            <!-- Image Skeleton with Multi-Pass Shimmer -->
            <div class="relative aspect-[4/3] bg-ink-8 overflow-hidden">
              <div class="absolute inset-0 skeleton-shimmer"></div>
              <div class="absolute inset-0 skeleton-shimmer" style="animation-delay: 0.7s"></div>

              <!-- Badge Skeleton -->
              <div
                class="absolute top-4 left-4 w-16 h-5 md:w-24 md:h-7 bg-ink-12 rounded-full animate-pulse"
              ></div>
            </div>

            <!-- Content Skeleton -->
            <div class="p-3 md:p-6 space-y-2 md:space-y-3">
              <div class="h-5 bg-ink-8 rounded w-3/4 animate-pulse"></div>
              <div
                class="h-4 bg-ink-8 rounded w-full animate-pulse"
                style="animation-delay: 0.1s"
              ></div>
              <div class="flex items-center gap-2">
                <div
                  class="w-4 h-4 bg-ink-8 rounded animate-pulse"
                  style="animation-delay: 0.2s"
                ></div>
                <div
                  class="h-3 bg-ink-8 rounded w-24 animate-pulse"
                  style="animation-delay: 0.2s"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Winners Grid Content (after initial load) -->
      <div v-else-if="!error" class="relative">
        <!-- Lightweight Loading Overlay (debounced) -->
        <Transition name="overlay-fade">
          <div
            v-if="showLoadingOverlay"
            class="absolute inset-0 flex items-start justify-center pt-20 z-20 pointer-events-none"
          >
            <div
            class="flex flex-col items-center gap-4 px-6 py-4 bg-mint-wash rounded-2xl shadow-2xl border border-ink-20 pointer-events-auto"
          >
              <div
                class="w-10 h-10 rounded-full border-3 border-ink-20 border-t-ink animate-spin"
              ></div>
              <p class="text-sm font-semibold text-ink">Loading winners...</p>
            </div>
          </div>
        </Transition>

        <!-- Winners Grid with fade on loading -->
        <TransitionGroup
          name="winners-grid"
          tag="div"
          class="relative grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 transition-opacity duration-200"
          :class="{ 'opacity-40 pointer-events-none': showLoadingOverlay }"
        >
          <WinnerCard
            v-for="(winner, index) in winners"
            :key="winner.id"
            :winner="winner"
            :index="index"
            :show-quotes="showQuotes"
          />
        </TransitionGroup>

        <!-- Empty State -->
        <div v-if="winners.length === 0 && !showLoadingOverlay" class="text-center py-20">
          <div
            class="w-20 h-20 mx-auto mb-6 rounded-full bg-mint-wash flex items-center justify-center"
          >
            <span class="material-symbols-outlined text-4xl text-ink-56">search_off</span>
          </div>
          <h3 class="text-xl font-bold text-ink mb-2">No winners found</h3>
          <p class="text-sm text-ink-56">Try selecting a different filter</p>
        </div>

        <!-- Load More Button -->
        <LoadMoreButton
          v-if="hasMore && winners.length > 0"
          :loading="loadingMore"
          :visible-count="winners.length"
          :total-count="pagination.total_items"
          :on-load-more="handleLoadMore"
        />
      </div>

      <!-- Error State -->
      <div v-if="error" class="max-w-md mx-auto text-center py-12 animate-[fadeIn_0.5s_ease-out]">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
          <span class="material-symbols-outlined text-3xl text-red-500">error</span>
        </div>
        <h3 class="text-lg font-bold text-ink mb-2">Failed to load winners</h3>
        <p class="text-sm text-ink-56 mb-4">{{ error }}</p>
        <button
          @click="retryFetch"
          class="px-6 py-2.5 bg-gradient-to-r from-forest to-sage text-ink rounded-lg font-semibold hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
        >
          Try Again
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useInfiniteQuery } from '@tanstack/vue-query';
import FilterTabs from './FilterTabs.vue';
import WinnerCard from './WinnerCard.vue';
import LoadMoreButton from './LoadMoreButton.vue';

/**
 * Main Winners Container - Enhanced with TanStack Query
 *
 * Responsibilities:
 * - Fetch winners from REST API with intelligent caching
 * - Manage three loading states: initial, filter, pagination
 * - Handle category filtering with instant cached responses
 * - Handle "Load More" pagination with automatic deduplication
 * - Render filter tabs, winner cards, and pagination
 *
 * TanStack Query Features:
 * - Automatic request deduplication (coalesces simultaneous requests)
 * - Smart caching (5min stale time matches server cache)
 * - Automatic retry with exponential backoff for 429 errors
 * - Request cancellation when filters change
 */
const props = defineProps({
  pageId: {
    type: Number,
    required: true,
  },
  perPage: {
    type: Number,
    default: 12,
  },
  showQuotes: {
    type: Boolean,
    default: true,
  },
});

// UI State (TanStack Query manages data state)
const activeFilter = ref('all');

// Add debounced loading overlay state
const showLoadingOverlay = ref(false);
let loadingDebounceTimer = null;

/**
 * Fetch winners data for a specific page
 * Pure function used by TanStack Query
 * @param {Object} params - Query parameters
 * @param {number} params.pageParam - Current page number
 * @param {Array} params.queryKey - Query key: [_key, category, perPage]
 * @returns {Promise} API response data
 */
const fetchWinnersPage = async ({ pageParam = 1, queryKey }) => {
  const [_key, category, perPage] = queryKey;

  const params = new URLSearchParams({
    page: pageParam.toString(),
    per_page: perPage.toString(),
    category,
  });

  const response = await fetch(`/wp-json/nera/v1/winners?${params}`);

  if (!response.ok) {
    const error = new Error(`HTTP error! status: ${response.status}`);
    error.status = response.status;

    if (response.status === 429) {
      error.message = 'Too many requests. Please wait a moment and try again.';
    }

    throw error;
  }

  const result = await response.json();

  if (!result.success) {
    throw new Error(result.message || 'Failed to load winners');
  }

  return result.data;
};

/**
 * Infinite query for winners list with pagination
 * TanStack Query handles caching, deduplication, and request management
 */
const {
  data: winnersData,
  error,
  fetchNextPage,
  hasNextPage,
  isFetching,
  isLoading,
  isFetchingNextPage,
  refetch,
} = useInfiniteQuery({
  queryKey: ['winners', activeFilter, props.perPage],
  queryFn: fetchWinnersPage,

  // Pagination config
  initialPageParam: 1,
  getNextPageParam: lastPage => {
    return lastPage.pagination.has_more ? lastPage.pagination.current_page + 1 : undefined;
  },

  // Cache config (matches server-side cache)
  staleTime: 5 * 60 * 1000, // 5 minutes
  gcTime: 10 * 60 * 1000, // 10 minutes

  // Retry config for rate limits
  retry: (failureCount, error) => {
    if (error.status === 429) {
      return failureCount < 2; // Retry 429s twice
    }
    return failureCount < 1; // Retry other errors once
  },
});

// Flatten paginated results into single array
const winners = computed(() => {
  if (!winnersData.value?.pages) return [];
  return winnersData.value.pages.flatMap(page => page.winners);
});

// Get pagination info from first page
const pagination = computed(() => {
  if (!winnersData.value?.pages?.[0]) {
    return {
      total: 0,
      total_items: 0,
      current_page: 1,
      total_pages: 1,
      has_more: false,
    };
  }
  return winnersData.value.pages[0].pagination;
});

// Lock filters after first successful response (API returns filters.items)
const defaultFilterItems = [
  { value: 'all', label: 'All Winners', count: 0 },
  { value: 'live-draw', label: 'Live Draw', count: 0 },
  { value: 'instant-win', label: 'Instant Win', count: 0 },
];
const staticFilters = ref({ items: defaultFilterItems });
const filtersLocked = ref(false);

watch(
  () => winnersData.value?.pages?.[0]?.filters,
  newFilters => {
    if (!newFilters || filtersLocked.value) return;
    let items;
    if (newFilters.items?.length > 0) {
      items = newFilters.items;
    } else if (typeof newFilters.all_count === 'number') {
      items = [
        { value: 'all', label: 'All Winners', count: newFilters.all_count || 0 },
        { value: 'live-draw', label: 'Live Draws', count: newFilters.live_draw_count || 0 },
        { value: 'instant-win', label: 'Instant Wins', count: newFilters.instant_win_count || 0 },
      ];
    }
    if (items) {
      staticFilters.value = { items };
      filtersLocked.value = true;
    }
  },
  { immediate: true }
);

// Computed loading states for template
const initialLoading = computed(() => isLoading.value);
const loadingMore = computed(() => isFetchingNextPage.value);
const hasMore = computed(() => hasNextPage.value);

/**
 * Handle filter change
 * TanStack Query automatically handles caching and request management
 * @param {string} filter - Category filter value
 */
const handleFilterChange = filter => {
  if (activeFilter.value === filter) return;
  activeFilter.value = filter;
  // That's it! TanStack Query detects queryKey change and handles the rest:
  // - Checks cache for this filter
  // - Returns cached data instantly if available
  // - Refetches in background if stale
  // - Deduplicates if another component is fetching same data
};

/**
 * Handle load more pagination
 * TanStack Query's fetchNextPage handles everything automatically
 */
const handleLoadMore = () => {
  if (hasNextPage.value && !isFetchingNextPage.value) {
    fetchNextPage();
  }
};

// Watch isFetching with 150ms debounce
watch(
  () => isFetching.value && !isFetchingNextPage.value,
  isCurrentlyFetching => {
    if (isCurrentlyFetching) {
      // Only show overlay if loading takes > 150ms
      loadingDebounceTimer = setTimeout(() => {
        showLoadingOverlay.value = true;
      }, 150);
    } else {
      // Clear timer and hide overlay immediately
      if (loadingDebounceTimer) {
        clearTimeout(loadingDebounceTimer);
        loadingDebounceTimer = null;
      }
      showLoadingOverlay.value = false;
    }
  }
);

/**
 * Retry fetch on error
 */
const retryFetch = () => {
  refetch();
};
</script>

<style scoped>
/* Skeleton Card Entrance */
.skeleton-card {
  animation: skeleton-fade-in 0.6s ease-out forwards;
  opacity: 0;
}

@keyframes skeleton-fade-in {
  to {
    opacity: 1;
  }
}

/* Multi-Pass Shimmer Effect */
.skeleton-shimmer {
  background: linear-gradient(
    90deg,
    transparent 0%,
    var(--color-ink-10) 50%,
    transparent 100%
  );
  background-size: 200% 100%;
  animation: shimmer-pass 2s ease-in-out infinite;
}

@keyframes shimmer-pass {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Faster Grid Transitions (300ms from 500ms) */
.winners-grid-move,
.winners-grid-enter-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.winners-grid-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  position: absolute;
}

.winners-grid-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.winners-grid-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

/* Fade In Keyframe */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Overlay Fade Transition */
.overlay-fade-enter-active {
  transition: opacity 0.15s ease-out;
}

.overlay-fade-leave-active {
  transition: opacity 0.1s ease-in;
}

.overlay-fade-enter-from,
.overlay-fade-leave-to {
  opacity: 0;
}

@media (prefers-reduced-motion: reduce) {
  .skeleton-card,
  .skeleton-shimmer,
  .winners-grid-move,
  .winners-grid-enter-active,
  .winners-grid-leave-active {
    animation: none !important;
    transition: none !important;
  }
}
</style>
