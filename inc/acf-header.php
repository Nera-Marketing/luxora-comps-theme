<?php
/**
 * ACF Header Settings (Theme Settings > Header)
 *
 * Log In and Enter Now button label + URL for the header when user is not logged in.
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

if (function_exists('acf_add_options_page')) {
  if (!function_exists('acf_get_options_page') || !acf_get_options_page('theme-settings')) {
    acf_add_options_page([
      'page_title' => 'Theme Settings',
      'menu_title' => 'Theme Settings',
      'menu_slug' => 'theme-settings',
      'capability' => 'edit_posts',
      'redirect' => false,
    ]);
  }

  acf_add_options_sub_page([
    'page_title' => 'Header Settings',
    'menu_title' => 'Header',
    'parent_slug' => 'theme-settings',
  ]);
}

if (function_exists('acf_add_local_field_group')) {
  acf_add_local_field_group([
    'key' => 'group_nera_header',
    'title' => 'Header CTAs (Logged Out)',
    'fields' => [
      [
        'key' => 'field_header_log_in_link',
        'label' => 'Log In',
        'name' => 'header_log_in_link',
        'type' => 'link',
        'return_format' => 'array',
        'instructions' => 'Link when user is not logged in. Leave empty to use default (My Account / login page).',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
      ],
      [
        'key' => 'field_header_enter_now_link',
        'label' => 'Enter Now',
        'name' => 'header_enter_now_link',
        'type' => 'link',
        'return_format' => 'array',
        'instructions' => 'Primary CTA when user is not logged in. Leave empty to use Shop page.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
      ],
    ],
    'location' => [
      [
        [
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'acf-options-header',
        ],
      ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ]);
}
