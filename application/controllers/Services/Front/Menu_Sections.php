<?php


class Menu_Sections {

	private $instance;

	public function __construct(){
		$this->instance =& get_instance();
		$this->instance->load->database();
	}

	public function getEnabledMenuSections(){
		$this->instance->db->select("*")->from("menu_sections")->where("is_enabled", "1");
		$results = $this->instance->db->get();

		return $results;
	}

}
