<?php

class News_entity {

	public $id;
	public $admin_user_id;
	public $author; // this is the same as the admin user id but with the name
	public $news_title;
	public $news_text;
	public $created;
	public $is_enabled;

}
