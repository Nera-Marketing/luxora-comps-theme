<template>
  <article
    class="nera-winner-card group relative bg-earthy-surface rounded-xl md:rounded-3xl overflow-hidden transition-all duration-500 ease-out border border-earthy-bronze-20"
    :class="[isVisible ? 'opacity-100' : 'opacity-0']"
    :style="cardEntranceStyle"
    @mouseenter="onHoverStart"
    @mouseleave="onHoverEnd"
    @mousemove="updateMousePosition"
  >
    <!-- Prize Image with Parallax -->
    <div class="relative aspect-[4/3] overflow-hidden">
      <!-- Image Container with Parallax -->
      <div
        class="absolute inset-0 transition-transform duration-700 ease-out"
        :style="imageTransformStyle"
      >
        <div
          v-if="winner.image"
          class="w-full h-full bg-center bg-no-repeat bg-cover"
          :style="{ backgroundImage: `url('${winner.image}')` }"
        ></div>
        <div
          v-else
          class="w-full h-full flex items-center justify-center bg-gradient-to-br from-earthy-terracotta-15 to-earthy-bronze-5"
        >
          <svg
            class="w-10 h-10 md:w-16 md:h-16 text-earthy-bronze-20"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
          >
            <path
              d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
            />
          </svg>
        </div>
      </div>

      <!-- Shine Overlay on Hover -->
      <div
        class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none shine-overlay"
      ></div>

      <!-- Category Badge with Pulse -->
      <div
        class="absolute top-4 left-4 z-10 text-white text-[10px] font-bold px-2 py-1 md:px-3 md:py-1.5 rounded-full uppercase tracking-widest transition-all duration-300"
        :class="categoryBadgeClass"
        :style="badgeStyle"
      >
        {{ categoryLabel }}
      </div>
    </div>

    <!-- Card Content -->
    <div class="p-3 md:p-6">
      <!-- Winner Name with Shimmer -->
      <h3 class="text-sm md:text-lg font-bold text-earthy-bronze mb-1 md:mb-2 relative">
        <span class="relative z-10">{{ winner.name }}</span>
        <span
          class="absolute inset-0 bg-gradient-to-r from-transparent via-earthy-terracotta-10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 text-shimmer"
        ></span>
      </h3>

      <!-- Prize -->
      <p class="text-xs md:text-sm text-earthy-terracotta font-semibold mb-2 md:mb-3">
        {{ winner.prize }}
      </p>

      <!-- Date -->
      <div v-if="winner.date" class="flex items-center gap-1 md:gap-2 text-xs md:text-sm text-earthy-bronze-56 mb-2 md:mb-4">
        <span class="material-symbols-outlined text-[14px] md:text-[18px]">calendar_today</span>
        <span>{{ winner.date }}</span>
      </div>

      <!-- Quote -->
      <div v-if="showQuotes && winner.quote" class="hidden md:block pt-4 border-t border-earthy-bronze-20">
        <div class="relative">
          <svg
            class="absolute -top-1 -left-1 w-6 h-6 text-earthy-bronze-20"
            fill="currentColor"
            viewBox="0 0 32 32"
          >
            <path
              d="M10 8c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8zm16 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"
            />
          </svg>
          <p class="text-sm text-earthy-bronze-56 italic pl-6 line-clamp-3">
            {{ winner.quote }}
          </p>
        </div>
      </div>
    </div>

    <!-- Decorative Corner Accents (appear on hover) -->
    <div
      class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-earthy-bronze-30 opacity-0 group-hover:opacity-100 transition-all duration-500 scale-75 group-hover:scale-100"
    ></div>
    <div
      class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-earthy-bronze-30 opacity-0 group-hover:opacity-100 transition-all duration-500 scale-75 group-hover:scale-100"
    ></div>
  </article>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';

/**
 * Winner Card Component - Enhanced with Premium Animations
 *
 * Displays individual winner information with:
 * - Trophy reveal entrance animation (3D rotation + scale)
 * - Parallax image hover effect
 * - Shine overlay and corner accents
 * - Category badge with pulse
 * - Winner name, prize, and date
 * - Optional quote
 */
