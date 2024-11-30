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
include_once(plugin_dir_path(__FILE__) . "/../constants.php");
include_once(plugin_dir_path(__FILE__) . "/../classes.php");

// get attributes from block
$header = $attributes['header'];
$buttonText = $attributes['buttonText'];
$tourCount = $attributes['tourCount'];

var_dump($tourCount);
var_dump($buttonText);
var_dump($header);

$tour_dates = new TourDates();

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
