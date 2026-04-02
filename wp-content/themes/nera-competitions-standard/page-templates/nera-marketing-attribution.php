<?php
/**
 * Template Name: Competition Website by Nera Marketing
 * Template Post Type: page
 *
 * Attribution / PR page — reference layout, Luxora palette.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (!function_exists('nera_attr_resolve')) {
  /**
   * Replace [site-name] with the site title.
   *
   * @param string|null $text Raw text.
   * @return string
   */
  function nera_attr_resolve($text)
  {
    return str_replace('[site-name]', get_bloginfo('name'), (string) $text);
  }
}

if (!function_exists('nera_attr_parse_em_line')) {
  /**
   * {{em}}text{{/em}} on a single line; other text escaped.
   *
   * @param string $text Raw text (no newlines expected).
   * @return string Safe HTML.
   */
  function nera_attr_parse_em_line($text)
  {
    $text = nera_attr_resolve((string) $text);
    $out = '';
    $offset = 0;
    while (preg_match('/\{\{em\}\}(.*?)\{\{\/em\}\}/s', $text, $m, PREG_OFFSET_CAPTURE, $offset)) {
      $full = $m[0][0];
      $pos = (int) $m[0][1];
      $inner = $m[1][0];
      $before = substr($text, $offset, $pos - $offset);
      $out .= esc_html($before);
      $out .= '<em class="not-italic font-medium text-sage">' . esc_html($inner) . '</em>';
      $offset = $pos + strlen($full);
    }
    $out .= esc_html(substr($text, $offset));
    return $out;
  }
}

if (!function_exists('nera_attr_parse_em_multiline')) {
  /**
   * Multi-line title: line breaks + {{em}} tokens per line.
   *
   * @param string|null $text Raw text.
   * @return string Safe HTML.
   */
  function nera_attr_parse_em_multiline($text)
  {
    $lines = preg_split('/\r\n|\r|\n/', (string) $text);
    $parts = [];
    foreach ($lines as $line) {
      $parts[] = nera_attr_parse_em_line($line);
    }
    return implode('<br />', $parts);
  }
}

if (!function_exists('nera_attr_parse_em')) {
  /**
   * Full-string {{em}} (legacy credit line etc.).
   *
   * @param string|null $text Raw text.
   * @return string Safe HTML.
   */
  function nera_attr_parse_em($text)
  {
    return nera_attr_parse_em_line((string) $text);
  }
}

