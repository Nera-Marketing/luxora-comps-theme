import { createApp } from 'vue';
import ProductGalleryContainer from './ProductGalleryContainer.vue';

// Wait for DOM to be ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initGallery);
} else {
  initGallery();
}

function initGallery() {
  const galleryEl = document.querySelector('[data-product-gallery]');

  if (!galleryEl) {
    return; // No gallery on this page
  }

  // Create mount point if it doesn't exist
  let mountPoint = document.getElementById('product-gallery-vue-root');
  if (!mountPoint) {
    mountPoint = document.createElement('div');
    mountPoint.id = 'product-gallery-vue-root';
    document.body.appendChild(mountPoint);
  }

  // Create Vue app and mount
  createApp(ProductGalleryContainer).mount(mountPoint);
}

export default ProductGalleryContainer;
