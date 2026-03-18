<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo "--- WordPress Debug Info ---\n";
echo "Home URL: " . get_option('home') . "\n";
echo "Site URL: " . get_option('siteurl') . "\n";
echo "Permalink Structure: " . get_option('permalink_structure') . "\n";
echo "Page for Posts (ID): " . get_option('page_for_posts') . "\n";

if (get_option('page_for_posts')) {
  $blog_page = get_post(get_option('page_for_posts'));
  echo "Blog Page Slug: " . ($blog_page ? $blog_page->post_name : 'Not Found') . "\n";
} else {
  echo "No page assigned for posts (using index.php as front page?)\n";
}

$args = array(
  'post_type' => 'page',
  'post_status' => 'publish',
  'posts_per_page' => -1
);
$pages = get_posts($args);
echo "\n--- Published Pages ---\n";
foreach ($pages as $page) {
  echo "ID: {$page->ID} | Slug: {$page->post_name} | Title: {$page->post_title}\n";
}

// Flush permalinks just in case
flush_rewrite_rules();
echo "\nPermalinks flushed.\n";
