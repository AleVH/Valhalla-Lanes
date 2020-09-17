<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

/**
 * This class uploads files, images and videos but could be anything. It will save those files in uploads
 * or a subfolder. This folder is NOT accessible from outside (chmod 700 or htaccess if it is an apache server)
 * Class Upload
 */
class Upload extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('images_model', 'images', true);
	}

	/**
	 * this method will load the upload section in the admin dashboard
	 */
	public function index(){
		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');

		if(isset($user_logged['user_name'])){
			// this bit is to check if the sections is enabled or disabled
			$results = $this->sections->getSectionStatus('upload');
			$section = $results->row(0,'sections_entity');

			$data['error'] = '';
			$data['form_attr'] = array('class' => 'admin_files');
			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['admin_logged'] = $user_logged['user_name'];

			// get existing media
			$data['existing'] = $this->getExistingMedia();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				header('Content-Type: image/jpeg');
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-upload', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Upload';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $this->user_logged;

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-upload', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
			// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}
	}

//	public function getExistingMedia(){
//		// load the right helper for the task
//		$this->load->helper('directory');
//		$upload_folder = directory_map('./uploads/');
//
//		return $upload_folder;
//
//	}

	public function getExistingMedia(){
		$existingImagesArray = array();
		$existingImages = $this->images->getUploadedImages();

		// change for images and all that shit
		$this->load->library('../entities/Images_entity');
		foreach ($existingImages->result('images_entity') as $existingImagesDetails){
			$existingImagesArray[$existingImagesDetails->id]['filename'] = $existingImagesDetails->filename;
			$existingImagesArray[$existingImagesDetails->id]['in_gallery'] = $existingImagesDetails->in_gallery;
			$existingImagesArray[$existingImagesDetails->id]['is_enabled'] = $existingImagesDetails->is_enabled;
			$existingImagesArray[$existingImagesDetails->id]['image_order'] = $existingImagesDetails->image_order;
		}
		return $existingImagesArray;
	}

	public function doUpload(){

		$this->load->helpers('response');

		$data['form_attr'] = array('class' => 'admin_files');
		$data['error'] = '';

		// Check form submit or not
//		$countfiles = count($_FILES['files']['name']);
//		echo 'count files: '. $countfiles;
		if($this->input->post('upload') != NULL ){

			$data = array();

			// Count total files
			$countfiles = count($_FILES['files']['name']);

			// Looping all files
			for($i = 0; $i < $countfiles; $i++){

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
					$this->load->library('upload', $config);

					// File upload - the 'file' between brackets makes reference to the 'file' in $_FILES['file'] so needs to match and can't be plural
//					try{
//						if($this->upload->do_upload('file')){
//
//							// Get data about the file
//							$uploadData = $this->upload->data();
//							$filename = $uploadData['file_name'];
//
//							// Initialize array
//							$data['filenames'][] = $filename;
//
//							$this->images->saveImageDetails($filename);
//
//						}
//					}catch (Exception $e){
//						echo 'code: '.$e->getCode().' - message'.$e->getMessage();
//					}


					if($this->upload->do_upload('file')){

						// Get data about the file
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						// Initialize array
						$data['filenames'][] = $filename;

//						echo 'filename1: '.$filename; // this throws back the filename with underscores instead of spaces
//						echo 'filename2: '.$_FILES['files']['name'][$i];
//						echo 'filename3: '.$_FILES['file']['name'];
						$this->images->saveImageDetails($filename);
					}else{
						// this displays the error message. needs to be done properly, otherwise in the front of the cms the image doesn't get uploaded but nobody knows why
						$error = array('error' => $this->upload->display_errors());
						var_dump($error);
					}
				}

			}

			// the error and success msgs need to be done properly <-------------<<<
			echo returnResponse('success', 'OK', 'jsonizeResponse');
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}

	}

	/**
	 * this method is to delete an image. that means delete the record in the database and delete the file in the server
	 */
	public function deleteImage(){
		$this->load->helper(array('response', 'input', 'security'));
		$filename = sanitize_filename($this->input->post('filename'));
		$imageId = sanitizeInteger($this->input->post('id'));

		// first delete the record in database
		if($this->images->deleteImageById($imageId)){
			// if it goes ok, delete the file

			$deleteFile = realpath("./uploads/".$filename);
			if(unlink($deleteFile)){
				// if all good, return success answer
				echo returnResponse('success', 'OK', 'jsonizeResponse');
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}

	}

	public function togglePublish(){
		$this->load->helper(array('response', 'input'));
		$imageId = sanitizeInteger($this->input->post('id'));
		$imageStatus = sanitizeInteger($this->input->post('publish_status'));

		if($this->images->toggleImageStatus($imageId, $imageStatus)){
			echo returnResponse('success', 'OK', 'jsonizeResponse');
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}

	}

	/**
	 * this method is to rename image files. DOES NOT include the extension (jpg, jpeg, gif, png, etc)
	 */
	public function renameImage(){
		$this->load->helper(array('response', 'input'));

		// if the filename is empty do nothing
		if(empty($this->input->post('new_filename'))){
			return false;
		}

		$imageId = sanitizeInteger($this->input->post('id'));
		$newFilename = sanitizeStringRemoveAllNonAlphanumeric($this->input->post('new_filename'));
		$currentFilenameResult = $this->images->getImageFilename($imageId);

		// the next lines are to get the file extension
		$extension = substr($currentFilenameResult->row()->filename, strpos($currentFilenameResult->row()->filename, "."));


		// check if that name is already used
		$checkDuplicates = $this->images->checkDuplicatedFilename($newFilename.$extension);
		if(!$checkDuplicates->row()){
			// this renames the actual file
			rename("./uploads/".$currentFilenameResult->row()->filename, "./uploads/".$newFilename.$extension);

			if($this->images->renameImageFile($imageId, $newFilename.$extension)){
				echo returnResponse('success', 'OK', 'jsonizeResponse');
			}else{
				echo returnResponse('error', 'ERROR', 'jsonizeResponse');
			}
		}else{
			echo returnResponse('error', 'Duplicated filename', 'jsonizeResponse');
		}



	}

	/**
	 * this method is to keep the table images updated with all the uploaded images
	 */
	public function updateImageDatabase(){

	}
}
