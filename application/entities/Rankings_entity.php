<?php


class Rankings_entity {

	public $id;
	public $title;
	public $tops;
	public $updated;
	public $created;
	public $is_enabled;
	public $admin_user_id;
	public $author; // this is the same as the admin user id but with the name
	public $start_date;
	public $end_date;

}
