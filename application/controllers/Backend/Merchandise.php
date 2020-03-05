<?php


class Merchandise extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url'));
	}

	public function index(){
		// need to check if there is a user logged in
//		$this->load->library('session');
//		$user_logged = $this->session->userdata('verified');
//		if(isset($user_logged['user_name'])){
		$data['error'] = '';

		// i only load the section selected
		$load_section = $this->load->view('pages/admin/admin-content-templates/content-merchandise', $data, true);
//		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
//			redirect('/index.php/admin');
//		}

		echo json_encode($load_section);
	}

}
