<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use function GuzzleHttp\json_encode;

class Api_model extends CI_model
{
    private $_client;

    public function __construct()
    {
        $this->load->database();
        $this->_client = new Client([
             'base_uri' => 'http://192.168.132.75:8080/restfull-api/lab/',
            // 'base_uri' => 'http://localhost/simrs_api/',
            'auth' => ['lis', 'lis123']
        ]);
    }

    public function getListRanap()
    {
        $response = $this->_client->request('GET', 'trx/ranap');
        $result1 = json_decode($response->getBody()->getContents(), true);
        $result2 = $result1['rows'];
        // $result1 = $response->getBody()->getContents();
        $no = 1;
        foreach ($result2 as $data) {
            $row[] = array(
                'no' => $no++,
                'pasien_cd' => $data['pasien_cd'],
                'no_rm' => $data['no_rm'],
                'pasien_nm' => $data['pasien_nm'],
                'alamat' => $data['address'],
                'bangsal_nm' => $data['bangsal_nm'],
                'dr_nm' => $data['dr_nm'],
                'tgl_masuk' => $this->tgl_indo($data['datetime_in']),
                // 'no_antrian_tpp' => $data['no_antrian_tpp'],
                'ruang_nm' => $data['ruang_nm']
            );
        }
        $result = array('aaData' => $row);
        echo  json_encode($result);
    }
    public function getListRanapEasyui()
    {
        $response = $this->_client->request('GET', 'ranap');
        $result1 = json_decode($response->getBody()->getContents(), true);
        // $result2 = $result1['data'];
        // // $result1 = $response->getBody()->getContents();
        // $no = 1;
        // foreach ($result2 as $data) {
        //     $row[] = array(
        //         'no' => $no++,
        //         'pasien_cd' => $data['pasien_cd'],
        //         'no_rm' => $data['no_rm'],
        //         'pasien_nm' => $data['pasien_nm'],
        //         'alamat' => $data['address'],
        //         'bangsal_nm' => $data['bangsal_nm'],
        //         'dr_nm' => $data['dr_nm'],
        //         'tgl_masuk' => $this->tgl_indo($data['datetime_in']),
        //         // 'no_antrian_tpp' => $data['no_antrian_tpp'],
        //         'ruang_nm' => $data['ruang_nm']
        //     );
        // }
        // $result = array('aaData' => $row);
        // echo  json_encode($result);
        return json_encode($result1);
    }

    public function getBulan($bln)
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

