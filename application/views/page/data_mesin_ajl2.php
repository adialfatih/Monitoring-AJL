<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produksi Mesin AJL</title>
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
    
</head>
<body>
<?php 
                $cek_mesin = $this->db->query("SELECT * FROM table_mesin ORDER BY no_mesin ");
                $cek_data = $this->data_model->get_byid('produksi_mesin_ajl',['proses'=>'onproses']);
?>
<div style="width:100%;padding:0 15px;">
    <h2>Data Produksi Mesin AJL</h2>
    <table id="myTable" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>MC</th>
                <th>Konstruksi</th>
                <th>OK</th>
                <th>Beam</th>
                <th>Lusi</th>
                <th>Sisa Lusi</th>
                <th>Naik Beam</th>
                <th>Estimasi Habis Beam (Hari)</th>
                <th>Estimasi Habis Beam (Jam)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                            $ar = ["01"=>"Jan", "02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"Mei","06"=>"Jun","07"=>"Jul","08"=>"Ags","09"=>"Sep","10"=>"Okt","11"=>"Nov","12"=>"Des",];
                            foreach ($cek_mesin->result() as $no => $value):
                                $mc = $value->no_mesin;
                                $ajl = $this->db->query("SELECT * FROM produksi_mesin_ajl WHERE no_mesin='$mc' ORDER BY id_produksi_mesin DESC LIMIT 1")->row_array();
                                $id_ajl = $ajl['id_produksi_mesin'];
                                $idbeam = $ajl['id_beam_sizing'];
                                $siz = $this->data_model->get_byid('v_beam_sizing2', ['id_beam_sizing'=>$idbeam])->row_array();
                                $beamsizing = $siz['kode_beam'];
                                $oka = $siz['oka'];
                                $status = $ajl['proses'];
                                $start_mesin = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_ajl, 'jenis_riwayat'=>'Start Mesin']);
                                if($start_mesin->num_rows() == 1){
                                    $k = explode(' ', $start_mesin->row("timestamprwyt"));
                                    $t = explode('-', $k[0]);
                                    $printTgl = $t[2]." ".$ar[$t[1]]." ".$t[0];
                                    $w = explode(':', $k[1]);
                                    $prinJam = $w[0].":".$w[1];
                                    $naik_beam = $printTgl.", ".$prinJam;
                                } else {
                                    $naik_beam = "<span style='font-size:12px;color:red;'>Tidak Terdeteksi</span>";
                                }
                                $potongan_kain = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_produksi_mesin'=>$id_ajl]);
                                if($potongan_kain->num_rows() > 0){
                                    $_nilaipot = 0;
                                    foreach($potongan_kain->result() as $vp){
                                        $_nilaipot+= $vp->ukuran_meter;
                                        
                                    }
                                    $potn = "ada";
                                    $sisa_lusi = $ajl['pjg_lusi'] - $_nilaipot;
                                } else {
                                    $potn = "tidak ada";
                                    $sisa_lusi = $ajl['pjg_lusi'];
                                }
                                $produksi_hari_itu = $this->db->query("SELECT id_loom,no_mesin,produksi FROM loom_counter WHERE no_mesin='$mc' ORDER BY id_loom DESC LIMIT 1")->row("produksi");
                                $pjg_lusi_10persen = ($sisa_lusi * 10) / 100;
                                $rumus1 = $sisa_lusi - $pjg_lusi_10persen;
                                $rumus2 = $rumus1 - $produksi_hari_itu;
                                $rumus3 = $rumus2 / 265;
                            ?>
                            <tr class="trtrbgoke">
                                <td style="padding:0px;text-align:center;"><?=$no+1;?></td>
                                <?php if($status == "onproses"){ ?>
                                <td style="padding:0px;text-align:center;"><a href="<?=base_url('data/produksi/mesin/'.$id_ajl);?>" style="text-decoration:none;color:#425466;"><?=strtoupper($mc);?></a></td>
                                <td style="padding:0px;text-align:center;"><?=$ajl['konstruksi'];?></td>
                                <td style="padding:0px;text-align:center;"><?=$oka;?></td>
                                <td style="padding:0px;text-align:center;"><?=$beamsizing;?></td>
                                <td style="padding:0px;text-align:center;"><?=number_format($ajl['pjg_lusi']);?></td>
                                <td style="padding:0px;text-align:center;">
                                    <?php
                                    if($potn == "ada"){
                                        //echo $sisa_lusi;
                                        if($sisa_lusi < 0){
                                            echo "Error";
                                        } else {
                                            echo number_format($sisa_lusi);
                                        }
                                    } else {
                                        echo number_format($ajl['pjg_lusi']);
                                    }
                                    ?>
                                </td>
                                <td style="padding:0px;text-align:center;"><?=$naik_beam;?></td>
                                <td style="padding:0px;text-align:center;"><?=round($rumus3) < 1 ? '-':round($rumus3);?></td>
                                <td style="padding:0px;text-align:center;">
                                    <?php
                                    if($sisa_lusi < 245){
                                        $sisa_lusibagi245 = $sisa_lusi / 245;
                                        $jamnya = $sisa_lusibagi245 * 24;
                                        //echo "$jamnya";
                                        $jam_decimal = round($jamnya,1);

                                        // Menghitung jumlah jam
                                        $jam = floor($jam_decimal);
                                        
                                        // Menghitung jumlah menit
                                        $menit_decimal = ($jam_decimal - $jam) * 60;
                                        $menit = round($menit_decimal);
                                        
                                        // Membuat format waktu yang diinginkan
                                        $waktu = $jam . ' jam ' . $menit . ' menit';
                                        
                                        echo $waktu;
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                                <?php } else { ?>
                                    <td style="padding:0px;text-align:center;"><a href="<?=base_url('data/produksi/mesin/'.$id_ajl);?>" style="text-decoration:none;color:red;"><?=strtoupper($mc);?></a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                                <td style="padding:7px;text-align:center;">
                                    <?php
                                    
                                        if($status == "onproses"){
                                            echo "<span style='font-size:11px;background:green;color:#fff;padding:2px 10px;border-radius:3px;'>ON</span>";
                                        } else {
                                            echo "<span style='font-size:11px;background:red;color:#fff;padding:2px 10px;border-radius:3px;'>OFF</span>";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
        </tbody>
    </table>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS CDN -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- DataTables JS CDN -->
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    let table = new DataTable('#myTable', {
        lengthMenu: [10, 25, 50, 100, [ -1, "All" ]], // Menambahkan opsi Show all entries
        pageLength: 10 // Menentukan jumlah entri per halaman default
    });
});
</script>
</body>
</html>
