<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class News extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('news_model', 'news', true);
	}

	/**
	 * This method will load the news section in the admin dashboard
	 */
	public function index(){

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			// the following lines are some setups common to all responses
			$results = $this->sections->getSectionStatus('news');
			$section = $results->row(0, 'sections_entity');

			$data['form_attr'] = array('class' => 'admin_news');
			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['news'] = $this->getAllNews();
			$data['error'] = '';

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-news', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'News';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $user_logged['user_name'];

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-news', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}

		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}

	}

	public function saveNews(){
		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			// simple sanitation
			$title = filter_var($this->input->post('title'), FILTER_SANITIZE_STRING);
			$text = filter_var($this->input->post('text'), FILTER_SANITIZE_STRING);
			$result = $this->news->saveNewNews($title, $text);

			$this->load->library('../entities/News_entity');
			$response = $this->news->getANews($result);

			echo returnResponse('success', $response->row(0, 'news_entity'), 'jsonizeResponse');
		}
	}

	private function getANews($id){
		$newsArray = array();
		$result = $this->getANews($id);
		$this->load->library('../entities/News_entity');
		foreach ($result->result('news_entity') as $newsDetails){
			$newsArray['id'] = $newsDetails->id;
			$newsArray['admin_user_id'] = $newsDetails->admin_user_id;
			$newsArray['news_title'] = $newsDetails->news_title;
			$newsArray['news_text'] = $newsDetails->news_text ;
			$newsArray['created'] = $newsDetails->created;
			$newsArray['is_enabled'] = $newsDetails->is_enabled;
		}
		return $newsArray;
	}

	private function getAllNews(){
		$newsArray = array();
		$results = $this->news->getAllNews();
		$this->load->library('../entities/News_entity');
		foreach($results->result('news_entity') as $eachNews){
			$newsArray[$eachNews->id]['title'] = $eachNews->news_title;
			$newsArray[$eachNews->id]['text'] = $eachNews->news_text;
			$newsArray[$eachNews->id]['is_enabled'] = $eachNews->is_enabled;
			$newsArray[$eachNews->id]['created'] = $eachNews->created;
			$newsArray[$eachNews->id]['admin_user_id'] = $eachNews->admin_user_id;
			$newsArray[$eachNews->id]['author'] = $eachNews->author;
		}
		return $newsArray;
	}

	public function updateNewsStatus(){

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			if($this->news->updateNews($this->input->post('id'), 'is_enabled',$this->input->post('is_enabled'))){
				echo returnResponse('success', 'OK', 'jsonizeResponse');
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}else{
			echo 'shit';
		}
	}

}
