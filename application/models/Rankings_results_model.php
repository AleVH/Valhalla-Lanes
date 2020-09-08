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
		$this->db->select("rr.id as rank_result_id, rr.player_score, rr.player_name_display, u.id as user_id, u.name, u.lastname, u.nickname")->from($this->table." as rr")->join("users as u", "u.id = rr.users_id")->where("rr.ranking_id", $rankResultId);

		$results = $this->db->get();

		return $results;
	}

	public function getRankResultsRankDetailsByRankResultId($rankResultId){
		$this->db->select("r.id, r.title, r.tops, r.start_date, r.end_date")->from($this->table." as rr")->join("rankings as r", "r.id = rr.ranking_id")->where("rr.ranking_id", $rankResultId);

		$results = $this->db->get();

		return $results;
	}

	public function updateRankResultsBatch(array $update_batch, $where){
		if(!is_array($update_batch)){
			return '$update_batch must be an array';
		}

		$this->db->trans_start();
		$this->db->update_batch($this->table, $update_batch, $where);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function insertRankResultsBatch(array $insert_batch){
		if(!is_array($insert_batch)){
			return '$insert_batch must be an array';
		}

		$this->db->trans_start();
		$this->db->insert_batch($this->table, $insert_batch);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

	public function deleteRankResultsBatch(array $where_rank_result_id_not, $rank_id){
		if(!is_array($where_rank_result_id_not)){
			return '$where_rank_result_id_not must be an array';
		}

		$this->db->trans_start();
		$this->db->where_not_in('id', $where_rank_result_id_not);
		$this->db->where('ranking_id', $rank_id);
		$this->db->delete($this->table);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			return false;
		}else{
			return true;
		}
	}

}
