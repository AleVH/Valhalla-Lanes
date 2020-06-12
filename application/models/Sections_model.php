<?php

class Sections_model extends CI_Model {

	private $table = 'sections';

	public function __construct(){
		parent::__construct();
	}

	public function getEnabledSections(){
		$this->db->select("*")->from($this->table)->where("is_enabled", "1");
		$results = $this->db->get();

		return $results->result();
	}

	public function getSectionStatus($sectionName){
		$this->db->select("*")->from($this->table)->where("name", $sectionName);
		$results = $this->db->get();

		return $results;
	}

	public function getAllSectionsStatus(){
		$this->db->select("name, is_enabled")->from($this->table);
		$results = $this->db->get();

		return $results;
	}

}
