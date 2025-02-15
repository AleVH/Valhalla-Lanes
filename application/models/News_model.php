<?php

class News_model extends CI_Model {

	private $table = 'news'; // name of the table of this mode

	public function __construct(){
		parent::__construct();
	}

	public function getAllNews(){
		$this->db->select("news.*, au.name as author")->from($this->table)->join("admin_users as au", "au.id = news.admin_user_id")->order_by("created", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function getAvailableFrontNews(){
		$this->db->select("*")->from($this->table)->where("is_enabled", "1")->order_by("created", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function saveNewNews($title, $text){
		$user_logged = $this->session->userdata('verified');
		$data = array(
			'news_title' => $title,
			'news_text' => $text,
			'is_enabled' => "0",
			'admin_user_id' => $user_logged['user_id']
		);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function getANews($id){
		$this->db->select("news.*, au.name as author")->from($this->table)->join("admin_users as au", "au.id = news.admin_user_id")->where("news.id", $id);
		$results = $this->db->get();

		return $results;
	}

	public function updateNews($newsId, $newsField, $newsValue){
		$this->db->trans_start();
		$this->db->set($newsField, $newsValue)->where("id", $newsId)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

}
