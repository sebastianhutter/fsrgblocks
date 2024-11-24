<?php
include_once(plugin_dir_path(__FILE__) . "constants.php");

class TourDateEntry
{
	public DateTime $date;
	public string $year;
	public string $ticket_link;

	public function __construct($date, $ticket_link)
	{
		$this->date = new DateTime($date);
		$this->ticket_link = $ticket_link;
		$this->year = $this->date->format('Y');
	}

	public function render_timestamp_string(): string
	{
		// renders the date in the format Sa 30.11.2024 00:00 Uhr

		$timestamp_formatted = $this->date->format("d.m.Y H:i");
		$timestamp_string = "$timestamp_formatted Uhr";
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

		return "$shortDay $timestamp_string";
	}

	public function get_ticket_link(): string
	{
		return $this->ticket_link;
	}
}
class TourDates
{
	private $post_id;
	public array $tour_dates = [];
	public function __construct($post_id = null)
	{
		$this->post_id = $post_id;
		$this->tour_dates = $this->get_tour_date_fields();
	}

	private function get_tour_date_fields(): array
	{
		$entries = get_field(FSRG_RUNDGANG_TERMIN_GROUP_FIELD, $this->post_id);
		if (empty($entries)) {
			return [];
		}

		$tour_dates = [];
		foreach ($entries as $entry) {
			$tour_dates[] = new TourDateEntry($entry[FSRG_RUNDGANG_TERMIN_DATE_AND_TIME_FIELD], $entry[FSRG_RUNDGANG_TERMIN_TICKET_LINK_FIELD]);
		}
		return $tour_dates;
	}

	public function has_tour_dates_for_year(string $year): bool
	{
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->year === $year) {
				return true;
			}
		}
	}

	public function return_tour_dates_for_year(string $year): array
	{
		$dates = [];
		foreach ($this->tour_dates as $tour_date) {
			if ($tour_date->year === $year) {
				$dates[] = $tour_date;
			}
		}
		return $dates;
	}
}

?>
