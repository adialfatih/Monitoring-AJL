<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Phpsp extends CI_Controller {
public function __construct(){
  parent::__construct();
  $this->load->model('data_model');
  date_default_timezone_set("Asia/Jakarta");
}
public function index(){
$this->load->view('spreadsheet');
}
// lets get export file
function export2(){
    $ipt = $this->input->post('ipt');
    echo $ipt;
    $bln = $this->uri->segment(3);
    $thn = $this->uri->segment(4);
}
public function export(){
    $bln = $this->uri->segment(3);
    $thn = $this->uri->segment(4);
    $_query = $this->db->query("SELECT tgl_akumulasi,nomesin FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' GROUP BY nomesin ORDER BY nomesin ASC");
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $style_col = [
        'font' => ['bold' => true], // Set font nya jadi bold
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
        ]
      ];
      // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = [
        'alignment' => [
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
        ]
      ];
      $sheet->setCellValue('A1', "NO"); // Set kolom A1 
      $sheet->setCellValue('B1', "No. Mesin"); // Set kolom A1 
      $sheet->setCellValue('C1', "Putus Lusi"); // Set kolom A1 
      $sheet->setCellValue('D1', "Menit"); // Set kolom A1 
      $sheet->setCellValue('E1', "Rata-rata Lost"); // Set kolom A1 
      $sheet->setCellValue('F1', "Putus Pakan"); // Set kolom A1 
      $sheet->setCellValue('G1', "Menit"); // Set kolom A1 
      $sheet->setCellValue('H1', "Rata-rata Lost");
      $sheet->setCellValue('I1', "EFF %");
      $sheet->setCellValue('J1', "Produksi");
      $sheet->setCellValue('K1', "Produksi Teoritis 3 Shift");
      $sheet->setCellValue('L1', "EFF Rill 3 Shift");
      $sheet->setCellValue('M1', "Produksi 100%");
      $sheet->setCellValue('N1', "Selisi Produksi 3 Shift");
      $sheet->setCellValue('O1', "EFF% counter VS EFF% Rill");
      $sheet->setCellValue('P1', "Jumlah Data");
      $sheet->getColumnDimension('A')->setAutoSize(true);
      $sheet->getColumnDimension('B')->setAutoSize(true);
      $sheet->getColumnDimension('C')->setAutoSize(true);
      $sheet->getColumnDimension('D')->setAutoSize(true);
      $sheet->getColumnDimension('E')->setAutoSize(true);
      $sheet->getColumnDimension('F')->setAutoSize(true);
      $sheet->getColumnDimension('G')->setAutoSize(true);
      $sheet->getColumnDimension('H')->setAutoSize(true);
      $sheet->getColumnDimension('I')->setAutoSize(true);
      $sheet->getColumnDimension('J')->setAutoSize(true);
      $sheet->getColumnDimension('K')->setAutoSize(true);
      $sheet->getColumnDimension('L')->setAutoSize(true);
      $sheet->getColumnDimension('M')->setAutoSize(true);
      $sheet->getColumnDimension('N')->setAutoSize(true);
      $sheet->getColumnDimension('O')->setAutoSize(true);
      $sheet->getColumnDimension('P')->setAutoSize(true);
      // Apply style header yang telah kita buat tadi ke masing-masing kolom header
      $sheet->getStyle('A1')->applyFromArray($style_col);
      $sheet->getStyle('B1')->applyFromArray($style_col);
      $sheet->getStyle('C1')->applyFromArray($style_col);
      $sheet->getStyle('D1')->applyFromArray($style_col);
      $sheet->getStyle('E1')->applyFromArray($style_col);
      $sheet->getStyle('F1')->applyFromArray($style_col);
      $sheet->getStyle('G1')->applyFromArray($style_col);
      $sheet->getStyle('H1')->applyFromArray($style_col);
      $sheet->getStyle('I1')->applyFromArray($style_col);
      $sheet->getStyle('J1')->applyFromArray($style_col);
      $sheet->getStyle('K1')->applyFromArray($style_col);
      $sheet->getStyle('L1')->applyFromArray($style_col);
      $sheet->getStyle('M1')->applyFromArray($style_col);
      $sheet->getStyle('N1')->applyFromArray($style_col);
      $sheet->getStyle('O1')->applyFromArray($style_col);
      $sheet->getStyle('P1')->applyFromArray($style_col);
      $no=1; $ok = 2;
        foreach($_query->result() as $dt):
            $_mc = $dt->nomesin;
            $jml_data2 = $this->db->query("SELECT tgl_akumulasi,nomesin FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'");
            $jml_data = $jml_data2->num_rows();
            $sheet->setCellValue('A'.$ok.'', $no);
            $sheet->setCellValue('B'.$ok.'', $dt->nomesin);
            //--ls
            $ls = $this->db->query("SELECT SUM(ls) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $ls2 = $ls / $jml_data;
            $ls3 = round($ls2,2);
            $sheet->setCellValue('C'.$ok.'', $ls3);
            //--end
            //--ls_mnt
            $ls_mnt = $this->db->query("SELECT SUM(ls_mnt) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $ls_mnt2 = $ls_mnt / $jml_data;
            $ls_mnt3 = round($ls_mnt2,2);
            $sheet->setCellValue('D'.$ok.'', $ls_mnt3);
            //--end
            //--rtlost
            $rtlost1 = $this->db->query("SELECT SUM(rtlost1) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $rtlost12 = $rtlost1 / $jml_data;
            $rtlost13 = round($rtlost12,2);
            $sheet->setCellValue('E'.$ok.'', $rtlost13);
            //--end
            //--pkn
            $pkn = $this->db->query("SELECT SUM(pkn) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $pkn2 = $pkn / $jml_data;
            $pkn3 = round($pkn2,2);
            $sheet->setCellValue('F'.$ok.'', $pkn3);
            //--end
            //--pkn_mnt
            $pkn_mnt = $this->db->query("SELECT SUM(pkn_mnt) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $pkn_mnt2 = $pkn_mnt / $jml_data;
            $pkn_mnt3 = round($pkn_mnt2,2);
            $sheet->setCellValue('G'.$ok.'', $pkn_mnt3);
            //--end
            //--rtlost2
            $rtlost2 = $this->db->query("SELECT SUM(rtlost2) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $rtlost22 = $rtlost2 / $jml_data;
            $rtlost23 = round($rtlost22,2);
            $sheet->setCellValue('H'.$ok.'', $rtlost23);
            //--end
            //--eff
            $eff = $this->db->query("SELECT SUM(eff) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $eff2 = $eff / $jml_data;
            $eff3 = round($eff2,2);
            $sheet->setCellValue('I'.$ok.'', $eff3);
            //--end
            //--produksi
            $produksi = $this->db->query("SELECT SUM(produksi) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $produksi2 = $produksi / $jml_data;
            $produksi3 = round($produksi2,2);
            $sheet->setCellValue('J'.$ok.'', $produksi3);
            //--end
            //--produksi teo
            $produksiteo = $this->db->query("SELECT SUM(prod_teo) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $produksiteo2 = $produksiteo / $jml_data;
            $produksiteo3 = round($produksiteo2,2);
            $sheet->setCellValue('K'.$ok.'', $produksiteo3);
            //--end
            //eff_rill
            $effrill = $this->db->query("SELECT SUM(eff_rill) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $effrill2 = $effrill / $jml_data;
            $effrill3 = round($effrill2,2);
            $sheet->setCellValue('L'.$ok.'', $effrill3);
            //--end
            //prod_100
            $prod_100 = $this->db->query("SELECT SUM(prod_100) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $prod_1002 = $prod_100 / $jml_data;
            $prod_1003 = round($prod_1002,2);
            $sheet->setCellValue('M'.$ok.'', $prod_1003);
            //--end
            //selisih_prod
            $selisih_prod = $this->db->query("SELECT SUM(selisih_prod) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $selisih_prod2 = $selisih_prod / $jml_data;
            $selisih_prod3 = round($selisih_prod2,2);
            $sheet->setCellValue('N'.$ok.'', $selisih_prod3);
            //--end
            //eff_counter
            $eff_counter = $this->db->query("SELECT SUM(eff_counter) AS jmlls FROM akumulasitigashift WHERE MONTH(tgl_akumulasi) = '$bln' AND YEAR(tgl_akumulasi) = '$thn' AND nomesin='$_mc'")->row("jmlls");
            $eff_counter2 = $eff_counter / $jml_data;
            $eff_counter3 = round($eff_counter2,2);
            $sheet->setCellValue('O'.$ok.'', $eff_counter3);
            $sheet->setCellValue('P'.$ok.'', $jml_data);
            //--end
            
            $no++;
            $ok++;
        endforeach;
      $sheet->getStyle('A1')->applyFromArray($style_row);
      $sheet->getStyle('B1')->applyFromArray($style_row);
      $sheet->getStyle('C1')->applyFromArray($style_row);
      $sheet->getStyle('D1')->applyFromArray($style_row);
      $sheet->getStyle('E1')->applyFromArray($style_row);
      $sheet->getStyle('F1')->applyFromArray($style_row);
      $sheet->getStyle('G1')->applyFromArray($style_row);
      $sheet->getStyle('H1')->applyFromArray($style_row);
      $sheet->getStyle('I1')->applyFromArray($style_row);
      $sheet->getStyle('J1')->applyFromArray($style_row);
      $sheet->getStyle('K1')->applyFromArray($style_row);
      $sheet->getStyle('L1')->applyFromArray($style_row);
      $sheet->getStyle('M1')->applyFromArray($style_row);
      $sheet->getStyle('N1')->applyFromArray($style_row);
      $sheet->getStyle('O1')->applyFromArray($style_row);
      $sheet->getStyle('P1')->applyFromArray($style_row);
      
      $writer = new Xlsx($spreadsheet);
      $filename = 'Laporan Monitoring Mesin AJL';

      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output'); // download file
}
//end export file
public function import(){
  $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  if(isset($_FILES['upload_file']['name']) && in_array($_FILES['upload_file']['type'], $file_mimes)) {
    $arr_file = explode('.', $_FILES['upload_file']['name']);
    $extension = end($arr_file);
    if('csv' == $extension){
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }
      $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
      $sheetData = $spreadsheet->getActiveSheet()->toArray();
      $kode_produksi = $sheetData[1][0];
      $satuan = $sheetData[1][7];
      $all_data=0;
      $stok_bs=0;
      $auto = $this->data_model->get_byid('tb_produksi',['kode_produksi'=>$kode_produksi]);
      $st_pkg = $auto->row("st_produksi");
      for ($i=1; $i <count($sheetData) ; $i++) { 
        if($sheetData[$i][0]!="" AND $sheetData[$i][1]!="" AND $sheetData[$i][2]!="" AND $sheetData[$i][6]!="" AND $sheetData[$i][7]!=""){
            if($satuan=="Yard"){
              $ukuran_in = floatval($sheetData[$i][2]) * 0.9144;
              $ukuranb_in = floatval($sheetData[$i][3]) * 0.9144;
              $ukuranc_in = floatval($sheetData[$i][4]) * 0.9144;
              $ukuranbs_in = floatval($sheetData[$i][5]) * 0.9144;
              $dtlist = [
                  'kode_produksi' => $sheetData[$i][0],
                  'no_roll' => $sheetData[$i][1],
                  'ukuran_ori' => round($ukuran_in,2),
                  'ukuran_b' => round($ukuranb_in,2),
                  'ukuran_c' => round($ukuranc_in,2),
                  'ukuran_bs' => round($ukuranbs_in,2),
                  'ukuran_now' => round($ukuran_in,2),
                  'operator' => $sheetData[$i][6],
                  'st_pkg' => $st_pkg,
                  'satuan' => $satuan,
                  'ukuran_ori_yard' => round($sheetData[$i][2],2),
                  'ukuran_b_yard' => round($sheetData[$i][3],2),
                  'ukuran_c_yard' => round($sheetData[$i][4],2),
                  'ukuran_bs_yard' => round($sheetData[$i][5],2),
                  'ukuran_now_yard' => round($sheetData[$i][2],2)
              ];
              $this->data_model->saved('new_tb_pkg_list', $dtlist);
              $all_data = $all_data + 1;
              $stok_bs = $stok_bs + floatval($sheetData[$i][5]);
          } elseif ($satuan=="Meter") {
              $ukuran_in = floatval($sheetData[$i][2]) / 0.9144;
              $ukuranb_in = floatval($sheetData[$i][3]) / 0.9144;
              $ukuranc_in = floatval($sheetData[$i][4]) / 0.9144;
              $ukuranbs_in = floatval($sheetData[$i][5]) / 0.9144;
              $dtlist = [
                  'kode_produksi' => $sheetData[$i][0],
                  'no_roll' => $sheetData[$i][1],
                  'ukuran_ori' => round($sheetData[$i][2],2),
                  'ukuran_b' => round($sheetData[$i][3],2),
                  'ukuran_c' => round($sheetData[$i][4],2),
                  'ukuran_bs' => round($sheetData[$i][5],2),
                  'ukuran_now' => round($sheetData[$i][2],2),
                  'operator' => $sheetData[$i][6],
                  'st_pkg' => $st_pkg,
                  'satuan' => $satuan,
                  'ukuran_ori_yard' => round($ukuran_in,2),
                  'ukuran_b_yard' => round($ukuranb_in,2),
                  'ukuran_c_yard' => round($ukuranc_in,2),
                  'ukuran_bs_yard' => round($ukuranbs_in,2),
                  'ukuran_now_yard' => round($ukuran_in,2)
              ];
              $this->data_model->saved('new_tb_pkg_list', $dtlist);
              $all_data = $all_data + 1;
              $stok_bs = $stok_bs + floatval($sheetData[$i][5]);
          }
          //jika data tidak kosong kode di atas ini
        } else {}
      } //end for
      
      $kode_kons = $auto->row("kode_konstruksi");
      $tgl_pros = $auto->row("tgl_produksi");
      $loc = $this->session->userdata('departement');

      $txt = "Menambahkan sebanyak ".$all_data." roll data di dalam packing list (".$kode_produksi.").";
      $this->data_model->saved("log_program", ["id_user"=>$this->session->userdata('id'), "log"=>$txt]);
      $cek_today = $this->data_model->get_byid('report_produksi_harian', ['kode_konstruksi'=>$kode_kons, 'lokasi_produksi'=>$loc, 'waktu'=>$tgl_pros]);
      $idnow = $cek_today->row("id_rptd");
      if($satuan=="Meter"){
          $up_nilai_bs_yard = $stok_bs / 0.9144 ;
          $ar_nilai = ['bs' => round($stok_bs,2), 'bs_yard' => round($up_nilai_bs_yard,2) ];
      } elseif($satuan=="Yard") {
          $up_nilai_bs_meter = $stok_bs * 0.9144 ;
          $ar_nilai = ['bs' => round($up_nilai_bs_meter,2), 'bs_yard' => round($stok_bs,2) ];
      }
      $this->data_model->updatedata('id_rptd', $idnow, 'report_produksi_harian', $ar_nilai);
      $cek_ttl_stok = $this->data_model->get_byid('report_stok', ['kode_konstruksi'=>$kode_kons, 'departement'=>$loc]);
            $id_stok = $cek_ttl_stok->row("id_stok");
            $rs_bs = $cek_ttl_stok->row("bs");
            $new_rs_bs = $rs_bs + $stok_bs;
            if($satuan=="Yard"){
                $bs_meter = $new_rs_bs * 0.9144;
                $ar_nilai2 = ['bs'=>round($bs_meter,2), 'bs_yard'=>round($new_rs_bs,)];
            } elseif ($satuan=="Meter") {
                $bs_yard = $new_rs_bs / 0.9144;
                $ar_nilai2 = ['bs'=>round($new_rs_bs,2), 'bs_yard'=>round($bs_yard,2)];
            }
            $this->data_model->updatedata('id_stok', $id_stok, 'report_stok', $ar_nilai2);
            $this->session->set_flashdata('announce', 'Berhasil menyimpan '.$all_data.' roll data ke packinglist.');
            redirect(base_url('input-produksi'));
  }
} //end

}