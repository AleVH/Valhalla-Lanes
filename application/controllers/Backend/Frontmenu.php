<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class Frontmenu extends Admin {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->model('menu_sections_model', 'menu_sections', true);
	}

	/**
	 * this method will load the front menu section in the admin dashboard
	 */
	public function index() {

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			$sections_results = $this->sections->getSectionStatus('front_menu');
			$section = $sections_results->row(0, 'sections_entity');

			$data['status'] = ($section->is_enabled) ? 'Enabled' : "Disabled";
			$data['disabled'] = ($section->is_enabled) ? '' : 'disabled';

			$menu_sections_results = $this->menu_sections->getAllMenuSectionsStatus();
			$data['error'] = '';
			$data['frontmenu'] = $menu_sections_results->result_array();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-front-menu', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Front Menu';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $this->user_logged;

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-front-menu', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}
	}

	public function updateMenuSectionStatus(){
		$this->load->helpers('response');

		if($this->menu_sections->updateMenuSection($this->input->post('id'), 'is_enabled',$this->input->post('is_enabled'))){
			echo returnResponse('success', 'OK', 'jsonizeResponse');
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}
	}

}
