<?php


class Rankings_model extends CI_Model {

	private $table = 'rankings';

	public function __construct(){
		parent::__construct();
	}

	public function getAllRanks(){
		$this->db->select("rankings.*, au.name as author")->from($this->table)->join("admin_users as au", "au.id = rankings.admin_user_id")->order_by("created", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function saveNewRanking($title, $tops, $start, $end = null){
		$data = array(
			'title' => $title,
			'is_enabled' => "0",
			'admin_user_id' => 1,
			'tops' => $tops,
			'start_date' => $start,
			'end_date' => $end
		);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function getARanking($id){
		$this->db->select("rankings.*, au.name as author")->from($this->table)->join("admin_users as au", "au.id = rankings.admin_user_id")->where("rankings.id", $id);
		$results = $this->db->get();

		return $results;
	}

	public function updateRankingStatus($rankId, $rankStatus){
		$this->db->trans_start();
		$this->db->set('is_enabled', $rankStatus)->set('updated', 'NOW()', false)->where("id", $rankId)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}
}
