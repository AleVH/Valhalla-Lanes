<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";
require APPPATH."controllers/Services/Admin/Sections.php";

//class Metrics extends CI_Controller {
class Metrics extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->library('../models/Sections');
	}

	/**
	 * this method will load the default view in the admin dashboard which is the metrics
	 */
	public function index(){

		$sections = new Sections();
		$results = $sections->getSectionStatus('metrics');
		$section = $results->row(0,'Sections');

		$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
		$data['disabled'] = ($section->is_enabled)?'':'disabled';

//		echo 'test1: '.$section->name."\n<br>";
//		echo 'test2: '.$section->created."\n<br>";
//		echo 'test3: '.$section->is_enabled."\n<br>";
//
//		echo "<pre>";
//		var_dump($results->result_array());
//		echo "</pre>";

//		$this->load->library('session');
//		$user_logged = $this->session->userdata('verified');
//		if(isset($user_logged['user_name'])){
			// then i check if it's an ajax request, if it's not means the user just logged in
		if($this->input->is_ajax_request()){
			$load_section = $this->load->view('pages/admin/admin-content-templates/content-metrics', $data, true);
			echo json_encode($load_section);
		}else{
			// this is when it loads the whole page, coming from the login area
			$todays = new Calendar();

			$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
			$data['styles'] = 'admin_cms_styles'; // load styles
			$data['section'] = 'Metrics';

			$data['admin_logged'] = $this->user_logged;

			$this->load->view('pages/admin/admin-head', $data);
			$this->load->view('pages/admin/admin-header', $data);
			$this->load->view('pages/admin/admin-body-begin', $data);
			$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
			$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
			$this->load->view('pages/admin/admin-content-templates/content-metrics', $data);
			$this->load->view('pages/admin/admin-content/admin-content-end', $data);
			$this->load->view('pages/admin/admin-body-end', $data);
			$this->load->view('pages/admin/admin-footer', $data);
		}
//		}else{
//			echo 'need more checking';
//			redirect('/admin');
//		}
	}
}
