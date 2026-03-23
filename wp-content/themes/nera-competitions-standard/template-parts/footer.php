<?php
/**
 * Custom Footer Template Part
 *
 * Luxora — dark ink footer with mint accents
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

$current_year = date('Y');
?>

<footer class="py-20 px-6 lg:px-20" id="site-footer">

  <!-- Main Footer Grid -->
  <div
    class="footer-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-[60px] max-w-[1400px] mx-auto pb-12 border-b border-[rgba(200,230,192,0.08)]">

    <?php if (is_active_sidebar('footer-1')): ?>
      <div class="footer-col footer-brand lg:col-span-1">
        <?php dynamic_sidebar('footer-1'); ?>
      </div>
    <?php endif; ?>
    <?php if (is_active_sidebar('footer-2')): ?>
      <div class="footer-col">
        <?php dynamic_sidebar('footer-2'); ?>
      </div>
    <?php endif; ?>
    <?php if (is_active_sidebar('footer-3')): ?>
      <div class="footer-col">
        <?php dynamic_sidebar('footer-3'); ?>
      </div>
    <?php endif; ?>
    <?php if (is_active_sidebar('footer-4')): ?>
      <div class="footer-col">
        <?php dynamic_sidebar('footer-4'); ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom max-w-[1400px] mx-auto pt-8 flex flex-col md:flex-row justify-between gap-10">
    <div class="footer-legal">
      <?php
      $copyright_text = get_field('footer_copyright', 'option');
      $copyright_default =
        '© ' .
        $current_year .
        ' Luxora Draws Ltd. All rights reserved. Registered in England & Wales.';
      if ($copyright_text) {
        echo esc_html(str_replace('{year}', $current_year, $copyright_text)) . '<br>';
      } else {
        echo esc_html($copyright_default) . '<br>';
      }

      $legal = get_field('footer_legal_disclaimer', 'option');
      $legal_default = __(
        'Skills-based competition. A free entry route is available on every competition. Participants must be aged 18 or over.',
        'nera-competitions',
      );
      if ($legal) {
        echo wp_kses_post(nl2br($legal));
      } else {
        echo esc_html($legal_default);
      }
      ?>
    </div>
    <div class="footer-legal md:text-right md:max-w-[320px]">
      <?php
      $legal_right = get_field('footer_legal_right', 'option');
      $legal_right_default = __(
        'Luxora Draws operates in accordance with the Gambling Act 2005. All competitions governed by English law. Full terms apply.',
        'nera-competitions',
      );
      if ($legal_right) {
        echo wp_kses_post(nl2br($legal_right));
      } else {
        echo esc_html($legal_right_default);
      }
      ?>
    </div>
  </div>

  <!-- Scroll to Top Button -->
  <button id="nera-scroll-top"
    class="fixed bottom-8 cursor-pointer right-8 z-[100] p-3 rounded-full shadow-xl hover:scale-110 hover:-translate-y-1 transform transition-all duration-300 translate-y-20 opacity-0 invisible border"
    style="background: var(--color-forest); color: var(--color-mint); border-color: rgba(200, 230, 192, 0.2);"
    aria-label="<?php esc_attr_e('Scroll to top', 'nera-competitions'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
      stroke-width="2.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
  </button>

</footer>