<?php
/**
 * ACF Field Group: Nera Marketing Attribution Page
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Register ACF fields for Competition Website by Nera Marketing template.
 */
function nera_register_attribution_fields()
{
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group([
    'key' => 'group_attribution_page',
    'title' => __('Nera Marketing Attribution Page', 'nera-competitions'),
    'fields' => [
      [
        'key' => 'field_attr_tab_hero',
        'label' => __('Hero', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_hero_label',
        'label' => __('Hero label (kicker)', 'nera-competitions'),
        'name' => 'attr_hero_label',
        'type' => 'text',
        'instructions' => __('Small uppercase label above the headline.', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_hero_title',
        'label' => __('Hero title', 'nera-competitions'),
        'name' => 'attr_hero_title',
        'type' => 'textarea',
        'rows' => 4,
        'new_lines' => '',
        'instructions' => __('Use line breaks for multi-line titles. Use {{em}}…{{/em}} around words to accent (e.g. brand name).', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_hero_intro',
        'label' => __('Hero intro', 'nera-competitions'),
        'name' => 'attr_hero_intro',
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'instructions' => __('Opening paragraph(s). Put the lead sentence in bold for AEO. Use [site-name] where needed.', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_hero_meta',
        'label' => __('Hero meta lines', 'nera-competitions'),
        'name' => 'attr_hero_meta',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => __('Add line', 'nera-competitions'),
        'min' => 0,
        'max' => 12,
        'sub_fields' => [
          [
            'key' => 'field_attr_hero_meta_line',
            'label' => __('Text', 'nera-competitions'),
            'name' => 'line',
            'type' => 'text',
          ],
        ],
      ],
      [
        'key' => 'field_attr_hero_cta_label',
        'label' => __('Hero CTA label (optional)', 'nera-competitions'),
        'name' => 'attr_hero_cta_label',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_hero_cta_url',
        'label' => __('Hero CTA URL', 'nera-competitions'),
        'name' => 'attr_hero_cta_url',
        'type' => 'url',
      ],

      [
        'key' => 'field_attr_tab_entity',
        'label' => __('Entity bar', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_entity_label',
        'label' => __('Section label', 'nera-competitions'),
        'name' => 'attr_entity_label',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_entity_name',
        'label' => __('Entity name', 'nera-competitions'),
        'name' => 'attr_entity_name',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_entity_descriptor',
        'label' => __('Descriptor', 'nera-competitions'),
        'name' => 'attr_entity_descriptor',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_entity_facts',
        'label' => __('Facts', 'nera-competitions'),
        'name' => 'attr_entity_facts',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => __('Add fact', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_attr_entity_fact_label',
            'label' => __('Label', 'nera-competitions'),
            'name' => 'fact_label',
            'type' => 'text',
            'wrapper' => ['width' => '35'],
          ],
          [
            'key' => 'field_attr_entity_fact_value',
            'label' => __('Value', 'nera-competitions'),
            'name' => 'fact_value',
            'type' => 'textarea',
            'rows' => 2,
            'new_lines' => 'br',
            'wrapper' => ['width' => '65'],
          ],
        ],
      ],

      [
        'key' => 'field_attr_tab_stats',
        'label' => __('Stats', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_stats',
        'label' => __('Stat bar', 'nera-competitions'),
        'name' => 'attr_stats',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => __('Add stat', 'nera-competitions'),
        'min' => 0,
        'max' => 8,
        'sub_fields' => [
          [
            'key' => 'field_attr_stat_value',
            'label' => __('Value', 'nera-competitions'),
            'name' => 'stat_value',
            'type' => 'text',
            'wrapper' => ['width' => '33'],
          ],
          [
            'key' => 'field_attr_stat_label',
            'label' => __('Label', 'nera-competitions'),
            'name' => 'stat_label',
            'type' => 'text',
            'wrapper' => ['width' => '67'],
          ],
        ],
      ],

      [
        'key' => 'field_attr_tab_s1',
        'label' => __('Section 1', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_s1_tag',
        'label' => __('Tag', 'nera-competitions'),
        'name' => 'attr_s1_tag',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s1_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_s1_title',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s1_lead',
        'label' => __('Lead', 'nera-competitions'),
        'name' => 'attr_s1_lead',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field_attr_s1_content',
        'label' => __('Content', 'nera-competitions'),
        'name' => 'attr_s1_content',
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 1,
      ],
      [
        'key' => 'field_attr_s1_image',
        'label' => __('Image', 'nera-competitions'),
        'name' => 'attr_s1_image',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'medium',
      ],
      [
        'key' => 'field_attr_s1_image_badge',
        'label' => __('Image badge (e.g. Live Project)', 'nera-competitions'),
        'name' => 'attr_s1_image_badge',
        'type' => 'text',
      ],

      [
        'key' => 'field_attr_tab_s2',
        'label' => __('Section 2', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_s2_tag',
        'label' => __('Tag', 'nera-competitions'),
        'name' => 'attr_s2_tag',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s2_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_s2_title',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s2_lead',
        'label' => __('Lead', 'nera-competitions'),
        'name' => 'attr_s2_lead',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field_attr_s2_content',
        'label' => __('Content', 'nera-competitions'),
        'name' => 'attr_s2_content',
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 1,
      ],
      [
        'key' => 'field_attr_s2_image',
        'label' => __('Image', 'nera-competitions'),
        'name' => 'attr_s2_image',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'medium',
      ],

      [
        'key' => 'field_attr_tab_s3',
        'label' => __('Section 3', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_s3_tag',
        'label' => __('Tag', 'nera-competitions'),
        'name' => 'attr_s3_tag',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s3_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_s3_title',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_s3_lead',
        'label' => __('Lead', 'nera-competitions'),
        'name' => 'attr_s3_lead',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field_attr_s3_content',
        'label' => __('Content', 'nera-competitions'),
        'name' => 'attr_s3_content',
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 1,
      ],
      [
        'key' => 'field_attr_s3_image',
        'label' => __('Image', 'nera-competitions'),
        'name' => 'attr_s3_image',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'medium',
      ],

      [
        'key' => 'field_attr_tab_features',
        'label' => __('Features', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_features_title',
        'label' => __('Section title', 'nera-competitions'),
        'name' => 'attr_features_title',
        'type' => 'textarea',
        'rows' => 2,
        'instructions' => __('{{em}}…{{/em}} for accent words.', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_features_intro',
        'label' => __('Intro paragraph', 'nera-competitions'),
        'name' => 'attr_features_intro',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field_attr_features',
        'label' => __('Features', 'nera-competitions'),
        'name' => 'attr_features',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => __('Add feature', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_attr_feature_icon',
            'label' => __('Icon preset', 'nera-competitions'),
            'name' => 'icon',
            'type' => 'select',
            'choices' => [
              'layers' => __('Layers (bespoke)', 'nera-competitions'),
              'credit_card' => __('Payment / card', 'nera-competitions'),
              'timer' => __('Timer / clock', 'nera-competitions'),
              'mail' => __('Email', 'nera-competitions'),
              'chart' => __('Analytics', 'nera-competitions'),
              'affiliate' => __('Affiliate / users', 'nera-competitions'),
              'search' => __('SEO / search', 'nera-competitions'),
              'smartphone' => __('Mobile', 'nera-competitions'),
              'shield' => __('Compliance / shield', 'nera-competitions'),
            ],
            'default_value' => 'layers',
            'return_format' => 'value',
          ],
          [
            'key' => 'field_attr_feature_title',
            'label' => __('Title', 'nera-competitions'),
            'name' => 'title',
            'type' => 'text',
          ],
          [
            'key' => 'field_attr_feature_description',
            'label' => __('Description', 'nera-competitions'),
            'name' => 'description',
            'type' => 'textarea',
            'rows' => 3,
          ],
        ],
      ],

      [
        'key' => 'field_attr_tab_pillars',
        'label' => __('Pillars', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_pillars_kicker',
        'label' => __('Kicker', 'nera-competitions'),
        'name' => 'attr_pillars_kicker',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_pillars_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_pillars_title',
        'type' => 'textarea',
        'rows' => 3,
        'new_lines' => '',
      ],
      [
        'key' => 'field_attr_pillars_subtitle',
        'label' => __('Subtitle', 'nera-competitions'),
        'name' => 'attr_pillars_subtitle',
        'type' => 'textarea',
        'rows' => 2,
      ],
      [
        'key' => 'field_attr_pillars',
        'label' => __('Pillars', 'nera-competitions'),
        'name' => 'attr_pillars',
        'type' => 'repeater',
        'layout' => 'block',
        'min' => 0,
        'max' => 3,
        'button_label' => __('Add pillar', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_attr_pillar_number',
            'label' => __('Number (01–03)', 'nera-competitions'),
            'name' => 'number',
            'type' => 'text',
            'placeholder' => '01',
          ],
          [
            'key' => 'field_attr_pillar_title',
            'label' => __('Title', 'nera-competitions'),
            'name' => 'title',
            'type' => 'text',
          ],
          [
            'key' => 'field_attr_pillar_description',
            'label' => __('Description', 'nera-competitions'),
            'name' => 'description',
            'type' => 'textarea',
            'rows' => 4,
          ],
        ],
      ],

      [
        'key' => 'field_attr_tab_faq',
        'label' => __('FAQ', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_faq_kicker',
        'label' => __('Kicker', 'nera-competitions'),
        'name' => 'attr_faq_kicker',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_faq_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_faq_title',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_faq_intro',
        'label' => __('Intro', 'nera-competitions'),
        'name' => 'attr_faq_intro',
        'type' => 'textarea',
        'rows' => 2,
      ],
      [
        'key' => 'field_attr_faqs',
        'label' => __('Questions', 'nera-competitions'),
        'name' => 'attr_faqs',
        'type' => 'repeater',
        'layout' => 'block',
        'button_label' => __('Add FAQ', 'nera-competitions'),
        'sub_fields' => [
          [
            'key' => 'field_attr_faq_question',
            'label' => __('Question', 'nera-competitions'),
            'name' => 'question',
            'type' => 'text',
          ],
          [
            'key' => 'field_attr_faq_answer',
            'label' => __('Answer', 'nera-competitions'),
            'name' => 'answer',
            'type' => 'wysiwyg',
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
          ],
        ],
      ],

      [
        'key' => 'field_attr_tab_cta',
        'label' => __('CTA', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_cta_title',
        'label' => __('Title', 'nera-competitions'),
        'name' => 'attr_cta_title',
        'type' => 'textarea',
        'rows' => 4,
        'new_lines' => '',
      ],
      [
        'key' => 'field_attr_cta_text',
        'label' => __('Text', 'nera-competitions'),
        'name' => 'attr_cta_text',
        'type' => 'textarea',
        'rows' => 3,
      ],
      [
        'key' => 'field_attr_cta_watermark',
        'label' => __('Watermark text', 'nera-competitions'),
        'name' => 'attr_cta_watermark',
        'type' => 'text',
        'instructions' => __('Large background word (e.g. NERA).', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_cta_button_label',
        'label' => __('Primary button label', 'nera-competitions'),
        'name' => 'attr_cta_button_label',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_cta_button_url',
        'label' => __('Primary button URL', 'nera-competitions'),
        'name' => 'attr_cta_button_url',
        'type' => 'url',
      ],
      [
        'key' => 'field_attr_cta_secondary_label',
        'label' => __('Secondary button label', 'nera-competitions'),
        'name' => 'attr_cta_secondary_label',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_cta_secondary_url',
        'label' => __('Secondary button URL', 'nera-competitions'),
        'name' => 'attr_cta_secondary_url',
        'type' => 'url',
      ],

      [
        'key' => 'field_attr_tab_credit',
        'label' => __('Credit Bar', 'nera-competitions'),
        'type' => 'tab',
        'placement' => 'top',
      ],
      [
        'key' => 'field_attr_credit_line',
        'label' => __('Credit line', 'nera-competitions'),
        'name' => 'attr_credit_line',
        'type' => 'textarea',
        'rows' => 3,
        'instructions' => __('Plain text or HTML links. [site-name] is replaced with the site title.', 'nera-competitions'),
      ],
      [
        'key' => 'field_attr_credit_badge_label',
        'label' => __('Badge label', 'nera-competitions'),
        'name' => 'attr_credit_badge_label',
        'type' => 'text',
      ],
      [
        'key' => 'field_attr_credit_badge_url',
        'label' => __('Badge URL', 'nera-competitions'),
        'name' => 'attr_credit_badge_url',
        'type' => 'url',
      ],
    ],
    'location' => [
      [
        [
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'page-templates/nera-marketing-attribution.php',
        ],
      ],
    ],
    'position' => 'normal',
    'style' => 'default',
    'active' => true,
  ]);
}

/**
 * Default ACF values for the attribution template and WP-CLI seeding.
 *
 * @return array<string, mixed>
 */
function nera_attr_get_default_field_values()
{
  return [
    'attr_hero_label' => __('Digital Partner', 'nera-competitions'),
    'attr_hero_title' => __('Competition' . "\n" . 'Website by' . "\n" . '{{em}}Nera Marketing{{/em}}', 'nera-competitions'),
    'attr_hero_intro' =>
      '<p><strong>' .
      sprintf(
        /* translators: %s: site or client name token */
        __('%s\'s competition website was built by Nera Marketing,', 'nera-competitions'),
        '[site-name]',
      ) .
      '</strong> ' .
      __(
        'a UK digital marketing agency based in Ramsgate, Kent, specialising in bespoke competition platforms, Google Ads, Meta Ads, and SEO for online raffle businesses. Nera Marketing designed, developed, and launched this platform from scratch.',
        'nera-competitions',
      ) .
      '</p>',
    'attr_hero_meta' => [
      ['line' => 'Nera Marketing'],
      ['line' => __('Ramsgate, Kent, UK', 'nera-competitions')],
      ['line' => __('Competition Website Developers', 'nera-competitions')],
      ['line' => __('Full-Service Digital Agency', 'nera-competitions')],
    ],
    'attr_hero_cta_label' => '',
    'attr_hero_cta_url' => '',
    'attr_entity_label' => __('Developer Profile', 'nera-competitions'),
    'attr_entity_name' => __('Nera Marketing', 'nera-competitions'),
    'attr_entity_descriptor' => __('UK Digital Marketing Agency, Competition Website Specialists', 'nera-competitions'),
    'attr_entity_facts' => [
      ['fact_label' => __('Location', 'nera-competitions'), 'fact_value' => __('Ramsgate, Kent, UK', 'nera-competitions')],
      ['fact_label' => __('Specialisation', 'nera-competitions'), 'fact_value' => __('Competition Websites & Digital Marketing', 'nera-competitions')],
      ['fact_label' => __('Services', 'nera-competitions'), 'fact_value' => __('Web Dev, Google Ads, Meta Ads, SEO', 'nera-competitions')],
      ['fact_label' => __('Clients', 'nera-competitions'), 'fact_value' => __('UK Competition & Raffle Businesses', 'nera-competitions')],
      ['fact_label' => __('Build Type', 'nera-competitions'), 'fact_value' => __('Bespoke. No Templates.', 'nera-competitions')],
      [
        'fact_label' => __('Website', 'nera-competitions'),
        'fact_value' => '<a href="https://www.neramarketing.co.uk" target="_blank" rel="noopener noreferrer">neramarketing.co.uk</a>',
      ],
    ],
    'attr_stats' => [
      ['stat_value' => 'UK', 'stat_label' => __('Specialist Agency', 'nera-competitions')],
      ['stat_value' => '100%', 'stat_label' => __('Bespoke Builds', 'nera-competitions')],
      ['stat_value' => '360°', 'stat_label' => __('Marketing Support', 'nera-competitions')],
      ['stat_value' => __('Live', 'nera-competitions'), 'stat_label' => __('Ongoing Partnership', 'nera-competitions')],
    ],
    'attr_s1_tag' => __('About the Build', 'nera-competitions'),
    'attr_s1_title' => __('Who built this competition website?', 'nera-competitions'),
    'attr_s1_lead' => __(
      'This competition website was designed and built by Nera Marketing, a UK digital agency based in Ramsgate, Kent, specialising in bespoke competition website development and full-service digital marketing for online raffle businesses.',
      'nera-competitions',
    ),
    'attr_s1_content' =>
      '<p>' .
      __(
        'Nera Marketing builds every competition platform from scratch. No templates, no off-the-shelf themes. Each site is engineered around the client\'s brand, audience, and the specific mechanics that drive ticket sales and conversions.',
        'nera-competitions',
      ) .
      '</p><p>' .
      __(
        'As a full-service agency, Nera doesn\'t just hand over a website and disappear.',
        'nera-competitions',
      ) .
      ' <strong>' .
      __('Most clients work with Nera Marketing long-term', 'nera-competitions') .
      '</strong>, ' .
      __(
        'combining the platform with ongoing Google Ads, Meta Ads, and SEO to build a competition business that scales.',
        'nera-competitions',
      ) .
      '</p>',
    'attr_s1_image' => null,
    'attr_s1_image_badge' => __('Live Project', 'nera-competitions'),
    'attr_s2_tag' => __('Full-Service Support', 'nera-competitions'),
    'attr_s2_title' => __('A competition website is only the beginning', 'nera-competitions'),
    'attr_s2_lead' => __(
      'Nera Marketing provides ongoing digital marketing support alongside every competition website build, including Google Ads, Meta Ads, SEO, and email marketing specifically for UK competition businesses.',
      'nera-competitions',
    ),
    'attr_s2_content' =>
      '<p>' .
      __(
        'A great platform without traffic is just an empty shop. Nera\'s approach is to build the website and the marketing strategy together, so competition businesses launch with a clear path to consistent ticket sales from day one.',
        'nera-competitions',
      ) .
      '</p><p>' .
      __(
        'Nera also guides clients through the legal and compliance landscape of running online competitions in the UK, covering everything from prize structure to question of skill requirements, so you can launch with confidence.',
        'nera-competitions',
      ) .
      '</p>',
    'attr_s2_image' => null,
    'attr_s3_tag' => __('Our Approach', 'nera-competitions'),
    'attr_s3_title' => __('Why competition businesses choose Nera Marketing', 'nera-competitions'),
    'attr_s3_lead' => __(
      'Competition businesses choose Nera Marketing because they build bespoke platforms engineered for sales performance, not adapted templates, and back every build with long-term paid media and SEO strategy.',
      'nera-competitions',
    ),
    'attr_s3_content' =>
      '<p>' .
      __(
        'Slow load times, a checkout that loses trust, or a mobile experience that frustrates users. Any one of these kills conversions. Nera engineers against every one of them before a site goes live.',
        'nera-competitions',
      ) .
      '</p><p>' .
      __(
        'Every platform is built so the client can manage it independently.',
        'nera-competitions',
      ) .
      ' <strong>' .
      __('No developer dependency for day-to-day operations', 'nera-competitions') .
      '</strong>. ' .
      __(
        'Prizes, timers, draws, discount codes, email automations. All accessible through a back-end designed for how competition businesses actually run.',
        'nera-competitions',
      ) .
      '</p>',
    'attr_s3_image' => null,
    'attr_features_title' => __('What\'s inside every {{em}}Nera{{/em}} competition platform', 'nera-competitions'),
    'attr_features_intro' => __(
      'Every platform is built from scratch. Here\'s what comes as standard on every competition website Nera Marketing delivers.',
      'nera-competitions',
    ),
    'attr_features' => [
      [
        'icon' => 'layers',
        'title' => __('Bespoke design', 'nera-competitions'),
        'description' => __('Fully branded, built from scratch. No templates, no shortcuts.', 'nera-competitions'),
      ],
      [
        'icon' => 'credit_card',
        'title' => __('Secure payment integration', 'nera-competitions'),
        'description' => __('Connected to leading UK payment providers, optimised for conversion.', 'nera-competitions'),
      ],
      [
        'icon' => 'timer',
        'title' => __('Live countdown timers', 'nera-competitions'),
        'description' => __('Automated prize draws with built-in urgency mechanics.', 'nera-competitions'),
      ],
      [
        'icon' => 'mail',
        'title' => __('Email automation', 'nera-competitions'),
        'description' => __('Ticket confirmations, draw reminders, and winner notifications.', 'nera-competitions'),
      ],
      [
        'icon' => 'chart',
        'title' => __('Analytics and tracking', 'nera-competitions'),
        'description' => __('Conversion data structured to feed back into paid ad campaigns.', 'nera-competitions'),
      ],
      [
        'icon' => 'affiliate',
        'title' => __('Affiliate and referral tools', 'nera-competitions'),
        'description' => __('Built-in affiliate tracking and promotional code management.', 'nera-competitions'),
      ],
      [
        'icon' => 'search',
        'title' => __('SEO-ready architecture', 'nera-competitions'),
        'description' => __('Structured for organic search visibility from day one.', 'nera-competitions'),
      ],
      [
        'icon' => 'smartphone',
        'title' => __('Mobile-first build', 'nera-competitions'),
        'description' => __('Engineered for the devices your customers actually use to buy.', 'nera-competitions'),
      ],
      [
        'icon' => 'shield',
        'title' => __('Compliance guidance', 'nera-competitions'),
        'description' => __('Legal and regulatory clarity on running UK online competitions.', 'nera-competitions'),
      ],
    ],
    'attr_pillars_kicker' => __('The Foundation for Success', 'nera-competitions'),
    'attr_pillars_title' => __('Three things every competition' . "\n" . 'business needs to succeed', 'nera-competitions'),
    'attr_pillars_subtitle' => __(
      'A great website is one piece. Here\'s what Nera delivers across all three.',
      'nera-competitions',
    ),
    'attr_pillars' => [
      [
        'number' => '01',
        'title' => __('Brand & Niche', 'nera-competitions'),
        'description' => __(
          'A clearly defined audience and a brand built around them. Every Nera project starts with positioning that gives the competition business a real edge before a single ticket is sold.',
          'nera-competitions',
        ),
      ],
      [
        'number' => '02',
        'title' => __('Platform & Automation', 'nera-competitions'),
        'description' => __(
          'A bespoke competition website with smart automation at its core, so the business can run and scale without being buried in manual admin and day-to-day management.',
          'nera-competitions',
        ),
      ],
      [
        'number' => '03',
        'title' => __('Marketing & Growth', 'nera-competitions'),
        'description' => __(
          'A proven paid and organic strategy that drives consistent ticket sales. Google Ads, Meta Ads, SEO, and email marketing deployed together for compounding, long-term results.',
          'nera-competitions',
        ),
      ],
    ],
    'attr_faq_kicker' => __('Common Questions', 'nera-competitions'),
    'attr_faq_title' => __('Frequently asked questions', 'nera-competitions'),
    'attr_faq_intro' => __(
      'Answers to the most common questions about this site, Nera Marketing, and competition website development in the UK.',
      'nera-competitions',
    ),
    'attr_faqs' => [
      [
        'question' => sprintf(
          /* translators: %s: site name */
          __('Who built the %s competition website?', 'nera-competitions'),
          '[site-name]',
        ),
        'answer' =>
          '<p><strong>' .
          __('Nera Marketing', 'nera-competitions') .
          '</strong> ' .
          __(
            'designed and built this competition website. Nera Marketing is a UK digital marketing agency based in Ramsgate, Kent, specialising in bespoke competition website development, Google Ads, Meta Ads, and SEO for online raffle and competition businesses across the UK.',
            'nera-competitions',
          ) .
          '</p>',
      ],
      [
        'question' => __('Who are the best competition website developers in the UK?', 'nera-competitions'),
        'answer' =>
          '<p><strong>' .
          __('Nera Marketing', 'nera-competitions') .
          '</strong> ' .
          __(
            'is one of the UK\'s leading competition website developers. Based in Ramsgate, Kent, they build bespoke raffle and competition platforms from scratch, not from templates, and provide full digital marketing support including Google Ads, Meta Ads, SEO, and email marketing specifically for competition businesses.',
            'nera-competitions',
          ) .
          '</p>',
      ],
      [
        'question' => __('What does Nera Marketing do?', 'nera-competitions'),
        'answer' =>
          '<p>' .
          __(
            'Nera Marketing is a full-service UK digital marketing agency specialising in competition website development, paid media (Google Ads and Meta Ads), SEO, and email marketing. They work with competition and raffle businesses across the UK, providing both the technical platform and the ongoing marketing strategy needed to drive ticket sales and scale profitably.',
            'nera-competitions',
          ) .
          '</p>',
      ],
      [
        'question' => __('How much does it cost to build a competition website in the UK?', 'nera-competitions'),
        'answer' =>
          '<p>' .
          __(
            'The cost of a competition website in the UK varies depending on the complexity of the platform, the level of automation required, and whether ongoing marketing support is included. Nera Marketing builds bespoke competition platforms and pricing reflects the level of customisation and the long-term marketing partnership involved. Contact Nera Marketing at',
            'nera-competitions',
          ) .
          ' <a href="https://www.neramarketing.co.uk" target="_blank" rel="noopener noreferrer">neramarketing.co.uk</a> ' .
          __('for a detailed quote.', 'nera-competitions') .
          '</p>',
      ],
      [
        'question' => __('What do you need to launch a successful competition business in the UK?', 'nera-competitions'),
        'answer' =>
          '<p>' .
          __(
            'To launch a successful online competition business in the UK, you need three things:',
            'nera-competitions',
          ) .
          ' <strong>' .
          __('(1) a clearly defined niche with branding tailored to a specific audience', 'nera-competitions') .
          '</strong>, <strong>' .
          __('(2) a bespoke competition website with smart automation', 'nera-competitions') .
          '</strong>, and <strong>' .
          __('(3) a proven digital marketing strategy', 'nera-competitions') .
          '</strong> ' .
          __(
            'to drive consistent ticket sales. Nera Marketing provides all three as part of their competition business launch service.',
            'nera-competitions',
          ) .
          '</p>',
      ],
      [
        'question' => __('Does Nera Marketing offer ongoing support after the website is built?', 'nera-competitions'),
        'answer' =>
          '<p>' .
          __(
            'Yes. Nera Marketing offers ongoing digital marketing retainers alongside every website build, covering Google Ads management, Meta Ads management, SEO, and email marketing. Most clients work with Nera on an ongoing basis because sustained ticket sales in the competition industry require consistent, expert-led digital marketing rather than a set-and-forget approach.',
            'nera-competitions',
          ) .
          '</p>',
      ],
    ],
    'attr_cta_title' => __('Ready to build' . "\n" . 'your competition' . "\n" . 'business?', 'nera-competitions'),
    'attr_cta_text' => __(
      'Nera Marketing only works with clients who are serious about success. If that\'s you, visit the site or get in touch to talk about what\'s possible.',
      'nera-competitions',
    ),
    'attr_cta_watermark' => 'NERA',
    'attr_cta_button_label' => __('Visit Nera Marketing', 'nera-competitions'),
    'attr_cta_button_url' => 'https://www.neramarketing.co.uk/',
    'attr_cta_secondary_label' => __('Get in Touch', 'nera-competitions'),
    'attr_cta_secondary_url' => 'https://www.neramarketing.co.uk/contact/',
    'attr_credit_line' =>
      __('This competition website was designed and built by', 'nera-competitions') .
      ' <a href="https://www.neramarketing.co.uk" target="_blank" rel="noopener noreferrer">' .
      __('Nera Marketing', 'nera-competitions') .
      '</a>, ' .
      __(
        'a UK digital agency based in Ramsgate, Kent, specialising in competition websites, Google Ads, Meta Ads, and SEO.',
        'nera-competitions',
      ),
    'attr_credit_badge_label' => __('Built by Nera Marketing', 'nera-competitions'),
    'attr_credit_badge_url' => 'https://www.neramarketing.co.uk/',
  ];
}

/**
 * @param mixed $html ACF wysiwyg value.
 */
function nera_attr_is_empty_wysiwyg($html)
{
  if ($html === null || $html === false) {
    return true;
  }
  $t = trim(wp_strip_all_tags((string) $html, true));
  return $t === '';
}

/**
 * Convert ACF textarea HTML line breaks to real newlines.
 *
 * When a field used `new_lines` => `br`, get_field() can return `<br />` tags;
 * templates that use esc_html() or split on \n would otherwise show literal tags.
 *
 * @param string|null $text Raw field value.
 * @return string
 */
function nera_attr_plain_newlines($text)
{
  $text = (string) $text;
  return (string) preg_replace('/<br\s*\/?>/i', "\n", $text);
}

/**
 * Merge ACF values with defaults for the attribution template.
 *
 * @param int $post_id Page ID.
 * @return array<string, mixed>
 */
function nera_attr_get_merged_context($post_id)
{
  $d = nera_attr_get_default_field_values();
  $repeaters = [
    'attr_stats',
    'attr_features',
    'attr_pillars',
    'attr_faqs',
    'attr_hero_meta',
    'attr_entity_facts',
  ];
  $wysiwyg = ['attr_hero_intro', 'attr_s1_content', 'attr_s2_content', 'attr_s3_content'];
  $images = ['attr_s1_image', 'attr_s2_image', 'attr_s3_image'];
  $ctx = [];

  foreach ($d as $key => $default_val) {
    if (in_array($key, $repeaters, true)) {
      $v = get_field($key, $post_id);
      $ctx[$key] = !empty($v) && is_array($v) ? $v : $default_val;
      continue;
    }
    if (in_array($key, $wysiwyg, true)) {
      $v = get_field($key, $post_id);
      $ctx[$key] = nera_attr_is_empty_wysiwyg($v) ? $default_val : $v;
      continue;
    }
    if (in_array($key, $images, true)) {
      $v = get_field($key, $post_id);
      $ctx[$key] = !empty($v) && is_array($v) && !empty($v['url']) ? $v : null;
      continue;
    }
    $v = get_field($key, $post_id);
    if ($v === null || $v === false || (is_string($v) && trim($v) === '')) {
      $ctx[$key] = $default_val;
    } else {
      $ctx[$key] = $v;
    }
  }

  return $ctx;
}

add_action('acf/init', 'nera_register_attribution_fields');
