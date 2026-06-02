<?php
/**
 * WP-CLI: seed Nera Marketing attribution options ACF fields.
 *
 * Run: `wp nera seed-attribution`
 * Use `wp nera seed-attribution --force` to overwrite fields that already have values.
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
   * Seed default ACF content for the hidden Nera attribution options page.
   *
   * ## OPTIONS
   *
   * [--force]
   * : Overwrite fields that already have a value.
   *
   * ## EXAMPLES
   *
   *     wp nera seed-attribution
   *     wp nera seed-attribution --force
   *
   * @param array<int, string> $args Positional args.
   * @param array<string, mixed> $assoc_args Flags.
   * @subcommand seed-attribution
   */
  public function seed_attribution($args, $assoc_args)
  {
    $force = !empty($assoc_args['force']);
    $result = nera_attr_seed_attribution_options($force);

    if (!$result['acf_active']) {
      WP_CLI::error($result['error'] ?? __('ACF is not active.', 'nera-competitions'));
      return;
    }

    WP_CLI::success(
      sprintf(
        /* translators: %d: number of attribution fields written */
        __('Attribution options seeded. %d field(s) written.', 'nera-competitions'),
        (int) ($result['seeded_fields'] ?? 0)
      )
    );
  }
}

WP_CLI::add_command('nera', 'Nera_CLI');
