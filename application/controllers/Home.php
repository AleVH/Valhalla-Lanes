<?php


class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('menu_sections_model', 'menu_sections', true);
		$this->load->library('../entities/Menu_sections_entity');
		$this->load->model('news_model', 'news', true);
		$this->load->model('promotions_model', 'promotions', true);
	}

	public function index(){
		// in case somebody accidentally deletes the page
		if ( ! file_exists(APPPATH.'views/pages/content-templates/content-home.php'))
		{
			// Whoops, we don't have a page for that!
            show_404();
		}

		$data['news'] = $this->getAvailableNews();

		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$load_section = $this->load->view('pages/content-templates/content-home', $data, true);
			echo returnResponse('success', $load_section, 'jsonizeResponse');
		}else{
			// check sections
			$results = $this->menu_sections->getEnabledMenuSections();
			$data['menu'] = array();
			if(count($results->result_array()) > 0){
				// if for whatever reason all menu sections where
				foreach ($results->result('menu_sections_model') as $section) {
					$data['menu'][$section->name] = $section->is_enabled;
				}
			}

			$data['title'] = 'Home'; // Capitalize the first letter
			$data['styles'] = "styles";
			$data['marquee'] = true;
			$data['calendar'] = $this->getCalendarDetails();
			$data['facebook'] = true;
			$data['instagram'] = false;

			$this->load->helper('url');
			$this->load->view('templates/head', $data);
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('templates/body-start', $data);
			$this->load->view('templates/body-content-start', $data);
			$this->load->view('pages/content-templates/content-gallery', $data);
			$this->load->view('templates/body-content-end', $data);
			$this->load->view('templates/body-sidebar', $data);
			$this->load->view('templates/body-end', $data);
			$this->load->view('templates/footer', $data);
		}

//		$data['gallery'] = $map_uploads;
//
//		$data['title'] = 'Gallery';
//
//		$this->load->helper('url');
//        $this->load->view('templates/header', $data);
//        $this->load->view('pages/gallery', $data);
//        $this->load->view('templates/footer', $data);
	}

	private function getAvailableNews(){
		$newsArray = array();
		$results = $this->news->getAvailableFrontNews();
		$this->load->library('../entities/News_entity');
		foreach ($results->result('news_entity')  as $eachNews) {
			$newsArray[$eachNews->id]['title'] = $eachNews->news_title;
			$newsArray[$eachNews->id]['text'] = $eachNews->news_text;
			$newsArray[$eachNews->id]['is_enabled'] = $eachNews->is_enabled;
			$newsArray[$eachNews->id]['created'] = $eachNews->created;
			$newsArray[$eachNews->id]['author'] = $eachNews->admin_user_id;
		}
		return $newsArray;
	}

	/**
	 * This method gets the promo that hisplays in the front. right now it can only display one promo
	 * at the time, this should be developed to be able to show multiple if necessary
	 */
	private function getPromo(){
		$promoDetailsArray = array();
	}

}
