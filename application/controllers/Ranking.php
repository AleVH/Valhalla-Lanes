<?php

require __DIR__."/Services/Calendar/Calendar.php";

class Ranking extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('../entities/Menu_sections_entity');
		$this->load->model('menu_sections_model', 'menu_sections', true);
		$this->load->model('rankings_model', 'rankings', true);
	}

	public function index(){
		// in case somebody accidentally deletes the page
		if ( ! file_exists(APPPATH.'views/pages/content-templates/content-ranking.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['rankings'] = $this->getFrontRankings();

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$load_section = $this->load->view('pages/content-templates/content-ranking', $data, true);
			echo returnResponse('success', $load_section, 'jsonizeResponse');
		}else{
			// check sections
			$results = $this->menu_sections->getEnabledMenuSections();
			$data['menu'] = array();
			if(count($results->result_array()) > 0){
				// if for whatever reason all menu sections where
				foreach ($results->result('menu_sections_model') as $section) {
					$data['menu'][$section->name] = $section->is_enabled;
				}
			}

			$data['title'] = 'Ranking'; // Capitalize the first letter
			$data['styles'] = "styles";
			$data['marquee'] = true;
			$data['calendar'] = $this->getCalendarDetails();
			$data['facebook'] = true;
			$data['instagram'] = false;

			$this->load->helper('url');
			$this->load->view('templates/head', $data);
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('templates/body-start', $data);
			$this->load->view('templates/body-content-start', $data);
			$this->load->view('pages/content-templates/content-ranking', $data);
			$this->load->view('templates/body-content-end', $data);
			$this->load->view('templates/body-sidebar', $data);
			$this->load->view('templates/body-end', $data);
			$this->load->view('templates/footer', $data);
		}

	}

	/**
	 * This method is to get the details of the calendar
	 * @return array
	 */
	private function getCalendarDetails(){
		$calendar = new Calendar();
		$calendar_array = array(
			"month_name" => $calendar->getMonthName(),
			"month_length" => $calendar->getMonthLength(),
			"month_first_day" => $calendar->getFirstDay(),
			"month_last_day" => $calendar->getLastDay(),
			"today" => $calendar->getDate()
		);
		return $calendar_array;
	}

	/**
	 * get all the data and prepare it for display in the frontend
	 * @return mixed
	 */
	private function getFrontRankings(){
		$rankingsResults = $this->rankings->getActiveRankings();
		$sortedRankings = array();

		foreach ($rankingsResults->result_array() as $eachPlayer){
			$sortedRankings[$eachPlayer['id']]['rank_details']['id'] = $eachPlayer['id'];
			$sortedRankings[$eachPlayer['id']]['rank_details']['title'] = $eachPlayer['title'];
			$sortedRankings[$eachPlayer['id']]['rank_details']['tops'] = $eachPlayer['tops'];
			$sortedRankings[$eachPlayer['id']]['rank_details']['start_date'] = $eachPlayer['start_date'];
			$sortedRankings[$eachPlayer['id']]['rank_details']['end_date'] = $eachPlayer['end_date'];

			$playerName = "";
			switch ($eachPlayer['player_name_display']) {
				case 'NAME':
					$playerName = $eachPlayer['name']." ".$eachPlayer['lastname'];
					break;
				case 'COMBINED':
					$playerName = $eachPlayer['name'].' "'.$eachPlayer['nickname'].'" '.$eachPlayer['lastname'];
					break;
				case 'NICKNAME':
					$playerName = '"'.$eachPlayer['nickname'].'"';
					break;
			}

			$sortedRankings[$eachPlayer['id']]['players_details'][] = array(
				'name' => $playerName,
				'score' => $eachPlayer['player_score']
			);

			// sort the players based on the score of each one
			usort($sortedRankings[$eachPlayer['id']]['players_details'], "self::sortByPlayerScore");
		}
		return $sortedRankings;
	}

	/**
	 * this method is to sort out the players in each ranking based on the score to show the ranking in order in the frontend
	 * @param $x
	 * @param $y
	 * @return int
	 */
	private static function sortByPlayerScore($x, $y){
		// equal items sort equally
		if ($x['score'] === $y['score']) {
			return 0;
		}
		// nulls sort after anything else
		else if ($x['score'] === null) {
			return 1;
		}
		else if ($y['score'] === null) {
			return -1;
		}

		return ($x['score'] > $y['score']) ? -1 : 1;

	}

}
