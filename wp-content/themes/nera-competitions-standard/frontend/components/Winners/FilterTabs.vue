<template>
  <div ref="rootRef" class="flex justify-center mb-12">
    <!-- Mobile: Scrollable chips (below md) -->
    <div class="relative w-full md:hidden">
      <!-- Right-fade hint – hints at horizontal scrollability -->
      <div class="absolute right-0 top-0 bottom-0 w-10 z-10 bg-gradient-to-l from-off-white to-transparent pointer-events-none"></div>

      <!-- Chip row -->
      <div ref="mobileScrollRef" class="flex gap-2 overflow-x-auto hide-scrollbar px-4 py-2">
        <button
          v-for="filter in filtersList"
          :key="filter.value"
          ref="mobileChipRefs"
          type="button"
          @click="selectFilter(filter.value)"
          :disabled="disabled"
          :aria-busy="disabled"
          :aria-disabled="disabled"
          class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300 whitespace-nowrap disabled:cursor-not-allowed"
          :class="[
            activeFilter === filter.value
              ? 'bg-gradient-to-r from-forest to-sage text-ink shadow-sage-20'
              : 'bg-mint-wash text-ink-56 border border-ink-20 active:bg-ink-10',
            disabled && activeFilter !== filter.value ? 'opacity-50' : '',
          ]"
        >
          <span>{{ filter.label }}</span>
          <!-- Count Badge -->
          <span
            class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold"
            :class="
              activeFilter === filter.value
                ? 'bg-ink-20 text-ink'
                : 'bg-ink-10 text-ink-56'
            "
          >{{ filter.count }}</span>
        </button>
      </div>
    </div>

    <!-- Desktop: Tab bar (md+) -->
    <div class="hidden md:block">
      <div class="relative inline-flex bg-mint-wash border border-ink-20 rounded-xl p-1.5 gap-1">
        <!-- Sliding Background Indicator -->
        <div
          class="absolute h-[calc(100%-12px)] rounded-lg bg-gradient-to-br from-forest to-sage shadow-lg transition-all duration-500 ease-out"
          :style="indicatorStyle"
        ></div>

        <!-- Filter Buttons -->
        <button
          v-for="filter in filtersList"
          :key="filter.value"
          ref="buttonRefs"
          type="button"
          @click="selectFilter(filter.value)"
          :disabled="disabled"
          :aria-busy="disabled"
          :aria-disabled="disabled"
          class="relative z-10 px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 disabled:cursor-not-allowed"
          :class="[
            activeFilter === filter.value
              ? 'text-ink'
              : 'text-ink-56 hover:text-ink hover:-translate-y-0.5',
            disabled && activeFilter !== filter.value ? 'opacity-50' : '',
          ]"
        >
          <span class="relative z-10">{{ filter.label }}</span>

          <!-- Count Badge -->
          <span
            class="relative z-10 ml-2 inline-flex items-center justify-center min-w-[24px] h-6 px-2 rounded-full text-xs font-bold transition-all duration-300"
            :class="
              activeFilter === filter.value
                ? 'bg-ink-20 text-ink'
                : 'bg-ink-10 text-ink-56 hover:bg-ink-15'
            "
          >
            <span class="inline-block">{{ filter.count }}</span>
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';

/**
 * Filter Tabs Component - Static filter buttons with sliding indicator
 *
 * Displays three filter buttons with:
 * - Animated sliding background indicator
 * - Count badges (static, no rerender on filter change)
 * - Disabled state support during loading
 */
const props = defineProps({
  activeFilter: {
    type: String,
    required: true,
    validator: () => true,
  },
  filters: {
    type: Object,
    required: true,
    validator: value => {
      return Array.isArray(value?.items) && value.items.every(
        item => typeof item?.value === 'string' && typeof item?.label === 'string' && typeof item?.count === 'number'
      );
    },
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  onFilterChange: {
    type: Function,
    required: true,
  },
});

// Mobile chip refs
const mobileScrollRef = ref(null);
const mobileChipRefs = ref([]);

// Button refs and widths for dynamic sliding indicator
const rootRef = ref(null);
const buttonRefs = ref([]);
const buttonWidths = ref([0, 0, 0]);
const filterChangeDebounce = ref(null);
let resizeObserver = null;

// Filter options from API (filters.items)
const filtersList = computed(() => props.filters.items || []);

// Active filter index for sliding indicator
const activeIndex = computed(() => {
  return filtersList.value.findIndex(f => f.value === props.activeFilter);
});

// Auto-scroll the active chip into view on mobile when filter changes
watch(
  () => props.activeFilter,
  () => {
    nextTick(() => {
      const activeIdx = filtersList.value.findIndex(f => f.value === props.activeFilter);
      const chip = mobileChipRefs.value[activeIdx];
      if (chip) {
        chip.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }
    });
  }
);

// Measure actual button widths for accurate sliding indicator
const measureButtonWidths = () => {
  if (buttonRefs.value && buttonRefs.value.length > 0) {
    buttonWidths.value = buttonRefs.value.map(btn => {
      return btn ? btn.offsetWidth : 140;
    });
  }
};

// Sliding indicator position with dynamic width
const indicatorStyle = computed(() => {
  const activeButtonWidth = buttonWidths.value[activeIndex.value] || 140;
  const gap = 4; // Gap between buttons
  const padding = 6; // Container padding

  // Calculate left position by summing widths of previous buttons + gaps
  let left = padding;
  for (let i = 0; i < activeIndex.value; i++) {
    left += (buttonWidths.value[i] || 140) + gap;
  }

  return {
    width: `${activeButtonWidth}px`,
    transform: `translateX(${left}px)`,
    boxShadow: 'var(--shadow-sage-20)',
  };
});

// Measure button widths after mount
let observedElement = null;

onMounted(() => {
  nextTick(() => {
    measureButtonWidths();
  });

  // Re-measure when viewport resizes (e.g. mobile to desktop)
  if (rootRef.value && typeof ResizeObserver !== 'undefined') {
    observedElement = rootRef.value;
    resizeObserver = new ResizeObserver(() => {
      nextTick(measureButtonWidths);
    });
    resizeObserver.observe(observedElement);
  }
});

onUnmounted(() => {
  if (resizeObserver && observedElement) {
    resizeObserver.unobserve(observedElement);
    resizeObserver = null;
    observedElement = null;
  }
});

const selectFilter = value => {
  if (!props.disabled && value !== props.activeFilter) {
    // Clear pending filter change
    if (filterChangeDebounce.value) {
      clearTimeout(filterChangeDebounce.value);
    }

    // Debounce to prevent rapid clicking
    filterChangeDebounce.value = setTimeout(() => {
      props.onFilterChange(value);
      filterChangeDebounce.value = null;
    }, 50);
  }
};
</script>

<style scoped>
@media (prefers-reduced-motion: reduce) {
  .relative {
    transition: none !important;
  }
}
</style>
