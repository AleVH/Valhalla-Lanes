<?php

require __DIR__."/Services/Calendar/Calendar.php";
require APPPATH."controllers/Services/Admin/Sections.php";
//use Services\Calendar;

class Pages extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('../models/Sections');
	}

    public function view($page = 'home'){
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        // check sections
		$sections = new \Sections();
		$results = $sections->getAllSectionsStatus();
		foreach ($results->result('Sections') as $section) {
			$data['menu'][$section->name] = $section->is_enabled;
		}


		$data['title'] = ucfirst($page); // Capitalize the first letter
		$data['styles'] = "styles";
		$data['marquee'] = true;
//var_dump($data);
        $this->load->helper('url');
        $this->load->view('templates/head', $data);
        $this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('templates/body', $data);
//        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }

	/**
	 * this is to test the new layout
	 */
    public function test(){echo "TEST";

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
