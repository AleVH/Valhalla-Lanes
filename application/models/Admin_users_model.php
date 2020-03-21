<?php


class Admin_users_model extends CI_Model {

	public $id;
	public $name;
	public $lastname;
	public $email;
	public $created;
	public $updated;
	public $status;

	public function __construct(){
		parent::__construct();
	}

}
