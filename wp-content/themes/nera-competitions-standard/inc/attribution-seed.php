<?php
/**
 * Shared ACF seeding for Nera Marketing attribution pages (WP-CLI + Tools admin).
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Whether an ACF field value counts as "already set" for skip-when-not-forcing.
 *
 * @param string $field_name ACF field name.
 * @param mixed  $value      Raw get_field result.
 */
function nera_attr_seed_field_has_value($field_name, $value)
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

/**
 * Seed default ACF values for all pages using the attribution page template.
 *
 * @param bool $force When true, overwrite non-empty fields.
 * @return array{
 *   acf_active: bool,
 *   no_matching_pages: bool,
 *   seeded_post_ids: int[],
 *   error?: string,
 *   warning?: string
 * }
 */
function nera_attr_seed_attribution_pages($force = false)
{
  $out = [
    'acf_active' => function_exists('update_field'),
    'no_matching_pages' => false,
    'seeded_post_ids' => [],
  ];

  if (!$out['acf_active']) {
    $out['error'] = __('ACF is not active; update_field() is unavailable.', 'nera-competitions');
    return $out;
  }

  $pages = get_posts([
    'post_type' => 'page',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'meta_key' => '_wp_page_template',
    'meta_value' => 'page-templates/nera-marketing-attribution.php',
    'fields' => 'ids',
  ]);

  if (empty($pages)) {
    $out['no_matching_pages'] = true;
    $out['warning'] = __(
      'No pages use the template "Competition Website by Nera Marketing". Create one and assign the template first.',
      'nera-competitions',
    );
    return $out;
  }

  $defaults = nera_attr_get_default_field_values();

  foreach ($pages as $post_id) {
    $post_id = (int) $post_id;

    foreach ($defaults as $field_name => $default_val) {
      $existing = get_field($field_name, $post_id);

      if (!$force && nera_attr_seed_field_has_value($field_name, $existing)) {
        continue;
      }

      update_field($field_name, $default_val, $post_id);
    }

    $out['seeded_post_ids'][] = $post_id;
  }

  return $out;
}
