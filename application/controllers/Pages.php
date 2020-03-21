<?php

require __DIR__."/Services/Calendar/Calendar.php";
//use Services\Calendar;

class Pages extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('menu_sections_model', 'menu_sections', true);
		$this->load->library('../entities/Menu_sections_entity');
		$this->load->model('news_model', 'news', true);
	}

    public function view($page = 'home'){
        if ( ! file_exists(APPPATH.'views/pages/content-templates/content-'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        // this first bit is for ajax requests
		if($this->input->is_ajax_request()){
			$this->load->helpers('response');
			$load_section = $this->load->view('pages/content-templates/content-'.$page, null, true);
			echo returnResponse('success', $load_section, 'jsonizeResponse');
		}else{
			// check sections
			$results = $this->menu_sections->getEnabledMenuSections();
			$data['menu'] = array();
			if(count($results->result_array()) > 0){
				// if for whatever reason all menu sections where
				foreach ($results->result('menu_sections_entity') as $section) {
					$data['menu'][$section->name] = $section->is_enabled;
				}
			}

			$data['title'] = ucfirst($page); // Capitalize the first letter
			$data['styles'] = "styles";
			$data['marquee'] = true;
			$data['calendar'] = $this->getCalendarDetails();
			$data['facebook'] = true;
			$data['instagram'] = false;
			$data['news'] = $this->getAvailableNews();

			$this->load->helper('url');
			$this->load->view('templates/head', $data);
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('templates/body-start', $data);
			$this->load->view('templates/body-content-start', $data);
			$this->load->view('pages/content-templates/content-'.$page, $data);
			$this->load->view('templates/body-content-end', $data);
			$this->load->view('templates/body-sidebar', $data);
			$this->load->view('templates/body-end', $data);
			$this->load->view('templates/footer', $data);
		}

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

	/**
	 * This method is to update dynamically the content part
	 */
	public function updateContent(){

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
	 * this is to test the new layout
	 */
    public function test(){

    	$test = new Calendar();
    	var_dump($test->getDay());
//		var_dump($test->getMonth());
		var_dump($test->getMonthName());
//		var_dump($test->getYear());
		var_dump($test->getDate());
//		var_dump($test->getMonthLength());
		var_dump($test->getFirstDay());
var_dump(__DIR__);
		$data['calendar']['month_name'] = $test->getMonthName();
		$data['calendar']['month_length'] = $test->getMonthLength();
		$data['calendar']['month_first_day'] = $test->getFirstDay();
		$data['calendar']['month_last_day'] = $test->getLastDay();
		$data['calendar']['today'] = $test->getDate();

		$this->load->view('structure', $data);
    }

}
