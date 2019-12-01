<?php


class Booking extends CI_Controller {
	public function view(){
		if ( ! file_exists(APPPATH.'views/pages/booking.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = 'Booking'; // Capitalize the first letter

		$this->load->helper('url');
		$this->load->view('templates/header', $data);
		$this->load->view('pages/booking', $data);
		$this->load->view('templates/footer', $data);
	}

	public function test(){
		$this->load->view('pages/admin');
	}
}
