<?php
class Data_model extends CI_Model{
 
  function delete($table,$key,$id){
    $this->db->where($key, $id);
    $this->db->delete($table);
  }
  function del_multi($table,$where){
    $this->db->where($where);
    $this->db->delete($table);
  }

  function saved($table, $datalist){
    $this->db->insert($table,$datalist);
  }

  function reportnew($kode, $tgl){

  }

  function get_record($table){
     $result = $this->db->get($table);
     return $result;
  }
  
  function sort_record($key, $table){
     $this->db->order_by($key, 'DESC');
     $result = $this->db->get($table);
     return $result;
  }

  function get_view($table, $key, $limit){
    $this->db->limit($limit);
    $this->db->order_by($key, 'DESC');
    $result = $this->db->get($table);
    return $result;
  }
  
  function get_byid($table,$where){      
        return $this->db->get_where($table,$where);
  }

  function updatedata($key, $id, $table, $data){
    $this->db->where($key, $id);
    $this->db->update($table, $data);
  }

  function get_sum($field, $table){
    $this->db->select_sum($field);
    $result = $this->db->get($table);
    return $result;
  }
  function kosongke($table){
    $this->db->truncate($table);
  }
  
  function get_spesifik($key, $id, $sort,$table){
    $this->db->where($key, $id);
    $this->db->order_by($sort, 'DESC');
    $result = $this->db->get($table);
    return $result;
  }
  function get_jml($key){
    $this->db->where('jnslog', $key);
    $result = $this->db->get('log');
    return $result->num_rows();
  }
    
  function filter($data){
        $str_in = strip_tags(htmlspecialchars($data));
        $arrrays = array('~', "`", '!', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '{', '[', ']', '}', '|', '\\', "'", '"', ':', ';', '<', '>', '?', "‘", '“',);
        $str = str_replace($arrrays, '', $str_in);
        return $str;
  }
  function clean($data){
        $str_in = strip_tags(htmlspecialchars($data));
        $arrrays = array('~', "`", '!', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+', '=', '{', '[', ']', '}', '|', '\\', "'", '"', ':', ';', '<', ',', '>', '?', '/', "‘", '“', '.', '_', '@');
        $str = str_replace($arrrays, '', $str_in);
        return $str;
  }
  function getTanggal($tgl){
      $ex = explode(" ", $tgl);
        $bln = array(
          'Januari' => '01', 'January' => '01', 'Jan' => '01',
          'Februari' => '02', 'February' => '02', 'Feb' => '02',
          'Maret' => '03', 'March' => '03', 'Mar' => '03',
          'April' => '04', 'Apr' => '04',
          'Mei' => '05', 'May' => '05', 'May' => '05',
          'Juni' => '06', 'June' => '06', 'Jun' => '06',
          'Juli' => '07', 'July' => '07', 'Jul' => '07',
          'Agustus' => '08', 'August' => '08', 'Aug' => '08',
          'September' => '09', 'Sep' => '09',
          'Oktober' => '10', 'October' => '10', 'Oct' => '10',
          'Nopember' => '11', 'November' => '11', 'Nov' => '11',
          'Desember' => '12', 'December' => '12', 'Dec' => '12'
        );
        $in_tgl = $ex[1]."/".$bln[$ex[2]]."/".$ex[3];
        return $in_tgl;
  }

  function getDate($tgl){
      $ex = explode(" ", $tgl);
        $bln = array(
          'Januari' => '01', 'January' => '01', 'Jan' => '01',
          'Februari' => '02', 'February' => '02', 'Feb' => '02',
          'Maret' => '03', 'March' => '03', 'Mar' => '03',
          'April' => '04', 'Apr' => '04',
          'Mei' => '05', 'May' => '05', 'May' => '05',
          'Juni' => '06', 'June' => '06', 'Jun' => '06',
          'Juli' => '07', 'July' => '07', 'Jul' => '07',
          'Agustus' => '08', 'August' => '08', 'Aug' => '08',
          'September' => '09', 'Sep' => '09',
          'Oktober' => '10', 'October' => '10', 'Oct' => '10',
          'Nopember' => '11', 'November' => '11', 'Nov' => '11',
          'Desember' => '12', 'December' => '12', 'Dec' => '12'
        );
        $in_tgl = $ex[3]."-".$bln[$ex[2]]."-".$ex[1];
        return $in_tgl;
  }
  // function acakKode($jml){
  //       $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
  //       $string = '';
  //           for ($i = 0; $i < $jml; $i++) {
  //               $pos = rand(0, strlen($karakter)-1);
  //               $string .= $karakter{$pos};
  //           }
  //       return $string;
  // }
  function acakKode($jml){
    return substr(str_shuffle(str_repeat($x='QWERTYUIOPLKJHGFDSAZXCVBNM', ceil($jml/strlen($x)) )),1,$jml);
  } //end
  function acakKode2($jml){
    return substr(str_shuffle(str_repeat($x='QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuiopasdfghjklmnbvcxz1234567890', ceil($jml/strlen($x)) )),1,$jml);
  } //end
  function generateID(){
      $query = $this->db->order_by('id_kdm', 'DESC')->limit(1)->get('kode_masuk');
      if($query->num_rows() == 1){
          $query1 = $query->row('kode');
          $lastNo = explode("-", $query1);
          $next = intval($lastNo[1]) + 1;
          $kd = 'IN-';
          return $kd.sprintf('%04s', $next);
      } else {
          $kode = "IN-0001";
          return $kode;
      }
      
  }
  
  function createID(){
      $query = $this->db->order_by('id_kdo', 'DESC')->limit(1)->get('kode_out');
      if($query->num_rows() == 1){
          $query1 = $query->row('kode');
          $lastNo = explode("-", $query1);
          $next = intval($lastNo[1]) + 1;
          $kd = 'OUT-';
          return $kd.sprintf('%04s', $next);
      } else {
          $kode = "OUT-0001";
          return $kode;
      }
  }
  
  function generateKODE($txt, $kd, $table){
      $query = $this->db->order_by($kd, 'DESC')->limit(1)->get($table)->row($kd);
      $lastNo = explode("/", $query);
      $next = intval($lastNo[1]) + 1;
      $kd = $txt.'/';
      return $kd.sprintf('%03s', $next);
  }
  function printBln($bln){
     $ar = array(
      '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
     );
     return $ar[$bln];
  }
  function printBln2($bln){
    $ar = array(
     '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
    );
    return $ar[$bln];
 }
  function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
      $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
      $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
      $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
      $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
      $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
      $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
      $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
      $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
      $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
      $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
  }
  public function isCodeExist($code) {
      $query = $this->db->get_where('penerimaan_benang', array('kode_karung' => $code));
      return $query->num_rows() > 0;
  }

  public function saveCode($code) {
      $data = array('kode' => $code);
      return $this->db->insert('nama_tabel', $data);
  }
}
?>