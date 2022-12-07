<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Group_model']);
		$logged_in = $this->session->userdata('logged_in');
		if (!$logged_in) {
			redirect('login');
		};
	}
	public function index()
	{
		$data = array(
			'title' => 'Halaman Group',
			'contents' => 'sistem/admin/group',
		);
		$this->load->view('template_new', $data);
	}
	public function getGroup()
	{
		$this->output->set_content_type('application/json');
		$employee = $this->Group_model->getGroup();
		echo json_encode($employee);
	}

	public function saveGroup()
	{
		$input = $this->Group_model->saveGroup();
		if ($input) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['Msg' => 'Terjadi kesalahan!.']);
		}
	}

	public function updateGroup($id)
	{
		$input = $this->Group_model->updateGroup($id);
		if ($input) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['Msg' => 'Terjadi kesalahan!.']);
		}
	}
	public function destroyGroup()
	{
		$id = $_REQUEST['id'];
		$input = $this->Group_model->destroyGroup($id);
		if ($input) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('errorMsg' => 'Some errors occured.'));
		}
	}
}
