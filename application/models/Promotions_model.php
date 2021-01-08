<?php

class Promotions_model extends CI_Model {

	private $table = 'promotions'; // why not protected?

	public function __construct() {
		parent::__construct();
	}

	public function getAllPromotions(){
		$this->db->select("*")->from($this->table);
		$results = $this->db->get();

		return $results;
	}

	public function getAllPromotionsWithExtras(){
		$this->db->select("promotions.*, au.name as author")->from($this->table)->join("admin_users as au", "au.id = promotions.admin_user_id")->order_by("promotions.created", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function createPromotion($title, $message, $format, $start = null, $end = null){
		// format can be null, so you need to set up the default value in the database. delete this when it's done
		$user_logged = $this->session->userdata('verified');
		$data = array(
			'title' => $title,
			'message' => $message,
			'promo_format' => $format, // this need to be in json format
			'start_date' => $start,
			'end_date' => $end,
			'admin_user_id' => $user_logged['user_id']
		);

		$this->db->insert($this->table, $data);
//		echo "QUERY:" . $this->db->set($data)->get_compiled_insert($this->table);

		return $this->db->insert_id();
	}

	/**
	 * this method gets the promos that have a start date in a specific month name, i.e. 'November'
	 * @param $month
	 * @return mixed
	 */
	public function getActivePromotionsForMonth($month){
		$this->db->select("*")->from($this->table)->where("monthname(`start_date`)", $month);
		$results = $this->db->get();

		return $results;
	}

}
