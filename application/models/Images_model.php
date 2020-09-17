<?php


class Images_model extends CI_Model {

	private $table = 'images';

	public function __construct(){
		parent::__construct();
	}

	public function saveImageDetails($filename){
		$user_logged = $this->session->userdata('verified');
		$data = array(
			'filename' => $filename,
			'in_gallery' => "0",
			'is_enabled' => "0",
			'admin_user_id' => $user_logged['user_id']
		);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function getGalleryImages(){
		$this->db->select("*")->from($this->table)->where("is_enabled", "1")->order_by("image_order", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function getImageFilename($imageId){
		$this->db->select("filename")->from($this->table)->where("id", $imageId);
		$result = $this->db->get();

		return $result;
	}

	public function getFrontGalleryImages(){
		$this->db->select("*")->from($this->table)->where("in_gallery", "1")->order_by("image_order", "desc");
		$results = $this->db->get();

		return $results;
	}

	public function getUploadedImages(){
		$this->db->select("*")->from($this->table)->order_by("created", "asc");
		$results = $this->db->get();

		return $results;
	}

	public function deleteImageById($imageId){
		$this->db->trans_start();
		$this->db->where("id", $imageId)->delete($this->table);
		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	public function toggleImageStatus($imageId, $imageStatus){
		$this->db->trans_start();
		$this->db->set('is_enabled', $imageStatus)->set('updated', 'NOW()', false)->where("id", $imageId)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	public function toogleImageGalleryStatus($imageId, $imageStatus){
		$this->db->trans_start();
		$this->db->set('in_gallery', $imageStatus)->set('updated', 'NOW()', false)->where("id", $imageId)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	public function renameImageFile($imageId, $newFilename){
		$this->db->trans_start();
		$this->db->set('filename', $newFilename)->set('updated', 'NOW()', false)->where("id", $imageId)->update($this->table);
		$this->db->trans_complete();
		if($this->db->trans_status() === FALSE){
			// generate an error... or use the log_message() function to log your error
			return false;
		}else{
			return true;
		}
	}

	public function checkDuplicatedFilename($filename){
		$this->db->select("*")->from($this->table)->where("filename", $filename);
		$results = $this->db->get();

		return $results;
	}
}