if (!function_exists('nera_attr_output_jsonld')) {
  /**
   * JSON-LD @graph aligned with reference AEO schema.
   */
  function nera_attr_output_jsonld()
  {
    if (!is_page_template('page-templates/nera-marketing-attribution.php')) {
      return;
    }

    $post_id = (int) get_queried_object_id();
    if ($post_id < 1) {
      return;
    }

    $ctx = nera_attr_get_merged_context($post_id);
    $page_url = get_permalink($post_id);
    $site_url = home_url('/');
    $site_name = get_bloginfo('name');
    $faqs = isset($ctx['attr_faqs']) && is_array($ctx['attr_faqs']) ? $ctx['attr_faqs'] : [];

    $nera_org_id = 'https://www.neramarketing.co.uk/#organization';
    $nera_service_id = 'https://www.neramarketing.co.uk/#competition-website-service';

    $faq_entities = [];
    foreach ($faqs as $row) {
      $q = isset($row['question']) ? trim(nera_attr_resolve((string) $row['question'])) : '';
      $a_raw = isset($row['answer']) ? $row['answer'] : '';
      $a = trim(wp_strip_all_tags((string) $a_raw, true));
      if ($q === '' || $a === '') {
        continue;
      }
      $faq_entities[] = [
        '@type' => 'Question',
        'name' => $q,
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => $a,
        ],
      ];
    }

    $desc = sprintf(
      /* translators: %s: site / client name */
      __('%s\'s competition platform was designed and built by Nera Marketing, a UK digital marketing agency specialising in competition websites.', 'nera-competitions'),
      $site_name,
    );

    $graph = [
      [
        '@type' => 'Organization',
        '@id' => $nera_org_id,
        'name' => 'Nera Marketing',
        'url' => 'https://www.neramarketing.co.uk',
        'logo' => apply_filters('nera_attr_nera_org_logo', ''),
        'description' => __(
          'Nera Marketing is a UK digital marketing agency based in Ramsgate, specialising in bespoke competition website development, Google Ads, Meta Ads, and SEO for competition businesses.',
          'nera-competitions',
        ),
        'address' => [
          '@type' => 'PostalAddress',
          'addressLocality' => 'Ramsgate',
          'addressRegion' => 'Kent',
          'addressCountry' => 'GB',
        ],
        'areaServed' => 'United Kingdom',
        'knowsAbout' => [
          'competition website development',
          'raffle website design',
          'online competition platforms',
          'Google Ads for competition businesses',
          'Meta Ads for raffle sites',
          'SEO for competition websites',
        ],
        'sameAs' => [
          'https://www.neramarketing.co.uk',
          'https://www.linkedin.com/company/nera-marketing',
        ],
      ],
      [
        '@type' => 'Service',
        '@id' => $nera_service_id,
        'name' => __('Competition Website Development', 'nera-competitions'),
        'provider' => ['@id' => $nera_org_id],
        'serviceType' => 'Web Development',
        'description' => __(
          'Bespoke competition and raffle website design and development for UK businesses. Includes custom branding, payment integration, countdown timers, prize management, and ongoing digital marketing support.',
          'nera-competitions',
        ),
        'areaServed' => 'United Kingdom',
        'url' => 'https://www.neramarketing.co.uk/web-design/competition-websites/',
      ],
      [
        '@type' => 'WebPage',
        '@id' => ($page_url ?: $site_url) . '#webpage',
        'name' => get_the_title($post_id),
        'url' => $page_url ?: $site_url,
        'description' => $desc,
        'about' => ['@id' => $nera_org_id],
        'mentions' => ['@id' => $nera_org_id],
        'datePublished' => get_the_date('c', $post_id),
        'dateModified' => get_the_modified_date('c', $post_id),
      ],
    ];

    if (!empty($faq_entities)) {
      $graph[] = [
        '@type' => 'FAQPage',
        '@id' => ($page_url ?: $site_url) . '#faq',
        'mainEntity' => $faq_entities,
      ];
    }

    $payload = [
      '@context' => 'https://schema.org',
      '@graph' => $graph,
    ];

    // Remove empty logo from Organization node if filter returns empty.
    foreach ($payload['@graph'] as $i => $node) {
      if (
        isset($node['@type'], $node['logo']) &&
        $node['@type'] === 'Organization' &&
        ($node['logo'] === '' || $node['logo'] === null)
      ) {
        unset($payload['@graph'][$i]['logo']);
      }
    }
    $payload['@graph'] = array_values($payload['@graph']);

    echo '<script type="application/ld+json">' . wp_json_encode(
      $payload,
      JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
    ) . '</script>' . "\n";
  }
}
add_action('wp_head', 'nera_attr_output_jsonld', 5);

get_header();

