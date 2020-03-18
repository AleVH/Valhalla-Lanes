<?php


class Sections {

	private $instance;

	public function __construct(){
		$this->instance =& get_instance();
		$this->instance->load->database();
	}

	public function getEnabledSections(){
		$this->instance->db->select("*")->from("sections")->where("is_enabled", "1");
		$results = $this->instance->db->get();

		return $results;
	}

	public function getSectionStatus($sectionName){
		$this->instance->db->select("*")->from("sections")->where("name", $sectionName);
		$results = $this->instance->db->get();

		return $results;
	}

	public function getAllSectionsStatus(){
		$this->instance->db->select("name, is_enabled")->from("sections");
		$results = $this->instance->db->get();

		return $results;
	}
}
