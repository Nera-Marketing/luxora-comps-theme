<?php
/**
 * ACF Field Group: Archive Winners Page Content
 *
 * Registers custom fields for the Archive Winners page template.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit(); // Exit if accessed directly
}

/**
 * Register ACF field group for Archive Winners page
 */
function nera_register_archive_winners_fields()
{
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_archive_winners_page',
    'title' => __('Archive Winners Page Content', 'nera-competitions'),
    'fields' => [
      // Hero Section Tab
      [
        'key' => 'field_archive_winners_tab_hero',
        'label' => __('Hero Section', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_archive_winners_heading',
        'label' => __('Page Heading', 'nera-competitions'),
        'name' => 'archive_winners_heading',
        'type' => 'text',
        'instructions' => __('Main heading for the archive winners page', 'nera-competitions'),
        'default_value' => __('Draw Results & Winners', 'nera-competitions'),
        'placeholder' => __('Draw Results & Winners', 'nera-competitions'),
      ],
      [
        'key' => 'field_archive_winners_subheading',
        'label' => __('Subheading', 'nera-competitions'),
        'name' => 'archive_winners_subheading',
        'type' => 'text',
        'instructions' => __('Optional subtitle (appears above main heading)', 'nera-competitions'),
        'default_value' => __('Competition Archive', 'nera-competitions'),
        'placeholder' => __('Competition Archive', 'nera-competitions'),
      ],
      [
        'key' => 'field_archive_winners_description',
        'label' => __('Description', 'nera-competitions'),
        'name' => 'archive_winners_description',
        'type' => 'textarea',
        'instructions' => __('Supporting text below the heading', 'nera-competitions'),
        'default_value' => __('Browse our completed draws, view entry lists, and catch up on any results you might have missed.', 'nera-competitions'),
        'rows' => 3,
      ],
    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-templates/archive-winners-template.php',
        ],
      ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => ['the_content', 'featured_image'],
    'active' => true,
    'description' => __('Custom fields for the Archive Winners page template', 'nera-competitions'),
  ]);
}
add_action('acf/init', 'nera_register_archive_winners_fields');