while (have_posts()) {
  the_post();
  $pid = get_the_ID();
  $c = nera_attr_get_merged_context($pid);

  $hero_label = nera_attr_resolve((string) $c['attr_hero_label']);
  $hero_title_raw = nera_attr_plain_newlines((string) $c['attr_hero_title']);
  $hero_intro_html = wp_kses_post(nera_attr_resolve((string) $c['attr_hero_intro']));
  $hero_meta = is_array($c['attr_hero_meta']) ? $c['attr_hero_meta'] : [];
  $hero_cta = (string) $c['attr_hero_cta_label'];
  $hero_cta_url = (string) $c['attr_hero_cta_url'];

  $entity_label = nera_attr_resolve((string) $c['attr_entity_label']);
  $entity_name = nera_attr_resolve((string) $c['attr_entity_name']);
  $entity_desc = nera_attr_resolve((string) $c['attr_entity_descriptor']);
  $entity_facts = is_array($c['attr_entity_facts']) ? $c['attr_entity_facts'] : [];

  $stats = is_array($c['attr_stats']) ? $c['attr_stats'] : [];

  $features_title = nera_attr_plain_newlines((string) $c['attr_features_title']);
  $features_intro = (string) $c['attr_features_intro'];
  $features = is_array($c['attr_features']) ? $c['attr_features'] : [];

  $pillars_kicker = (string) $c['attr_pillars_kicker'];
  $pillars_title = nera_attr_plain_newlines((string) $c['attr_pillars_title']);
  $pillars_sub = (string) $c['attr_pillars_subtitle'];
  $pillars = is_array($c['attr_pillars']) ? $c['attr_pillars'] : [];

  $faq_kicker = (string) $c['attr_faq_kicker'];
  $faq_title = (string) $c['attr_faq_title'];
  $faq_intro = (string) $c['attr_faq_intro'];
  $faqs = is_array($c['attr_faqs']) ? $c['attr_faqs'] : [];

  $cta_title = nera_attr_plain_newlines((string) $c['attr_cta_title']);
  $cta_text = (string) $c['attr_cta_text'];
  $cta_wm = (string) $c['attr_cta_watermark'];
  $cta_btn = (string) $c['attr_cta_button_label'];
  $cta_url = (string) $c['attr_cta_button_url'];
  $cta_btn2 = (string) $c['attr_cta_secondary_label'];
  $cta_url2 = (string) $c['attr_cta_secondary_url'];

  $credit_html = wp_kses_post(nera_attr_resolve((string) $c['attr_credit_line']));
  $credit_badge = (string) $c['attr_credit_badge_label'];
  $credit_badge_url = (string) $c['attr_credit_badge_url'];

  $sections = [
    [
      'tag' => (string) $c['attr_s1_tag'],
      'title' => (string) $c['attr_s1_title'],
      'lead' => (string) $c['attr_s1_lead'],
      'content' => (string) $c['attr_s1_content'],
      'image' => $c['attr_s1_image'],
      'badge' => (string) $c['attr_s1_image_badge'],
      'reverse' => true,
    ],
    [
      'tag' => (string) $c['attr_s2_tag'],
      'title' => (string) $c['attr_s2_title'],
      'lead' => (string) $c['attr_s2_lead'],
      'content' => (string) $c['attr_s2_content'],
      'image' => $c['attr_s2_image'],
      'badge' => '',
      'reverse' => false,
    ],
    [
      'tag' => (string) $c['attr_s3_tag'],
      'title' => (string) $c['attr_s3_title'],
      'lead' => (string) $c['attr_s3_lead'],
      'content' => (string) $c['attr_s3_content'],
      'image' => $c['attr_s3_image'],
      'badge' => '',
      'reverse' => true,
    ],
  ];

  $placeholder_hints = [
    0 => __('Add client website screenshot', 'nera-competitions'),
    1 => __('Add marketing / mobile mockup', 'nera-competitions'),
    2 => __('Add dashboard / back-end screenshot', 'nera-competitions'),
  ];
  ?>

<main id="main" class="nera-attribution-page" role="main">

  <section class="nera-attr-hero border-b border-border bg-off-white" data-aos="fade-up">
    <div class="nera-attr-container relative py-16 md:py-24 lg:py-28">
      <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-sage/15 blur-3xl" aria-hidden="true"></div>
      <p class="nera-attr-hero-label mb-7"><?php echo esc_html($hero_label); ?></p>
      <h1 class="nera-attr-hero-title font-heading text-ink">
        <?php echo nera_attr_parse_em_multiline($hero_title_raw); ?>
      </h1>
      <div class="nera-attr-hero-intro mt-8 max-w-xl text-[17px] font-light leading-relaxed text-ink-soft [&_strong]:font-medium [&_strong]:text-ink">
        <?php echo $hero_intro_html; ?>
      </div>
      <?php if (!empty($hero_meta)): ?>
        <div class="nera-attr-hero-meta mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-[13px] font-medium uppercase tracking-widest text-ink-soft">
          <?php
          $mi = 0;
          foreach ($hero_meta as $row) {
            $line = isset($row['line']) ? trim(nera_attr_resolve((string) $row['line'])) : '';
            if ($line === '') {
              continue;
            }
            if ($mi > 0) {
              echo '<span class="hidden h-3.5 w-px bg-border sm:inline" aria-hidden="true"></span>';
            }
            echo '<span class="inline-flex items-center gap-2">' . esc_html($line) . '</span>';
            $mi++;
          }
          ?>
        </div>
      <?php endif; ?>
      <?php if ($hero_cta !== '' && $hero_cta_url !== ''): ?>
        <p class="mt-10">
          <a href="<?php echo esc_url($hero_cta_url); ?>"
            class="inline-flex items-center justify-center rounded-lg bg-forest px-8 py-3.5 text-xs font-semibold uppercase tracking-widest text-mint shadow-primary transition hover:bg-sage-dark">
            <?php echo esc_html($hero_cta); ?>
          </a>
        </p>
      <?php endif; ?>
    </div>
  </section>

  <section class="border-b border-border bg-mint-wash py-10 md:py-12" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)] lg:gap-12">
        <div>
          <p class="nera-attr-entity-label text-sage"><?php echo esc_html($entity_label); ?></p>
          <h2 class="nera-attr-entity-name mt-2 font-heading text-3xl uppercase tracking-wide text-ink md:text-4xl">
            <?php echo esc_html($entity_name); ?>
          </h2>
          <p class="mt-2 text-sm font-light text-ink-soft"><?php echo esc_html($entity_desc); ?></p>
        </div>
        <dl class="nera-attr-entity-facts grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($entity_facts as $fact): ?>
            <?php
            $fl = isset($fact['fact_label']) ? trim((string) $fact['fact_label']) : '';
            $fv = isset($fact['fact_value']) ? nera_attr_resolve((string) $fact['fact_value']) : '';
            if ($fl === '' && $fv === '') {
              continue;
            }
            ?>
            <div class="min-w-0">
              <dt class="text-[10px] font-semibold uppercase tracking-widest text-ink-soft"><?php echo esc_html($fl); ?></dt>
              <dd class="mt-1 text-sm font-medium text-ink [&_a]:font-semibold [&_a]:text-sage [&_a]:underline [&_a]:decoration-sage/40 [&_a]:underline-offset-2 [&_a]:transition hover:[&_a]:decoration-sage">
                <?php echo wp_kses_post($fv); ?>
              </dd>
            </div>
          <?php endforeach; ?>
        </dl>
      </div>
    </div>
  </section>

  <section class="border-b border-border bg-off-white py-9" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="nera-attr-stat-grid grid grid-cols-2 divide-y divide-border border-border sm:grid-cols-4 sm:divide-x sm:divide-y-0">
        <?php foreach ($stats as $stat): ?>
          <?php
          $sv = isset($stat['stat_value']) ? (string) $stat['stat_value'] : '';
          $sl = isset($stat['stat_label']) ? (string) $stat['stat_label'] : '';
          if ($sv === '' && $sl === '') {
            continue;
          }
          ?>
          <div class="nera-attr-stat-cell px-4 py-6 text-center first:pl-0 last:pr-0 sm:py-4 sm:first:pl-0 sm:last:pr-0">
            <p class="nera-attr-stat-num font-heading text-4xl text-sage md:text-5xl"><?php echo esc_html(nera_attr_resolve($sv)); ?></p>
            <p class="nera-attr-stat-lbl mt-1 text-[12px] font-medium uppercase tracking-widest text-ink-soft">
              <?php echo esc_html(nera_attr_resolve($sl)); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php
