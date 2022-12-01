<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Sql_model extends CI_Model {


    public function __construct()
    {
        parent::__construct();
    }

    function getAllData($no_page){
        $perpage = 20; // nilai $perpage disini sama dengan di $config['per_page']
        if($no_page == 1){
            $first = 1;
            $last  = $perpage; 
        }else{
            $first = ($no_page - 1) * $perpage + 1;
            $last  = $first + ($perpage -1);
        }
        
        return $this->db->query("WITH CTE AS (
                                        SELECT  a.*,
                                                ROW_NUMBER() OVER (ORDER BY a.id_tabel desc) as RowNumber
                                        FROM come_code a 
                                    )
                                SELECT * FROM CTE WHERE RowNumber BETWEEN $first AND $last")->result_array();   
    }
 
 
 
function data_pagination($url, $rows = 10, $uri = 3){
 $this->load->library('pagination');
   
 $config['per_page']   = 20;
 $config['base_url']   = site_url($url);
 $config['total_rows']   = $rows;
 $config['use_page_numbers'] = TRUE;
 $config['uri_segment']   = $uri;
 $config['num_links']   = 3;
 $config['next_link']   = 'Next';
 $config['prev_link']   = 'Previous';
 // untuk config class pagination yg lainnya optional (suka2 lu.. :D )
 
 $this->pagination->initialize($config);
 return $this->pagination->create_links();
}


function getTotalRowAllData(){
    $query = $this->db->query("SELECT count(*) as row FROM come_code")->row_array();
    return $query['row'];
   }
   
}
 
 
 
