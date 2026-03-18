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
  <div class="footer-bottom max-w-[1400px] mx-auto pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="footer-legal text-[11px] leading-relaxed max-w-[750px] space-y-2">
      <?php
      $legal = get_field('footer_legal_disclaimer', 'option');
      $legal_default = __(
        'UK residents only 18+. Live Life Prizes operates as a prize draw. No purchase necessary. Free postal route available. T&Cs apply. All entrants have an equal chance to win regardless of how they enter.',
        'nera-competitions',
      );
      if ($legal) {
        echo '<p class="m-0">' . wp_kses_post(nl2br($legal)) . '</p>';
      } else {
        echo '<p class="m-0">' . esc_html($legal_default) . '</p>';
      }

      $copyright_text = get_field('footer_copyright', 'option');
      $copyright_default =
        '© ' .
        $current_year .
        ' All Rights Reserved. Van Life Builds Ltd trading as Live Life Prizes. Company Registration Number: 14663089';
      if ($copyright_text) {
        echo '<p class="m-0">' .
          esc_html(str_replace('{year}', $current_year, $copyright_text)) .
          '</p>';
      } else {
        echo '<p class="m-0">' . esc_html($copyright_default) . '</p>';
      }

      $contact_email = get_field('footer_contact_email', 'option');
      if ($contact_email) {
        echo '<p class="m-0">' .
          esc_html(__('Have a question?', 'nera-competitions')) .
          ' <a href="mailto:' .
          esc_attr($contact_email) .
          '" class="transition-colors">' .
          esc_html($contact_email) .
          '</a></p>';
      }
      ?>
    </div>

    <?php
    $vlb_label = get_field('footer_vlb_label', 'option');
    $vlb_url = get_field('footer_vlb_url', 'option');
    $vlb_label = $vlb_label ? $vlb_label : __('Part of Van Life Builds', 'nera-competitions');
    $badge_class =
      'footer-vlb-badge flex items-center gap-2.5 py-2.5 px-5 rounded-lg border text-[11px] tracking-[1px]';
    $badge_style =
      'background: rgba(200, 230, 192, 0.05); border-color: rgba(200, 230, 192, 0.12); color: rgba(200, 230, 192, 0.5);';
    ?>
    <div class="flex items-center shrink-0">
      <?php if ($vlb_url): ?>
        <a href="<?php echo esc_url($vlb_url); ?>" class="<?php echo esc_attr(
  $badge_class,
); ?>" style="<?php echo esc_attr(
  $badge_style,
); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($vlb_label); ?></a>
      <?php else: ?>
        <span class="<?php echo esc_attr($badge_class); ?>" style="<?php echo esc_attr(
  $badge_style,
); ?>"><?php echo esc_html($vlb_label); ?></span>
      <?php endif; ?>
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