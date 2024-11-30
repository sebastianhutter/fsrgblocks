<?php
include_once(plugin_dir_path(__FILE__) . "constants.php");

// helper functiuon sorting objects by date
function sort_by_date(array $tour_dates): array
{
	if (empty($tour_dates)) {
		return [];
	}

	usort($tour_dates, function ($a, $b) {
		return $a->date <=> $b->date;
	});
	return $tour_dates;
}

// tour date entry for a specific event
class TourDateEntry
{
	public DateTime $date;
	public string $year;
	public string $month;
	public string $ticket_link;

	public string $title;
	public string $description;

	public bool $show_in_homepage_carousel;
	public string $carousel_picture;

	public function __construct($date, $ticket_link = "", string $title = "", string $description = "", bool $show_in_homepage_carousel = true, string $carousel_picture = "")
	{
		$this->date = new DateTime($date);
		$this->ticket_link = $ticket_link;
		$this->year = $this->date->format('Y');
		$this->month = $this->date->format('m');
		$this->title = $title;
		$this->description = $description;
		$this->show_in_homepage_carousel = $show_in_homepage_carousel;
		$this->carousel_picture = $carousel_picture;

	}

	public function render_timestamp_string(): string
	{
		// renders the date in the format Sa 30.11.2024 00:00 Uhr

		$date_formatted = $this->date->format("d.m.Y");
		$time_formatted = $this->date->format("H:i");
		$shortDay = "";
		switch ($this->date->format('N')) {
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

	public function get_ticket_link(): string
	{
		return $this->ticket_link;
	}

	public function render_ticket_description(): string
	{
		if ($this->description) {
			return "($this->description)";
		}

		return "";
	}

	public function render_ticket_link(string $text): string
	{

		$rendered_text = $text;
		if ($this->title) {
			$rendered_text = $this->title;
		}

		if (empty($this->ticket_link)) {
			return $rendered_text . ' ' . $this->render_ticket_description();
		}

		return '<a href="' . esc_url($this->ticket_link) . '" target="_blank" rel="noreferrer noopener">' . $rendered_text . ' ' . $this->render_ticket_description() . '</a>';
	}
}

// Tour Dates for a specific event
class TourDates
{
	private $post_id;
	private string $title;
	private bool $show_in_homepage_carousel;
	private string $carousel_picture;
	private array $tour_dates = [];
	public function __construct($post_id = null)
	{
		$this->post_id = $post_id;
		$this->title = get_the_title($post_id);
		$this->set_tour_date_fields();
		$this->set_carousel_picture();
		$this->set_show_in_home_carousel();
	}

	private function set_carousel_picture(): void
	{
		$this->carousel_picture = get_field(FSRG_RUNDGANG_CAROUSEL_PICTURE_FIELD, $this->post_id);
	}

	private function set_show_in_home_carousel(): void
	{
		$this->show_in_homepage_carousel = get_field(FSRG_RUNDGANG_CAROUSEL_SHOW_ON_HOMEPAGE_FIELD, $this->post_id);
	}

	private function set_tour_date_fields(): void
	{
		$entries = get_field(FSRG_RUNDGANG_TERMIN_GROUP_FIELD, $this->post_id);
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
			);
		}
		$this->tour_dates = $tour_dates;
	}

	public function has_tour_dates_for_year(string $year): bool
	{
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->year === $year) {
				return true;
			}
		}

		return false;
	}

	public function return_tour_dates_for_year(string $year): array
	{
		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->year === $year) {
				$dates[] = $tour_date;
			}
		}

		return sort_by_date($dates);
	}

	public function return_tour_dates_for_year_and_month(string $year, string $month): array
	{
		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->year === $year && $tour_date->month === $month) {
				$dates[] = $tour_date;
			}
		}

		// sort the dates by date
		usort($dates, function ($a, $b) {
			return $a->date <=> $b->date;
		});

		return sort_by_date($dates);
	}

	public function get_title(): string
	{
		return $this->title;
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