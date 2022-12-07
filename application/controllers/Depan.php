<?php

use function GuzzleHttp\json_encode;

defined('BASEPATH') or exit('No direct script access allowed');

class Depan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Api_model');
		$logged_in = $this->session->userdata('logged_in');
		if (!$logged_in) {
			redirect('login');
		};
	}

	public function index()
	{
		// $logged_in = $this->session->userdata('logged_in');
		// $role = $this->session->userdata('rolecd');
		// $mnside = $this->Api_model->get_menu_sidebar();
		$data = array(
			'title' => 'Halaman Beranda',
			'contents' => 'depan',
			// 'men' => $mnside
		);
		$this->load->view('template_new', $data);
	}


	public function apilistranap()
	{
		// $tgl = '2019-03-11';
		// $tgl1= $this->input->post('tanggal');
		// $tgl = date('Y-m-d');
		echo $this->Api_model->getListRanap();
	}
	public function apilistranapeasyui()
	{
		// $tgl = '2019-03-11';
		// $tgl1= $this->input->post('tanggal');
		// $tgl = date('Y-m-d');
		echo $this->Api_model->getListRanapeasyui();
	}

	public function getdata()
	{
		echo json_encode($this->Api_model->getCustomers());
	}

	public function getmenu()
	{
		// echo json_encode( $this->Api_model->getmenu());
		echo $this->Api_model->get_menu_json();
	}
	public function menusidebar()
	{
		echo $this->Api_model->get_menu_sidebar('admin');
	}
}
