<?php
/**
 * Front to the WordPress application. THIS FILE DOESN'T DO ANYTHING, but LOAD
 * 'wp-blog-header.php' which does and tells WordPress to LOAD the THEME.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to LOAD the WordPress THEME and OUTPUT it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** LOADS the WordPress ENVIRONMENT and TEMPLATE */
require __DIR__ . '/wp-blog-header.php'; // var_dump(__DIR__) : string(53) "C:\Users\bmfc9\OneDrive\Desktop\Fun\cloning\WordPress"
