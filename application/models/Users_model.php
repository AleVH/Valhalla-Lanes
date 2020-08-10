<?php


class Users_model extends CI_Model {

	private $table = 'users';

	public function __construct(){
		parent::__construct();
	}

	public function getAllUsers(){
		$this->db->select("*")->from($this->table)->order_by("id", "asc");
		$results = $this->db->get();

		return $results;
	}

	public function getUsersByField($field, $value){
		$this->db->select("*")->from($this->table)->where($field, $value);
		$results = $this->db->get();

		return $results;
	}

	public function getUserIdByNameAndLastname($user_name, $user_lastname){
		$constraints = array(
			'name' => $user_name,
			'lastname' => $user_lastname
		);
		$this->db->select('id')->from($this->table)->where($constraints);
		$results = $this->db->get();
		return $results;
	}

	public function saveNewUser($name, $lastname, $nickname){
		$this->load->helper('dberror');
		$data = array(
			'name' => $name,
			'lastname' => $lastname,
			'nickname' => $nickname
		);
		if($this->db->insert($this->table, $data)){
			$results = array(
				'status' => 'success',
				'message' => $this->db->insert_id()
			);
		}else{
			$results = array(
				'status' => 'error',
				'message' => standardisedMessage($this->db->error()['message'])
			);
		}

		return $results;
	}

	public function deleteUserById($id){
		$this->db->trans_start();
		$this->db->where("id", $id)->delete($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	/**
	 * This method is to retrieve the data to build dropdowns that involve users data such as name, surname and nickname
	 * @param $field
	 * @param array|null $controlField
	 * @param array|null $controlValue
	 * @return mixed
	 */
	public function getFieldValues($field, array $controlField = null, array $controlValue = null){
		// this bit is to be able to search all names or then surnames based on an existing name, or a nickname based on the
		if($controlField === null && $controlValue === null){
			$constraintsArray = array(1 => 1);
		}else{
			$constraintsArray = array_combine($controlField, $controlValue);
		}
		$this->db->select($field)->from($this->table)->where($constraintsArray)->order_by($field, "asc");
		$results = $this->db->get();

		return $results;
	}

}
