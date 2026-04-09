<?php
/**
 * Giveaway for WooCommerce Plugin Customizations
 * Custom hooks and modifications for the lottery/giveaway plugin
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

/**
 * Render “Actual draw date (live)” in the product Giveaway tab (after plugin fields).
 */
function nera_render_actual_draw_date_product_field()
{
  global $product_object;

  if (!$product_object || !function_exists('lty_is_lottery_product') || !lty_is_lottery_product($product_object)) {
    return;
  }

  $product_id = $product_object->get_id();
  $value = get_post_meta($product_id, '_nera_actual_draw_date', true);
  $placeholder = class_exists('LTY_Date_Time')
    ? LTY_Date_Time::get_wp_datetime_format()
    : (get_option('date_format') . ' ' . get_option('time_format'));

  echo '<div class="options_group show_if_lottery">';
  echo '<h4>' . esc_html__('Actual draw date (live)', 'nera-competitions') . '</h4>';
  echo '<p class="description" style="padding: 0 12px 8px;">';
  echo esc_html__(
    'When the winner will be drawn live (e.g. on social media). This can differ from the giveaway End Date (ticket sales close). Leave empty to use the End Date everywhere we show the draw.',
    'nera-competitions',
  );
  echo '</p>';
  if (function_exists('lty_get_datepicker_html')) {
    echo '<p class="form-field nera-actual-draw-date-field">';
    echo '<label for="_nera_actual_draw_date">' . esc_html__('Actual draw date', 'nera-competitions') . '</label>';
    lty_get_datepicker_html(
      [
        'id' => '_nera_actual_draw_date',
        'with_time' => true,
        'wp_zone' => false,
        'value' => $value ? (string) $value : '',
        'placeholder' => $placeholder,
        'error' => __('Actual draw date could not be read. Please pick a valid date and time.', 'nera-competitions'),
      ],
      true,
    );
    echo '</p>';
  } else {
    woocommerce_wp_text_input(
      [
        'id' => '_nera_actual_draw_date',
        'name' => '_nera_actual_draw_date',
        'type' => 'text',
        'label' => __('Actual draw date', 'nera-competitions'),
        'value' => $value ? (string) $value : '',
        'placeholder' => $placeholder,
        'description' => __(
          'Enter date and time in your site timezone, same format as WordPress date/time settings.',
          'nera-competitions',
        ),
      ],
    );
  }
  echo '</div>';
}

add_action('woocommerce_product_options_lottery_product_data', 'nera_render_actual_draw_date_product_field', 10);

/**
 * Persist actual draw date meta (local + GMT) on lottery product save.
 *
 * @param int $post_id Product ID.
 */
function nera_save_actual_draw_date_meta($post_id)
{
  $post_id = absint($post_id);
  if (!$post_id || !current_user_can('edit_product', $post_id)) {
    return;
  }

  if (!isset($_POST['_nera_actual_draw_date'])) {
    return;
  }

  $product = function_exists('wc_get_product') ? wc_get_product($post_id) : null;
  if (!$product || !$product->is_type('lottery')) {
    return;
  }

  $raw = isset($_POST['_nera_actual_draw_date']) ? wc_clean(wp_unslash($_POST['_nera_actual_draw_date'])) : '';
  if ($raw === '') {
    delete_post_meta($post_id, '_nera_actual_draw_date');
    delete_post_meta($post_id, '_nera_actual_draw_date_gmt');
    return;
  }

  update_post_meta($post_id, '_nera_actual_draw_date', $raw);

  if (class_exists('LTY_Date_Time')) {
    $gmt = LTY_Date_Time::get_mysql_date_time_format($raw, false, 'UTC');
  } else {
    $gmt = get_gmt_from_date($raw);
  }

  if (!$gmt) {
    delete_post_meta($post_id, '_nera_actual_draw_date');
    delete_post_meta($post_id, '_nera_actual_draw_date_gmt');
    return;
  }

  update_post_meta($post_id, '_nera_actual_draw_date_gmt', $gmt);
}

add_action('woocommerce_process_product_meta_lottery', 'nera_save_actual_draw_date_meta', 20, 1);
