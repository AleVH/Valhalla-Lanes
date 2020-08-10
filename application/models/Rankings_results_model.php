<?php


class Rankings_results_model extends CI_Model {

	private $table = "rankings_results";

	public function __construct(){
		parent::__construct();
	}

	public function getAllRankingsResults(){
		$this->db->select("*")->from($this->table);
		$results = $this->db->get();

		return $results;
	}

	public function getRankResultsByRankId($rankResultId){
		$this->db->select("rr.player_score, u.name, u.lastname, u.nickname, r.title, r.start_date, r.end_date")->from($this->table." as rr")->join("users as u", "u.id = rr.users_id")->join("rankings as r", "r.id = rr.ranking_id")->where("rr.ranking_id", $rankResultId);
//
//		echo $this->db->select("rr.player_score, u.name, u.lastname, u.nickname, r.title, r.start_date, r.end_date")->from($this->table." as rr")->join("users as u", "u.id = rr.users_id")->join("rankings as r", "r.id = rr.ranking_id")->where("rr.ranking_id", $rankResultId)->get_compiled_select();
//
		$results = $this->db->get();

		return $results;
	}

	public function getRankResultsUsersDetailsByRankResultId($rankResultId){
		$this->db->select("rr.player_score, rr.player_name_display, u.id, u.name, u.lastname, u.nickname")->from($this->table." as rr")->join("users as u", "u.id = rr.users_id")->where("rr.ranking_id", $rankResultId);

		$results = $this->db->get();

		return $results;
	}

	public function getRankResultsRankDetailsByRankResultId($rankResultId){
		$this->db->select("r.id, r.title, r.tops, r.start_date, r.end_date")->from($this->table." as rr")->join("rankings as r", "r.id = rr.ranking_id")->where("rr.ranking_id", $rankResultId);

		$results = $this->db->get();

		return $results;
	}

	public function updateRankResults(){

	}

	public function insertRankResults($new_rank_results){
		$this->db->insert_batch($this->table, $new_rank_results);
	}

}
