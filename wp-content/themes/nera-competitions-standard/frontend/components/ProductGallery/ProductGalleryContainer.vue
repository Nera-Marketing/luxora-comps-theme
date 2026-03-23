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
          slides: { perView: 3, spacing: 8 },
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
        const rel = slider.track.details.rel;
        currentSlide.value = rel;
        // Sync thumbnails (image slides only; video thumb has no main slide)
        if (thumbsInstance.value && data.images?.length) {
          const thumbIdx = Math.min(rel, data.images.length - 1);
          thumbsInstance.value.moveToIdx(thumbIdx);
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

  // Attach thumbnail click handlers (video thumb opens URL, does not map to main slides)
  const imageCount = data.images?.length ?? 0;
  const thumbSlides = thumbsEl?.querySelectorAll('.keen-slider__slide');
  if (thumbSlides) {
    thumbSlides.forEach((slide, index) => {
      slide.onclick = () => {
        if (slide.hasAttribute('data-video-thumb') && data.videoUrl) {
          window.open(data.videoUrl, '_blank', 'noopener,noreferrer');
          return;
        }
        if (mainInstance.value && index < imageCount) {
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
