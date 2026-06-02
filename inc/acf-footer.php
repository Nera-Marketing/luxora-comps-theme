<?php
/**
 * ACf Footer Settings
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

if (function_exists('acf_add_options_page')) {
  // Check if Theme Settings page exists, if not create it
  if (!function_exists('acf_get_options_page') || !acf_get_options_page('theme-settings')) {
    acf_add_options_page([
      'page_title' => 'Theme Settings',
      'menu_title' => 'Theme Settings',
      'menu_slug' => 'theme-settings',
      'capability' => 'edit_posts',
      'redirect' => false,
    ]);
  }

  // Add Footer Settings Subpage
  acf_add_options_sub_page([
    'page_title' => 'Footer Settings',
    'menu_title' => 'Footer',
    'parent_slug' => 'theme-settings',
  ]);
}

if (function_exists('acf_add_local_field_group')) {
  acf_add_local_field_group([
    'key' => 'group_neracompetitions_footer',
    'title' => 'Footer Settings',
    'fields' => [
      [
        'key' => 'field_footer_legal_disclaimer',
        'label' => 'Legal Disclaimer (Left)',
        'name' => 'footer_legal_disclaimer',
        'type' => 'textarea',
        'instructions' => 'Skills-based / free entry text shown in the left footer block.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '100', 'class' => '', 'id' => ''],
        'default_value' => 'Skills-based competition. A free entry route is available on every competition. Participants must be aged 18 or over.',
        'rows' => 3,
        'new_lines' => 'br',
      ],
      [
        'key' => 'field_footer_legal_right',
        'label' => 'Legal Text (Right)',
        'name' => 'footer_legal_right',
        'type' => 'textarea',
        'instructions' => 'Right-column legal text (Gambling Act, English law, terms).',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '100', 'class' => '', 'id' => ''],
        'default_value' => 'Luxora Draws operates in accordance with the Gambling Act 2005. All competitions governed by English law. Full terms apply.',
        'rows' => 3,
        'new_lines' => 'br',
      ],
      [
        'key' => 'field_footer_copyright',
        'label' => 'Copyright Text',
        'name' => 'footer_copyright',
        'type' => 'text',
        'instructions' => 'Copyright line. Use {year} for dynamic current year.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
        'default_value' => '© {year} Luxora Draws Ltd. All rights reserved. Registered in England & Wales.',
      ],
      [
        'key' => 'field_footer_contact_email',
        'label' => 'Contact Email',
        'name' => 'footer_contact_email',
        'type' => 'email',
        'instructions' => 'Email shown in "Have a question?" line. Leave empty to hide.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
        'default_value' => 'jj@livelifeprizes.co.uk',
      ],
      [
        'key' => 'field_footer_vlb_label',
        'label' => 'Van Life Builds Badge Label',
        'name' => 'footer_vlb_label',
        'type' => 'text',
        'instructions' => 'Text for the right-side badge (e.g. "Part of Van Life Builds").',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
        'default_value' => 'Part of Van Life Builds',
      ],
      [
        'key' => 'field_footer_vlb_url',
        'label' => 'Van Life Builds Badge URL',
        'name' => 'footer_vlb_url',
        'type' => 'url',
        'instructions' => 'Optional. Link for the badge; leave empty to show as non-clickable text.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => ['width' => '50', 'class' => '', 'id' => ''],
        'default_value' => '',
      ],
    ],
    'location' => [
      [
        [
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'acf-options-footer',
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
