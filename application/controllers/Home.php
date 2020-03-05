<?php


class Home extends CI_Controller {
	public function index(){
		if ( ! file_exists(APPPATH.'views/pages/home.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
echo "HOME";
		$data['title'] = 'Home';
		$data['styles'] = 'styles';
		$data['marquee'] = 'testing marquee - huge promotions!!';

		$this->load->helper('url');
		$this->load->view('templates/head', $data);
		$this->load->view('templates/header', $data);
		$this->load->view('pages/home', $data);
		$this->load->view('templates/footer', $data);
	}

}
