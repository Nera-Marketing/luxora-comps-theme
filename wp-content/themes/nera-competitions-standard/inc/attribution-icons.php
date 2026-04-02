<?php
/**
 * Inline SVG icons for attribution feature grid (preset keys).
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

/**
 * Echo inline SVG for a feature icon preset (stroke, currentColor).
 *
 * @param string $preset Preset key from ACF select.
 */
function nera_attr_feature_icon_svg($preset)
{
  $p = (string) $preset;
  // Legacy material-style names map to closest preset.
  $legacy = [
    'bolt' => 'layers',
    'travel_explore' => 'search',
    'support_agent' => 'affiliate',
  ];
  if (isset($legacy[$p])) {
    $p = $legacy[$p];
  }
  switch ($p) {
    case 'credit_card':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>';
      return;
    case 'timer':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
      return;
    case 'mail':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
      return;
    case 'chart':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>';
      return;
    case 'affiliate':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
      return;
    case 'search':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
      return;
    case 'smartphone':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>';
      return;
    case 'shield':
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
      return;
    case 'layers':
    default:
      echo '<svg class="nera-attr-feature-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>';
  }
}
