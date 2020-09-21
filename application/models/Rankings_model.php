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

	public function updateRankingStatus($rank_id, $rank_status){
		$this->db->trans_start();
		$this->db->set('is_enabled', $rank_status)->set('updated', 'NOW()', false)->where("id", $rank_id)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	public function updateRanking($rank_id, array $rank_new_details){
		if(!is_array($rank_new_details)){
			return '$rank_new_details must be an array';
		}
		$this->db->trans_start();

		$this->db->set($rank_new_details)->where('id', $rank_id)->update($this->table);

		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function getRankingTops($rank_id){
		$this->db->select("tops")->from($this->table)->where("id", $rank_id);
		$result = $this->db->get();

		return $result;
	}

	public function getActiveRankings(){
		$this->db->select('r.id, r.title, r.tops, r.start_date, r.end_date, rr.player_score, rr.player_name_display, u.name, u.lastname, u.nickname')->from($this->table." as r")->join("rankings_results as rr", "r.id = rr.ranking_id")->join("users as u", "rr.users_id = u.id")->where("r.is_enabled", "1");
		$results = $this->db->get();

		return $results;
	}
}
