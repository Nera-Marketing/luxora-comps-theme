<?php
/**
 * Advanced Custom Fields - Luxora Homepage
 *
 * Registers the ACF field group for the Luxora Homepage template.
 * All sections in one unified group with tabs.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (function_exists('acf_add_local_field_group')) {
  acf_add_local_field_group([
    'key' => 'group_luxora_homepage',
    'title' => 'Luxora Homepage',
    'fields' => [
      // ─── Tab: Hero ───
      [
        'key' => 'field_luxora_tab_hero',
        'label' => 'Hero',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_hero_eyebrow',
        'label' => 'Eyebrow',
        'name' => 'luxora_hero_eyebrow',
        'type' => 'text',
        'default_value' => 'Premium Competitions',
      ],
      [
        'key' => 'field_luxora_hero_tagline',
        'label' => 'Tagline',
        'name' => 'luxora_hero_tagline',
        'type' => 'text',
        'default_value' => 'Every draw, a chance worth taking.',
      ],
      [
        'key' => 'field_luxora_hero_body',
        'label' => 'Body Text',
        'name' => 'luxora_hero_body',
        'type' => 'textarea',
        'rows' => 3,
        'default_value' => 'Curated competitions for people who appreciate quality. Transparent draws, genuine prizes, and a cash alternative on every entry.',
      ],
      [
        'key' => 'field_luxora_hero_cta_text',
        'label' => 'Primary CTA Text',
        'name' => 'luxora_hero_cta_text',
        'type' => 'text',
        'default_value' => 'View Competitions',
      ],
      [
        'key' => 'field_luxora_hero_cta_url',
        'label' => 'Primary CTA URL',
        'name' => 'luxora_hero_cta_url',
        'type' => 'url',
        'default_value' => '',
      ],
      [
        'key' => 'field_luxora_hero_secondary_text',
        'label' => 'Secondary Link Text',
        'name' => 'luxora_hero_secondary_text',
        'type' => 'text',
        'default_value' => 'How it works',
      ],
      [
        'key' => 'field_luxora_hero_secondary_url',
        'label' => 'Secondary Link URL',
        'name' => 'luxora_hero_secondary_url',
        'type' => 'url',
        'default_value' => '',
      ],
      [
        'key' => 'field_luxora_hero_trust',
        'label' => 'Trust Stats',
        'name' => 'luxora_hero_trust',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => 'Add Trust Stat',
        'sub_fields' => [
          [
            'key' => 'field_luxora_hero_trust_num',
            'label' => 'Number',
            'name' => 'num',
            'type' => 'text',
            'placeholder' => '1,240+',
          ],
          [
            'key' => 'field_luxora_hero_trust_label',
            'label' => 'Label',
            'name' => 'label',
            'type' => 'text',
            'placeholder' => 'Verified Winners',
          ],
        ],
        'default_value' => [
          ['num' => '1,240+', 'label' => 'Verified Winners'],
          ['num' => '£48k', 'label' => 'Prizes Awarded'],
          ['num' => '4.9 ★', 'label' => 'Trustpilot'],
        ],
      ],
      [
        'key' => 'field_luxora_hero_featured_product',
        'label' => 'Featured Product',
        'name' => 'luxora_hero_featured_product',
        'type' => 'post_object',
        'post_type' => ['product'],
        'return_format' => 'object',
        'allow_null' => 1,
      ],

      // ─── Tab: Marquee ───
      [
        'key' => 'field_luxora_tab_marquee',
        'label' => 'Marquee',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_marquee_items',
        'label' => 'Marquee Items',
        'name' => 'luxora_marquee_items',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => 'Add Item',
        'sub_fields' => [
          [
            'key' => 'field_luxora_marquee_text',
            'label' => 'Text',
            'name' => 'text',
            'type' => 'text',
            'placeholder' => 'Tech Bundles',
          ],
        ],
        'default_value' => [
          ['text' => 'Tech Bundles'],
          ['text' => 'Cash Prizes'],
          ['text' => 'Kitchen Appliances'],
          ['text' => 'Beauty Collections'],
          ['text' => 'Lifestyle Packages'],
          ['text' => 'Luxury Watches'],
          ['text' => 'Free Entry Always Available'],
        ],
      ],

      // ─── Tab: Competitions ───
      [
        'key' => 'field_luxora_tab_comp',
        'label' => 'Competitions',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_comp_label',
        'label' => 'Section Label',
        'name' => 'luxora_comp_label',
        'type' => 'text',
        'default_value' => 'Live Now',
      ],
      [
        'key' => 'field_luxora_comp_title',
        'label' => 'Section Title',
        'name' => 'luxora_comp_title',
        'type' => 'text',
        'default_value' => 'Current',
      ],
      [
        'key' => 'field_luxora_comp_title_em',
        'label' => 'Section Title (emphasised)',
        'name' => 'luxora_comp_title_em',
        'type' => 'text',
        'default_value' => 'Competitions',
      ],
      [
        'key' => 'field_luxora_comp_see_all_text',
        'label' => 'See All Link Text',
        'name' => 'luxora_comp_see_all_text',
        'type' => 'text',
        'default_value' => 'View all competitions',
      ],
      [
        'key' => 'field_luxora_comp_see_all_url',
        'label' => 'See All Link URL',
        'name' => 'luxora_comp_see_all_url',
        'type' => 'url',
        'default_value' => '',
      ],
      [
        'key' => 'field_luxora_comp_count',
        'label' => 'Number of Products',
        'name' => 'luxora_comp_count',
        'type' => 'number',
        'default_value' => 6,
        'min' => 1,
        'max' => 12,
      ],

      // ─── Tab: Why Luxora ───
      [
        'key' => 'field_luxora_tab_why',
        'label' => 'Why Luxora',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_why_label',
        'label' => 'Section Label',
        'name' => 'luxora_why_label',
        'type' => 'text',
        'default_value' => 'Why Luxora',
      ],
      [
        'key' => 'field_luxora_why_title',
        'label' => 'Section Title',
        'name' => 'luxora_why_title',
        'type' => 'text',
        'default_value' => 'Different by',
      ],
      [
        'key' => 'field_luxora_why_title_em',
        'label' => 'Section Title (emphasised)',
        'name' => 'luxora_why_title_em',
        'type' => 'text',
        'default_value' => 'design.',
      ],
      [
        'key' => 'field_luxora_why_pullquote',
        'label' => 'Pullquote',
        'name' => 'luxora_why_pullquote',
        'type' => 'textarea',
        'rows' => 2,
        'default_value' => '"The most refined competition platform I\'ve used."',
      ],
      [
        'key' => 'field_luxora_why_image_main',
        'label' => 'Main Image',
        'name' => 'luxora_why_image_main',
        'type' => 'image',
        'return_format' => 'array',
      ],
      [
        'key' => 'field_luxora_why_image_accent',
        'label' => 'Accent Image',
        'name' => 'luxora_why_image_accent',
        'type' => 'image',
        'return_format' => 'array',
      ],
      [
        'key' => 'field_luxora_why_items',
        'label' => 'Items',
        'name' => 'luxora_why_items',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add Item',
        'max' => 4,
        'sub_fields' => [
          [
            'key' => 'field_luxora_why_item_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
          ],
          [
            'key' => 'field_luxora_why_item_text',
            'label' => 'Text',
            'name' => 'text',
            'type' => 'textarea',
            'rows' => 3,
          ],
        ],
        'default_value' => [
          ['title' => 'Genuinely Curated Prizes', 'text' => 'We don\'t list anything we wouldn\'t enter ourselves. Every prize is selected for quality, desirability, and value. No padding, no filler.'],
          ['title' => 'Transparent, Verified Draws', 'text' => 'Winners are selected via Randomness.org and announced publicly. Every draw is documented. No ambiguity, ever.'],
          ['title' => 'Cash Alternative, Always', 'text' => 'Every prize has a cash equivalent. Winners choose what works for them. No questions asked.'],
          ['title' => 'UK Compliant, Always Free to Enter', 'text' => 'A free postal entry route is available on every competition. Luxora operates fully within UK law.'],
        ],
      ],

      // ─── Tab: Winners ───
      [
        'key' => 'field_luxora_tab_winners',
        'label' => 'Winners',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_winners_label',
        'label' => 'Section Label',
        'name' => 'luxora_winners_label',
        'type' => 'text',
        'default_value' => 'Recent Winners',
      ],
      [
        'key' => 'field_luxora_winners_title',
        'label' => 'Section Title',
        'name' => 'luxora_winners_title',
        'type' => 'text',
        'default_value' => 'People are',
      ],
      [
        'key' => 'field_luxora_winners_title_em',
        'label' => 'Section Title (emphasised)',
        'name' => 'luxora_winners_title_em',
        'type' => 'text',
        'default_value' => 'winning.',
      ],
      [
        'key' => 'field_luxora_winners_list',
        'label' => 'Winners List',
        'name' => 'luxora_winners_list',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add Winner',
        'sub_fields' => [
          [
            'key' => 'field_luxora_winner_prize',
            'label' => 'Prize',
            'name' => 'prize',
            'type' => 'text',
            'placeholder' => 'Apple MacBook Air M3',
          ],
          [
            'key' => 'field_luxora_winner_location',
            'label' => 'Location',
            'name' => 'location',
            'type' => 'text',
            'placeholder' => 'James T. — Manchester',
          ],
          [
            'key' => 'field_luxora_winner_quote',
            'label' => 'Quote',
            'name' => 'quote',
            'type' => 'textarea',
            'rows' => 2,
          ],
          [
            'key' => 'field_luxora_winner_date',
            'label' => 'Date',
            'name' => 'date',
            'type' => 'text',
            'placeholder' => 'February 2025',
          ],
        ],
        'default_value' => [
          ['prize' => 'Apple MacBook Air M3', 'location' => 'James T. — Manchester', 'quote' => '"Genuinely didn\'t believe it at first. The whole process was seamless from entry to delivery."', 'date' => 'February 2025'],
          ['prize' => '£500 Cash Prize', 'location' => 'Sophie L. — London', 'quote' => '"In my account within 24 hours. No fuss, no drama. Will absolutely enter again."', 'date' => 'February 2025'],
          ['prize' => 'Charlotte Tilbury Bundle', 'location' => 'Priya M. — Birmingham', 'quote' => '"The packaging alone felt premium. Everything arrived perfectly, exactly as described."', 'date' => 'January 2025'],
          ['prize' => 'KitchenAid Artisan Mixer', 'location' => 'David R. — Edinburgh', 'quote' => '"The draw was live streamed and completely transparent. My wife had wanted one for years."', 'date' => 'January 2025'],
        ],
      ],

      // ─── Tab: How It Works ───
      [
        'key' => 'field_luxora_tab_how',
        'label' => 'How It Works',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_how_label',
        'label' => 'Section Label',
        'name' => 'luxora_how_label',
        'type' => 'text',
        'default_value' => 'The Process',
      ],
      [
        'key' => 'field_luxora_how_title',
        'label' => 'Section Title',
        'name' => 'luxora_how_title',
        'type' => 'text',
        'default_value' => 'Simple to enter.',
      ],
      [
        'key' => 'field_luxora_how_title_em',
        'label' => 'Section Title (emphasised)',
        'name' => 'luxora_how_title_em',
        'type' => 'text',
        'default_value' => 'Honest by nature.',
      ],
      [
        'key' => 'field_luxora_how_steps',
        'label' => 'Steps',
        'name' => 'luxora_how_steps',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => 'Add Step',
        'max' => 3,
        'sub_fields' => [
          [
            'key' => 'field_luxora_how_step_num',
            'label' => 'Step Number',
            'name' => 'step_num',
            'type' => 'text',
            'placeholder' => '01',
          ],
          [
            'key' => 'field_luxora_how_step_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'placeholder' => 'Browse and Select',
          ],
          [
            'key' => 'field_luxora_how_step_text',
            'label' => 'Text',
            'name' => 'text',
            'type' => 'textarea',
            'rows' => 3,
          ],
        ],
        'default_value' => [
          ['step_num' => '01', 'title' => 'Browse and Select', 'text' => 'Explore live competitions across tech, lifestyle, beauty and more. Every prize includes full details, retail value, and a cash alternative amount.'],
          ['step_num' => '02', 'title' => 'Choose Your Tickets', 'text' => 'Select how many tickets you\'d like. Bundle pricing rewards multiple entries. A free postal entry route is available on every competition.'],
          ['step_num' => '03', 'title' => 'Live Draw and Win', 'text' => 'Draws are conducted live. Winners selected via certified randomisation and contacted directly. Prize or cash — your choice.'],
        ],
      ],

      // ─── Tab: Free Entry Banner ───
      [
        'key' => 'field_luxora_tab_free',
        'label' => 'Free Entry Banner',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_luxora_free_title',
        'label' => 'Title',
        'name' => 'luxora_free_title',
        'type' => 'text',
        'default_value' => 'Free entry is always available.',
      ],
      [
        'key' => 'field_luxora_free_body',
        'label' => 'Body',
        'name' => 'luxora_free_body',
        'type' => 'textarea',
        'rows' => 2,
        'default_value' => 'You never need to pay to enter a Luxora Draw. A free postal entry route is provided on every competition in compliance with UK law.',
      ],
      [
        'key' => 'field_luxora_free_cta_text',
        'label' => 'CTA Text',
        'name' => 'luxora_free_cta_text',
        'type' => 'text',
        'default_value' => 'View Free Entry Details',
      ],
      [
        'key' => 'field_luxora_free_cta_url',
        'label' => 'CTA URL',
        'name' => 'luxora_free_cta_url',
        'type' => 'url',
        'default_value' => '',
      ],
    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-templates/luxora-homepage-template.php',
        ],
      ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => [
      0 => 'the_content',
    ],
    'active' => true,
  ]);
}
