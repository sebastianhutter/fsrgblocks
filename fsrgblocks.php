<?php
/**
 * Plugin Name:       Fsrgblocks
 * Description:       Show FSRG Events
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.2
 * Author:            Sebastian Hutter <me@sebastianhutter.dev>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       fsrgblocks
 *
 * @package Fsrg
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function fsrg_fsrgblocks_block_init()
{
	register_block_type(__DIR__ . '/build/list-tour-dates-of-season');
	register_block_type(__DIR__ . '/build/list-all-tour-dates-of-current-year');
	register_block_type(__DIR__ . '/build/next-tour-dates-slider');
}
add_action('init', 'fsrg_fsrgblocks_block_init');
