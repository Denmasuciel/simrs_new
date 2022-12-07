<?php

class Group_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getGroup()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'com_role.role_nm';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $search = isset($_POST['search_group']) ? strval($_POST['search_group']) : '';
        $offset = ($page - 1) * $rows;

        $result = array();
        $query = "WITH CTE AS (
            SELECT  *,
                    ROW_NUMBER() OVER (ORDER BY $sort $order) as RowNumber
            FROM com_role  where role_cd  like '%$search%' OR role_nm  like '%$search%' 
            )
            SELECT * FROM CTE WHERE RowNumber BETWEEN 1 AND 500";
        $result['total'] = $this->db->query($query)->num_rows();
        $country = $this->db->query($query)->result_array();
        $result = array_merge($result, ['rows' => $country]);
        return $result;
    }

    public function saveGroup()
    {
        $data = [
            'role_cd' => $this->input->post('role_cd'),
            'role_nm' => $this->input->post('role_nm'),
            'modi_id' => $this->session->userdata('user_id'),
            'modi_datetime' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('com_role', $data);
        return $this->db->insert_id();
    }

    public function updateGroup($id)
    {
        $data =  [
            'role_nm' => $this->input->post('role_nm'),
            'modi_id' => $this->session->userdata('user_id'),
            'modi_datetime' => date('Y-m-d H:i:s')
        ];

        $this->db->where('role_cd', $id);
        $this->db->set($data);
        return $this->db->update('com_role');
    }

    public function destroyGroup($id)
    {
        $userdata = $this->db->query("delete from com_role_user where role_cd='$id'");
        if ($userdata) {
            $this->db->where('role_cd', $id);
            return $this->db->delete('com_role');
        }
    }
}
