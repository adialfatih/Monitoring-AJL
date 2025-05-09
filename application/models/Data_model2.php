<?php
class Data_model2 extends CI_Model{
 
  function insfinish($kode_roll,$formatTgl,$panjang,$bs,$crt,$ukrbp,$satuan,$oper_nama,$ket,$_kdkons){
    
        $datalist = [
            'kode_roll' => $kode_roll,
            'tgl' => $formatTgl,
            'panjang' => $panjang,
            'bs' => $bs,
            'crt' => $crt,
            'bp' => $ukrbp,
            'satuan' => $satuan,
            'operator' => $oper_nama,
            'ket' => $ket,
            'kodekons' => $_kdkons,
            'posisi' => 'gudang',
            'panjang_now' => $panjang
        ];
        $this->db->insert('data_if_lama',$datalist);
  }
  
  function foldinglama($kode_roll,$ukuran,$folding,$formatTgl,$operator,$_kdkons,$loc,$join){
        if($join=="JS" OR $join=="js" OR $join=="Js"){ $jointrue ="true"; } else { $jointrue="false"; }
        if($kode_roll=="NULL" AND $ukuran=="NULL" AND $folding=="NULL" OR $kode_roll=="" AND $ukuran=="" AND $folding==""){

        } else {
        $datalist = [
            'kode_roll' => $kode_roll,
            'ukuran_asli' => $ukuran,
            'ukuran_now' => $ukuran,
            'folding' => $folding,
            'lokasi' => $loc,
            'tanggal' => $formatTgl,
            'operator' => $operator,
            'konstruksi' => $_kdkons,
            'join' => $jointrue
        ];
        $this->db->insert('data_fol_lama',$datalist); }
  }

  
}
?>