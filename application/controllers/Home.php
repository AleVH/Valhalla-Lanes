<?php


class Home extends CI_Controller {
	public function view(){
		if ( ! file_exists(APPPATH.'views/pages/home.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = 'Home'; // Capitalize the first letter

		$this->load->helper('url');
		$this->load->view('templates/header', $data);
		$this->load->view('pages/home', $data);
		$this->load->view('templates/footer', $data);
	}

	public function test(){
		$this->load->view('pages/admin');
	}
}
