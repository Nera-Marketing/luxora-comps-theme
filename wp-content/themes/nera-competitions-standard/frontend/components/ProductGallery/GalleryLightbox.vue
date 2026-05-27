<template>
  <Transition name="lightbox">
    <div
      v-if="isOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4"
      @click.self="handleClose"
    >
      <!-- Close Button -->
      <button
        class="absolute top-4 right-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors"
        @click="handleClose"
        aria-label="Close lightbox"
      >
        <span class="material-symbols-outlined text-2xl">close</span>
      </button>

      <!-- Lightbox Slider -->
      <div
        ref="sliderContainer"
        class="w-full max-w-5xl h-[85vh] flex items-center relative"
        id="gallery-lightbox-slider"
      >
        <div class="keen-slider h-full w-full">
          <div
            v-for="(image, index) in images"
            :key="index"
            class="keen-slider__slide h-full flex items-center justify-center"
          >
            <img
              :src="image.full"
              :alt="image.alt || `Image ${index + 1}`"
              :class="[
                'max-w-full max-h-full object-contain transition-transform duration-300',
                zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in',
              ]"
              :style="
                zoomed
                  ? {
                      transform: 'scale(2)',
                      transformOrigin: `${zoomOrigin.x}% ${zoomOrigin.y}%`,
                    }
                  : {}
              "
              @click="handleImageClick"
              @mousemove="handleMouseMove"
            />
          </div>
        </div>

        <!-- Navigation Arrows -->
        <template v-if="images.length > 1">
          <button
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 rounded-full hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10"
            @click="handlePrev"
            aria-label="Previous image"
          >
            <span class="material-symbols-outlined text-lg">chevron_left</span>
          </button>
          <button
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 rounded-full hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10"
            @click="handleNext"
            aria-label="Next image"
          >
            <span class="material-symbols-outlined text-lg">chevron_right</span>
          </button>
        </template>
      </div>

      <!-- Image Counter -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm">
        {{ currentIndex + 1 }} / {{ images.length }}
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';
import KeenSlider from 'keen-slider';
import 'keen-slider/keen-slider.min.css';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  images: {
    type: Array,
    required: true,
  },
  currentIndex: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['close', 'slideChange']);

const sliderContainer = ref(null);
const sliderInstance = ref(null);
const zoomed = ref(false);
const zoomOrigin = ref({ x: 50, y: 50 });

// Watch for lightbox opening/closing
watch(
  () => props.isOpen,
  newVal => {
    if (newVal) {
      initSlider();
      document.addEventListener('keydown', handleKeyDown);
      document.body.style.overflow = 'hidden';
    } else {
      cleanup();
    }
  }
);

// Watch for currentIndex changes to update slider
watch(
  () => props.currentIndex,
  newIndex => {
    if (sliderInstance.value && props.isOpen) {
      sliderInstance.value.moveToIdx(newIndex);
    }
  }
);

const initSlider = () => {
  // Wait for next tick to ensure DOM is ready
  setTimeout(() => {
    const lightboxEl = document.getElementById('gallery-lightbox-slider');
    if (!lightboxEl) return;

    sliderInstance.value = new KeenSlider(lightboxEl, {
      initial: props.currentIndex,
      loop: false,
      slideChanged: slider => {
        emit('slideChange', slider.track.details.rel);
        zoomed.value = false; // Reset zoom on slide change
      },
    });
  }, 0);
};

const cleanup = () => {
  document.removeEventListener('keydown', handleKeyDown);
  document.body.style.overflow = '';
  if (sliderInstance.value) {
    sliderInstance.value.destroy();
    sliderInstance.value = null;
  }
};

const handleKeyDown = e => {
  if (e.key === 'Escape') {
    handleClose();
  } else if (e.key === 'ArrowLeft') {
    handlePrev();
  } else if (e.key === 'ArrowRight') {
    handleNext();
  }
};

const handleClose = () => {
  emit('close');
};

const handlePrev = () => {
  sliderInstance.value?.prev();
};

const handleNext = () => {
  sliderInstance.value?.next();
};

const handleImageClick = e => {
  if (zoomed.value) {
    zoomed.value = false;
  } else {
    // Calculate zoom origin from click position
    const rect = e.currentTarget.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    zoomOrigin.value = { x, y };
    zoomed.value = true;
  }
};

const handleMouseMove = e => {
  if (!zoomed.value) return;
  const rect = e.currentTarget.getBoundingClientRect();
  const x = ((e.clientX - rect.left) / rect.width) * 100;
  const y = ((e.clientY - rect.top) / rect.height) * 100;
  zoomOrigin.value = { x, y };
};

onUnmounted(() => {
  cleanup();
});
</script>

<style scoped>
.lightbox-enter-active,
.lightbox-leave-active {
  transition: opacity 0.3s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
  opacity: 0;
}
</style>
