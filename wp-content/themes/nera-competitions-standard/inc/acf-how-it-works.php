<?php
/**
 * ACF Field Group: How It Works Page Content
 *
 * Registers custom fields for the How It Works page template.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Register ACF field group for How It Works page
 */
function nera_register_how_it_works_fields()
{
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_how_it_works_page',
    'title' => __('How It Works Page Content', 'nera-competitions'),
    'fields' => [

      // ── Hero Section ──────────────────────────────────────────────────────
      [
        'key' => 'field_hiw_tab_hero',
        'label' => __('Hero Section', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_hiw_hero_title',
        'label' => __('Hero Title', 'nera-competitions'),
        'name' => 'hiw_hero_title',
        'type' => 'text',
        'instructions' => __('Main heading displayed in the hero. Defaults to the page title.', 'nera-competitions'),
        'default_value' => __('How It Works', 'nera-competitions'),
        'placeholder' => __('How It Works', 'nera-competitions'),
      ],
      [
        'key' => 'field_hiw_hero_subtitle',
        'label' => __('Hero Subtitle', 'nera-competitions'),
        'name' => 'hiw_hero_subtitle',
        'type' => 'text',
        'instructions' => __('Supporting line below the hero title.', 'nera-competitions'),
        'default_value' => __('Win your dream prizes in just 4 simple steps', 'nera-competitions'),
        'placeholder' => __('Win your dream prizes in just 4 simple steps', 'nera-competitions'),
      ],
      [
        'key' => 'field_hiw_hero_badge',
        'label' => __('Hero Badge', 'nera-competitions'),
        'name' => 'hiw_hero_badge',
        'type' => 'text',
        'instructions' => __('Small badge label shown in the hero area.', 'nera-competitions'),
        'default_value' => __('Simple & Fair', 'nera-competitions'),
        'placeholder' => __('Simple & Fair', 'nera-competitions'),
      ],

      // ── Draw Process ──────────────────────────────────────────────────────
      [
        'key' => 'field_hiw_tab_draw',
        'label' => __('Draw Process', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_hiw_draw_title',
        'label' => __('Section Title', 'nera-competitions'),
        'name' => 'hiw_draw_title',
        'type' => 'text',
        'instructions' => __('Heading for the "Draw Process" section.', 'nera-competitions'),
        'default_value' => __('The Draw Process', 'nera-competitions'),
        'placeholder' => __('The Draw Process', 'nera-competitions'),
      ],
      [
        'key' => 'field_hiw_draw_content',
        'label' => __('Draw Content', 'nera-competitions'),
        'name' => 'hiw_draw_content',
        'type' => 'wysiwyg',
        'instructions' => __('Rich-text description of how draws are conducted. Leave empty to show the default text.', 'nera-competitions'),
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
      ],
      [
        'key' => 'field_hiw_draw_image',
        'label' => __('Draw Image', 'nera-competitions'),
        'name' => 'hiw_draw_image',
        'type' => 'image',
        'instructions' => __('Optional image shown beside the draw process text. Displayed as a rounded card.', 'nera-competitions'),
        'return_format' => 'array',
        'preview_size' => 'medium',
        'library' => 'all',
      ],

      // ── Postal Entry ──────────────────────────────────────────────────────
      [
        'key' => 'field_hiw_tab_postal',
        'label' => __('Postal Entry', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_hiw_postal_title',
        'label' => __('Section Title', 'nera-competitions'),
        'name' => 'hiw_postal_title',
        'type' => 'text',
        'instructions' => __('Heading for the postal entry section.', 'nera-competitions'),
        'default_value' => __('Free Postal Entry Route', 'nera-competitions'),
        'placeholder' => __('Free Postal Entry Route', 'nera-competitions'),
      ],
      [
        'key' => 'field_hiw_postal_steps',
        'label' => __('Postal Steps', 'nera-competitions'),
        'name' => 'hiw_postal_steps',
        'type' => 'repeater',
        'instructions' => __('Numbered steps for the postal entry process. Leave empty to use the built-in defaults.', 'nera-competitions'),
        'min' => 0,
        'max' => 10,
        'layout' => 'block',
        'button_label' => __('Add Step', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_hiw_postal_step_number',
            'label' => __('Step Number', 'nera-competitions'),
            'name' => 'number',
            'type' => 'text',
            'instructions' => __('Display number for this step (e.g. 1, 2, 3).', 'nera-competitions'),
            'placeholder' => '1',
            'wrapper' => ['width' => '20'],
          ],
          [
            'key' => 'field_hiw_postal_step_text',
            'label' => __('Step Text', 'nera-competitions'),
            'name' => 'text',
            'type' => 'textarea',
            'instructions' => __('Description of this step.', 'nera-competitions'),
            'rows' => 3,
            'wrapper' => ['width' => '80'],
          ],
        ],
      ],
      [
        'key' => 'field_hiw_postal_note',
        'label' => __('Postal Note', 'nera-competitions'),
        'name' => 'hiw_postal_note',
        'type' => 'text',
        'instructions' => __('Small print note shown below the postal steps.', 'nera-competitions'),
        'default_value' => __('Please note: One entry per postcard. Entries must be received before the competition closes.', 'nera-competitions'),
        'placeholder' => __('Please note: One entry per postcard.', 'nera-competitions'),
      ],

      // ── Transparency & Fairness ───────────────────────────────────────────
      [
        'key' => 'field_hiw_tab_transparency',
        'label' => __('Transparency & Fairness', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_hiw_transparency_title',
        'label' => __('Section Title', 'nera-competitions'),
        'name' => 'hiw_transparency_title',
        'type' => 'text',
        'instructions' => __('Heading for the transparency section.', 'nera-competitions'),
        'default_value' => __('Transparency & Fairness', 'nera-competitions'),
        'placeholder' => __('Transparency & Fairness', 'nera-competitions'),
      ],
      [
        'key' => 'field_hiw_transparency_features',
        'label' => __('Feature Cards', 'nera-competitions'),
        'name' => 'hiw_transparency_features',
        'type' => 'repeater',
        'instructions' => __('Up to 3 feature cards. Leave empty to use the built-in defaults (Fully Insured, Community Focused, Secure & Safe).', 'nera-competitions'),
        'min' => 0,
        'max' => 3,
        'layout' => 'block',
        'button_label' => __('Add Feature Card', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_hiw_feature_icon',
            'label' => __('Material Icon Name', 'nera-competitions'),
            'name' => 'icon',
            'type' => 'text',
            'instructions' => __('Google Material Symbols icon name, e.g. verified_user, diversity_3, shield_with_heart.', 'nera-competitions'),
            'placeholder' => 'verified_user',
          ],
          [
            'key' => 'field_hiw_feature_title',
            'label' => __('Title', 'nera-competitions'),
            'name' => 'title',
            'type' => 'text',
            'placeholder' => __('Fully Insured', 'nera-competitions'),
          ],
          [
            'key' => 'field_hiw_feature_description',
            'label' => __('Description', 'nera-competitions'),
            'name' => 'description',
            'type' => 'textarea',
            'rows' => 3,
          ],
        ],
      ],

    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-templates/how-it-works-template.php',
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
    'description' => __('Custom fields for the How It Works page template', 'nera-competitions'),
  ]);
}
add_action('acf/init', 'nera_register_how_it_works_fields');
