<?php
include_once(plugin_dir_path(__FILE__) . "constants.php");

// helper functiuon sorting objects by date
function sort_by_date(array $tour_dates): array
{
	if (empty($tour_dates)) {
		return [];
	}

	usort($tour_dates, function ($a, $b) {
		return $a->get_date() <=> $b->get_date();
	});
	return $tour_dates;
}

function render_timestamp_string(DateTime $date): string
{
	$date_formatted = $date->format("d.m.Y");
	$time_formatted = $date->format("H:i");
	$shortDay = "";
	switch ($date->format('N')) {
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

	// if hour, minutes are all 0, we can assume that the time is not set
	// and we can skip the time part
	$timestamp_string = "$shortDay. $date_formatted";
	if ($time_formatted !== "00:00") {
		$timestamp_string .= ", $time_formatted Uhr";
	}
	return $timestamp_string;
}

// tour date entry for a specific event
class TourDateEntry
{
	private DateTime $date;
	private string $year;
	private string $month;
	private string $ticket_link;

	private string $title;
	private string $description;

	private bool $show_in_homepage_carousel;
	private string $carousel_picture;

	private string $carousel_picture_position;

	public function __construct($date, $ticket_link = "", string $title = "", string $description = "", bool $show_in_homepage_carousel = true, string $carousel_picture = "", string $carousel_picture_position = "")
	{
		$this->date = new DateTime($date);
		$this->ticket_link = $ticket_link;
		$this->year = $this->date->format('Y');
		$this->month = $this->date->format('m');
		$this->title = $title;
		$this->description = $description;
		$this->show_in_homepage_carousel = $show_in_homepage_carousel;
		$this->carousel_picture = $carousel_picture;
		$this->carousel_picture_position = $carousel_picture_position;
	}

	public function get_ticket_link(): ?string
	{
		if (empty($this->ticket_link)) {
			return null;
		}
		return $this->ticket_link;
	}

	public function get_description(): ?string
	{

		if (empty($this->description)) {
			return null;
		}
		return $this->description;
	}

	public function is_displayed_in_homepage_carousel(): bool
	{
		return $this->show_in_homepage_carousel;
	}

	public function get_carousel_picture(): ?string
	{
		if (empty($this->carousel_picture)) {
			return null;
		}
		return $this->carousel_picture;
	}

	public function get_title(): ?string
	{
		if (empty($this->title)) {
			return null;
		}
		return $this->title;
	}

	public function get_year(): string
	{
		return $this->year;
	}

	public function get_month(): string
	{
		return $this->month;
	}

	public function get_date(): DateTime
	{
		return $this->date;
	}

	public function get_carousel_picture_position(): ?string
	{
		if (empty($this->carousel_picture_position)) {
			return null;
		}
		return $this->carousel_picture_position;
	}
}

// Tour Dates for a specific event
class TourDates
{
	private $post_id;
	private string $title;
	private string $perma_link;

	private bool $show_in_homepage_carousel;
	private string $carousel_picture;
	private string $carousel_picture_position;
	private array $tour_dates = [];
	public function __construct($post_id = null)
	{
		$this->post_id = $post_id;
		$this->title = get_the_title($post_id);
		$this->perma_link = get_permalink($post_id);
		$this->set_tour_date_fields();
		$this->set_carousel_picture();
		$this->set_show_in_home_carousel();
		$this->set_carousel_picture_position();
	}

	private function set_carousel_picture(): void
	{
		try {
			$this->carousel_picture = get_field(FSRG_RUNDGANG_CAROUSEL_PICTURE_FIELD, $this->post_id);
		} catch (Exception $e) {
			$this->carousel_picture = null;
		}
	}

	private function set_show_in_home_carousel(): void
	{
		try {
			$this->show_in_homepage_carousel = get_field(FSRG_RUNDGANG_CAROUSEL_SHOW_ON_HOMEPAGE_FIELD, $this->post_id);
		} catch (Exception $e) {
			$this->show_in_homepage_carousel = false;
		}
	}

	private function set_carousel_picture_position(): void
	{
		try {
			$this->carousel_picture_position = get_field(FSRG_RUNDGANG_CAROUSEL_PICTURE_POSITION_FIELD, $this->post_id);
		} catch (Exception $e) {
			$this->carousel_picture_position = null;
		}
	}

	private function set_tour_date_fields(): void
	{
		try {
			$entries = get_field(FSRG_RUNDGANG_TERMIN_GROUP_FIELD, $this->post_id);
		} catch (Exception $e) {
			$entries = [];
		}
		if (empty($entries)) {
			return;
		}

		$tour_dates = [];
		foreach ($entries as $entry) {
			$tour_dates[] = new TourDateEntry(
				$entry[FSRG_RUNDGANG_TERMIN_DATE_AND_TIME_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_TICKET_LINK_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_TITEL_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_BESCHREIBUNG_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_SHOW_ON_HOMEPAGE_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_HOMEPAGE_PICTURE_FIELD],
				$entry[FSRG_RUNDGANG_TERMIN_HOMEPAGE_PICTURE_POSITION_FIELD],
			);
		}
		$this->tour_dates = $tour_dates;
	}

	public function has_tour_dates_for_year(string $year): bool
	{
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->get_year() === $year) {
				return true;
			}
		}

		return false;
	}

	public function return_tour_dates_for_year(string $year): array
	{
		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->get_year() === $year) {
				$dates[] = $tour_date;
			}
		}

		return sort_by_date($dates);
	}

	public function return_tour_dates_for_year_and_month(string $year, string $month): array
	{
		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->get_year() === $year && $tour_date->get_month() === $month) {
				$dates[] = $tour_date;
			}
		}

		// sort the dates by date
		usort($dates, function ($a, $b) {
			return $a->get_date() <=> $b->get_date();
		});

		return sort_by_date($dates);
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function is_displayed_in_homepage_carousel(): bool
	{
		return $this->show_in_homepage_carousel;
	}

	public function get_all_future_tour_dates(): array
	{
		// return any dates with a date either today or in the future
		$today = new DateTime("now");
		$today->setTime(0, 0, 0);

		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->get_date() >= $today) {
				$dates[] = $tour_date;
			}
		}

		return sort_by_date($dates);
	}

	public function get_perma_link(): string
	{
		return $this->perma_link;
	}

	public function get_carousel_picture(): ?string
	{
		return $this->carousel_picture;
	}

	public function get_carousel_picture_position(): ?string
	{
		return $this->carousel_picture_position;
	}
}


// tour dates for all events found on the website!
// this is a helper class to get all tour dates for all events on the website
// used to display the list of next events in "unsere rundgaenge" and to render
// the homepage carousel
class AllTourDates
{
	private $tours = [];

	public function __construct()
	{
		$pages_with_tours = $this->get_published_pages_with_tour_dates();
		foreach ($pages_with_tours as $page) {
			$this->tours[] = new TourDates($page->ID);
		}
	}

	private function get_published_pages_with_tour_dates(): array
	{
		return get_posts(array(
			'posts_per_page' => -1,
			'post_type' => 'page',
			'post_status' => 'publish',
			'meta_key' => FSRG_RUNDGANG_TERMIN_GROUP_FIELD,
		));
	}

	public function get_tours(): array
	{
		return $this->tours;
	}
}


?>