const props = defineProps({
  winner: {
    type: Object,
    required: true,
    validator: value => {
      return value.name && value.prize && value.category;
    },
  },
  showQuotes: {
    type: Boolean,
    default: true,
  },
  index: {
    type: Number,
    default: 0,
  },
});

// State
const isVisible = ref(false);
const isHovered = ref(false);
const mouseX = ref(0.5);
const mouseY = ref(0.5);

// Trigger entrance animation on mount
onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 50);
});

/**
 * Stagger with easing curve (not linear)
 * Creates more natural cascading effect
 */
const entranceDelay = computed(() => {
  const baseDelay = (props.index % 12) * 80;
  const easingFactor = 1 + (props.index % 12) * 0.05;
  return Math.floor(baseDelay * easingFactor);
});

/**
 * Card entrance style with trophy reveal animation
 */
const cardEntranceStyle = computed(() => ({
  animationDelay: `${entranceDelay.value}ms`,
  animation: isVisible.value ? 'trophy-reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards' : 'none',
  '--entrance-rotation': `${((props.index % 3) - 1) * 2}deg`,
}));

/**
 * Image parallax transform on hover
 */
const imageTransformStyle = computed(() => {
  if (!isHovered.value) return {};

  const offsetX = (mouseX.value - 0.5) * 10;
  const offsetY = (mouseY.value - 0.5) * 10;

  return {
    transform: `scale(1.08) translate(${offsetX}px, ${offsetY}px)`,
  };
});

/**
 * Badge pulse animation on hover
 */
const badgeStyle = computed(() => ({
  animation: isHovered.value ? 'badge-pulse 1.5s ease-in-out infinite' : 'none',
}));

/**
 * Category badge label (from API category_label, or fallback for legacy data)
 */
const categoryLabel = computed(() => {
  if (props.winner.category_label) return props.winner.category_label;
  return props.winner.category === 'instant-win' ? 'Instant Win' : 'Live Draw';
});

/**
 * Category badge color class - Earthy theme (all categories use same style)
 */
const categoryBadgeClass = computed(() => {
  return 'transition-all duration-300 bg-earthy-bg/70 text-earthy-bronze group-hover:shadow-lg group-hover:shadow-earthy-glow';
});

/**
 * Hover handlers
 */
const onHoverStart = event => {
  isHovered.value = true;
  updateMousePosition(event);
};

const onHoverEnd = () => {
  isHovered.value = false;
  mouseX.value = 0.5;
  mouseY.value = 0.5;
};

const updateMousePosition = event => {
  const rect = event.currentTarget.getBoundingClientRect();
  mouseX.value = (event.clientX - rect.left) / rect.width;
  mouseY.value = (event.clientY - rect.top) / rect.height;
};
</script>

<style scoped>
/* Trophy Reveal Entrance Animation */
@keyframes trophy-reveal {
  0% {
    opacity: 0;
    transform: translateY(40px) scale(0.92) rotateX(8deg) rotateZ(var(--entrance-rotation, 0deg));
  }
  60% {
    transform: translateY(-5px) scale(1.02) rotateX(0deg) rotateZ(0deg);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1) rotateX(0deg) rotateZ(0deg);
  }
}

/* Shine Sweep Effect */
.shine-overlay {
  background: linear-gradient(
    135deg,
    transparent 0%,
    rgba(255, 255, 255, 0.3) 50%,
    transparent 100%
  );
  background-size: 200% 200%;
}

.group:hover .shine-overlay {
  animation: shine-sweep 1.5s ease-out;
}

@keyframes shine-sweep {
  0% {
    background-position: -200% center;
  }
  100% {
    background-position: 200% center;
  }
}

/* Text Shimmer on Hover */
.text-shimmer {
  animation: text-shimmer 2s ease-in-out infinite;
}

@keyframes text-shimmer {
  0%,
  100% {
    transform: translateX(-100%);
  }
  50% {
    transform: translateX(100%);
  }
}

/* Badge Pulse */
@keyframes badge-pulse {
  0%,
  100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  @keyframes trophy-reveal {
    0% {
      opacity: 0;
    }
    100% {
      opacity: 1;
    }
  }

  .nera-winner-card,
  .nera-winner-card * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>
