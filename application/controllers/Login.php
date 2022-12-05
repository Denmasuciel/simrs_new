<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('pdf');
        //  $this->load->library('upload');
        // $this->load->model('setting_model');
        $this->load->model('app_model');
        // $logged_in = $this->session->userdata('logged_in');
        // if (!$logged_in) {
        //     redirect('login');
        // }else{
        //     redirect('depan');
        // };
    }

	public function index()
	{

		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in) {
			redirect('login');
		} else {
			$d['judul'] = "Log In";
			// $d['prg'] = $this->config->item('prg');
			// $d['web_prg'] = $this->config->item('web_prg');
			// $d['nama_program'] = $this->config->item('nama_program');
			// $d['instansi'] = $this->config->item('instansi');
			// $d['alamat_instansi'] = $this->config->item('alamat_instansi');
			$this->load->view('login', $d);
		}
	}

	public function validasi()
	{
		$u = $this->input->post('username');
		$p = $this->input->post('password');
		$this->app_model->getLoginData($u, $p);
	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');  // <!-- note that
		//header('location:'.base_url().'index.php');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/koperasi.php */