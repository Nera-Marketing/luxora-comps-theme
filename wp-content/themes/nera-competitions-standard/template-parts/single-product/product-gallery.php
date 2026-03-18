<?php
/**
 * Product Gallery Template Part
 *
 * Unified gallery template for all product types (competitions, lottery).
 * Combines features from gallery.php and hero-gallery.php.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

// Normalize input - accept both 'images' and 'gallery_images'
$images = $args['images'] ?? ($args['gallery_images'] ?? []);
$product = $args['product'] ?? null;
$badge_text = $args['badge_text'] ?? __('Featured Prize', 'nera-competitions');
$badge_color = $args['badge_color'] ?? 'red';
$video_url = $args['video_url'] ?? '';

if (!$product) {
  return;
}

// Fallback if no images
if (empty($images)) {
  $images = [
    [
      'id' => 0,
      'full' => wc_placeholder_img_src('full'),
      'large' => wc_placeholder_img_src('large'),
      'thumbnail' => wc_placeholder_img_src('thumbnail'),
      'alt' => __('Product Image', 'nera-competitions'),
    ],
  ];
}

// Thumbnail settings
$visible_thumbs = 4; // Desktop: 4, Mobile: 2 (handled by responsive classes)
$extra_images = max(0, count($images) - $visible_thumbs);

// Badge color classes
$badge_classes_map = [
  'red' => 'bg-red-500 text-white',
  'primary' => 'bg-primary text-white',
  'orange' => 'bg-orange-500 text-white',
  'green' => 'bg-green-500 text-white',
  'blue' => 'bg-blue-500 text-white',
];
$badge_classes = $badge_classes_map[$badge_color] ?? $badge_classes_map['red'];
?>

<!-- Main Gallery Container -->
<div class="product-gallery" data-product-gallery data-product-id="<?php echo esc_attr(
  $product->get_id(),
); ?>">
  
  <!-- Main Image Display -->
  <div class="relative rounded-2xl overflow-hidden shadow-lg group">
    
    <!-- Badge (if provided) -->
    <?php if ($badge_text): ?>
      <div class="absolute top-4 left-4 z-20">
        <span class="<?php echo esc_attr(
          $badge_classes,
        ); ?> text-xs font-bold px-4 py-2 rounded-md uppercase tracking-wider shadow-lg">
          <?php echo esc_html($badge_text); ?>
        </span>
      </div>
    <?php endif; ?>
    
    <!-- Keen Slider Main (React will hydrate here) -->
    <div class="keen-slider aspect-[4/3]" data-gallery-main>
      <?php foreach ($images as $index => $image): ?>
        <div class="keen-slider__slide relative cursor-pointer flex items-center justify-center" data-lightbox-trigger data-index="<?php echo esc_attr(
          $index,
        ); ?>">
          <img 
            src="<?php echo esc_url($image['large']); ?>"
            alt="<?php echo esc_attr($image['alt'] ?: $product->get_name()); ?>"
            class="w-full h-full object-contain"
            loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
          />
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Navigation Arrows (React will control these) -->
    <?php if (count($images) > 1): ?>
      <button 
        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 rounded-full shadow-lg flex items-center justify-center hover:bg-white transition-colors opacity-0 group-hover:opacity-100"
        data-gallery-prev
        aria-label="<?php esc_attr_e('Previous image', 'nera-competitions'); ?>"
      >
        <span class="material-symbols-outlined text-gray-700">chevron_left</span>
      </button>
      <button 
        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 rounded-full shadow-lg flex items-center justify-center hover:bg-white transition-colors opacity-0 group-hover:opacity-100"
        data-gallery-next
        aria-label="<?php esc_attr_e('Next image', 'nera-competitions'); ?>"
      >
        <span class="material-symbols-outlined text-gray-700">chevron_right</span>
      </button>
    <?php endif; ?>
  </div>
  
  <!-- Thumbnail Navigation -->
  <?php if (count($images) > 1 || $video_url): ?>
    <div class="mt-4 flex items-center gap-3">
      <div class="keen-slider flex-1" data-gallery-thumbs>
        <?php foreach (array_slice($images, 0, $visible_thumbs) as $index => $image): ?>
          <div class="keen-slider__slide !w-20 !h-20 sm:!w-24 sm:!h-20 cursor-pointer">
            <div class="w-full h-full rounded-lg overflow-hidden border-2 border-transparent transition-all hover:border-primary/50 bg-gray-100">
              <img 
                src="<?php echo esc_url($image['thumbnail']); ?>"
                alt="<?php echo esc_attr(
                  sprintf(__('Thumbnail %d', 'nera-competitions'), $index + 1),
                ); ?>"
                class="w-full h-full object-cover"
                loading="lazy"
              />
            </div>
          </div>
        <?php endforeach; ?>
        
        <?php if ($video_url): ?>
          <div class="keen-slider__slide !w-20 !h-20 sm:!w-24 sm:!h-20 cursor-pointer">
            <div class="w-full h-full rounded-lg overflow-hidden border-2 border-transparent bg-gray-900 flex items-center justify-center transition-all hover:border-primary/50">
              <span class="material-symbols-outlined text-white text-2xl">play_arrow</span>
            </div>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Show All Images Button -->
      <?php if ($extra_images > 0): ?>
        <button
          class="flex-shrink-0 w-20 h-20 sm:w-24 sm:h-20 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center text-text-secondary hover:border-primary hover:text-primary transition-colors"
          data-show-all-images
          aria-label="<?php echo esc_attr(
            sprintf(__('Show all %d images', 'nera-competitions'), count($images)),
          ); ?>"
        >
          <span class="text-sm font-medium">+<?php echo $extra_images; ?></span>
        </button>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Output gallery data for Vue hydration -->
<script>
  window.productGalleryData = window.productGalleryData || {};
  window.productGalleryData[<?php echo $product->get_id(); ?>] = <?php echo wp_json_encode([
  'images' => $images,
  'productId' => $product->get_id(),
  'videoUrl' => $video_url,
  'totalImages' => count($images),
]); ?>;
</script>

<!-- Vue mount point -->
<div id="product-gallery-vue-root"></div>
