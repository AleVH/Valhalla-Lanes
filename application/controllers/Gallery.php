<?php


class Gallery extends CI_Controller {

    public function index(){
        // in case somebody accidentally deletes the page
        if ( ! file_exists(APPPATH.'views/pages/gallery.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }
//        $this->load->library('image_lib');

        $config['image_library'] = 'gd2';
        $config['source_image'] = '/assets/images/gallery/1.jpg';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 75;
        $config['height'] = 50;

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();


        $data['title'] = 'Gallery';
        $data['image'] = $config;

        $this->load->helper('url');
        $this->load->view('templates/header', $data);
        $this->load->view('pages/gallery', $data);
        $this->load->view('templates/footer', $data);
    }
}