<?php
/**
 * Plugin Name:       Fsrgblocks
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
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
	// TODO: rmeove original block registration
	register_block_type(__DIR__ . '/build/original-block');
	register_block_type(__DIR__ . '/build/list-tour-dates-of-season');
}
add_action('init', 'fsrg_fsrgblocks_block_init');
