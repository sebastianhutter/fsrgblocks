<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php

include_once(plugin_dir_path(__FILE__) . "/../constants.php");

$pagesWithTourDates = get_posts(array(
	'posts_per_page' => -1,
	'post_type' => 'page',
	'meta_key' => FSRG_RUNDGANG_TERMIN_GROUP_FIELD,
));

// iterate over the returned list and prepare an array containing the title and id of each page
$pagesWithTourDatesArray = [];
foreach ($pagesWithTourDates as $page) {
	if ($page->post_type == 'page' && $page->post_status == 'publish') {
		$pagesWithTourDatesArray[] = array(
			'id' => $page->ID,
			'title' => $page->post_title,
		);
	}
}

var_dump($pagesWithTourDatesArray);

// iterate over the pages and return their dates

?>
