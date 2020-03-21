<?php

class News_model extends CI_Model {

	public $id;
	public $admin_user_id;
	public $author; // this is the same as the admin user id but with the name
	public $news_title;
	public $news_text;
	public $created;
	public $is_enabled;

	public function __construct(){
		parent::__construct();
	}

	public function getAllNews(){
		$this->db->select("news.*, au.name as author")->from("news")->join("admin_users as au", "au.id = news.admin_user_id")->order_by("created", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function getAvailableFrontNews(){
		$this->db->select("*")->from("news")->where("is_enabled", "1");
		$results = $this->db->get();

		return $results;
	}

	public function saveNewNews($title, $text){
		$data = array(
			'news_title' => $title,
			'news_text' => $text,
			'is_enabled' => "0",
			'admin_user_id' => 1
		);
		$this->db->insert('news', $data);
		return $this->db->insert_id();
	}

	public function getANews($id){
		$this->db->select("news.*, au.name as author")->from("news")->join("admin_users as au", "au.id = news.admin_user_id")->where("news.id", $id);
		$results = $this->db->get();

		return $results;
	}

	public function updateNews($newsId, $newsField, $newsValue){
		$this->db->trans_start();
		$this->db->set($newsField, $newsValue)->where("id", $newsId)->update("news");
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

}
