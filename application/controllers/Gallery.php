<?php

require __DIR__."/Services/Calendar/Calendar.php";

class Gallery extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('../entities/Menu_sections_entity');
	}

    public function index(){
        // in case somebody accidentally deletes the page
        if ( ! file_exists(APPPATH.'views/pages/content-templates/content-gallery.php'))
        {
            // Whoops, we don't have a page for that!
//            show_404();
            return "fuck...";
        }
        $this->load->library('image_lib');
		$this->load->helper('directory');

        $map_uploads = directory_map('./assets/media/gallery');

//		$images = scandir('./assets/media/gallery/');

//        $config['image_library'] = 'gd2';
//        $config['source_image'] = '/assets/media/gallery/1.jpg';
//        $config['create_thumb'] = TRUE;
//        $config['maintain_ratio'] = TRUE;
//        $config['width'] = 75;
//        $config['height'] = 50;

//        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
		$data['gallery'] = $map_uploads;

        if($this->input->is_ajax_request()){
        	$this->load->helpers('response');
        	$load_section = $this->load->view('pages/content-templates/content-gallery', $data, true);
        	echo returnResponse('success', $load_section, 'jsonizeResponse');
		}else{
			// check sections
			$sections = new Menu_sectionsmodel();
			$results = $this->menu_sections->getEnabledMenuSections();
			$data['menu'] = array();
			if(count($results->result_array()) > 0){
				// if for whatever reason all menu sections where
				foreach ($results->result('menu_sections_model') as $section) {
					$data['menu'][$section->name] = $section->is_enabled;
				}
			}

			$data['title'] = 'Gallery'; // Capitalize the first letter
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

	/**
	 * This method is to get the details of the calendar
	 * @return array
	 */
	private function getCalendarDetails(){
		$calendar = new Calendar();
		$calendar_array = array(
			"month_name" => $calendar->getMonthName(),
			"month_length" => $calendar->getMonthLength(),
			"month_first_day" => $calendar->getFirstDay(),
			"month_last_day" => $calendar->getLastDay(),
			"today" => $calendar->getDate()
		);
		return $calendar_array;
	}

}
