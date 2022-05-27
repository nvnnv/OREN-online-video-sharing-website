<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		// $this->load->view('style_sheet');
		// $this->load->view('header');
		// $this->load->view('index');
		// $this->load->view('footer');
		$this->load->view('pic_resize');
	}
}
