<?php
/**
 * WP-CLI: seed Nera Marketing attribution page ACF fields.
 *
 * Run after assigning the page template: `wp nera seed-attribution`
 * Use `wp nera seed-attribution --force` to overwrite fields that already have values.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (!defined('WP_CLI') || !WP_CLI) {
  return;
}

/**
 * Nera WP-CLI commands.
 */
class Nera_CLI
{
  /**
   * Seed default ACF content for pages using the Nera Marketing attribution template.
   *
   * ## OPTIONS
   *
   * [--force]
   * : Overwrite fields that already have a value.
   *
   * ## EXAMPLES
   *
   *     wp nera seed-attribution
   *     wp nera seed-attribution --force
   *
   * @param array<int, string> $args Positional args.
   * @param array<string, mixed> $assoc_args Flags.
   */
  public function seed_attribution($args, $assoc_args)
  {
    if (!function_exists('update_field')) {
      WP_CLI::error(__('ACF is not active; update_field() is unavailable.', 'nera-competitions'));
      return;
    }

    $force = !empty($assoc_args['force']);

    $pages = get_posts([
      'post_type' => 'page',
      'post_status' => 'any',
      'posts_per_page' => -1,
      'meta_key' => '_wp_page_template',
      'meta_value' => 'page-templates/nera-marketing-attribution.php',
      'fields' => 'ids',
    ]);

    if (empty($pages)) {
      WP_CLI::warning(
        __('No pages use the template "Competition Website by Nera Marketing". Create one and assign the template first.', 'nera-competitions'),
      );
      return;
    }

    $defaults = nera_attr_get_default_field_values();

    foreach ($pages as $post_id) {
      $post_id = (int) $post_id;
      $title = get_the_title($post_id);
      WP_CLI::log(sprintf('Seeding page ID %d — %s', $post_id, $title));

      foreach ($defaults as $field_name => $default_val) {
        $existing = get_field($field_name, $post_id);

        if (!$force && $this->field_has_value($field_name, $existing)) {
          continue;
        }

        update_field($field_name, $default_val, $post_id);
      }

      WP_CLI::success(sprintf(__('Finished seed for page ID %d.', 'nera-competitions'), $post_id));
    }
  }

  /**
   * @param string $field_name ACF field name.
   * @param mixed  $value      Raw get_field result.
   */
  private function field_has_value($field_name, $value)
  {
    if ($value === null || $value === false) {
      return false;
    }
    if (is_string($value) && trim($value) === '') {
      return false;
    }
    if (is_array($value) && empty($value)) {
      return false;
    }
    if ($field_name === 'attr_dev_image' && is_array($value) && empty($value['ID']) && empty($value['id'])) {
      return false;
    }
    return true;
  }
}

WP_CLI::add_command('nera', 'Nera_CLI');
