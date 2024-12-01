<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>

<?php
// pass along the autplay delay to the js script
$autoPlayDelayInMs = (int) $attributes['autoPlayDelayInMs'];
wp_register_script('Swiper-Delay', '');
wp_enqueue_script('Swiper-Delay');
wp_add_inline_script('Swiper-Delay', "const autoPlayDelayInMs = $autoPlayDelayInMs;");
?>

<?php
// load the swipper js script for the next-tour-dates-slider block
wp_enqueue_style('Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.15/swiper-bundle.min.css');
wp_enqueue_script('Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11.1.15/swiper-bundle.min.js');
?>

<?php
include_once(plugin_dir_path(__FILE__) . "/../classes.php");



// get attributes from block
$buttonText = $attributes['buttonText'];
$tourCount = $attributes['tourCount'];

// units for css styles
$heightUnit = $attributes['heightUnit'];
$widthUnit = $attributes['widthUnit'];
$heightValueDesktop = $attributes['heightValueDesktop'];
$widthValueDesktop = $attributes['widthValueDesktop'];
$heightValueMobile = $attributes['heightValueMobile'];
$widthValueMobile = $attributes['widthValueMobile'];
$colorActive = $attributes['colorActive'];
$colorInactive = $attributes['colorInactive'];
$colorText = $attributes['colorText'];
$colorBackground = $attributes['colorBackground'];


// add styles for scss
$styles = "";
$styles .= "--fsrg-slider-height-desktop: " . $heightValueDesktop . $heightUnit . ";";
$styles .= "--fsrg-slider-width-desktop: " . $widthValueDesktop . $widthUnit . ";";
$styles .= "--fsrg-slider-height-mobile: " . $heightValueMobile . $heightUnit . ";";
$styles .= "--fsrg-slider-width-mobile: " . $widthValueMobile . $widthUnit . ";";
$styles .= "--fsrg-slider-active-color: " . $colorActive . ";";
$styles .= "--fsrg-slider-inactive-color: " . $colorInactive . ";";
$styles .= "--fsrg-slider-text-color: " . $colorText . ";";
$styles .= "--fsrg-slider-background-color: " . $colorBackground . ";";

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
			"_date_for_sorting" => $tourdate->get_date(),
			"date" => render_timestamp_string($tourdate->get_date()),
			"tour_link" => $tour->get_perma_link(),
			"ticket_link" => $tourdate->get_ticket_link(),
			// if the date has a custom title use it, else use the default title
			"title" => $tourdate->get_title() ? $tourdate->get_title() : $tour->get_title(),
			// if the tour date has a picture, use it, otherwise use the default picture
			"picture" => $tourdate->get_carousel_picture() ? $tourdate->get_carousel_picture() : $tour->get_carousel_picture(),
			// get the picture position for the tour date, if not set use the default position
			"picture_position" => $tourdate->get_carousel_picture_position() ? $tourdate->get_carousel_picture_position() : $tour->get_carousel_picture_position(),
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
	<div class="swiper fsrg-swiper" <?php echo get_block_wrapper_attributes(array("style" => $styles)); ?>>
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper fsrg-swipper-wrapper">
			<!-- Slides -->
			<?php
			foreach ($allSliderEntries as $entry) {
				?>
				<div class="swiper-slide fsrg-swiper-slide"
					style="background-image: url('<?php echo $entry['picture']; ?>'); background-position: <?php echo $entry['picture_position']; ?>;">
					<?php // ensure the slide background is clickable ?>
					<a href="<?php echo $entry['tour_link']; ?>" class="fsrg-slide-block-link"></a>
					<div class="fsrg-slide-content">
						<?php // ensure the slide text is clickable ?>
						<a href="<?php echo $entry['tour_link']; ?>" class="fsrg-slide-block-link"></a>
						<div class="fsrg-slide-content-blur">
							<div class="fsrg-slide-content-date"><?php echo $entry['date']; ?></div>
							<div class="fsrg-slide-content-title"><?php echo $entry['title']; ?></div>
						</div>
					</div>
					<?php if ($entry['ticket_link']) { ?>
						<div class="fsrg-slider-button">
							<div class="wp-block-buttons">
								<div class="wp-block-button">
									<a class="fsrg-slider-button wp-block-button__link wp-element-button"
										href="<?php echo $entry['ticket_link']; ?>"
										style="border-radius:0px"><?php echo $buttonText; ?></a>
								</div>
							</div>
						</div>
					<?php } ?>
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