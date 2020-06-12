<?php
/**
 * This class should go in services or something like that since it is not a controller
 */

require APPPATH."controllers/Backend/Admin.php";

class Imagerender extends Admin {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * this method is to display images that are protected and should not be display everywhere
	 * @param $file
	 */
	public function renderProtectedImage($file){ //echo 'checkpoint image render class';

		// there should be an instance before rendering the image to check there is a session active, otherwise would be the same as putting the images in public
		// need to check if there is a user logged in
//		$user_logged = $this->session->userdata('verified');
//
//		if(isset($user_logged['user_name'])){
//
//			var_dump($user_logged["user_name"]);

			if(($image = file_get_contents('uploads/'.$file)) === FALSE)
				show_404();

			// choose the right mime type
			$mimeType = 'image/jpeg';

			$this->output
				->set_status_header(200)
				->set_content_type($mimeType)
				->set_output($image)
				->_display();
//		}

	}

	/**
	 * This method is to find the image extension so we can send the correct headers
	 * @param $filenameWithExtension
	 */
	private function checkImageExtension($filenameWithExtension){

	}

}
