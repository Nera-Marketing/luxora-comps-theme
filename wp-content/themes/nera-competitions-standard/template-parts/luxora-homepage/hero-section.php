<?php
/**
 * Luxora Homepage — Hero Section
 *
 * Two-column hero: left (forest) with brand statement, right (mint) with featured prize card.
 * Countdown reads _lty_end_date_gmt from the ACF-selected product.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

$eyebrow = get_field('luxora_hero_eyebrow') ?: 'Premium Competitions';
$tagline = get_field('luxora_hero_tagline') ?: 'Every draw, a chance worth taking.';
$body = get_field('luxora_hero_body') ?: 'Curated competitions for people who appreciate quality. Transparent draws, genuine prizes, and a cash alternative on every entry.';
$cta_text = get_field('luxora_hero_cta_text') ?: 'View Competitions';
$cta_url = get_field('luxora_hero_cta_url') ?: (function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/'));
$sec_text = get_field('luxora_hero_secondary_text') ?: 'How it works';
$sec_url = get_field('luxora_hero_secondary_url') ?: home_url('/#how-it-works');
$trust = get_field('luxora_hero_trust') ?: [['num' => '1,240+', 'label' => 'Verified Winners'], ['num' => '£48k', 'label' => 'Prizes Awarded'], ['num' => '4.9 ★', 'label' => 'Trustpilot']];
$featured = get_field('luxora_hero_featured_product');

$product = null;
$end_date_gmt = '';
$countdown_ts = 0;

if ($featured && is_object($featured)) {
  $product = $featured;
  $end_date_gmt = get_post_meta($product->ID, '_lty_end_date_gmt', true);
  if ($end_date_gmt) {
    $countdown_ts = strtotime($end_date_gmt . ' UTC');
    if ($countdown_ts <= time()) {
      $countdown_ts = 0;
    }
  }
}
?>

<section class="min-h-screen grid grid-cols-2 hero">
  <div class="hero-left bg-forest p-[80px_60px] flex flex-col justify-center relative overflow-hidden">
    <div class="hero-eyebrow text-[0.6rem] tracking-[0.32em] uppercase text-mint font-normal mb-7 flex items-center gap-[14px] opacity-70"><?php echo esc_html($eyebrow); ?></div>

    <div class="mb-9 hero-logo-display">
      <span class="font-heading font-black tracking-[0.1em] uppercase text-white leading-[0.95] block text-[clamp(3.8rem,7vw,6.5rem)]">Luxora</span>
      <span class="font-draws font-semibold leading-none block text-mint -mt-[0.05em] pl-3 text-[clamp(2rem,3.8vw,3.4rem)]">draws</span>
    </div>

    <p class="font-heading text-[clamp(1rem,1.8vw,1.3rem)] italic font-normal text-[rgba(200,230,192,0.75)] mb-5 leading-[1.5] max-w-[360px] hero-tagline"><?php echo esc_html($tagline); ?></p>
    <p class="text-[0.85rem] leading-[1.9] text-[rgba(200,230,192,0.55)] max-w-[380px] mb-12 font-light hero-body"><?php echo esc_html($body); ?></p>

    <div class="flex items-center gap-8 hero-actions">
      <a href="<?php echo esc_url($cta_url); ?>" class="inline-block py-3.5 px-10 bg-mint text-forest text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline transition-colors duration-300 rounded-none hover:bg-white hover:text-forest"><?php echo esc_html($cta_text); ?></a>
      <a href="<?php echo esc_url($sec_url); ?>" class="text-[0.68rem] tracking-[0.15em] uppercase text-[rgba(200,230,192,0.6)] no-underline font-normal border-b border-[rgba(200,230,192,0.25)] pb-0.5 transition-colors duration-[0.25s] hover:text-mint hover:border-mint"><?php echo esc_html($sec_text); ?></a>
    </div>

    <?php if (is_array($trust) && !empty($trust)): ?>
      <div class="mt-14 pt-8 border-t border-[rgba(200,230,192,0.12)] flex gap-9 hero-trust">
        <?php foreach ($trust as $item): ?>
          <div class="flex flex-col trust-item">
            <span class="font-heading text-[1.6rem] font-normal text-white leading-none trust-num"><?php echo esc_html($item['num'] ?? ''); ?></span>
            <span class="text-[0.58rem] tracking-[0.18em] uppercase text-[rgba(200,230,192,0.45)] mt-1 trust-label"><?php echo esc_html($item['label'] ?? ''); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="hero-right bg-mint-soft relative overflow-hidden flex flex-col justify-center py-[60px] px-[52px]">
    <div class="hero-right-bg-logo absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-center pointer-events-none select-none opacity-[0.06] whitespace-nowrap" aria-hidden="true">
      <span class="font-heading text-[9rem] text-forest font-black tracking-[0.1em] uppercase block leading-none">Luxora</span>
      <span class="font-draws text-[5rem] text-forest block pl-5 -mt-[0.1em]">draws</span>
    </div>

    <div class="prize-card bg-white border border-[rgba(61,74,58,0.1)] relative z-[2] max-w-[400px] mx-auto w-full rounded-none [animation:luxora-fadeUp_0.85s_ease_0.3s_forwards] opacity-0">
      <?php if ($product && function_exists('wc_get_product')): ?>
        <?php
        $wc = wc_get_product($product->ID);
        $image_id = $wc ? $wc->get_image_id() : 0;
        $price = $wc ? $wc->get_price() : '';
        $max_tickets = get_post_meta($product->ID, '_lty_maximum_tickets', true);
        $sold_tickets = ($wc && method_exists($wc, 'get_purchased_ticket_count')) ? $wc->get_purchased_ticket_count() : 0;
        $remaining = $max_tickets ? max(0, (int) $max_tickets - (int) $sold_tickets) : 0;
        $progress = $max_tickets ? min(100, round(($sold_tickets / $max_tickets) * 100)) : 0;
        $terms = get_the_terms($product->ID, 'product_cat');
        $category = $terms && !is_wp_error($terms) ? $terms[0]->name : 'Lifestyle';
        ?>
        <div class="prize-card-img h-[280px] flex items-center justify-center relative overflow-hidden [background:linear-gradient(145deg,#d0e8c8,#b8d8b0,#c4e0bc)]" <?php
          if ($image_id) {
            $img_url = wp_get_attachment_image_url($image_id, 'large');
            echo 'style="background-image:url(\'' . esc_url($img_url) . '\');background-size:cover;background-position:center"';
          }
        ?>>
          <?php if (!$image_id): ?>
            <svg width="200" height="210" viewBox="0 0 220 220" fill="none" aria-hidden="true">
              <rect x="88" y="10" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
              <rect x="93" y="15" width="34" height="44" rx="7" fill="#2e3a2c"/>
              <rect x="97" y="22" width="26" height="2" rx="1" fill="rgba(200,230,192,0.15)"/>
              <rect x="97" y="27" width="26" height="2" rx="1" fill="rgba(200,230,192,0.08)"/>
              <rect x="97" y="32" width="26" height="2" rx="1" fill="rgba(200,230,192,0.08)"/>
              <rect x="88" y="158" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
              <rect x="93" y="162" width="34" height="44" rx="7" fill="#2e3a2c"/>
              <circle cx="110" cy="110" r="60" fill="url(#wcase)" stroke="#3d4a3a" stroke-width="2"/>
              <circle cx="110" cy="110" r="47" fill="url(#wdial)"/>
              <line x1="110" y1="110" x2="88" y2="86" stroke="rgba(200,230,192,0.95)" stroke-width="2.5" stroke-linecap="round"/>
              <line x1="110" y1="110" x2="130" y2="76" stroke="rgba(200,230,192,0.9)" stroke-width="1.8" stroke-linecap="round"/>
              <circle cx="110" cy="110" r="3.5" fill="#6b8c6b"/>
              <defs>
                <radialGradient id="wcase" cx="38%" cy="30%"><stop offset="0%" stop-color="#566054"/><stop offset="60%" stop-color="#3a4238"/><stop offset="100%" stop-color="#2a3028"/></radialGradient>
                <radialGradient id="wdial" cx="40%" cy="35%"><stop offset="0%" stop-color="#3c4a3a"/><stop offset="100%" stop-color="#252e24"/></radialGradient>
              </defs>
            </svg>
          <?php endif; ?>
          <div class="absolute top-[18px] left-[18px] text-[0.54rem] tracking-[0.2em] uppercase py-1.5 px-3 bg-forest text-mint font-normal z-[2] rounded-none"><?php esc_html_e('Featured Draw', 'nera-competitions'); ?></div>
        </div>
        <div class="p-6 pt-6 pb-7 px-[26px] prize-card-body">
          <div class="text-[0.58rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 prize-cat"><?php echo esc_html($category); ?></div>
          <div class="font-heading text-[1.4rem] font-normal text-ink leading-[1.25] mb-2 prize-name"><?php echo esc_html($product->post_title); ?></div>
          <div class="text-[0.7rem] text-ink-soft mb-6 prize-val-row"><?php
            $retail = get_post_meta($product->ID, '_lty_cash_alternative', true);
            echo esc_html__('Retail value', 'nera-competitions');
            if ($retail) {
              echo ' <strong class="text-forest font-medium">' . wp_kses_post(wc_price($retail)) . '</strong>';
            }
            echo ' &nbsp;·&nbsp; ' . esc_html__('Cash alternative available', 'nera-competitions');
          ?></div>

          <?php if ($countdown_ts > 0): ?>
            <div class="flex items-start mb-[22px] countdown" data-end-ts="<?php echo esc_attr($countdown_ts); ?>">
              <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-d">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Days', 'nera-competitions'); ?></span></div>
              <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
              <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-h">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Hours', 'nera-competitions'); ?></span></div>
              <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
              <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-m">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Mins', 'nera-competitions'); ?></span></div>
              <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
              <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-s">00</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Secs', 'nera-competitions'); ?></span></div>
            </div>
          <?php endif; ?>

          <div class="flex justify-between mb-2 ticket-labels">
            <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium"><?php echo esc_html(number_format_i18n($sold_tickets)); ?></strong> <?php esc_html_e('tickets sold', 'nera-competitions'); ?></span>
            <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium"><?php echo esc_html(number_format_i18n($remaining)); ?></strong> <?php esc_html_e('remaining', 'nera-competitions'); ?></span>
          </div>
          <div class="h-0.5 bg-mint mb-[22px] overflow-hidden prog-track"><div class="h-full bg-forest transition-[width_1.2s_ease] prog-fill" style="width:<?php echo esc_attr($progress); ?>%"></div></div>

          <a href="<?php echo esc_url(get_permalink($product->ID)); ?>" class="block w-full py-[15px] text-center bg-forest text-mint text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline border-none cursor-pointer transition-colors duration-300 rounded-none hover:bg-ink enter-btn"><?php
            printf(
              /* translators: %s: ticket price */
              esc_html__('Enter from %s per ticket', 'nera-competitions'),
              $price ? wp_kses_post(wc_price($price)) : '—'
            );
          ?></a>
          <div class="text-center mt-2.5 text-[0.6rem] text-ink-soft tracking-[0.04em] cash-note"><?php esc_html_e('Prefer cash?', 'nera-competitions'); ?> <?php
            if ($retail) {
              echo '<a href="' . esc_url(get_permalink($product->ID)) . '" class="text-sage underline underline-offset-[2px]">' . wp_kses_post(wc_price($retail)) . ' ' . esc_html__('alternative available', 'nera-competitions') . '</a>';
            } else {
              esc_html_e('Cash alternative available', 'nera-competitions');
            }
          ?></div>
        </div>
      <?php else: ?>
        <div class="prize-card-img h-[280px] flex items-center justify-center relative overflow-hidden [background:linear-gradient(145deg,#d0e8c8,#b8d8b0,#c4e0bc)]">
          <svg width="200" height="210" viewBox="0 0 220 220" fill="none" aria-hidden="true">
            <rect x="88" y="10" width="44" height="52" rx="9" fill="#3a4838" stroke="rgba(61,74,58,0.5)" stroke-width="1"/>
            <rect x="93" y="15" width="34" height="44" rx="7" fill="#2e3a2c"/>
            <circle cx="110" cy="110" r="60" fill="url(#wcase2)" stroke="#3d4a3a" stroke-width="2"/>
            <circle cx="110" cy="110" r="47" fill="url(#wdial2)"/>
            <line x1="110" y1="110" x2="88" y2="86" stroke="rgba(200,230,192,0.95)" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="110" y1="110" x2="130" y2="76" stroke="rgba(200,230,192,0.9)" stroke-width="1.8" stroke-linecap="round"/>
            <circle cx="110" cy="110" r="3.5" fill="#6b8c6b"/>
            <defs>
              <radialGradient id="wcase2" cx="38%" cy="30%"><stop offset="0%" stop-color="#566054"/><stop offset="60%" stop-color="#3a4238"/><stop offset="100%" stop-color="#2a3028"/></radialGradient>
              <radialGradient id="wdial2" cx="40%" cy="35%"><stop offset="0%" stop-color="#3c4a3a"/><stop offset="100%" stop-color="#252e24"/></radialGradient>
            </defs>
          </svg>
          <div class="absolute top-[18px] left-[18px] text-[0.54rem] tracking-[0.2em] uppercase py-1.5 px-3 bg-forest text-mint font-normal z-[2] rounded-none"><?php esc_html_e('Featured Draw', 'nera-competitions'); ?></div>
        </div>
        <div class="p-6 pt-6 pb-7 px-[26px] prize-card-body">
          <div class="text-[0.58rem] tracking-[0.22em] uppercase text-ink-soft mb-1.5 prize-cat"><?php esc_html_e('Lifestyle', 'nera-competitions'); ?></div>
          <div class="font-heading text-[1.4rem] font-normal text-ink leading-[1.25] mb-2 prize-name"><?php esc_html_e('Emporio Armani Chronograph Watch', 'nera-competitions'); ?></div>
          <div class="text-[0.7rem] text-ink-soft mb-6 prize-val-row"><?php esc_html_e('Retail value', 'nera-competitions'); ?> <strong class="text-forest font-medium">£395</strong> &nbsp;·&nbsp; <?php esc_html_e('Cash alternative available', 'nera-competitions'); ?></div>
          <div class="flex items-start mb-[22px] countdown" data-end-ts="<?php echo esc_attr(time() + 2 * 86400 + 14 * 3600 + 37 * 60 + 8); ?>">
            <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-d">02</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Days', 'nera-competitions'); ?></span></div>
            <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
            <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-h">14</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Hours', 'nera-competitions'); ?></span></div>
            <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
            <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-m">37</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Mins', 'nera-competitions'); ?></span></div>
            <span class="font-heading text-[1.5rem] text-[rgba(61,74,58,0.2)] px-0.5 leading-[1.05] cd-sep">:</span>
            <div class="text-center w-[54px] cd-unit"><span class="font-heading text-[1.9rem] font-normal text-forest block leading-none cd-num" id="luxora-cd-s">08</span><span class="text-[0.5rem] tracking-[0.2em] uppercase text-ink-soft block mt-1 cd-label"><?php esc_html_e('Secs', 'nera-competitions'); ?></span></div>
          </div>
          <div class="flex justify-between mb-2 ticket-labels">
            <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium">682</strong> <?php esc_html_e('tickets sold', 'nera-competitions'); ?></span>
            <span class="text-[0.6rem] text-ink-soft ticket-lbl"><strong class="text-forest font-medium">318</strong> <?php esc_html_e('remaining', 'nera-competitions'); ?></span>
          </div>
          <div class="h-0.5 bg-mint mb-[22px] overflow-hidden prog-track"><div class="h-full bg-forest transition-[width_1.2s_ease] prog-fill" style="width:68%"></div></div>
          <a href="<?php echo esc_url(function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/')); ?>" class="block w-full py-[15px] text-center bg-forest text-mint text-[0.68rem] tracking-[0.2em] uppercase font-medium font-['Jost',sans-serif] no-underline border-none cursor-pointer transition-colors duration-300 rounded-none hover:bg-ink enter-btn"><?php esc_html_e('Enter from £1.99 per ticket', 'nera-competitions'); ?></a>
          <div class="text-center mt-2.5 text-[0.6rem] text-ink-soft tracking-[0.04em] cash-note"><?php esc_html_e('Prefer cash?', 'nera-competitions'); ?> <a href="#" class="text-sage underline underline-offset-[2px]"><?php esc_html_e('£316 alternative available', 'nera-competitions'); ?></a></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if ($countdown_ts > 0 || !$product): ?>
<script>
(function(){
  var el = document.querySelector('.luxora-homepage .countdown[data-end-ts]');
  if (!el) return;
  var ts = parseInt(el.getAttribute('data-end-ts'), 10) * 1000;
  function tick(){
    var diff = Math.max(0, ts - Date.now());
    var d = Math.floor(diff/86400000);
    var h = Math.floor(diff%86400000/3600000);
    var m = Math.floor(diff%3600000/60000);
    var s = Math.floor(diff%60000/1000);
    var dd = document.getElementById('luxora-cd-d');
    var hh = document.getElementById('luxora-cd-h');
    var mm = document.getElementById('luxora-cd-m');
    var ss = document.getElementById('luxora-cd-s');
    if (dd) dd.textContent = String(d).padStart(2,'0');
    if (hh) hh.textContent = String(h).padStart(2,'0');
    if (mm) mm.textContent = String(m).padStart(2,'0');
    if (ss) ss.textContent = String(s).padStart(2,'0');
  }
  tick();
  setInterval(tick, 1000);
})();
</script>
<?php endif; ?>