    public function tgl_indo($tgl)
    {
        $jam = substr($tgl, 11, 10);
        $tgl = substr($tgl, 0, 10);
        $tanggal = substr($tgl, 8, 2);
        $bulan = $this->getBulan(substr($tgl, 5, 2));
        $tahun = substr($tgl, 0, 4);
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    public function getCustomers()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'trx_pasien.pasien_cd';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $search = isset($_POST['search_customer']) ? strval($_POST['search_customer']) : '';
        $offset = ($page - 1) * $rows;

        $result = array();
        // $result['total'] = $this->db->get('trx_pasien')->num_rows();
        // $result['total'] = $this->db->query("SELECT top 500 *
        // from trx_pasien
        // where no_rm  like '%$search%' OR pasien_nm  like '%$search%' order by $sort $order")->num_rows();
        $row = array();

        // select data from table product
        // $query = "SELECT top 500 *
        //     from trx_pasien
        //     where no_rm  like '%$search%' OR pasien_nm  like '%$search%' order by $sort $order ";


        $query = "WITH CTE AS (
                                SELECT  *,
                                        ROW_NUMBER() OVER (ORDER BY $sort $order) as RowNumber
                                FROM trx_pasien  where no_rm  like '%$search%' OR pasien_nm  like '%$search%' 
                            )
                        SELECT * FROM CTE WHERE RowNumber BETWEEN 1 AND 500";
        $result['total'] = $this->db->query($query)->num_rows();
        $country = $this->db->query($query)->result_array();
        $result = array_merge($result, ['rows' => $country]);
        return $result;
    }

    public function getmenu($lvl = '1', $root = '', $role = 'admin')
    {
        $this->db->select('*');
        $this->db->from('com_menu AS cm');
        $this->db->join('com_role_menu AS crm', 'cm.menu_cd=crm.menu_cd', 'left');
        $this->db->join('com_role AS cr', 'cr.role_cd=crm.role_cd', 'left');
        $this->db->order_by('menu_no', 'asc');
        $this->db->where(array('cm.menu_level' => $lvl, 'cm.menu_root' => $root, 'cr.role_cd' => $role));
        return $this->db->get()->result_array();
    }

   
    function has_child($role, $lvl, $root_cd)
    {
        $rs = $this->db->query("
                SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                FROM com_role_menu A, com_menu B  
                WHERE A.menu_cd=B.menu_cd  
                AND B.active_st='1'  
                AND A.role_cd='$role' AND menu_level='$lvl'  and menu_root='$root_cd'
                ORDER BY B.menu_no            
        ");
        $anak = array();
        foreach ($rs->result() as $row) {
            $anak[] = $row;
        }
        return $anak;
    }

    function get_menu_new($lvl = '1', $root = '', $role = 'admin')
    {
        $result = array();
        $menu_array_all = array();
        $menu1 = array();
        $menu2 = array();
        $menu3 = array();
        $menu4 = array();

        $rs = $this->db->query("
                    SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                     B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                     FROM com_role_menu A, com_menu B  
                     WHERE A.menu_cd=B.menu_cd  
                     AND B.active_st='1'  
                     AND A.role_cd='$role' AND menu_level='$lvl'  
                     ORDER BY B.menu_no
      ");

        foreach ($rs->result_array() as $row) {
            $menu1['level'] = $row['menu_level'];
            $menu1['id'] = $row['menu_cd'];
            $menu1['text'] = $row['menu_nm'];
            $menucd = $row['menu_cd'];
            $menu1['children'] = array();
            $rs2 = $this->db->query("
                                    SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                                    B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                                    FROM com_role_menu A, com_menu B  
                                    WHERE A.menu_cd=B.menu_cd  
                                    AND B.active_st='1'  
                                    AND A.role_cd='$role' AND menu_level=2  and menu_root='$menucd'
                                    ORDER BY B.menu_no
                                    ");
            foreach ($rs2->result_array() as $row) {
                $menu2['level'] = $row['menu_level'];
                $menu2['id'] = $row['menu_cd'];
                $menu2['text'] = $row['menu_nm'];
                $menucd = $row['menu_cd'];
                $menu2['children'] = array();
                $rs2 = $this->db->query("
                                    SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                                    B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                                    FROM com_role_menu A, com_menu B  
                                    WHERE A.menu_cd=B.menu_cd  
                                    AND B.active_st='1'  
                                    AND A.role_cd='$role' AND menu_level=3  and menu_root='$menucd'
                                    ORDER BY B.menu_no
                                    ");
                foreach ($rs2->result_array() as $row) {
                    $menu3['level'] = $row['menu_level'];
                    $menu3['id'] = $row['menu_cd'];
                    $menu3['text'] = $row['menu_nm'];
                    $menucd = $row['menu_cd'];
                    $menu3['children'] = array();
                    $rs3 = $this->db->query("
                                    SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                                    B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                                    FROM com_role_menu A, com_menu B  
                                    WHERE A.menu_cd=B.menu_cd  
                                    AND B.active_st='1'  
                                    AND A.role_cd='$role' AND menu_level=4  and menu_root='$menucd'
                                    ORDER BY B.menu_no
                                    ");
                    foreach ($rs3->result_array() as $row) {
                        $menu4['level'] = $row['menu_level'];
                        $menu4['id'] = $row['menu_cd'];
                        $menu4['text'] = $row['menu_nm'];
                        // $menucd = $row['menu_cd'];
                        array_push($menu3['children'], $menu4);
                    }
                    array_push($menu2['children'], $menu3);
                }
                array_push($menu1['children'], $menu2);
            }
            array_push($menu_array_all, $menu1);
        }
        echo json_encode($menu_array_all, JSON_PRETTY_PRINT);
    }
}
