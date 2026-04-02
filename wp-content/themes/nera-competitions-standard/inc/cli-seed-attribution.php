<?php
/**
 * WP-CLI: seed Nera Marketing attribution page ACF fields.
 *
 * Run after assigning the page template: `wp nera seed_attribution`
 * Use `wp nera seed_attribution --force` to overwrite fields that already have values.
 *
 * @package Nera_Competitions
 */

if (!defined('ABSPATH')) {
  exit();
}

if (!defined('WP_CLI') || !WP_CLI) {
  return;
}

/**
 * Nera WP-CLI commands.
 */
class Nera_CLI
{
  /**
   * Seed default ACF content for pages using the Nera Marketing attribution template.
   *
   * ## OPTIONS
   *
   * [--force]
   * : Overwrite fields that already have a value.
   *
   * ## EXAMPLES
   *
   *     wp nera seed_attribution
   *     wp nera seed_attribution --force
   *
   * @param array<int, string> $args Positional args.
   * @param array<string, mixed> $assoc_args Flags.
   */
  public function seed_attribution($args, $assoc_args)
  {
    $force = !empty($assoc_args['force']);
    $result = nera_attr_seed_attribution_pages($force);

    if (!$result['acf_active']) {
      WP_CLI::error($result['error'] ?? __('ACF is not active.', 'nera-competitions'));
      return;
    }

    if (!empty($result['no_matching_pages'])) {
      WP_CLI::warning($result['warning'] ?? '');
      return;
    }

    foreach ($result['seeded_post_ids'] as $post_id) {
      $title = get_the_title($post_id);
      WP_CLI::log(sprintf('Seeding page ID %d — %s', (int) $post_id, $title));
      WP_CLI::success(sprintf(__('Finished seed for page ID %d.', 'nera-competitions'), (int) $post_id));
    }
  }
}

WP_CLI::add_command('nera', 'Nera_CLI');
