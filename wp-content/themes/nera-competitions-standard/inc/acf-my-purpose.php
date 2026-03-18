<?php
/**
 * ACF Field Group: My Purpose Page Content
 *
 * Registers custom fields for the My Purpose page template.
 *
 * @package Nera_Competitions
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Register ACF field group for My Purpose page
 */
function nera_register_my_purpose_fields()
{
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_my_purpose_page',
    'title' => __('My Purpose Page Content', 'nera-competitions'),
    'fields' => [

      // ── Hero Section ──────────────────────────────────────────────────────
      [
        'key' => 'field_mp_tab_hero',
        'label' => __('Hero Section', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_mp_title',
        'label' => __('Page Title', 'nera-competitions'),
        'name' => 'my_purpose_title',
        'type' => 'text',
        'instructions' => __('Main heading in the hero. Defaults to the WordPress page title.', 'nera-competitions'),
        'placeholder' => __('My Purpose', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_hero_image',
        'label' => __('Hero Image', 'nera-competitions'),
        'name' => 'my_purpose_hero_image',
        'type' => 'image',
        'instructions' => __("Portrait photo displayed in the hero (recommended ratio 4:5). Leave empty to show the placeholder.", 'nera-competitions'),
        'return_format' => 'array',
        'preview_size' => 'medium',
        'library' => 'all',
      ],

      // ── Narrative ─────────────────────────────────────────────────────────
      [
        'key' => 'field_mp_tab_narrative',
        'label' => __('Narrative', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_mp_narrative',
        'label' => __('Narrative Content', 'nera-competitions'),
        'name' => 'my_purpose_narrative',
        'type' => 'wysiwyg',
        'instructions' => __("The main personal story / narrative section. Supports rich text.", 'nera-competitions'),
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
      ],

      // ── Health Journey ────────────────────────────────────────────────────
      [
        'key' => 'field_mp_tab_health',
        'label' => __('Health Journey', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_mp_health_title',
        'label' => __('Section Title', 'nera-competitions'),
        'name' => 'health_journey_title',
        'type' => 'text',
        'instructions' => __('Heading for the health journey section.', 'nera-competitions'),
        'default_value' => __('My Health Journey', 'nera-competitions'),
        'placeholder' => __('My Health Journey', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_health_content',
        'label' => __('Health Journey Content', 'nera-competitions'),
        'name' => 'health_journey_content',
        'type' => 'wysiwyg',
        'instructions' => __('Rich-text content for the health journey section.', 'nera-competitions'),
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
      ],

      // ── Autism Diagnosis ──────────────────────────────────────────────────
      [
        'key' => 'field_mp_tab_autism',
        'label' => __('Autism Diagnosis', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_mp_autism_title',
        'label' => __('Section Title', 'nera-competitions'),
        'name' => 'autism_diagnosis_title',
        'type' => 'text',
        'instructions' => __('Heading for the autism diagnosis section.', 'nera-competitions'),
        'default_value' => __('Autism Diagnosis', 'nera-competitions'),
        'placeholder' => __('Autism Diagnosis', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_autism_content',
        'label' => __('Autism Diagnosis Content', 'nera-competitions'),
        'name' => 'autism_diagnosis_content',
        'type' => 'wysiwyg',
        'instructions' => __('Rich-text content for the autism diagnosis section.', 'nera-competitions'),
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
      ],

      // ── Community CTA ─────────────────────────────────────────────────────
      [
        'key' => 'field_mp_tab_cta',
        'label' => __('Community CTA', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_mp_cta_heading',
        'label' => __('CTA Heading', 'nera-competitions'),
        'name' => 'community_cta_heading',
        'type' => 'text',
        'instructions' => __('Heading for the call-to-action section at the bottom of the page.', 'nera-competitions'),
        'default_value' => __('Join Our Community', 'nera-competitions'),
        'placeholder' => __('Join Our Community', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_cta_description',
        'label' => __('CTA Description', 'nera-competitions'),
        'name' => 'community_cta_description',
        'type' => 'textarea',
        'instructions' => __('Supporting text below the CTA heading.', 'nera-competitions'),
        'default_value' => __('Be a part of a transparent, supportive, and exciting journey where everyone has a chance to change their life.', 'nera-competitions'),
        'rows' => 3,
      ],
      [
        'key' => 'field_mp_cta_primary_text',
        'label' => __('Primary Button Text', 'nera-competitions'),
        'name' => 'community_cta_primary_btn_text',
        'type' => 'text',
        'instructions' => __('Label for the primary CTA button.', 'nera-competitions'),
        'default_value' => __('Explore Competitions', 'nera-competitions'),
        'placeholder' => __('Explore Competitions', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_cta_primary_url',
        'label' => __('Primary Button URL', 'nera-competitions'),
        'name' => 'community_cta_primary_btn_url',
        'type' => 'url',
        'instructions' => __('Destination URL for the primary button. Defaults to /shop/.', 'nera-competitions'),
        'placeholder' => '/shop/',
      ],
      [
        'key' => 'field_mp_cta_secondary_text',
        'label' => __('Secondary Button Text', 'nera-competitions'),
        'name' => 'community_cta_secondary_btn_text',
        'type' => 'text',
        'instructions' => __('Label for the secondary (outline) CTA button.', 'nera-competitions'),
        'default_value' => __('Get in Touch', 'nera-competitions'),
        'placeholder' => __('Get in Touch', 'nera-competitions'),
      ],
      [
        'key' => 'field_mp_cta_secondary_url',
        'label' => __('Secondary Button URL', 'nera-competitions'),
        'name' => 'community_cta_secondary_btn_url',
        'type' => 'url',
        'instructions' => __('Destination URL for the secondary button. Defaults to /contact/.', 'nera-competitions'),
        'placeholder' => '/contact/',
      ],

    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-templates/my-purpose-template.php',
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
    'description' => __('Custom fields for the My Purpose page template', 'nera-competitions'),
  ]);
}
add_action('acf/init', 'nera_register_my_purpose_fields');
