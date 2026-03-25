<?php
/**
 * Advanced Custom Fields - Brand Statement Section
 *
 * Registers the ACF field group for the Brand Statement homepage section.
 * Shown on the Nera Homepage template.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (function_exists('acf_add_local_field_group')) {
  acf_add_local_field_group([
    'key'    => 'group_brand_statement',
    'title'  => 'Brand Statement Section',
    'fields' => [
      [
        'key'           => 'field_bs_label',
        'label'         => 'Eyebrow Label',
        'name'          => 'bs_label',
        'type'          => 'text',
        'default_value' => 'Our Promise',
        'instructions'  => 'Small label displayed above the heading.',
      ],
      [
        'key'           => 'field_bs_heading',
        'label'         => 'Heading — Line 1',
        'name'          => 'bs_heading',
        'type'          => 'text',
        'default_value' => 'Get on a',
        'instructions'  => 'First line of the heading (plain text, bold).',
      ],
      [
        'key'           => 'field_bs_heading_em',
        'label'         => 'Heading — Line 2 (Italic)',
        'name'          => 'bs_heading_em',
        'type'          => 'text',
        'default_value' => 'winning streak',
        'instructions'  => 'Second line of the heading — rendered in italic script below Line 1.',
      ],
      [
        'key'           => 'field_bs_body',
        'label'         => 'Body Text',
        'name'          => 'bs_body',
        'type'          => 'textarea',
        'rows'          => 3,
        'default_value' => 'Capped tickets. Fair draws. Real winners. Every competition on Luxora gives you a genuine shot at winning something extraordinary — no bots, no bulk buyers, just you.',
        'instructions'  => 'Supporting paragraph displayed below the heading.',
      ],
      [
        'key'           => 'field_bs_cta_text',
        'label'         => 'CTA Button Text',
        'name'          => 'bs_cta_text',
        'type'          => 'text',
        'default_value' => 'Enter a Competition',
        'instructions'  => 'Text for the call-to-action button.',
      ],
      [
        'key'           => 'field_bs_cta_url',
        'label'         => 'CTA Button Link',
        'name'          => 'bs_cta_url',
        'type'          => 'link',
        'return_format' => 'array',
        'instructions'  => 'Link for the call-to-action button (URL, optional title, open in new tab).',
      ],
      [
        'key'           => 'field_bs_image_1',
        'label'         => 'Image 1 (Left)',
        'name'          => 'bs_image_1',
        'type'          => 'image',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'instructions'  => 'Portrait image displayed on the left side of the image cluster.',
      ],
      [
        'key'           => 'field_bs_image_2',
        'label'         => 'Image 2 (Centre — featured)',
        'name'          => 'bs_image_2',
        'type'          => 'image',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'instructions'  => 'Portrait image displayed in the centre, in front of the others.',
      ],
      [
        'key'           => 'field_bs_image_3',
        'label'         => 'Image 3 (Right)',
        'name'          => 'bs_image_3',
        'type'          => 'image',
        'return_format' => 'array',
        'preview_size'  => 'medium',
        'instructions'  => 'Portrait image displayed on the right side of the image cluster.',
      ],
    ],
    'location' => [
      // Nera Homepage template
      [
        [
          'param'    => 'page_template',
          'operator' => '==',
          'value'    => 'page-templates/homepage-template.php',
        ],
      ],
      // Luxora Homepage template
      [
        [
          'param'    => 'page_template',
          'operator' => '==',
          'value'    => 'page-templates/luxora-homepage-template.php',
        ],
      ],
    ],
    'menu_order'          => 10,
    'position'            => 'normal',
    'style'               => 'default',
    'label_placement'     => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'      => [
      0 => 'the_content',
    ],
    'active' => true,
  ]);
}
