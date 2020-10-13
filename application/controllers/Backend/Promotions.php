<?php

require APPPATH."controllers/Backend/Admin.php";
require APPPATH."controllers/Services/Calendar/Calendar.php";

class Promotions extends Admin {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('promotions_model', 'promotions', true);
	}

	/**
	 * this method will load the promotions section in the admin dashboard
	 */
	public function index(){

		// need to check if there is a user logged in
		$user_logged = $this->session->userdata('verified');
		if(isset($user_logged['user_name'])){
			$results = $this->sections->getSectionStatus('promotions');
			$section = $results->row(0,'sections_entity');

			$data['form_attr'] = array('class' => 'admin_promotions');
			$data['status'] = ($section->is_enabled)?'Enabled':"Disabled";
			$data['disabled'] = ($section->is_enabled)?'':'disabled';
			$data['calendar'] = $this->getCalendarDetails();
			$data['error'] = '';
			$data['promotions'] = $this->getAllPromos();

			// then i check if it's an ajax request, if it's not means the user just logged in
			if($this->input->is_ajax_request()){
				// i only load the section selected
				$load_section = $this->load->view('pages/admin/admin-content-templates/content-promotions', $data, true);
				echo json_encode($load_section);
			}else{
				// this is when it loads the whole page, coming from the login area
				$todays = new Calendar();

				$data['todays'] = $todays->getDay()." ".$todays->getDate()." ".$todays->getMonthShortName()." ".$todays->getYear();
				$data['styles'] = 'admin_cms_styles'; // load styles
				$data['section'] = 'Promotions';

				$sidebar = $this->sections->getAllSectionsStatus();
				$data['sidebar'] = $sidebar->result_array();

				$data['admin_logged'] = $user_logged['user_name'];

				$this->load->view('pages/admin/admin-head', $data);
				$this->load->view('pages/admin/admin-header', $data);
				$this->load->view('pages/admin/admin-body-begin', $data);
				$this->load->view('pages/admin/admin-content/admin-sidebar', $data);
				$this->load->view('pages/admin/admin-content/admin-content-begin', $data);
				$this->load->view('pages/admin/admin-content-templates/content-promotions', $data);
				$this->load->view('pages/admin/admin-content/admin-content-end', $data);
				$this->load->view('pages/admin/admin-body-end', $data);
				$this->load->view('pages/admin/admin-footer', $data);
			}
		}else{
		// this would mean that somebody is trying to access this url without being logged in, so redirect to log in area
			redirect('/admin');
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

	public function createPromo(){
		if($this->input->is_ajax_request()){
			$this->load->helpers(array('response', 'input'));
//			var_dump($this->input->post());
			// simple sanitation
			$promo_title = sanitizeString($this->input->post("promo-title"));
			$promo_message = sanitizeString($this->input->post("promo-text")); // this needs to have a limit of 255 characters and you need to display a countdown in characters in the admin so jace will understand
			$promo_start = (empty($this->input->post("promo-start"))) ? null : (new DateTime(sanitizeDate($this->input->post("promo-start"))))->format('Y-m-d H:i:s');
			$promo_end = (empty($this->input->post("promo-end"))) ? null : (new DateTime(sanitizeDate($this->input->post('promo-end'))))->format('Y-m-d H:i:s');
			$promo_format_color = $this->input->post("promo-format_color");
			$promo_format_fontsize = sanitizeInteger($this->input->post("promo-format_font-size"));
			$promo_format_speed = sanitizeInteger($this->input->post("promo-format_speed"));

			$promo_format = json_encode(array(
				"color" => $promo_format_color,
				"fontsize" => $promo_format_fontsize."px",
				"speed" => $promo_format_speed
			), JSON_FORCE_OBJECT);

			$result = $this->promotions->createPromotion($promo_title, $promo_message, $promo_format, $promo_start, $promo_end);

			if($result){
				echo returnResponse("success", array("id", $result), "jsonizeResponse");
			}else{
				echo returnResponse("error", "ERROR", "jsonizeResponse");
			}

		}
	}

	public function getAllPromos(){
		$promosArray = array();
		$results = $this->promotions->getAllPromotionsWithExtras();
		$this->load->library("../entities/Promotions_entity");

		foreach($results->result("promotions_entity") as $eachPromo){
			$promosArray[$eachPromo->id]["title"] = $eachPromo->title;
			$promosArray[$eachPromo->id]["message"] = $eachPromo->message;
			$promosArray[$eachPromo->id]["promo_format"] = json_decode($eachPromo->promo_format);
			$promosArray[$eachPromo->id]["start_date"] = $eachPromo->start_date;
			$promosArray[$eachPromo->id]["end_date"] = $eachPromo->end_date;
			$promosArray[$eachPromo->id]["is_enabled"] = $eachPromo->is_enabled;
			$promosArray[$eachPromo->id]["created"] = $eachPromo->created;
			$promosArray[$eachPromo->id]["author"] = $eachPromo->author;
		}

		return $promosArray;//$results->result();
	}

}
