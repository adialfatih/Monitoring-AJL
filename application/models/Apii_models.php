<?php
class Apii_models extends CI_Model{
 
  function data_kelas($id = null){
    if($id === null){
        return $this->db->get('data_kelas')->result_array();
    } else {
        return $this->db->get_where('data_kelas', ['id_kelas'=> $id])->result_array();
    }
   
  }

  function insertdataty($data){
    $nonfc = $data['nonfc'];
    $tgl = $data['tgl_log'];
    $siswa = $this->db->query("SELECT nis_siswa,id_kelas,norfid FROM profile_siswa WHERE norfid='".$nonfc."'");
    $i = $siswa->row_array();
    $id_kelas = $i['id_kelas'];
    $cek = $this->db->query("SELECT * FROM data_absensi WHERE idkls='".$id_kelas."' AND nonfc='".$nonfc."' AND abs_tgl='".$tgl."'");
    $num_cek = $cek->num_rows();
    if($num_cek>0){
        $rtn = 0;
        return $rtn;
    } else {
        $dt = array(
          'idkls' => $id_kelas,
          'nonfc' => $nonfc,
          'kehadiran' => 'H',
          'ket_abs' => '',
          'abs_tgl' => $tgl
        );
        $this->db->insert('data_absensi', $dt);
        return $this->db->affected_rows();
    }
        
  } // end
  
  function tesinsert($data){
    $nonfc = $data['nonfc'];
    $tgl = $data['tgl_log'];
    $datainsert = array('idnfc' => $nonfc, 'nis_siswa' => 'tes');
    $this->db->insert('card', $datainsert);
    return $this->db->affected_rows();
  }

  function insertdata($data){
    $nonfc = $data['nonfc'];
    $fulltgl = $data['tgl_log'];
    $ex = explode("-", $fulltgl);
    $tgl = $ex[2]; $bln = $ex[1]; $thn = $ex[0];
    $cek = $this->db->get_where('profile_siswa', ['norfid' => $nonfc])->row_array();
    $nis = $cek['nis_siswa'];

      $ceknis = $this->db->query("SELECT data_rombel.id_rom,data_rombel.id_kelas,data_kelas.id_kelas,data_kelas.nm_kelas,data_kelas.ta_kelas,setup_ta.id_set_ta,setup_ta.st_ta,data_rombel.nis FROM data_rombel,data_kelas,setup_ta WHERE data_rombel.id_kelas=data_kelas.id_kelas AND data_kelas.ta_kelas=setup_ta.id_set_ta AND setup_ta.st_ta='1' AND data_rombel.nis='$nis'");
      if($ceknis->num_rows() == 1){
        $dt = $ceknis->row_array();
        $idkelas = $dt['id_kelas'];
        $cek = $this->db->query("SELECT * FROM data_absensi WHERE idkls='".$idkelas."' AND nonfc='".$nonfc."' AND abs_tgl='".$fulltgl."'");
        $num_cek = $cek->num_rows();
        if($num_cek>0){
            $rtn = 0;
            return $rtn;
        } else {
            $dataAbsen = array(
              'idkls' => $idkelas,
              'nonfc' => $nonfc,
              'kehadiran' => 'H',
              'ket_abs' => '',
              'abs_tgl' => $fulltgl
            );
            $this->db->insert('data_absensi', $dataAbsen);
            $cek = $this->db->get_where('log_absen_bulan', ['log_tgl' => $tgl, 'log_bln' => $bln, 'log_thn' => $thn, 'log_idkls' => $idkelas]);
            if($cek->num_rows() == 1){
              $ls = $cek->row_array();
              $idlog = $ls['idlog_absbl'];
              $hdr = $ls['log_hdr'];
              $up_hdr = $hdr+1;
              $this->db->where('idlog_absbl', $idlog);
              $this->db->update('log_absen_bulan', ['log_hdr' => $up_hdr]);
            } else {
              $dataLog = array(
                  'log_tgl' => $tgl,
                  'log_bln' => $bln,
                  'log_thn' => $thn,
                  'log_idkls' => $idkelas,
                  'log_hdr' => '1',
                  'log_skt' => '0',
                  'log_ijin' => '0',
                  'log_alpa' => '0',
                  'log_status' => 'Aktif',
                  'ket_libur' => ''
              );
              $this->db->insert('log_absen_bulan', $dataLog);
            }
            $rtn = 1;
            return $rtn;
        }
            
      } else {
        $rtn = 0;
        return $rtn;
      }
        
  } // end

}
?>