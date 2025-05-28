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
    public function getAllMesin($id = null)
    {
        if($id === null){
            $qry_mesin = $this->db->query("SELECT no_mesin FROM table_mesin ORDER BY no_mesin ASC");
        } else {
            $qry_mesin = $this->db->query("SELECT no_mesin FROM table_mesin WHERE no_mesin = ? ", [$id]);
        }
        $result = [];
        foreach($qry_mesin->result() as $mc){
            $no_mc  = $mc->no_mesin;
            $dt     = $this->db->query("SELECT id_produksi_mesin,tgl_produksi,id_beam_sizing,konstruksi,pick,pjg_lusi,proses FROM produksi_mesin_ajl WHERE no_mesin=? ORDER BY id_produksi_mesin DESC LIMIT 1", [$no_mc])->row_array();
            
            $id_produksi_mesin   = $dt['id_produksi_mesin'];
            $kons   = strtoupper(preg_replace('/[\s\-\.,]/', '', $dt['konstruksi']));
            $tglprod= date('d M Y', strtotime($dt['tgl_produksi']));
            $tujuhPersen2 = intval($dt['pjg_lusi']) * 0.07;
            $tujuhPersen = round($tujuhPersen2);
            $lusi_kurangi = intval($dt['pjg_lusi']) - $tujuhPersen;
            $potongan = $this->db->query("SELECT SUM(ukuran_meter) AS jml FROM produksi_mesin_ajl_potongan WHERE id_produksi_mesin='$id_produksi_mesin'")->row("jml");
            $sisa_lusi = $lusi_kurangi - $potongan;
            $result[] = [
                'id_produksi_mesin' => $id_produksi_mesin,
                'no_mesin'      => $no_mc,
                'konstruksi'    => $kons,
                'tgl_produksi'  => $tglprod,
                'beam_sizing'   => $dt['id_beam_sizing'],
                'pjg_lusi'      => $dt['pjg_lusi'],
                'pjg_lusi7'     => $lusi_kurangi,
                'potongan'      => $potongan,
                'sisa_lusi'      => $sisa_lusi,
                'status_proses' => $dt['proses'],
                'pick' => $dt['pick'],
            ];
        }
        return $result;
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