foreach ($sections as $idx => $block):
  $hint = isset($placeholder_hints[$idx]) ? $placeholder_hints[$idx] : '';
  $rev = !empty($block['reverse']);
  $section_bg = ($idx % 2 === 0) ? 'bg-mint-wash' : 'bg-off-white';
  ?>
  <section class="border-b border-border py-16 lg:py-28 <?php echo esc_attr($section_bg); ?>" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16 <?php echo $rev ? 'lg:[&>div:first-child]:order-2' : ''; ?>">
        <div class="nera-attr-section-copy min-w-0">
          <?php if ($block['tag'] !== ''): ?>
            <span class="nera-attr-section-tag text-sage"><?php echo esc_html(nera_attr_resolve($block['tag'])); ?></span>
          <?php endif; ?>
          <h2 class="nera-attr-section-h2 mt-4 font-heading text-3xl uppercase leading-tight tracking-wide text-ink md:text-4xl lg:text-5xl">
            <?php echo esc_html(nera_attr_resolve($block['title'])); ?>
          </h2>
          <?php if ($block['lead'] !== ''): ?>
            <p class="nera-attr-section-lead mt-5 mb-6 border-l-2 border-sage pl-4 text-base font-medium leading-relaxed text-ink md:mb-8">
              <?php echo esc_html(nera_attr_resolve($block['lead'])); ?>
            </p>
          <?php endif; ?>
          <div class="nera-attr-section-body prose prose-lg max-w-prose text-[16px] font-light leading-relaxed text-ink-soft prose-headings:font-heading prose-p:mb-4 prose-strong:font-medium prose-strong:text-ink prose-a:font-medium prose-a:text-sage prose-a:underline prose-a:decoration-sage/40 prose-a:underline-offset-2">
            <?php echo apply_filters('the_content', $block['content']); ?>
          </div>
        </div>
        <div class="nera-attr-img-block relative <?php echo $rev ? 'lg:order-1' : ''; ?>">
          <?php if (!empty($block['image']['url'])): ?>
            <div class="overflow-hidden rounded border border-border bg-white shadow-sm">
              <img src="<?php echo esc_url($block['image']['url']); ?>"
                alt="<?php echo esc_attr($block['image']['alt'] ?? ''); ?>"
                class="aspect-square w-full object-cover"
                loading="lazy"
                width="800"
                height="800" />
            </div>
          <?php else: ?>
            <div class="nera-attr-img-placeholder flex aspect-square flex-col items-center justify-center gap-3 rounded border border-border bg-mint-wash p-6 text-center text-sm text-ink-soft">
              <svg class="h-12 w-12 opacity-30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <path d="M21 15l-5-5L5 21" />
              </svg>
              <span><?php echo esc_html($hint); ?><br /><small class="text-xs opacity-80"><?php esc_html_e('Recommended: 800×800px', 'nera-competitions'); ?></small></span>
            </div>
          <?php endif; ?>
          <?php if ($block['badge'] !== ''): ?>
            <div class="nera-attr-img-badge absolute -bottom-4 -right-4 rounded bg-sage px-4 py-2.5 font-heading text-sm uppercase tracking-widest text-white shadow-md">
              <?php echo esc_html(nera_attr_resolve($block['badge'])); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php
