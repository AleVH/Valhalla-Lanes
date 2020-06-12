<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class Ranking extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('rankings_model', 'rankings', true);
		$this->load->model('rankings_results_model', 'rankings_results', true);
	}

	/**
	 * this method will load the ranking section in the admin dashboard
	 */
	public function index(){

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			$results = $this->sections->getSectionStatus('ranking');
			$section = $results->row(0,'sections_entity');

			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['error'] = '';
			$data['form_attr_1'] = array('class' => 'admin_ranking-create');
			$data['form_attr_2'] = array('class' => 'admin_ranking-edit');
			$data['rankings'] = $this->getAllRanks();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-rankings', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Ranking';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $user_logged['user_name'];

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-rankings', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}

	}

	private function getAllRanks(){
		$ranksArray = array();
		$results = $this->rankings->getAllRanks();
		$this->load->library('../entities/Rankings_entity');
		foreach($results->result('rankings_entity') as $eachRank){
			$ranksArray[$eachRank->id]['title'] = $eachRank->title;
			$ranksArray[$eachRank->id]['tops'] = $eachRank->tops;
			$ranksArray[$eachRank->id]['start_date'] = $eachRank->start_date;
			$ranksArray[$eachRank->id]['end_date'] = $eachRank->end_date;
			$ranksArray[$eachRank->id]['author'] = $eachRank->author;
			$ranksArray[$eachRank->id]['is_enabled'] = $eachRank->is_enabled;
		}
		return $ranksArray;

	}

	/**
	 * This method is to handle ajax request to create a new ranking
	 * @throws Exception
	 */
	public function create(){

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			// simple sanitation
			$title = filter_var($this->input->post('ranking-title'), FILTER_SANITIZE_STRING);
			$tops = filter_var($this->input->post('ranking-tops'), FILTER_SANITIZE_NUMBER_INT);
//			$start = filter_var($this->input->post('ranking-start'), FILTER_SA)
			$start =  (new DateTime(preg_replace("([^0-9/] | [^0-9-])","",htmlentities($this->input->post('ranking-start')))))->format('Y-m-d H:i:s');
			$end = (empty($this->input->post('ranking-end')))?null:(new DateTime(preg_replace("([^0-9/] | [^0-9-])","",htmlentities($this->input->post('ranking-end')))))->format('Y-m-d H:i:s');
			$result = $this->rankings->saveNewRanking($title, $tops, $start, $end);

			$this->load->library('../entities/Rankings_entity');
			$response = $this->rankings->getARanking($result);

			echo returnResponse('success', $response->row(0, 'rankings_entity'), 'jsonizeResponse');
		}

//		$xxx = $this->input->post(null, true);
//		var_dump($xxx);
	}

	public function edit($id){

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');

		}
	}

	public function getRankToEdit($id){

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$this->load->library('../entities/Rankings_results_entity');
			$this->load->library('../entities/Rankings_players_entity');
			$response_players = $this->rankings_results->getRankResultsUsersDetailsByRankResultId($id);
			$response_ranking = $this->rankings->getARanking($id);
			$rankingResultsData = $response_ranking->custom_row_object(0, 'Rankings_results_entity');
			foreach ($response_players->custom_result_object('Rankings_players_entity') as $eachPlayer){
				$rankingResultsData->players[] = $eachPlayer;
			}

			echo returnResponse('success', $rankingResultsData, 'jsonizeResponse');
		}
	}

	public function updateRankingStatus(){

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');

			if($this->rankings->updateRankingStatus($this->input->post('rank_id'), $this->input->post('status'))){
				echo returnResponse('success', 'OK', 'jsonizeResponse');
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}
	}

}
