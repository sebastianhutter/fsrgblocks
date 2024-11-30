<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<?php
// load the swipper js script for the next-tour-dates-slider block
wp_enqueue_style('Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.15/swiper-bundle.min.css');
wp_enqueue_script('Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.15/swiper-bundle.min.js');
?>

<?php
include_once(plugin_dir_path(__FILE__) . "/../classes.php");

// get attributes from block
$header = $attributes['header'];
$buttonText = $attributes['buttonText'];
$tourCount = $attributes['tourCount'];

$all_tours = new AllTourDates();

// get all tour dates which should be displayed in the slider
$allSliderEntries = [];
foreach ($all_tours->get_tours() as $tour) {
	if (!$tour->is_displayed_in_homepage_carousel()) {
		continue;
	}

	foreach ($tour->get_all_future_tour_dates() as $tourdate) {
		if (!$tourdate->is_displayed_in_homepage_carousel()) {
			continue;
		}
		$allSliderEntries[] = array(
			"_date_for_sorting" => $tourdate->date,
			"date" => render_timestamp_string($tourdate->date),
			"tour_link" => $tour->get_perma_link(),
			"ticket_link" => $tourdate->get_ticket_link(),
			// if the date has a custom title use it, else use the default title
			"title" => $tourdate->get_title() ? $tourdate->get_title() : $tour->get_title(),
			// if the tour date has a picture, use it, otherwise use the default picture
			"picture" => $tourdate->get_carousel_picture() ? $tourdate->get_carousel_picture() : $tour->get_carousel_picture(),
		);
	}

	// sort the entries
	usort($allSliderEntries, function ($a, $b) {
		return $a["_date_for_sorting"] <=> $b["_date_for_sorting"];
	});
}

// get the first $tourCount entries
$allSliderEntries = array_slice($allSliderEntries, 0, $tourCount);
?>


<?php
// only render carousel if any slides are available
if ($allSliderEntries) {
	?>

	<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_25_REF); ?>
	<h2 class="wp-block-heading"><?php echo esc_html_e($header); ?></h2>
	<?php echo do_blocks(FSRG_BLOCK_SPACER_ALLE_10_REF); ?>
	<div class="swiper fsrg-swiper">
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper fsrg-swipper-wrapper">
			<!-- Slides -->
			<?php
			foreach ($allSliderEntries as $entry) {
				?>

				<div class="swiper-slide fsrg-swiper-slide" style="background-image: url('<?php echo $entry['picture']; ?>');">
					<a href="<?php echo $entry['tour_link']; ?>" class="fsrg-slide-link"></a>
					<div class="fsrg-slide-content">

						<h2><?php echo $entry['title']; ?></h2>
						<p><?php echo $entry['date']; ?></p>
						<div class="fsrg-slider-button wp-block-buttons">
							<div class="wp-block-button">
								<a class="wp-block-button__link wp-element-button" href="<?php echo $entry['ticket_link']; ?>"
									style="border-radius:0px"><?php echo $buttonText; ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<!-- If we need pagination -->
		<div class="swiper-pagination"></div>
	</div>
	<?php
}
?>