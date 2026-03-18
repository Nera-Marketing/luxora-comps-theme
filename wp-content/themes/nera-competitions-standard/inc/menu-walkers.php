<?php
/**
 * Custom Menu Walker Classes for Nera Theme
 *
 * Provides TailwindCSS styling for WordPress navigation menus
 *
 * @package Nera_Competitions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

/**
 * Desktop Header Menu Walker
 * Applies TailwindCSS classes to desktop navigation menu items
 */
class Nera_Header_Menu_Walker extends Walker_Nav_Menu
{
  /**
   * Start the output of a sub-menu level.
   */
  public function start_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= '<ul class="luxora-submenu">';
  }

  /**
   * End the output of a sub-menu level.
   */
  public function end_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= '</ul>';
  }

  /**
   * Start the element output.
   */
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $classes = ['menu-item'];

    // Add has-children class for dropdown support
    if (in_array('menu-item-has-children', $item->classes)) {
      $classes[] = 'menu-item-has-children';
    }

    // Add active class if current
    if (
      in_array('current-menu-item', $item->classes) ||
      in_array('current_page_item', $item->classes)
    ) {
      $classes[] = 'current-menu-item';
    }

    $class_names = implode(
      ' ',
      apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth),
    );

    $output .= '<li class="' . esc_attr($class_names) . '">';

    // Build link attributes
    $atts = [];
    $atts['href'] = !empty($item->url) ? $item->url : '';
    $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
    $atts['target'] = !empty($item->target) ? $item->target : '';
    $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

    // Apply TailwindCSS classes (Earthy Editorial: bronze-56, hover bronze)
    $link_classes = 'text-[13px] tracking-[0.5px] text-[rgba(216,181,130,0.56)] hover:text-[#d8b582] font-normal transition-colors';

    // Add active styling
    if (
      in_array('current-menu-item', $item->classes) ||
      in_array('current_page_item', $item->classes)
    ) {
      $link_classes = 'text-[13px] tracking-[0.5px] text-[#d8b582] font-semibold transition-colors';
    }

    $atts['class'] = $link_classes;

    $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (!empty($value)) {
        $value = 'href' === $attr ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $item_output = isset($args->before) ? $args->before : '';
    $item_output .= '<a' . $attributes . '>';
    $item_output .=
      (isset($args->link_before) ? $args->link_before : '') .
      apply_filters('the_title', $item->title, $item->ID) .
      (isset($args->link_after) ? $args->link_after : '');
    if (in_array('menu-item-has-children', $item->classes) && $depth === 0) {
      $item_output .=
        '<span class="luxora-nav-chevron" aria-hidden="true">' .
        '<svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><path d="M2 3.5L5 6.5L8 3.5"/></svg>' .
        '</span>';
    }
    $item_output .= '</a>';
    $item_output .= isset($args->after) ? $args->after : '';

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  /**
   * End the element output.
   */
  public function end_el(&$output, $item, $depth = 0, $args = null)
  {
    $output .= '</li>';
  }
}

/**
 * Mobile Menu Walker
 * Applies TailwindCSS classes to mobile navigation menu items
 */
class Nera_Mobile_Menu_Walker extends Walker_Nav_Menu
{
  /**
   * Start the output of a sub-menu level.
   */
  public function start_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= '<ul class="luxora-mobile-submenu hidden">';
  }

  /**
   * End the output of a sub-menu level.
   */
  public function end_lvl(&$output, $depth = 0, $args = null)
  {
    $output .= '</ul>';
  }

  /**
   * Start the element output.
   */
  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $classes = ['menu-item'];

    // Add has-children class for accordion support
    if (in_array('menu-item-has-children', $item->classes)) {
      $classes[] = 'menu-item-has-children';
    }

    // Add active class if current
    if (
      in_array('current-menu-item', $item->classes) ||
      in_array('current_page_item', $item->classes)
    ) {
      $classes[] = 'current-menu-item';
    }

    $class_names = implode(
      ' ',
      apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth),
    );

    $output .= '<li class="' . esc_attr($class_names) . '">';

    // Build link attributes
    $atts = [];
    $atts['href'] = !empty($item->url) ? $item->url : '';
    $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
    $atts['target'] = !empty($item->target) ? $item->target : '';
    $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

    // Apply TailwindCSS classes for mobile (Earthy Editorial)
    $link_classes =
      'block text-[rgba(216,181,130,0.56)] hover:text-[#d8b582] font-normal py-2 transition-colors';

    // Add active styling
    if (
      in_array('current-menu-item', $item->classes) ||
      in_array('current_page_item', $item->classes)
    ) {
      $link_classes = 'block text-[#d8b582] font-semibold py-2 transition-colors';
    }

    $atts['class'] = $link_classes;

    $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (!empty($value)) {
        $value = 'href' === $attr ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $has_children = in_array('menu-item-has-children', $item->classes);
    $is_parent = $has_children && $depth === 0;

    $item_output = isset($args->before) ? $args->before : '';

    if ($is_parent) {
      $item_output .= '<div class="flex items-center justify-between">';
    }

    $item_output .= '<a' . $attributes . '>';
    $item_output .=
      (isset($args->link_before) ? $args->link_before : '') .
      apply_filters('the_title', $item->title, $item->ID) .
      (isset($args->link_after) ? $args->link_after : '');
    $item_output .= '</a>';

    if ($is_parent) {
      $item_output .=
        '<button class="luxora-accordion-toggle" type="button" aria-expanded="false" aria-label="' .
        esc_attr__('Toggle submenu', 'nera-competitions') .
        '">' .
        '<svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" aria-hidden="true"><path d="M3.5 5.5L7 9L10.5 5.5"/></svg>' .
        '</button>' .
        '</div>';
    }

    $item_output .= isset($args->after) ? $args->after : '';

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  /**
   * End the element output.
   */
  public function end_el(&$output, $item, $depth = 0, $args = null)
  {
    $output .= '</li>';
  }
}
