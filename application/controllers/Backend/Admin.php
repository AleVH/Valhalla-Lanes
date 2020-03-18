<?php

//session_start(); //we need to start session in order to access it through CI

Class Admin extends CI_Controller {

	protected $user_logged;

	public function __construct(){
		parent::__construct();
		$this->load->library('../models/Admin_Users');
		$this->load->library('../models/Sections');
		$this->load->library('session');
		$this->user_logged = "Ale";
//		echo 'username: '.$this->session->userdata('name');
//		echo 'something: '.$this->session->userdata('id');
//		$this->load->database();
//		$user_results = $this->db->select("admin_users.id, admin_users.name, admin_users.email, admin_permissions.id as perms, admin_permissions.password ")
//			->from("admin_users")->join("admin_permissions", "admin_permissions.admin_user_id = admin_users.id")
//			->where("admin_users.email = '".$user."' AND admin_permissions.password = '".$password."'");
	}

	public function index(){
		if ( ! file_exists(APPPATH.'views/pages/admin.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['section'] = 'Admin';
		$data['title'] = 'Admin Section';
		$data['styles'] = 'admin_styles';
		$data['admin_logged'] = $this->user_logged;

		// testing database
//		$this->load->database();
//
//		$results = $this->db->query("SELECT * FROM admin_users");
//
//		$this->load->library('../models/Admin_Users');
//		foreach ($results->result('Admin_Users') as $adminUsers){
//			echo "id: ".$adminUsers->id." - name: ".$adminUsers->name." - email: ".$adminUsers->email." - created: ".$adminUsers->created." - status: ".$adminUsers->status."<br>";
//		}
//
//		echo "<pre>";
//		var_dump($results->result_array());
//		echo "</pre>";
//		$this->db->close();

		$this->load->helper('url');
		$this->load->view('templates/head', $data);
		$this->load->view('templates/header', $data);
		$this->load->view('pages/admin', $data);
		$this->load->view('templates/footer', $data);
	}

	public function test(){
		$this->load->view('pages/admin');
	}

	public function login(){
		// first we check if it is an ajax request
		if($this->input->is_ajax_request()){
			// fetch form inputs, this also do sanitation
			$user = $this->input->post('user');
			$password = $this->input->post('password');

			// check with database
//			$this->load->database();

			// for debug
//			echo "QUERY : ".$this->db->select("admin_users.email, admin_permissions.password ")
//				->from("admin_users")->join("admin_permissions", "admin_permissions.admin_user_id = admin_users.id")
//				->where("admin_users.email = '".$user."' AND admin_permissions.password = '".$password."'")->get_compiled_select();

			$this->db->select("admin_users.id, admin_users.name, admin_users.email, admin_permissions.id as perms, admin_permissions.password ")
				->from("admin_users")->join("admin_permissions", "admin_permissions.admin_user_id = admin_users.id")
				->where("admin_users.email = '".$user."' AND admin_permissions.password = '".$password."'");

			$results = $this->db->get();

			$this->load->helpers('response');
//			if(!empty($results->result_array())){
			if(!empty($results->row())){
//				var_dump($results->row());
				$admin_user = $results->row();
				// this means the user exists and all matches
				// so we redirect to the dashboard
				$this->load->library('session');
				$new_admin_login = array(
					'user_id' => $admin_user->id,
					'user_name' => $admin_user->name,
					'user_perms' => $admin_user->perms
				);
				$_SERVER['user_name2'] = 'testing';
//				$this->session->set_userdata($new_admin_login); // this way the data is set permanent

				$session_data = $this->session->userdata('verified');
				$session_data = array(
					'user_id' => $admin_user->id,
					'user_name' => $admin_user->name,
					'user_perms' => $admin_user->perms
				);
				$this->session->set_tempdata('verified', $new_admin_login, 45);
				echo returnResponse('success', 'admin/dashboard', 'jsonizeResponse');
//				redirect('/admin/dashboard');
			}else{
				// this means some credentials are wrong
				// so we send back an error message
//				echo "array vacio<br>";
				echo returnResponse('error', 'Wrong Credentials', 'jsonizeResponse');
			}
		}
	}

//	public function dashboard(){
////		$this->load->library('session');
////		$user_logged = $this->session->userdata('verified');
////		if(isset($user_logged['user_name'])){
//			echo 'this is the dashboard from admin controller';
////			$data['section'] = 'Dashboard';
////			$data['status'] = 'Disabled';
//			$data['styles'] = 'admin_cms_styles';
//			$this->load->view('pages/admin/admin-head', $data);
//			$this->load->view('pages/admin/admin-header', $data);
//			$this->load->view('pages/admin/admin-body-begin', $data);
//			$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
//			$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
//			$this->load->view('pages/admin/admin-content-templates/content-metrics', $data);
//			$this->load->view('pages/admin/admin-content/admin-content-end', $data);
//			$this->load->view('pages/admin/admin-body-end', $data);
//			$this->load->view('pages/admin/admin-footer', $data);
////		}else{
////			echo 'need more checking';
////			redirect('/admin');
////		}
//	}

}
