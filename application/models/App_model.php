<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App_Model extends CI_Model
{


	function getAllData($table)
	{
		return $this->db->get($table);
	}

	function getAllDataLimited($table, $limit, $offset)
	{
		return $this->db->get($table, $limit, $offset);
	}

	function getSelectedDataLimited($table, $data, $limit, $offset)
	{
		return $this->db->get_where($table, $data, $limit, $offset);
	}

	//select table
	function getSelectedData($table, $data)
	{
		return $this->db->get_where($table, $data);
	}

	//QUERY table
	function updateData($table, $data, $field_key)
	{
		$this->db->update($table, $data, $field_key);
	}
	function deleteData($table, $data)
	{
		$this->db->delete($table, $data);
	}

	function insertData($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	//Query manual
	function manualQuery($q)
	{
		return $this->db->query($q);
	}



	//Konversi tanggal
	function tgl_sql($date)
	{
		$exp = explode('-', $date);
		if (count($exp) == 3) {
			$date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
		}
		return $date;
	}

	function tgl_str($date)
	{
		$exp = explode('-', $date);
		if (count($exp) == 3) {
			$date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
		}
		return $date;
	}

	function ambilTgl($tgl)
	{
		$exp = explode('-', $tgl);
		$tgl = $exp[2];
		return $tgl;
	}

	function ambilBln2($tgl)
	{
		$exp = explode('-', $tgl);
		$tgl = $exp[1];
		$bln = $this->app_model->getBulan($tgl);
		$hasil = substr($bln, 0, 3);
		return $bln;
		//return $hasil;
	}

	function ambilBln($tgl)
	{
		$exp = explode('-', $tgl);
		$tgl = $exp[1];
		$bln = $this->app_model->getBulan($tgl);
		$hasil = substr($bln, 0, 3);
		//return $bln;
		return $hasil;
	}

	function tgl_indo($tgl)
	{
		$jam = substr($tgl, 11, 10);
		$tgl = substr($tgl, 0, 10);
		$tanggal = substr($tgl, 8, 2);
		$bulan = $this->app_model->getBulan(substr($tgl, 5, 2));
		$tahun = substr($tgl, 0, 4);
		return $tanggal . ' ' . $bulan . ' ' . $tahun;
	}

	function tgl_indo_jam($tgl)
	{
		$jam = substr($tgl, 11, 10);
		$tgl = substr($tgl, 0, 10);
		$tanggal = substr($tgl, 8, 2);
		$bulan = $this->app_model->getBulan(substr($tgl, 5, 2));
		$tahun = substr($tgl, 0, 4);
		return $tanggal . ' ' . $bulan . ' ' . $tahun . '&nbsp;&nbsp;<p><b>' . $jam . '</b></p>';
	}

	function tgl_bulan($tgl)
	{
		$jam = substr($tgl, 11, 10);
		$tgl = substr($tgl, 0, 10);
		$tanggal = substr($tgl, 8, 2);
		$bulan = $this->app_model->getBulan(substr($tgl, 5, 2));
		$tahun = substr($tgl, 0, 4);
		return $tanggal . ' ' . $bulan;
	}

	function getBulan($bln)
	{
		switch ($bln) {
			case 1:
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	}

	function hari_ini($hari)
	{
		date_default_timezone_set('Asia/Jakarta'); // PHP 6 mengharuskan penyebutan timezone.
		$seminggu = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
		//$hari = date("w");
		$hari_ini = $seminggu[$hari];
		return $hari_ini;
	}


	function terbilang($satuan)
	{
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		if ($satuan < 12) return  $huruf[$satuan];
		elseif ($satuan < 20) return $huruf[$satuan - 10] . " belas ";
		elseif ($satuan < 100) return $huruf[$satuan / 10] . " puluh " . $huruf[$satuan % 10];
		elseif ($satuan < 200) return "seratus" . $huruf[$satuan - 100];
		elseif ($satuan < 1000) return $huruf[$satuan / 100] . " ratus " . $huruf[$satuan % 100];
		elseif ($satuan < 2000) return "seribu" . $huruf[$satuan - 1000];
		elseif ($satuan < 1000000) return $huruf[$satuan / 1000] . " ribu " . $huruf[$satuan % 1000];
		elseif ($satuan < 1000000000) return $huruf[$satuan / 1000000] . " juta " . $huruf[$satuan % 1000000];
		elseif ($satuan >= 1000000000) echo "Angka yang Anda masukkan terlalu besar";
	}


	function datediff($tgl1, $tgl2)
	{
		$tgl1 = (is_string($tgl1) ? strtotime($tgl1) : $tgl1);
		$tgl2 = (is_string($tgl2) ? strtotime($tgl2) : $tgl2);
		$diff_secs = abs($tgl1 - $tgl2);
		$base_year = min(date("Y", $tgl1), date("Y", $tgl2));
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
		return array(
			"years" => date("Y", $diff) - $base_year,
			"months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
			"months" => date("n", $diff) - 1,
			"days_total" => floor($diff_secs / (3600 * 24)),
			"days" => date("j", $diff) - 1,
			"hours_total" => floor($diff_secs / 3600),
			"hours" => date("G", $diff),
			"minutes_total" => floor($diff_secs / 60),
			"minutes" => (int) date("i", $diff),
			"seconds_total" => $diff_secs,
			"seconds" => (int) date("s", $diff)
		);
	}

	//query login
	function getLoginData($usr, $psw)
	{
		$u = $usr;
		$p = md5($psw);
		$this->db->select('com_user.*,com_role.role_cd,com_role.role_nm');
		$this->db->from('com_user');
		$this->db->join('com_role_user', 'com_role_user.user_id = com_user.user_id');
		$this->db->join('com_role', 'com_role_user.role_cd = com_role.role_cd');
		$this->db->where('com_user.user_id', $u);
		$this->db->where('com_user.password_web', $p);
		$q_cek_login = $this->db->get();
		// $q_cek_login = $this->db->get_where('com_user', array('user_id' => $u, 'password_web' => $p));
		if (count($q_cek_login->result()) > 0) {
			// foreach ($q_cek_login->result() as $qck) {
			foreach ($q_cek_login->result() as $qad) {
				$sess_data['logged_in'] = 'mlebu_coy_armod';
				$sess_data['isLog'] = TRUE;
				$sess_data['user_id'] = $qad->user_cd;
				$sess_data['user_nm'] = $qad->user_nm;
				$sess_data['rolecd'] = $qad->role_cd;
				$sess_data['role'] = $qad->role_nm;
				$sess_data['unit'] = $qad->default_medunit;
				$sess_data['bangsal'] = $qad->default_bangsal;
				// $sess_data['id_pegawai'] = $qad->id_pegawai;
				$this->session->set_userdata($sess_data);
			}
			header('location:' . base_url() . 'depan');
			// }
		} else {
			$this->session->set_flashdata('result_login', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
				Username atau Password yang anda masukkan salah.</div>');
			header('location:' . base_url() . 'login');
		}
	}

	function logged_id()
	{
		return $this->session->userdata('id_user');
	}

	function rp($angka)
	{
		$angka = number_format($angka);
		$angka = str_replace(',', '.', $angka);
		// $angka ="Rp"."$angka".",00";	
		return $angka;
	}

	function rp_ind($angka)
	{
		$angka = number_format($angka, 2, ',', '.');
		// $angka ="Rp ".$angka;	
		return $angka;
	}




	function cari_sekolah()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tb_set where set_group='sekolah' and  set_nm like '%$q%' ");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}


	public function cari_kantor()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT * from tbl_kantor where nama_kantor like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_kantor_report()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$usl = $this->session->userdata('user_lv');
		$kan = $this->session->userdata('id_kantor');

		if ($usl == 'lv1' || $usl == 'lv2') {
			$rs = $this->db->query(
				"SELECT '' as id_kantor, 'Semua' as nama_kantor 
			Union select id_kantor,nama_kantor from tbl_kantor where nama_kantor like '%$q%'"
			);
		} else {
			$rs = $this->db->query(
				"select id_kantor,nama_kantor from tbl_kantor where id_kantor='$kan' and nama_kantor like '%$q%'"
			);
		}
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_jabatan()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT * from tbl_jabatan where nama_jabatan like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_bidang()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT * from tbl_bidang where bidang like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_bidang_report()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query(" SELECT '0' as id_bidang, 'Semua' as bidang
								UNION SELECT id_bidang,bidang from tbl_bidang where bidang like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_agama()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='agama' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_jenis_cuti()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm,set_value from tbl_set where set_group='cuti' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_pegawai()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$usl = $this->session->userdata('user_lv');
		$kan = $this->session->userdata('id_kantor');

		if ($usl == 'lv1' || $usl == 'lv2') {
			$rs = $this->db->query("SELECT a.id_pegawai,a.nip,a.nama,b.nama_kantor,c.bidang from tbl_pegawai a
								left join tbl_kantor b on a.id_kantor=b.id_kantor
								left join tbl_bidang c on a.id_bidang=c.id_bidang
								 where a.aktif_st='1' and ( a.nama like '%$q%' or a.nip like '%$q%')");
		} else {
			$rs = $this->db->query("SELECT a.id_pegawai,a.nip,a.nama,b.nama_kantor,c.bidang from tbl_pegawai a
								left join tbl_kantor b on a.id_kantor=b.id_kantor
								left join tbl_bidang c on a.id_bidang=c.id_bidang
								 where a.aktif_st='1' and a.id_kantor='$kan' and ( a.nama like '%$q%' or a.nip like '%$q%')");
		}
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_jenis_ijin()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='ijin' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_status_karyawan()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='statuspegawai' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_jenis_ijin_report()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT '' as set_cd, 'Semua' as set_nm UNION 
								SELECT set_cd,set_nm from tbl_set where set_group='ijin' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_jenis_sp()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='sp' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}
	public function cari_jenis_asset()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='asset' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	public function cari_leveluser()
	{
		$q = isset($_POST['q']) ? strval($_POST['q']) : '';
		$rs = $this->db->query("SELECT set_cd,set_nm from tbl_set where set_group='level' and set_cd <> 'lv1' and  set_nm like '%$q%'");
		$rows = array();
		foreach ($rs->result_array() as $row) {
			$rows[] = $row;
		}
		echo json_encode($rows);
	}

	function cek_idp($idp)
	{
		// return $this->db->query("select id_pegawai from tbl_pegawai where id_pegawai='$idp'")->row()->id_pegawai;
		$cek = $this->db->query("select id_pegawai from tbl_pegawai where id_pegawai='$idp'")->num_rows();
		if ($cek == 0) {
			return 'Kosong';
		} else {
			return 'Ada';
		}
	}
}

/* End of file app_model.php */
/* Location: ./application/models/app_model.php */