<?php

class Menu_sections_model extends CI_Model {

	public $id;
	public $admin_user_id;
	public $name;
	public $created;
	public $is_enabled;
	public $section_id;

	public function __construct(){
		parent::__construct();
	}

	public function getAllMenuSectionsStatus(){
		$this->db->select("id, name, is_enabled")->from("menu_sections");
		$results = $this->db->get();

		return $results;
	}

	public function getEnabledMenuSections(){
		$this->db->select("*")->from("menu_sections")->where("is_enabled", "1");
		$results = $this->db->get();

		return $results;
	}

	public function updateMenuSection($menuSectionId, $menuSectionField, $menuSectionValue){
		$this->db->trans_start();
		$this->db->set($menuSectionField, $menuSectionValue)->where("id", $menuSectionId)->update("menu_sections");
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}
}
