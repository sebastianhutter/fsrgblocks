<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>


<?php

include_once(plugin_dir_path(__FILE__) . "/../classes.php");

// get attributes from block
$seasonYear = $attributes['seasonYear'] ?? date('Y');
$header = $attributes['header'];
$linkText = $attributes['linkText'];
$noEntriesFoundText = $attributes['noEntriesFoundText'];

$tour_dates = new TourDates();
?>

<p class="fsrg-block-list-tour-dates-of-season-header">
	<strong>
		<?php echo esc_html_e("$header $seasonYear"); ?>
	</strong>
</p>
<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_10_REF); ?>

<ul class="fsrg-block-list-tour-dates-of-season-list">
	<?php if (!$tour_dates->has_tour_dates_for_year($seasonYear)) { ?>
		<li class="fsrg-block-list-tour-dates-of-season-entry">
			<?php echo esc_html_e($noEntriesFoundText); ?>
		</li>
	<?php } ?>
	<?php foreach ($tour_dates->return_tour_dates_for_year($seasonYear) as $tour_date) { ?>

		<?php
		// prepare the link text
		$link_text = $linkText;
		if ($tour_date->get_title()) {
			$link_text = $tour_date->get_title();
			if ($tour_date->get_description()) {
				$link_text .= " (" . $tour_date->get_description() . ")";
			}
		}
		?>

		<li class="fsrg-block-list-tour-dates-of-season-entry">
			<?php echo esc_html_e(render_timestamp_string($tour_date->get_date())); ?>:

			<?php if ($tour_date->get_ticket_link()) { ?>
				<a href="<?php echo esc_url($tour_date->get_ticket_link()); ?>" target="_blank" rel="noreferrer noopener">
				<?php } ?>
				<?php echo $link_text; ?>
				<?php if ($tour_date->get_ticket_link()) { ?>
				</a>
			<?php } ?>
		</li>
	<?php } ?>
</ul>
