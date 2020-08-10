<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class Users extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('users_model', 'users', true);
	}

	/**
	 * this method will load the users section in the admin dashboard
	 */
	public function index(){

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			$results = $this->sections->getSectionStatus('users');
			$section = $results->row(0,'sections_entity');

			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['error'] = '';
			$data['form_attr_1'] = array('class' => 'admin_users-create');
			$data['form_attr_2'] = array('class' => 'admin_users-edit');
			$data['users'] = $this->getAllUsers();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-users', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Users';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $user_logged['user_name'];

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-users', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
			// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}

	}

	public function getAllUsers(){
		$usersArray = array();
		$results = $this->users->getAllUsers();
		$this->load->library('../entities/Users_entity');
		foreach($results->result('users_entity') as $eachUser){
			$usersArray[$eachUser->id]['name'] = $eachUser->name;
			$usersArray[$eachUser->id]['lastname'] = $eachUser->lastname;
			$usersArray[$eachUser->id]['nickname'] = $eachUser->nickname;
		}
		return $usersArray;
	}

	public function saveUser(){
		if($this->input->is_ajax_request()){
			$this->load->helper('response');
			// simple sanitation
			$name = filter_var($this->input->post('user-name'), FILTER_SANITIZE_STRING);
			$lastname = filter_var($this->input->post('user-lastname'), FILTER_SANITIZE_STRING);
			$nickname = filter_var($this->input->post('user-nickname'), FILTER_SANITIZE_STRING);
			// prevent from saving users without name and surename
			if(!empty($name) && !empty($lastname)){
				$result = $this->users->saveNewUser($name, $lastname, $nickname);
				if($result['status'] === 'success'){
					$this->load->library('../entities/Users_entity');
					$response = $this->users->getUsersByField('id', $result['message']);
					echo returnResponse('success', $response->row(0, 'users_entity'), 'jsonizeResponse');
				}else{
					echo returnResponse('error', $result['message'], 'jsonizeResponse');
				}
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}
	}

	public function deleteUser(){
		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$userId = filter_var($this->input->post('user_id'), FILTER_SANITIZE_NUMBER_INT);
			if(!empty($userId)){
				if($this->users->deleteUserById($userId)){
					echo returnResponse('success', 'OK', 'jsonizeResponse');
				}else{
					echo returnResponse('error', 'ERROR', 'jsonizeResponse');
				}
			}
		}
	}

	/**
	 * This method gathers the data to build the dropdowns that involves users data such as name, lastname and nickname
	 */
	public function dropDowns(){
		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$dropDownField = filter_var($this->input->post('field'), FILTER_SANITIZE_STRING);

			// the control field and values is in case the dropdown field value depends on an existing value, i.e. the lastname depends on the name chosen and so on
			$controlValue = $controlField = null;
			if($this->input->post('control_field') && $this->input->post('control_value')){
				$controlField = array();
				$controlValue = array();
				if(is_array($this->input->post('control_field'))){
					foreach ($this->input->post('control_field') as $eachField){
						$controlField[] = filter_var($eachField, FILTER_SANITIZE_STRING);
					}
					foreach ($this->input->post('control_value') as $eachValue){
						$controlValue[] = filter_var($eachValue, FILTER_SANITIZE_STRING);
					}
				}else{
					$controlField[] = filter_var($this->input->post('control_field'), FILTER_SANITIZE_STRING);
					$controlValue[] = filter_var($this->input->post('control_value'), FILTER_SANITIZE_STRING);
				}
			}

			$results = $this->users->getFieldValues($dropDownField, $controlField, $controlValue);

			if($results){
				$dropDownArray = array();
				foreach ($results->result() as $eachFieldValue){
					if(!empty($eachFieldValue->{$dropDownField})){ // this if is in case the search returns no values, to avoid creating an array with only 1 entry and an empty value
						$dropDownArray[] = $eachFieldValue->{$dropDownField};
					}
				}
				echo returnResponse('success', $dropDownArray, 'jsonizeResponse');
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}
	}

}
