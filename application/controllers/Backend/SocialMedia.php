<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Admin/Sections.php";

//class SocialMedia extends CI_Controller {
class SocialMedia extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->library('../models/Sections');
	}

	public function index(){

		$sections = new Sections();
		$results = $sections->getSectionStatus('social_media');
		$section = $results->row(0,'Sections');

		$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
		$data['disabled'] = ($section->is_enabled)?'':'disabled';

		// need to check if there is a user logged in
//		$this->load->library('session');
//		$user_logged = $this->session->userdata('verified');
//		if(isset($user_logged['user_name'])){
		$data['error'] = '';

		// i only load the section selected
		$load_section = $this->load->view('pages/admin/admin-content-templates/content-social-media', $data, true);
//		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
//			redirect('/admin');
//		}

		echo json_encode($load_section);
	}

}
