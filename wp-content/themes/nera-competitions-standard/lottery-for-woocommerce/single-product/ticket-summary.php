<?php
/**
 * Ticket summary — dialog variant (Browse & Choose Tickets).
 *
 * @package Nera_Competitions
 * @var WC_Product $product
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * This hook is used to do extra action before lottery ticket container.
 *
 * @since 1.0
 */
do_action('lty_before_lottery_ticket_container');

$ticket_tabs = function_exists('lty_get_ticket_tabs') ? lty_get_ticket_tabs($product) : [];
$has_multiple_tabs = count($ticket_tabs) > 1;
$max_order = method_exists($product, 'get_lty_order_maximum_tickets')
  ? absint($product->get_lty_order_maximum_tickets())
  : 0;
$first_tab_label = '';
if (!empty($ticket_tabs)) {
  $tab_values = array_values($ticket_tabs);
  $first_tab_label = $tab_values[0];
}
?>

<div
  class="nera-ticket-picker-wrap mb-6"
  x-data="{ ticketOpen: false, openTicketPicker() { this.ticketOpen = true; this.$dispatch('nera-close-gallery-lightbox'); } }"
  @keydown.escape.window="ticketOpen = false">
  <button
    type="button"
    class="nera-ticket-picker-trigger w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl border-2 border-dashed border-sage/50 bg-off-white hover:border-sage hover:bg-sage/5 transition-colors text-left"
    @click="openTicketPicker()"
    data-nera-ticket-trigger>
    <span class="flex items-center gap-2 min-w-0">
      <span class="material-symbols-outlined text-sage shrink-0" aria-hidden="true">confirmation_number</span>
      <span class="font-semibold text-ink truncate">
        <?php esc_html_e('Browse & Choose Tickets', 'nera-competitions'); ?>
      </span>
    </span>
    <span class="flex items-center gap-2 shrink-0">
      <span
        class="nera-ticket-picker-count-pill inline-flex items-center justify-center min-w-[1.75rem] h-7 px-2 rounded-full text-sm font-bold bg-sage/15 text-sage"
        data-count-badge
        data-has-selection="false">
        <span data-selected-ticket-count>0</span>
      </span>
      <span class="material-symbols-outlined text-ink-soft text-xl" aria-hidden="true">chevron_right</span>
    </span>
  </button>

  <template x-teleport="body">
    <div
      x-show="ticketOpen"
      x-cloak
      x-transition.opacity
      class="nera-ticket-picker-modal-root fixed inset-0 z-[10000000] flex flex-col justify-end sm:justify-center sm:items-center p-0 sm:p-4 pointer-events-none"
      data-nera-ticket-picker-modal
      role="presentation">
      <div
        class="absolute inset-0 bg-ink/60 backdrop-blur-sm pointer-events-auto"
        @click="ticketOpen = false"
        aria-hidden="true"></div>

      <div
        class="nera-ticket-picker-sheet relative z-[1] w-full max-h-[90vh] overflow-hidden flex flex-col bg-off-white rounded-t-2xl sm:rounded-2xl border border-border shadow-2xl pointer-events-auto sm:max-w-lg sm:max-h-[85vh]"
        role="dialog"
        aria-modal="true"
        aria-labelledby="nera-ticket-picker-title">

        <div class="nera-ticket-picker-dialog-header flex gap-3 px-4 pt-4 pb-3 border-b border-border shrink-0 items-start">
          <div
            class="nera-ticket-picker-header-icon flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sage/15 text-sage"
            aria-hidden="true">
            <span class="material-symbols-outlined text-2xl">confirmation_number</span>
          </div>
          <div class="min-w-0 flex-1 pt-0.5">
            <h3 id="nera-ticket-picker-title" class="text-lg font-bold text-ink leading-tight">
              <?php esc_html_e('Choose Your Tickets', 'nera-competitions'); ?>
            </h3>
            <p class="text-sm text-ink-soft mt-1">
              <?php if ($max_order > 0): ?>
                <?php
                printf(
                  /* translators: %d: max tickets per order */
                  esc_html__('Select up to %d tickets.', 'nera-competitions'),
                  $max_order,
                );
                ?>
              <?php else: ?>
                <?php esc_html_e('Tap a number to select it.', 'nera-competitions'); ?>
              <?php endif; ?>
            </p>
          </div>
          <div class="flex items-center gap-1.5 shrink-0 pt-0.5">
            <span
              class="nera-ticket-picker-header-pill inline-flex items-center gap-1 rounded-full border border-sage/30 bg-sage/10 px-2.5 py-1 text-xs font-semibold text-sage"
              data-count-badge
              data-has-selection="false">
              <span class="material-symbols-outlined text-base text-sage" aria-hidden="true">check_circle</span>
              <span class="text-ink tabular-nums">
                <span data-selected-ticket-count>0</span>
                <?php esc_html_e('selected', 'nera-competitions'); ?>
              </span>
            </span>
            <button
              type="button"
              class="p-1.5 rounded-full text-ink-soft hover:text-ink hover:bg-ink/5 transition-colors"
              @click="ticketOpen = false"
              aria-label="<?php esc_attr_e('Close', 'nera-competitions'); ?>">
              <span class="material-symbols-outlined text-2xl" aria-hidden="true">close</span>
            </button>
          </div>
        </div>

        <?php if ($has_multiple_tabs): ?>
          <div class="px-4 pb-3 pt-2 shrink-0">
            <label class="nera-ticket-picker-range-row flex w-full cursor-pointer items-center gap-3 rounded-xl border border-border bg-white px-3 py-2.5 shadow-sm transition-colors hover:border-sage/40">
              <span class="material-symbols-outlined text-sage shrink-0" aria-hidden="true">confirmation_number</span>
              <select
                id="nera-lty-tab-select"
                class="nera-lty-tab-select min-w-0 flex-1 cursor-pointer appearance-none border-0 bg-transparent text-sm font-semibold text-ink focus:outline-none focus:ring-0"
                data-tab-select
                aria-label="<?php esc_attr_e('Ticket number range', 'nera-competitions'); ?>">
                <?php foreach ($ticket_tabs as $tab_key => $label): ?>
                  <option value="<?php echo esc_attr($tab_key); ?>">
                    <?php echo esc_html($label); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span class="material-symbols-outlined text-ink-soft shrink-0" aria-hidden="true">chevron_right</span>
            </label>
          </div>
        <?php elseif ($first_tab_label !== ''): ?>
          <div class="px-4 pb-3 pt-2 shrink-0">
            <div
              class="nera-ticket-picker-range-row flex w-full items-center gap-3 rounded-xl border border-border bg-white px-3 py-2.5 shadow-sm"
              aria-hidden="true">
              <span class="material-symbols-outlined text-sage shrink-0">confirmation_number</span>
              <span class="min-w-0 flex-1 text-sm font-semibold text-ink"><?php echo esc_html($first_tab_label); ?></span>
              <span class="material-symbols-outlined text-ink-soft shrink-0">expand_more</span>
            </div>
          </div>
        <?php endif; ?>

        <div class="flex-1 min-h-0 overflow-y-auto px-4 pb-4">
          <div class="lty-lottery-ticket-container nera-lty-lottery-ticket-container-inner">

            <div class="lty-lottery-ticket-header nera-ticket-picker-hide-default-header">
              <h3><?php esc_html_e('Select your ticket(s)', 'lottery-for-woocommerce'); ?></h3>
            </div>
            <?php
            do_action('lty_before_lottery_ticket_panel');
            ?>
            <div class="lty-lottery-ticket-panel">
              <?php
              do_action('lty_before_lottery_ticket');
              ?>
              <div class="lty-lottery-ticket-wrapper">
                <div
                  class="lty-lottery-ticket-tab-wrapper <?php echo $has_multiple_tabs
                    ? 'nera-lty-tab-buttons-sr-only'
                    : ''; ?>"
                  <?php echo $has_multiple_tabs ? 'aria-hidden="true"' : ''; ?>>
                  <?php
                  $index = 0;
                  foreach ($ticket_tabs as $tab_key => $label):
                    ?>
                    <button
                      type="button"
                      class="lty-lottery-ticket-tab <?php echo esc_attr(0 === $index ? 'lty-active-tab' : ''); ?>"
                      data-index="<?php echo esc_attr($index++); ?>"
                      data-tab="<?php echo esc_attr($tab_key); ?>"
                      <?php echo $has_multiple_tabs ? 'tabindex="-1"' : ''; ?>>
                      <?php echo esc_html($label); ?>
                    </button>
                  <?php
                  endforeach;
                  ?>
                </div>

                <div class="lty-lottery-ticket-tab-content">
                  <?php
                  do_action('lty_lottery_ticket_tab_content', $product);
                  ?>
                </div>
              </div>
              <?php
              do_action('lty_after_lottery_ticket');
              ?>
            </div>

            <input type="hidden" name="quantity" class="lty-lottery-ticket-quantity" />
            <input type="hidden" name="lty_lottery_ticket_numbers" class="lty-lottery-ticket-numbers" />
            <input type="hidden" class="lty-ticket-product-id" value="<?php echo esc_attr($product->get_id()); ?>" />
          </div>
          <?php
          do_action('lty_after_lottery_ticket_container');
          ?>
        </div>

        <div class="nera-ticket-picker-legend px-4 pb-4 flex flex-wrap gap-x-4 gap-y-2 text-xs text-ink-soft shrink-0 border-t border-border/60 pt-3">
          <span class="inline-flex items-center gap-1.5">
            <span class="nera-ticket-legend-swatch nera-ticket-legend-available" aria-hidden="true"></span>
            <?php esc_html_e('Available', 'nera-competitions'); ?>
          </span>
          <span class="inline-flex items-center gap-1.5">
            <span class="nera-ticket-legend-swatch nera-ticket-legend-selected" aria-hidden="true"></span>
            <?php esc_html_e('Selected', 'nera-competitions'); ?>
          </span>
          <span class="inline-flex items-center gap-1.5">
            <span class="nera-ticket-legend-swatch nera-ticket-legend-sold" aria-hidden="true"></span>
            <?php esc_html_e('Sold', 'nera-competitions'); ?>
          </span>
          <span class="inline-flex items-center gap-1.5">
            <span class="nera-ticket-legend-swatch nera-ticket-legend-cart" aria-hidden="true"></span>
            <?php esc_html_e('In cart', 'nera-competitions'); ?>
          </span>
          <span class="inline-flex items-center gap-1.5">
            <span class="nera-ticket-legend-swatch nera-ticket-legend-reserved" aria-hidden="true"></span>
            <?php esc_html_e('Reserved', 'nera-competitions'); ?>
          </span>
        </div>

        <div class="px-4 py-3 border-t border-border shrink-0 bg-off-white">
          <button
            type="button"
            class="nera-ticket-picker-confirm flex w-full items-center justify-between gap-3 rounded-xl bg-forest px-4 py-3.5 text-mint shadow-lg transition-opacity hover:opacity-90"
            @click="ticketOpen = false">
            <span class="flex min-w-0 items-center gap-2 font-bold">
              <span class="material-symbols-outlined shrink-0 text-mint" aria-hidden="true">check_circle</span>
              <?php esc_html_e('Confirm selection', 'nera-competitions'); ?>
            </span>
            <span
              class="inline-flex shrink-0 items-center rounded-full bg-mint/20 px-2.5 py-1 text-xs font-bold text-mint tabular-nums">
              <span data-nera-confirm-ticket-count>0</span>
              <?php esc_html_e('tickets', 'nera-competitions'); ?>
            </span>
          </button>
        </div>
      </div>
    </div>
  </template>
</div>

<?php if ($has_multiple_tabs): ?>
  <script>
    (function () {
      document.addEventListener(
        'change',
        function (e) {
          var t = e.target;
          if (!t || t.id !== 'nera-lty-tab-select') {
            return;
          }
          var modal = t.closest('[data-nera-ticket-picker-modal]');
          if (!modal) {
            return;
          }
          var v = t.value;
          var btn = modal.querySelector(
            '.lty-lottery-ticket-tab[data-tab="' + v.replace(/\\/g, '\\\\').replace(/"/g, '\\"') + '"]'
          );
          if (btn) {
            btn.click();
          }
        },
        true
      );
    })();
  </script>
<?php endif; ?>
