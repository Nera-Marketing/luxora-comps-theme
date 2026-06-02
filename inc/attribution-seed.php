<?php
/**
 * Shared ACF seeding for Nera Marketing attribution options (WP-CLI + Tools admin).
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
 * Seed default ACF values for attribution options.
 *
 * @param bool $force When true, overwrite non-empty fields.
 * @return array{acf_active: bool, seeded_fields: int, error?: string}
 */
function nera_attr_seed_attribution_options($force = false)
{
  $out = [
    'acf_active' => function_exists('update_field'),
    'seeded_fields' => 0,
  ];

  if (!$out['acf_active']) {
    $out['error'] = __('ACF is not active; update_field() is unavailable.', 'nera-competitions');
    return $out;
  }

  $defaults = nera_attr_get_default_field_values();
  foreach ($defaults as $field_name => $default_val) {
    $existing = get_field($field_name, 'option');
    if (!$force && nera_attr_seed_field_has_value($field_name, $existing)) {
      continue;
    }
    update_field($field_name, $default_val, 'option');
    $out['seeded_fields']++;
  }

  return $out;
}

/**
 * Backward-compatible alias.
 *
 * @param bool $force Overwrite existing values when true.
 * @return array{acf_active: bool, seeded_fields: int, error?: string}
 */
function nera_attr_seed_attribution_pages($force = false)
{
  return nera_attr_seed_attribution_options($force);
}
