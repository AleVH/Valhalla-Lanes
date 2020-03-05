<?php

require APPPATH."controllers/Services/Calendar/Calendar.php";

class Dashboard extends CI_Controller {

	/**
	 * this method will load the default view in the admin dashboard which is the metrics
	 */
	public function index(){
//		$this->load->library('session');
//		$user_logged = $this->session->userdata('verified');
//		if(isset($user_logged['user_name'])){
			// then i check if it's an ajax request, if it's not means the user just logged in
		if($this->input->is_ajax_request()){
			$load_section = $this->load->view('pages/admin/admin-content-templates/content-metrics', null, true);
			echo json_encode($load_section);
		}else{
			// this is when it loads the whole page, coming from the login area
			$todays = new Calendar();

			$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
			$data['styles'] = 'admin_cms_styles';

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
//			redirect('/index.php/admin');
//		}
	}
}
