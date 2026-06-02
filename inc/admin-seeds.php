<?php
/**
 * Tools -> Seeds: seed Nera Marketing attribution ACF defaults (no WP-CLI required).
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Register Tools submenu.
 */
function nera_register_seeds_tools_page()
{
  add_management_page(
    __('Seeds', 'nera-competitions'),
    __('Seeds', 'nera-competitions'),
    'manage_options',
    'nera-seeds',
    'nera_render_seeds_page',
  );
}
add_action('admin_menu', 'nera_register_seeds_tools_page');

/**
 * Handle POST from Seeds page.
 */
function nera_handle_seed_attribution_post()
{
  if (!isset($_POST['nera_seed_submit'])) {
    return;
  }

  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have permission to run this action.', 'nera-competitions'));
  }

  check_admin_referer('nera_seed_attribution', 'nera_seed_nonce');

  $force = !empty($_POST['nera_seed_force']);

  $result = nera_attr_seed_attribution_options($force);

  set_transient(
    'nera_seeds_last_result_' . get_current_user_id(),
    $result,
    120,
  );

  wp_safe_redirect(
    add_query_arg(
      ['page' => 'nera-seeds', 'nera-seeded' => '1'],
      admin_url('tools.php'),
    ),
  );
  exit();
}
add_action('admin_post_nera_seed_attribution', 'nera_handle_seed_attribution_post');

/**
 * Admin notices after redirect.
 */
function nera_seeds_admin_notices()
{
  if (!isset($_GET['page']) || (string) $_GET['page'] !== 'nera-seeds') {
    return;
  }

  if (empty($_GET['nera-seeded'])) {
    return;
  }

  $result = get_transient('nera_seeds_last_result_' . get_current_user_id());
  if ($result === false || !is_array($result)) {
    return;
  }

  delete_transient('nera_seeds_last_result_' . get_current_user_id());

  if (empty($result['acf_active'])) {
    echo '<div class="notice notice-error is-dismissible"><p>';
    echo esc_html($result['error'] ?? __('ACF is not available.', 'nera-competitions'));
    echo '</p></div>';
    return;
  }

  $count = (int) ($result['seeded_fields'] ?? 0);
  echo '<div class="notice notice-success is-dismissible"><p>';
  echo esc_html(
    sprintf(
      /* translators: %d: number of fields written */
      __('Attribution ACF options seeded. %d field(s) written.', 'nera-competitions'),
      $count,
    ),
  );
  echo '</p>';
  echo '</div>';
}
add_action('admin_notices', 'nera_seeds_admin_notices');

/**
 * Render Tools → Seeds.
 */
function nera_render_seeds_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have permission to access this page.', 'nera-competitions'));
  }

  $action_url = admin_url('admin-post.php');
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(__('Seeds', 'nera-competitions')); ?></h1>
    <p class="description">
      <?php
      echo esc_html(
        __(
          'Fill Advanced Custom Fields with default copy for the hidden Nera attribution options page used by the virtual route.',
          'nera-competitions',
        ),
      );
      ?>
    </p>

    <form method="post" action="<?php echo esc_url($action_url); ?>" style="max-width:40em;margin-top:1.5em;">
      <input type="hidden" name="action" value="nera_seed_attribution" />
      <?php wp_nonce_field('nera_seed_attribution', 'nera_seed_nonce'); ?>

      <fieldset style="padding:1em 0;">
        <label>
          <input type="checkbox" name="nera_seed_force" value="1" />
          <?php echo esc_html(__('Overwrite existing field values', 'nera-competitions')); ?>
        </label>
        <p class="description">
          <?php
          echo esc_html(
            __(
              'If unchecked, only empty fields are filled. If checked, defaults replace current values for every attribution field.',
              'nera-competitions',
            ),
          );
          ?>
        </p>
      </fieldset>

      <p>
        <button type="submit" name="nera_seed_submit" class="button button-primary" value="1">
          <?php echo esc_html(__('Seed attribution options fields', 'nera-competitions')); ?>
        </button>
      </p>
    </form>
  </div>
  <?php
}
