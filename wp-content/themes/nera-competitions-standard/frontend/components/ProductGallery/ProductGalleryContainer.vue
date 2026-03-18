<template>
  <GalleryLightbox
    v-if="mounted && galleryData"
    :isOpen="lightboxOpen"
    :images="galleryData.images"
    :currentIndex="currentSlide"
    @close="lightboxOpen = false"
    @slideChange="handleSlideChange"
  />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import KeenSlider from 'keen-slider';
import 'keen-slider/keen-slider.min.css';
import GalleryLightbox from './GalleryLightbox.vue';

const mounted = ref(false);
const lightboxOpen = ref(false);
const currentSlide = ref(0);
const galleryData = ref(null);

const mainSliderRef = ref(null);
const thumbsSliderRef = ref(null);
const mainInstance = ref(null);
const thumbsInstance = ref(null);

const handleSlideChange = index => {
  currentSlide.value = index;
};

onMounted(() => {
  // Find gallery element
  const galleryEl = document.querySelector('[data-product-gallery]');
  if (!galleryEl) return;

  // Get product ID and data
  const productId = galleryEl.dataset.productId;
  const data = window.productGalleryData?.[productId];

  if (!data) {
    console.warn('Product gallery data not found');
    return;
  }

  galleryData.value = data;

  // Initialize thumbnails slider first
  const thumbsEl = galleryEl.querySelector('[data-gallery-thumbs]');
  if (thumbsEl) {
    thumbsInstance.value = new KeenSlider(thumbsEl, {
      slides: {
        perView: 4,
        spacing: 12,
      },
      breakpoints: {
        '(max-width: 640px)': {
          slides: { perView: 2, spacing: 8 },
        },
      },
    });
  }

  // Initialize main slider
  const mainEl = galleryEl.querySelector('[data-gallery-main]');
  if (mainEl) {
    mainInstance.value = new KeenSlider(mainEl, {
      loop: false,
      slideChanged: slider => {
        currentSlide.value = slider.track.details.rel;
        // Sync thumbnails
        if (thumbsInstance.value) {
          thumbsInstance.value.moveToIdx(slider.track.details.rel);
        }
      },
    });

    mainSliderRef.value = mainEl;
  }

  // Attach navigation button handlers
  const prevBtn = galleryEl.querySelector('[data-gallery-prev]');
  const nextBtn = galleryEl.querySelector('[data-gallery-next]');

  if (prevBtn && mainInstance.value) {
    prevBtn.onclick = () => mainInstance.value.prev();
  }
  if (nextBtn && mainInstance.value) {
    nextBtn.onclick = () => mainInstance.value.next();
  }

  // Attach lightbox triggers
  const triggers = galleryEl.querySelectorAll('[data-lightbox-trigger]');
  triggers.forEach(trigger => {
    trigger.onclick = e => {
      e.preventDefault();
      const index = parseInt(trigger.dataset.index, 10);
      currentSlide.value = index;
      lightboxOpen.value = true;
    };
  });

  // Attach "Show All Images" button
  const showAllBtn = galleryEl.querySelector('[data-show-all-images]');
  if (showAllBtn) {
    showAllBtn.onclick = () => {
      lightboxOpen.value = true;
    };
  }

  // Attach thumbnail click handlers
  const thumbSlides = thumbsEl?.querySelectorAll('.keen-slider__slide');
  if (thumbSlides) {
    thumbSlides.forEach((slide, index) => {
      slide.onclick = () => {
        if (mainInstance.value) {
          mainInstance.value.moveToIdx(index);
        }
      };
    });
  }

  mounted.value = true;
});

onUnmounted(() => {
  // Cleanup
  mainInstance.value?.destroy();
  thumbsInstance.value?.destroy();
});
</script>
