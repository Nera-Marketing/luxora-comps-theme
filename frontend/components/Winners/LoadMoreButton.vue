<template>
  <div class="mt-8 md:mt-12 text-center">
    <!-- Progress Text -->
    <p class="text-sm text-ink-56 mb-4 font-medium">
      Showing <span class="font-bold text-sage">{{ visibleCount }}</span> of
      <span class="font-bold text-ink">{{ totalCount }}</span> winners
    </p>

    <!-- Load More Button -->
    <button
      type="button"
      @click="onLoadMore"
      :disabled="loading"
      class="group relative px-5 py-2.5 md:px-8 md:py-4 bg-gradient-to-r from-forest to-sage text-ink rounded-xl font-bold text-sm md:text-base shadow-lg hover:shadow-primary-hover disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-500 overflow-hidden"
      :class="{ 'hover:-translate-y-1': !loading }"
    >
      <!-- Background Pulse Effect (when loading) -->
      <div v-if="loading" class="absolute inset-0 bg-white/20 rounded-xl animate-pulse-slow"></div>

      <!-- Button Content -->
      <span class="relative z-10 flex items-center justify-center gap-2 md:gap-3">
        <!-- Loading Spinner -->
        <span v-if="loading" class="material-symbols-outlined animate-spin">
          progress_activity
        </span>

        <!-- Static Icon -->
        <span
          v-else
          class="material-symbols-outlined transition-transform duration-300 group-hover:translate-y-1"
        >
          expand_more
        </span>

        <!-- Button Text -->
        <span>{{ loading ? 'Loading More Winners...' : 'Load More Winners' }}</span>
      </span>

      <!-- Hover Shine Effect -->
      <div
        class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none button-shine"
      ></div>
    </button>

    <!-- Loading Dots Indicator -->
    <div v-if="loading" class="flex justify-center gap-2 mt-4">
      <div
        v-for="i in 3"
        :key="i"
        class="w-2 h-2 rounded-full bg-sage animate-bounce"
        :style="{ animationDelay: `${i * 0.15}s` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
/**
 * Load More Button Component - Enhanced with Premium Feedback
 *
 * Displays "Load More Winners" button with:
 * - Progress text showing current/total count
 * - Multi-element loading state (pulse + spinner + dots)
 * - Hover effects (shadow, translate, shine)
 * - Material icon with animation
 */
defineProps({
  loading: {
    type: Boolean,
    default: false,
  },
  visibleCount: {
    type: Number,
    required: true,
  },
  totalCount: {
    type: Number,
    required: true,
  },
  onLoadMore: {
    type: Function,
    required: true,
  },
});
</script>

<style scoped>
/* Button Shine Animation */
.button-shine {
  background: linear-gradient(
    135deg,
    transparent 0%,
    rgba(255, 255, 255, 0.2) 50%,
    transparent 100%
  );
  background-size: 200% 200%;
  animation: button-shine 2s ease-in-out infinite;
}

@keyframes button-shine {
  0%,
  100% {
    background-position: -200% center;
  }
  50% {
    background-position: 200% center;
  }
}

/* Slow Pulse */
@keyframes pulse-slow {
  0%,
  100% {
    opacity: 0.2;
  }
  50% {
    opacity: 0.4;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 2s ease-in-out infinite;
}

@media (prefers-reduced-motion: reduce) {
  .group:hover {
    transform: none !important;
  }

  button {
    transition: none !important;
  }

  .animate-spin,
  .animate-bounce,
  .animate-pulse-slow,
  .button-shine {
    animation: none !important;
  }
}
</style>
