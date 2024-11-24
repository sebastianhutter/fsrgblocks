<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php

include_once(plugin_dir_path(__FILE__) . "/../classes.php");


// get the defined year from the wordpress block
$seasonYear = $attributes['seasonYear'] ?? date('Y');

$pagesWithTourDates = get_posts(array(
	'posts_per_page' => -1,
	'post_type' => 'page',
	'meta_key' => FSRG_RUNDGANG_TERMIN_GROUP_FIELD,
));

// iterate over the returned list and prepare an array containing the title, id and all registered tour dates
$pagesWithTourDatesArray = [];
foreach ($pagesWithTourDates as $page) {
	if ($page->post_type == 'page' && $page->post_status == 'publish') {
		$pagesWithTourDatesArray[] = array(
			'id' => $page->ID,
			'title' => $page->post_title,
			'tour_dates' => new TourDates($page->ID),
		);
	}
}

// create a flattened list for each month (semi hardcoded to ensure german output)
$months = array(
	'01' => 'Januar',
	'02' => 'Februar',
	'03' => 'März',
	'04' => 'April',
	'05' => 'Mai',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'August',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'November',
	'12' => 'Dezember',
);

$flattenedTourDates = [];
foreach ($months as $key => $value) {
	$dates_for_month = [];
	foreach ($pagesWithTourDatesArray as $k2 => $page) {
		foreach ($page["tour_dates"]->return_tour_dates_for_year_and_month($seasonYear, $key) as $date) {
			$dates_for_month[] = array(
				"date" => $date->render_timestamp_string(),
				"ticket_link" => $date->get_ticket_link(),
				"ticket_text" => $page["title"]
			);
		}
	}
	$flattenedTourDates[$value] = $dates_for_month;
}

// and finally drop empty months
$monthlyTourDates = [];
foreach ($flattenedTourDates as $key => $value) {
	if (count($value) == 0) {
		continue;
	}
	$monthlyTourDates[$key] = $value;
}
?>

<?php foreach ($monthlyTourDates as $m => $values) { ?>
	<?php echo do_blocks('<!-- wp:group {"metadata":{"name":"' . $m . '"},"layout":{"type":"default"}} -->') ?>
	<div class="wp-block-group">
		<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_25_REF); ?>
		<?php echo do_blocks('<!-- wp:heading {"level":3,"fontSize":"medium"} -->') ?>
		<h3 class="wp-block-heading has-medium-font-size"><strong><?php echo esc_html_e($m); ?></strong></h3>
		<?php echo do_blocks('<!-- /wp:heading -->') ?>
		<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_10_REF); ?>
		<?php echo do_blocks('<!-- wp:list {"metadata":{"name":"' . $m . '"}} -->'); ?>
		<ul class="fsrg-block-list-all-tour-dates-of-current-year-list">
			<?php foreach ($values as $value) { ?>
				<?php echo do_blocks('<!-- wp:list-item -->'); ?>
				<li class="fsrg-block-list-all-tour-dates-of-current-year-entry">
					<?php echo esc_html_e($value["date"]); ?>:
					<a href="<?php echo esc_url($value["ticket_link"]); ?>" target="_blank" rel="noreferrer noopener">
						<?php echo esc_html_e($value["ticket_text"]); ?>
					</a>
				</li>
				<?php echo do_blocks('<!-- /wp:list-item -->'); ?>
			<?php } ?>
		</ul>
		<?php echo do_blocks('<!-- /wp:list -->'); ?>
	</div>
	<?php echo do_blocks('<!-- /wp:group -->') ?>
<?php } ?>
