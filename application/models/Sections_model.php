<?php

class Sections_model extends CI_Model {

	public $id;
	public $admin_user_id;
	public $name;
	public $created;
	public $updated;
	public $is_enabled;

	public function __construct(){
		parent::__construct();
	}

	public function getEnabledSections(){
		$this->db->select("*")->from("sections")->where("is_enabled", "1");
		$results = $this->db->get();

		return $results->result();
	}

	public function getSectionStatus($sectionName){
		$this->db->select("*")->from("sections")->where("name", $sectionName);
		$results = $this->db->get();

		return $results;
	}

	public function getAllSectionsStatus(){
		$this->db->select("name, is_enabled")->from("sections");
		$results = $this->db->get();

		return $results;
	}

}
