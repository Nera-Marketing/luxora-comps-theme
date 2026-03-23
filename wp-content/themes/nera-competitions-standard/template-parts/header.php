<?php
/**
 * Custom Header Template Part
 * 
 * Sticky navigation with dynamic logo, menu, and CTA buttons
 * All elements editable through WordPress admin
 * 
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

// Get site info
$site_name = get_bloginfo('name');
$site_url = home_url('/');

// Get CTA settings: ACF Theme Settings > Header (Link fields) first, then Customizer, then defaults
$log_in_link = function_exists('get_field') ? get_field('header_log_in_link', 'option') : null;
if (!empty($log_in_link) && is_array($log_in_link) && (!empty($log_in_link['url']) || !empty($log_in_link['title']))) {
  $cta_secondary_text = !empty($log_in_link['title']) ? $log_in_link['title'] : get_theme_mod('nera_header_cta_secondary_text', __('Log In', 'nera-competitions'));
  $cta_secondary_url = !empty($log_in_link['url']) ? $log_in_link['url'] : '';
} else {
  $cta_secondary_text = get_theme_mod('nera_header_cta_secondary_text', __('Sign In', 'nera-competitions'));
  $cta_secondary_url = get_theme_mod('nera_header_cta_secondary_url', '');
}
$cta_secondary_logged_in_text = get_theme_mod('nera_header_cta_secondary_logged_in_text', __('My Account', 'nera-competitions'));

$enter_now_link = function_exists('get_field') ? get_field('header_enter_now_link', 'option') : null;
if (!empty($enter_now_link) && is_array($enter_now_link) && (!empty($enter_now_link['url']) || !empty($enter_now_link['title']))) {
  $cta_primary_text = !empty($enter_now_link['title']) ? $enter_now_link['title'] : get_theme_mod('nera_header_cta_primary_text', __('Enter Now', 'nera-competitions'));
  $cta_primary_url = !empty($enter_now_link['url']) ? $enter_now_link['url'] : '';
} else {
  $cta_primary_text = get_theme_mod('nera_header_cta_primary_text', __('Enter Now', 'nera-competitions'));
  $cta_primary_url = get_theme_mod('nera_header_cta_primary_url', '');
}
$cta_show_arrow = get_theme_mod('nera_header_cta_show_arrow', true);

// Build URLs with WooCommerce fallbacks when empty
if (empty($cta_secondary_url)) {
  $cta_secondary_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
}
if (empty($cta_primary_url)) {
  $cta_primary_url = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/');
}

// Logged-in user URL for secondary CTA
$cta_secondary_logged_in_url = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('dashboard') : home_url('/my-account/');

?>

<header class="sticky top-0 z-50 backdrop-blur-sm"
  id="site-header">
  <div class="max-w-7xl mx-auto">
    <nav class="flex items-center justify-between h-16 lg:h-[73px] relative px-4 md:px-0">

      <!-- Logo (Customizer, then fallback) -->
      <a href="<?php echo esc_url($site_url); ?>"
        class="flex items-center gap-2 text-ink font-bold text-xl lg:text-2xl z-10">
        <?php if (has_custom_logo()): ?>
          <?php
          $custom_logo_id = get_theme_mod('custom_logo');
          $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
          if ($logo):
            ?>
            <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr($site_name); ?>"
              class="w-auto !max-w-28 object-contain">
          <?php endif; ?>
        <?php else: ?>
          <span class="text-2xl">🏆</span>
          <span class="font-heading">
            <?php echo esc_html($site_name); ?>
          </span>
        <?php endif; ?>
      </a>

      <!-- Desktop Navigation (centered per Earthy Editorial) -->
      <div class="hidden lg:flex items-center gap-10 absolute left-1/2 -translate-x-1/2">
        <?php
        if (has_nav_menu('primary-menu')) {
          wp_nav_menu(array(
            'theme_location' => 'primary-menu',
            'container' => false,
            'menu_class' => 'flex items-center gap-8',
            'fallback_cb' => false,
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'link_before' => '',
            'link_after' => '',
            'depth' => 0,
            'walker' => new Nera_Header_Menu_Walker(),
          ));
        } else {
          ?>
          <a href="#competitions"
            class="text-[13px] tracking-[0.5px] text-ink-soft hover:text-ink font-normal transition-colors">
            <?php _e('Competitions', 'nera-competitions'); ?>
          </a>
          <a href="#testimonials"
            class="text-[13px] tracking-[0.5px] text-ink-soft hover:text-ink font-normal transition-colors">
            <?php _e('Testimonials', 'nera-competitions'); ?>
          </a>
          <a href="#faq"
            class="text-[13px] tracking-[0.5px] text-ink-soft hover:text-ink font-normal transition-colors">
            <?php _e('FAQs', 'nera-competitions'); ?>
          </a>
          <a href="<?php echo esc_url(home_url('/my-purpose/')); ?>"
            class="text-[13px] tracking-[0.5px] text-ink-soft hover:text-ink font-normal transition-colors">
            <?php _e('My Purpose', 'nera-competitions'); ?>
          </a>
          <a href="#how-it-works"
            class="text-[13px] tracking-[0.5px] text-ink-soft hover:text-ink font-normal transition-colors">
            <?php _e('How It Works', 'nera-competitions'); ?>
          </a>
        <?php } ?>
      </div>

      <!-- CTA Buttons -->
      <div class="hidden lg:flex items-center gap-5">
        <!-- Cart Button (logged in only) -->
        <?php if (function_exists('wc_get_cart_url')): ?>
          <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
            class="relative text-ink-soft hover:text-ink transition-colors p-2 text-[13px]"
            aria-label="View Cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
            <span class="nera-header-cart-count-desktop-wrapper absolute -top-1 -right-1">
              <?php if ($cart_count > 0): ?>
                <span
                  class="bg-forest text-mint text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
                  <?php echo esc_html($cart_count); ?>
                </span>
              <?php endif; ?>
            </span>
          </a>
        <?php endif; ?>

        <!-- My Account Icon Button -->
        <?php if (is_user_logged_in()): ?>
          <a href="<?php echo esc_url($cta_secondary_logged_in_url); ?>"
            class="relative flex items-center justify-center text-ink hover:opacity-90 transition-all p-1.5 rounded-full ring-2 ring-border bg-ink/5"
            aria-label="<?php echo esc_attr($cta_secondary_logged_in_text); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </a>
        <?php else: ?>
          <a href="<?php echo esc_url($cta_secondary_url); ?>"
            class="text-ink-soft hover:text-ink transition-colors text-[13px] no-underline"
            aria-label="<?php echo esc_attr($cta_secondary_text); ?>">
            <?php echo esc_html($cta_secondary_text); ?>
          </a>
          <a href="<?php echo esc_url($cta_primary_url); ?>"
            class="nera-header-enter-btn inline-flex items-center justify-center py-2.5 px-7 text-[0.68rem] tracking-[0.18em] uppercase font-medium text-mint bg-forest rounded-none transition-colors duration-300 hover:bg-ink no-underline">
            <?php echo esc_html($cta_primary_text); ?>
          </a>
        <?php endif; ?>
      </div>

      <!-- Mobile: Cart + Hamburger -->
      <div class="flex lg:hidden items-center gap-1">
        <?php if (function_exists('wc_get_cart_url')): ?>
          <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
            class="relative text-ink-soft hover:text-ink transition-colors p-2" aria-label="View Cart">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
            <span class="nera-header-cart-count-mobile-nav-wrapper absolute -top-1 -right-1">
              <?php if ($cart_count > 0): ?>
                <span
                  class="bg-forest text-mint text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
                  <?php echo esc_html($cart_count); ?>
                </span>
              <?php endif; ?>
            </span>
          </a>
        <?php endif; ?>
        <button type="button" class="p-2 text-ink hover:text-sage transition-colors"
          id="mobile-menu-toggle" aria-label="Toggle menu">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            id="menu-icon-open">
            <path d="M3 12h18M3 6h18M3 18h18" />
          </svg>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            class="hidden" id="menu-icon-close">
            <path d="M18 6L6 18M6 6l12 12" />
          </svg>
        </button>
      </div>

    </nav>
  </div>

  <!-- Mobile Menu -->
  <div class="lg:hidden hidden border-t border-border" id="mobile-menu">
    <div class="px-4 py-6 space-y-4">
      <?php
      if (has_nav_menu('primary-menu')) {
        wp_nav_menu(array(
          'theme_location' => 'primary-menu',
          'container' => false,
          'menu_class' => 'space-y-2',
          'fallback_cb' => false,
          'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
          'depth' => 0,
          'walker' => new Nera_Mobile_Menu_Walker(),
        ));
      } else {
        // Fallback menu if no menu is assigned
        ?>
        <a href="#competitions"
          class="block text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <?php _e('Competitions', 'nera-competitions'); ?>
        </a>
        <a href="#testimonials"
          class="block text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <?php _e('Testimonials', 'nera-competitions'); ?>
        </a>
        <a href="#faq" class="block text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <?php _e('FAQs', 'nera-competitions'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/my-purpose/')); ?>"
          class="block text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <?php _e('My Purpose', 'nera-competitions'); ?>
        </a>
        <a href="#how-it-works"
          class="block text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <?php _e('How It Works', 'nera-competitions'); ?>
        </a>
      <?php } ?>

      <hr class="border-border">

      <!-- Cart Button (logged in only) -->
      <?php if (function_exists('wc_get_cart_url')): ?>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
          class="flex items-center justify-between text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <span class="flex items-center gap-2">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <?php _e('Cart', 'nera-competitions'); ?>
          </span>
          <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="nera-header-cart-count-mobile-wrapper">
            <?php if ($cart_count > 0): ?>
              <span
                class="bg-forest text-mint text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center leading-none">
                <?php echo esc_html($cart_count); ?>
              </span>
            <?php endif; ?>
          </span>
        </a>
      <?php endif; ?>

      <!-- My Account Button -->
      <?php if (is_user_logged_in()): ?>
        <a href="<?php echo esc_url($cta_secondary_logged_in_url); ?>"
          class="flex items-center gap-2 text-ink font-normal py-2 transition-colors hover:opacity-90">
          <span
            class="flex items-center justify-center p-1.5 rounded-full ring-2 ring-border bg-ink/5">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </span>
          <?php echo esc_html($cta_secondary_logged_in_text); ?>
        </a>
      <?php else: ?>
        <a href="<?php echo esc_url($cta_secondary_url); ?>"
          class="flex items-center gap-2 text-ink-soft hover:text-ink font-normal py-2 transition-colors">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <?php echo esc_html($cta_secondary_text); ?>
        </a>
      <?php endif; ?>

      <a href="<?php echo esc_url($cta_primary_url); ?>"
        class="nera-header-enter-btn block w-full text-center px-6 py-3 text-[0.68rem] tracking-[0.18em] uppercase font-medium text-mint bg-forest rounded-none">
        <?php echo esc_html($cta_primary_text); ?>
      </a>
    </div>
  </div>
</header>

<script>
  // Mobile menu toggle with enhanced animations
  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');
    let isAnimating = false;

    if (toggle && menu) {
      // Toggle menu open/close
      toggle.addEventListener('click', function () {
        if (isAnimating) return; // Prevent rapid clicking during animation

        const isHidden = menu.classList.contains('hidden');

        if (isHidden) {
          // Opening animation
          menu.classList.remove('hidden');
          menu.classList.add('menu-opening');
          menu.classList.remove('menu-closing');

          // Animate icons
          iconOpen.classList.add('rotating');
          setTimeout(() => {
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            iconClose.classList.add('rotating');
            setTimeout(() => {
              iconOpen.classList.remove('rotating');
              iconClose.classList.remove('rotating');
            }, 100);
          }, 150);

          isAnimating = true;
          setTimeout(() => {
            menu.classList.remove('menu-opening');
            isAnimating = false;
          }, 300);
        } else {
          // Closing animation
          menu.classList.add('menu-closing');
          menu.classList.remove('menu-opening');

          // Animate icons
          iconClose.classList.add('rotating');
          setTimeout(() => {
            iconClose.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconOpen.classList.add('rotating');
            setTimeout(() => {
              iconOpen.classList.remove('rotating');
              iconClose.classList.remove('rotating');
            }, 100);
          }, 150);

          isAnimating = true;
          setTimeout(() => {
            menu.classList.add('hidden');
            menu.classList.remove('menu-closing');
            isAnimating = false;
          }, 300);
        }
      });

      // Close menu when clicking a link
      menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          if (isAnimating) return;

          menu.classList.add('menu-closing');
          menu.classList.remove('menu-opening');

          // Animate icons
          iconClose.classList.add('rotating');
          setTimeout(() => {
            iconClose.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconOpen.classList.add('rotating');
            setTimeout(() => {
              iconOpen.classList.remove('rotating');
              iconClose.classList.remove('rotating');
            }, 100);
          }, 150);

          isAnimating = true;
          setTimeout(() => {
            menu.classList.add('hidden');
            menu.classList.remove('menu-closing');
            isAnimating = false;
          }, 300);
        });
      });

      // Accordion toggle for sub-menus
      menu.querySelectorAll('.luxora-accordion-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var submenu = this.closest('li').querySelector('.luxora-mobile-submenu');
          if (!submenu) return;
          submenu.classList.toggle('hidden');
          this.classList.toggle('is-open');
          this.setAttribute('aria-expanded', submenu.classList.contains('hidden') ? 'false' : 'true');
        });
      });
    }
  });
</script>