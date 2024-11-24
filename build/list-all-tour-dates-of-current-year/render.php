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
				"_date_for_sorting" => $date->date,
				"date" => $date->render_timestamp_string(),
				// "ticket_link" => $date->get_ticket_link(),
				// "ticket_text" => $page["title"],
				// "ticket_description" => $date->render_ticket_description(),
				"ticket_link" => $date->render_ticket_link($page["title"]),
			);
		}
	}
	// sort the dates for the month
	usort($dates_for_month, function ($a, $b) {
		return $a["_date_for_sorting"] <=> $b["_date_for_sorting"];
	});

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
	<div class="wp-block-group is-layout-flow wp-block-group-is-layout-flow">
		<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_25_REF); ?>
		<h3 class="wp-block-heading has-medium-font-size"><strong><?php echo esc_html_e($m); ?></strong></h3>
		<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_10_REF); ?>
		<ul class="wp-block-list fsrg-block-list-all-tour-dates-of-current-year-list">
			<?php foreach ($values as $value) { ?>
				<li class="fsrg-block-list-all-tour-dates-of-current-year-entry">
					<?php echo esc_html_e($value["date"]); ?>:
					<?php echo $value["ticket_link"] ?>
				</li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
