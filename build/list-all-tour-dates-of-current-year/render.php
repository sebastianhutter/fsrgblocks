<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php

include_once(plugin_dir_path(__FILE__) . "/../classes.php");


// get the defined year from the wordpress block
$seasonYear = $attributes['seasonYear'] ?? date('Y');


$all_tours = new AllTourDates();

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
	foreach ($all_tours->get_tours() as $tour) {
		foreach ($tour->return_tour_dates_for_year_and_month($seasonYear, $key) as $date) {
			$dates_for_month[] = array(
				"_date_for_sorting" => $date->get_date(),
				"date" => render_timestamp_string($date->get_date()),
				"ticket_link_text" => $date->get_title() ? $date->get_title() : $tour->get_title(),
				"ticket_link_description" => $date->get_description() ? $date->get_description() : null,
				"ticket_link" => $date->get_ticket_link(),
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
				<?php
				// prepare the link text
				$link_text = $value["ticket_link_text"];
				if ($value["ticket_link_description"]) {
					$link_text .= " (" . $value["ticket_link_description"] . ")";
				}
				?>
				<li class="fsrg-block-list-all-tour-dates-of-current-year-entry">
					<?php echo esc_html_e($value["date"]); ?>:
					<?php if ($value["ticket_link"]) { ?>
						<a href="<?php echo esc_url($value["ticket_link"]); ?>" target="_blank" rel="noreferrer noopener">
						<?php } ?>
						<?php echo $link_text; ?>
						<?php if ($value["ticket_link"]) { ?>
						</a>
					<?php } ?>
				</li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>