endforeach;
?>

  <section class="border-b border-border bg-mint-wash py-16 lg:py-28" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="mb-12 grid gap-8 lg:mb-16 lg:grid-cols-2 lg:items-end lg:gap-16">
        <h2 class="nera-attr-features-title font-heading text-3xl uppercase leading-tight tracking-wide text-ink md:text-4xl lg:text-5xl">
          <?php echo nera_attr_parse_em_multiline($features_title); ?>
        </h2>
        <p class="max-w-prose text-[15px] font-light leading-relaxed text-ink-soft"><?php echo esc_html(nera_attr_resolve($features_intro)); ?></p>
      </div>
      <div class="nera-attr-features-grid grid grid-cols-1 gap-px border border-border bg-border sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($features as $f): ?>
          <?php
          $ft = isset($f['title']) ? (string) $f['title'] : '';
          $fd = isset($f['description']) ? (string) $f['description'] : '';
          $fi = isset($f['icon']) ? (string) $f['icon'] : 'layers';
          if ($ft === '') {
            continue;
          }
          ?>
          <div class="nera-attr-feature flex gap-4 bg-off-white p-6 transition-colors hover:bg-mint-soft/60">
            <div class="text-sage shrink-0"><?php nera_attr_feature_icon_svg($fi); ?></div>
            <div class="min-w-0">
              <strong class="block text-sm font-semibold text-ink"><?php echo esc_html(nera_attr_resolve($ft)); ?></strong>
              <span class="mt-1 block text-[13px] font-light leading-relaxed text-ink-soft"><?php echo esc_html(nera_attr_resolve($fd)); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="border-b border-border bg-off-white py-16 lg:py-28" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="nera-attr-pillars-header mb-14 text-center lg:mb-16">
        <?php if ($pillars_kicker !== ''): ?>
          <span class="nera-attr-section-tag text-sage"><?php echo esc_html(nera_attr_resolve($pillars_kicker)); ?></span>
        <?php endif; ?>
        <h2 class="nera-attr-pillars-title mt-4 font-heading text-3xl uppercase leading-none tracking-wide text-ink md:text-4xl lg:text-5xl">
          <?php echo nl2br(esc_html(nera_attr_resolve($pillars_title)), false); ?>
        </h2>
        <?php if ($pillars_sub !== ''): ?>
          <p class="mx-auto mt-4 max-w-lg text-base font-light text-ink-soft"><?php echo esc_html(nera_attr_resolve($pillars_sub)); ?></p>
        <?php endif; ?>
      </div>
      <div class="grid grid-cols-1 gap-px border border-border bg-border lg:grid-cols-3">
        <?php foreach ($pillars as $p): ?>
          <?php
          $pt = isset($p['title']) ? (string) $p['title'] : '';
          $pd = isset($p['description']) ? (string) $p['description'] : '';
          $pn = isset($p['number']) ? (string) $p['number'] : '';
          if ($pt === '') {
            continue;
          }
          ?>
          <div class="nera-attr-pillar relative overflow-hidden bg-mint-wash p-10">
            <?php if ($pn !== ''): ?>
              <div class="nera-attr-pillar-num font-heading text-7xl leading-none text-sage/25"><?php echo esc_html($pn); ?></div>
            <?php endif; ?>
            <h3 class="nera-attr-pillar-h3 mt-5 font-heading text-xl uppercase tracking-widest text-ink"><?php echo esc_html(nera_attr_resolve($pt)); ?></h3>
            <p class="mt-3 text-sm font-light leading-relaxed text-ink-soft"><?php echo esc_html(nera_attr_resolve($pd)); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="border-b border-border bg-mint-wash py-16 lg:py-28" data-aos="fade-up">
    <div class="nera-attr-container max-w-3xl">
      <div class="mb-10 lg:mb-12">
        <?php if ($faq_kicker !== ''): ?>
          <span class="nera-attr-section-tag text-sage"><?php echo esc_html(nera_attr_resolve($faq_kicker)); ?></span>
        <?php endif; ?>
        <h2 class="nera-attr-faq-h2 mt-4 font-heading text-3xl uppercase tracking-wide text-ink md:text-4xl"><?php echo esc_html(nera_attr_resolve($faq_title)); ?></h2>
        <?php if ($faq_intro !== ''): ?>
          <p class="mt-3 text-[15px] font-light leading-relaxed text-ink-soft"><?php echo esc_html(nera_attr_resolve($faq_intro)); ?></p>
        <?php endif; ?>
      </div>
      <div class="flex flex-col gap-0.5">
        <?php foreach ($faqs as $index => $faq): ?>
          <?php
          $fq = isset($faq['question']) ? trim((string) $faq['question']) : '';
          $fa_raw = isset($faq['answer']) ? $faq['answer'] : '';
          if ($fq === '') {
            continue;
          }
          $fq = nera_attr_resolve($fq);
          ?>
          <details class="nera-attr-faq-item group border border-border bg-off-white">
            <summary class="nera-attr-faq-summary flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-5 text-base font-medium text-ink marker:content-none [&::-webkit-details-marker]:hidden">
              <span><?php echo esc_html($fq); ?></span>
              <span class="nera-attr-faq-chevron flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-border text-ink-soft transition group-open:border-sage group-open:bg-sage group-open:text-white">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                  <line x1="5" y1="1" x2="5" y2="9" />
                  <line x1="1" y1="5" x2="9" y2="5" />
                </svg>
              </span>
            </summary>
            <div class="nera-attr-faq-a px-5 pb-5 text-[15px] font-light leading-relaxed text-ink-soft prose-a:font-medium prose-a:text-sage [&_strong]:font-medium [&_strong]:text-ink">
              <?php echo apply_filters('the_content', nera_attr_resolve((string) $fa_raw)); ?>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="relative overflow-hidden bg-forest py-16 text-mint lg:py-24" data-aos="fade-up">
    <?php if ($cta_wm !== ''): ?>
      <div class="pointer-events-none absolute -right-4 top-1/2 -translate-y-1/2 select-none font-heading text-[clamp(6rem,22vw,14rem)] leading-none text-ink/10" aria-hidden="true">
        <?php echo esc_html($cta_wm); ?>
      </div>
    <?php endif; ?>
    <div class="nera-attr-container relative z-10 grid gap-10 lg:grid-cols-[1fr_auto] lg:items-center lg:gap-12">
      <div>
        <h2 class="font-heading text-3xl uppercase leading-none tracking-wide text-white md:text-4xl lg:text-5xl">
          <?php echo nl2br(esc_html(nera_attr_resolve($cta_title)), false); ?>
        </h2>
        <p class="mt-4 max-w-md text-[15px] font-normal leading-relaxed text-mint/90"><?php echo esc_html(nera_attr_resolve($cta_text)); ?></p>
      </div>
      <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
        <?php if ($cta_btn !== '' && $cta_url !== ''): ?>
          <a href="<?php echo esc_url($cta_url); ?>"
            class="inline-flex items-center justify-center whitespace-nowrap rounded border-2 border-ink bg-ink px-7 py-3.5 text-center text-xs font-semibold uppercase tracking-widest text-mint transition hover:bg-transparent hover:text-mint">
            <?php echo esc_html($cta_btn); ?>
          </a>
        <?php endif; ?>
        <?php if ($cta_btn2 !== '' && $cta_url2 !== ''): ?>
          <a href="<?php echo esc_url($cta_url2); ?>"
            class="inline-flex items-center justify-center whitespace-nowrap rounded border-2 border-mint/40 bg-transparent px-7 py-3.5 text-center text-xs font-semibold uppercase tracking-widest text-mint transition hover:border-mint">
            <?php echo esc_html($cta_btn2); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="border-t border-border bg-mint-wash py-8" data-aos="fade-up">
    <div class="nera-attr-container">
      <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
        <p class="max-w-3xl text-[13px] leading-relaxed text-ink-soft [&_a]:font-semibold [&_a]:text-sage [&_a]:underline [&_a]:decoration-sage/40 [&_a]:underline-offset-2">
          <?php echo $credit_html; ?>
        </p>
        <?php if ($credit_badge !== '' && $credit_badge_url !== ''): ?>
          <a href="<?php echo esc_url($credit_badge_url); ?>"
            class="nera-attr-credit-badge inline-flex shrink-0 items-center gap-2 rounded border border-border bg-off-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-ink-soft transition hover:border-sage hover:text-sage"
            target="_blank"
            rel="noopener noreferrer">
            <span class="h-2 w-2 rounded-sm bg-sage" aria-hidden="true"></span>
            <?php echo esc_html($credit_badge); ?>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

</main>

<?php
}
get_footer();
