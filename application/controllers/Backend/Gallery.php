<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class Gallery extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url'));
		$this->load->model('images_model', 'images', true);
	}

	/**
	 * this method will load the gallery section in the admin dashboard
	 */
	public function index(){

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			$results = $this->sections->getSectionStatus('gallery');
			$section = $results->row(0,'sections_entity');

			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['error'] = '';

			// get images enabled in gallery
			$data['gallery_images'] = $this->getGalleryImages();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-gallery', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Gallery';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $user_logged['user_name'];

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-gallery', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
		}

	}

	public function getGalleryImages(){
		$galleryImagesArray = array();
		$galleryImages = $this->images->getGalleryImages();

		// change for images and all that shit
		$this->load->library('../entities/Images_entity');
		foreach ($galleryImages->result('images_entity') as $galleryImagesDetails){
			// divide the array in images "in gallery" and "not in gallery"
			if($galleryImagesDetails->in_gallery){
				$galleryImagesArray['published'][] = array(
					'id' => $galleryImagesDetails->id,
					'filename' => $galleryImagesDetails->filename,
					'in_gallery' => $galleryImagesDetails->in_gallery,
					'is_enabled' => $galleryImagesDetails->is_enabled,
					'image_order' => $galleryImagesDetails->image_order
				);
			}else{
				$galleryImagesArray['unpublished'][$galleryImagesDetails->id]['filename'] = $galleryImagesDetails->filename;
				$galleryImagesArray['unpublished'][$galleryImagesDetails->id]['in_gallery'] = $galleryImagesDetails->in_gallery;
				$galleryImagesArray['unpublished'][$galleryImagesDetails->id]['is_enabled'] = $galleryImagesDetails->is_enabled;
				$galleryImagesArray['unpublished'][$galleryImagesDetails->id]['image_order'] = $galleryImagesDetails->image_order;
			}
		}
		usort($galleryImagesArray['published'], 'self::sortByImageOrder');
		return $galleryImagesArray;
	}

	/**
	 * this method is to put in order the subarray $galleryImagesArray['published'] so the images
	 * get display in the right order in the gallery section
	 * @param $x
	 * @param $y
	 * @return int
	 */
	private static function sortByImageOrder($x, $y){
		// equal items sort equally
		if ($x['image_order'] === $y['image_order']) {
			return 0;
		}
		// nulls sort after anything else
		else if ($x['image_order'] === null) {
			return 1;
		}
		else if ($y['image_order'] === null) {
			return -1;
		}

		return ($x['image_order'] < $y['image_order']) ? -1 : 1;

	}

	public function toggleImageDisplay(){
		$this->load->helper(array('response', 'input'));
		$imageID = sanitizeInteger($this->input->post('id'));
		$imageGalleryStatus = sanitizeInteger($this->input->post('status'));
		if($this->images->toogleImageGalleryStatus($imageID, $imageGalleryStatus)){
			echo returnResponse('success', 'OK', 'jsonizeResponse');
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}
	}

	public function updateImagePositions(){
		$this->load->helper(array('response', 'input'));
		$updateData = $this->input->post('new_positions');
		if($updateData){
			$updateArray = array();

			foreach($updateData as $eachRow){
				$updateArray[] = array(
					'id' => sanitizeInteger($eachRow['id']),
					'image_order' => sanitizeInteger($eachRow['image_order'])
				);
			}
		}

		if($this->images->updateImagePositions($updateArray, 'id')){
			echo returnResponse('success', 'OK', 'jsonizeResponse');
		}else{
			echo returnResponse('error', 'ERROR', 'jsonizeResponse');
		}
	}

}
