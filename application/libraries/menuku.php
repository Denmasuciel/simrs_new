<?php
class Menuku
{
    function __construct()
    {
        $this->ci =& get_instance();
		$this->ci->load->database();
		$this->ci->load->library(array('session'));
    }
    function ambildatamenu($lvl, $root_cd, $role)
    {
        $rs = "
                SELECT A.menu_cd,B.menu_nm,B.menu_root, 
                B.menu_url,B.menu_image,B.menu_level,B.menu_tp,B.menu_param  
                FROM com_role_menu A, com_menu B  
                WHERE A.menu_cd=B.menu_cd  
                AND B.active_st='1'  
                AND A.role_cd='$role' AND menu_level='$lvl'  and menu_root='$root_cd'
                ORDER BY B.menu_no            
            ";
        return $this->db->query($rs);
    }

    public function check_root($cd)
    {
        return $this->db->get_where('com_menu', array('menu_root' => $cd));
    }

    public function get_menu_sidebar()
    {
        $role = $this->session->userdata('rolecd');
        $str = "";
        $menu_1 = $this->ambildatamenu('1', '', $role)->result();
        foreach ($menu_1 as $level_1) {
            $cek_1 = $this->check_root($level_1->menu_cd)->num_rows();
            if ($cek_1 > 0) {
                $str .= '<li class="treeview" id="' . $level_1->menu_cd . '">
						        <a href="#">
                            <i class="fa  ' . $level_1->menu_image . '"></i> <span>' . $level_1->menu_nm . '</span>
                            <i class="fa fa-angle-left pull-right"></i>
                                </a>';
            } else {
                $str .= '<li><a href="' . site_url("$level_1->menu_url") . '" >
                                <i class="fa fa-angle-double-right pull-right"></i> ' . $level_1->menu_nm . '			
								</a></li>';
            }
            $str .= '<ul class="treeview-menu" >';
            $menu_2 = $this->ambildatamenu('2', $level_1->menu_cd, $role)->result();
            foreach ($menu_2 as $level_2) {
                $cek_2 = $this->check_root($level_2->menu_cd)->num_rows();
                if ($cek_2 > 0) {
                    $str .= '<li class="treeview" id="' . $level_2->menu_cd . '">
                                    <a href="#">
                                <i class="fa  ' . $level_2->menu_image . '"></i> <span>' . $level_2->menu_nm . '</span>
                                <i class="fa fa-angle-left pull-right"></i>
                                    </a>';
                } else {
                    $str .= '<li><a href="' . site_url("$level_2->menu_url") . '" >
                                    <i class="fa fa-angle-double-right pull-right"></i> ' . $level_2->menu_nm . '			
                                    </a></li>';
                }
                $str .= '<ul class="treeview-menu" >';
                $menu_3 = $this->ambildatamenu('3', $level_2->menu_cd, $role)->result();
                foreach ($menu_3 as $level_3) {
                    $cek_3 = $this->check_root($level_3->menu_cd)->num_rows();
                    if ($cek_3 > 0) {
                        $str .= '<li class="treeview" id="' . $level_3->menu_cd . '">
                                        <a href="#">
                                    <i class="fa  ' . $level_3->menu_image . '"></i> <span>' . $level_3->menu_nm . '</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                        </a>';
                    } else {
                        $str .= '<li><a href="' . site_url("$level_3->menu_url") . '" >
                                        <i class="fa fa-angle-double-right pull-right"></i> ' . $level_3->menu_nm . '			
                                        </a></li>';
                    }
                    $str .= '<ul class="treeview-menu" >';
                    $menu_4 = $this->ambildatamenu('4', $level_3->menu_cd, $role)->result();
                    foreach ($menu_4 as $level_4) {
                        $cek_4 = $this->check_root($level_4->menu_cd)->num_rows();
                        if ($cek_4 > 0) {
                            $str .= '<li class="treeview" id="' . $level_4->menu_cd . '">
                                            <a href="#">
                                        <i class="fa  ' . $level_4->menu_image . '"></i> <span>' . $level_4->menu_nm . '</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                            </a>';
                        } else {
                            $str .= '<li><a href="' . site_url("$level_4->menu_url") . '" >
                                            <i class="fa fa-angle-double-right pull-right"></i> ' . $level_4->menu_nm . '			
                                            </a></li>';
                        }
                        $str .= '<ul class="treeview-menu" >';
                        $menu_5 = $this->ambildatamenu('5', $level_4->menu_cd, $role)->result();
                        foreach ($menu_5 as $level_5) {
                            $cek_5 = $this->check_root($level_5->menu_cd)->num_rows();
                            if ($cek_5 > 0) {
                                $str .= '<li class="treeview" id="' . $level_5->menu_cd . '">
                                                <a href="#">
                                            <i class="fa  ' . $level_5->menu_image . '"></i> <span>' . $level_5->menu_nm . '</span>
                                            <i class="fa fa-angle-left pull-right"></i>
                                                </a>';
                            } else {
                                $str .= '<li><a href="' . site_url("$level_5->menu_url") . '" >
                                                <i class="fa fa-angle-double-right pull-right"></i> ' . $level_5->menu_nm . '			
                                                </a></li>';
                            }
                            // $str .='<ul class="treeview-menu" >';
                        }

                        $str .= '</ul></li>';
                    }
                    $str .= '</ul></li>';
                }
                $str .= '</ul></li>';
            }
            $str .= '</ul></li>';
        }
        return $str;
    }
}
