<?php

class Rindang_model extends CI_model
{
    public function getAllPotongan($id = null)
    {
        if($id === null){
            return $this->db->get('v_potongan');
        } else {
            return $this->db->get_where('v_potongan', ['id_potongan'=>$id]);
        }
        
    }
    public function deletePotongan($id){
        $this->db->delete('produksi_mesin_ajl_potongan', ['id_potongan'=>$id]);
        return $this->db->affected_rows();
    } //end
    public function deleteInspect($id){
        $this->db->delete('produksi_inspect', ['kode_roll'=>$id]);
        return $this->db->affected_rows();
    } //end

    public function createPotongan($data){
        $this->db->insert('produksi_mesin_ajl_potongan', $data);
        return $this->db->affected_rows();

    } //end
    public function updatePotongan($data, $id){
        $this->db->update('produksi_mesin_ajl_potongan', $data, ['id_potongan'=>$id]);
        return $this->db->affected_rows();
    } //end
    public function createInspect($data){
        $this->db->insert('produksi_inspect', $data);
        return $this->db->affected_rows();

    } //end


}
