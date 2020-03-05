<?php


class Upload extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	public function index(){
		// need to check if there is a user logged in
//		$this->load->library('session');
//		$user_logged = $this->session->userdata('verified');
//		if(isset($user_logged['user_name'])){
			$data['error'] = '';
			$data['form_attr'] = array('class' => 'admin_files');

			// i only load the section selected
			$load_section = $this->load->view('pages/admin/admin-content-templates/content-upload2', $data, true);
//		}else{
			// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
//			redirect('/index.php/admin');
//		}

		echo json_encode($load_section);
	}

//	public function doUpload(){
//		$config['upload_path']          = './uploads/';
//		$config['allowed_types']        = 'gif|jpg|png';
//		$config['max_size']             = 100;
//		$config['max_width']            = 1024;
//		$config['max_height']           = 768;
//
//		$this->load->library('upload', $config);
//
//		$data['form_attr'] = array('class' => 'admin_files');
//
//		var_dump($this->upload->do_upload());
//		if ( ! $this->upload->do_upload('files')) {
//			$data['error'] = 'uhhh: '.$this->upload->display_errors();
////			$error = array('error' => $this->upload->display_errors(), 'class' => 'admin_files');
//			$load_Section = $this->load->view('pages/admin/admin-content-templates/content-upload', $data);
//			echo  json_encode($load_Section);
//		} else {
////			$error = array('upload_data' => $this->upload->data());
//			$data['upload_data'] = 'uhhhh2: '.$this->upload->data();
//			$load_Section = $this->load->view('pages/admin/admin-content-templates/content-upload', $data);
//			echo  json_encode($load_Section);
//		}
//	}

	public function doUpload(){

		$data['form_attr'] = array('class' => 'admin_files');
		$data['error'] = '';
		// Check form submit or not
		var_dump($_POST);

		$countfiles = count($_FILES['files']['name']);
		echo 'count files: '. $countfiles;
		if($this->input->post('upload') != NULL ){echo 'nonononono';

			$data = array();

			// Count total files
			$countfiles = count($_FILES['files']['name']);

			// Looping all files
			for($i=0;$i<$countfiles;$i++){

				if(!empty($_FILES['files']['name'][$i])){

					// Define new $_FILES array - $_FILES['file']
					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					// Set preference
					$config['upload_path'] = 'uploads/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['max_size'] = '5000'; // max_size in kb
					$config['file_name'] = $_FILES['files']['name'][$i];

					//Load upload library
					$this->load->library('upload',$config);

					// File upload
					if($this->upload->do_upload('files')){
						// Get data about the file
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						// Initialize array
						$data['filenames'][] = $filename;
					}
				}

			}

			// load view
//			$load_Section = $this->load->view('pages/admin/admin-content-templates/content-upload',$data);
//			echo  json_encode($load_Section);
		}else{echo 'sisisisisi';

			// load view
//			$load_Section = $this->load->view('pages/admin/admin-content-templates/content-upload', $data);
//			echo  json_encode($load_Section);
		}

	}
}
