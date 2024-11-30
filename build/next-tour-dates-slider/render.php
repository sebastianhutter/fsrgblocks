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
			"date" => $tourdate->render_timestamp_string(),
			"tour_link" => $tour->get_perma_link(),
			"ticket_link" => $tourdate->get_ticket_link(),
			"tour_title" => $tour->get_title(),
		);
	}

	// sort the entries
	usort($allSliderEntries, function ($a, $b) {
		return $a["_date_for_sorting"] <=> $b["_date_for_sorting"];
	});
}

// get the first $tourCount entries
$allSliderEntries = array_slice($allSliderEntries, 0, $tourCount);

var_dump($allSliderEntries);

?>


<div class="swiper">
	<!-- Additional required wrapper -->
	<div class="swiper-wrapper">
		<!-- Slides -->
		<div class="swiper-slide">Slide 1</div>
		<div class="swiper-slide">Slide 2</div>
		<div class="swiper-slide">Slide 3</div>
		...
	</div>
	<!-- If we need pagination -->
	<div class="swiper-pagination"></div>

	<!-- If we need navigation buttons -->
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>

	<!-- If we need scrollbar -->
	<div class="swiper-scrollbar"></div>
</div>
{{/*