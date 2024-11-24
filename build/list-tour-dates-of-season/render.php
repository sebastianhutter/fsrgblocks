<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php

include_once(plugin_dir_path(__FILE__) . "/../constants.php");

// get attributes from block
$seasonYear = $attributes['seasonYear'] ?? date('Y');
$header = $attributes['header'];
$linkText = $attributes['linkText'];
$noEntriesFoundText = $attributes['noEntriesFoundText'];

// find all date entries for the current season
$allDates = get_field(FSRG_RUNDGANG_TERMIN_GROUP_FIELD) ?? [];

// prepare the found date entries for the list output
// each entry has two fields, datum_und_zeit and ticket_link
// the datum)_und_zeit field is a string in the format 2024-11-30 00:00:00
// to render it we need to change it to Sa 30.11.2024 00:00 Uhr
$noDatesFound = false;
$dateList = [];
if (!empty($allDates)) {
	foreach ($allDates as $event) {
		// convert the date string to a DateTime object
		$eventDate = new DateTime($event[FSRG_RUNDGANG_TERMIN_DATE_AND_TIME_FIELD]);
		// if the events date is not in the current season, skip it
		if ($eventDate->format('Y') !== $seasonYear) {
			continue;
		}
		// check the date and map it to the german 2 letter short names
		$shortDay = "";
		switch ($eventDate->format('N')) {
			case 1:
				$shortDay = "Mo";
				break;
			case 2:
				$shortDay = "Di";
				break;
			case 3:
				$shortDay = "Mi";
				break;
			case 4:
				$shortDay = "Do";
				break;
			case 5:
				$shortDay = "Fr";
				break;
			case 6:
				$shortDay = "Sa";
				break;
			case 7:
				$shortDay = "So";
				break;
		}

		$dateString = $eventDate->format('d.m.y H:i');
		$dateString = "$shortDay " . $dateString . " Uhr";
		$dateList[$dateString] = $event[FSRG_RUNDGANG_TERMIN_TICKET_LINK_FIELD];
	}

}
if (empty($dateList)) {
	$noDatesFound = true;
}
?>

<?php echo do_blocks('<!-- wp:paragraph {"metadata":{"name":"Termine"}} -->'); ?>
<p class="fsrg-block-list-tour-dates-of-season-header">
	<strong>
		<?php echo esc_html_e("$header $seasonYear"); ?>
	</strong>
</p>
<?php echo do_blocks('<!-- /wp:paragraph -->'); ?>
<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_10_REF); ?>

<?php echo do_blocks('<!-- wp:list {"metadata":{"name":"Termine"}} -->'); ?>
<ul class="fsrg-block-list-tour-dates-of-season-list">
	<?php if ($noDatesFound) { ?>
		<li class="fsrg-block-list-tour-dates-of-season-entry">
			<?php echo esc_html_e($noEntriesFoundText); ?>
		</li>
	<?php } ?>
	<?php foreach ($dateList as $date => $link) { ?>
		<?php echo do_blocks('<!-- wp:list-item -->'); ?>
		<li class="fsrg-block-list-tour-dates-of-season-entry">
			<?php echo esc_html_e($date); ?>:
			<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noreferrer noopener">
				<?php echo esc_html_e($linkText); ?>
			</a>
		</li>
		<?php echo do_blocks('<!-- /wp:list-item -->'); ?>
	<?php } ?>
</ul>
<?php echo do_blocks('<!-- /wp:list -->'); ?>
