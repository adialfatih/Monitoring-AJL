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
    $uri = $this->uri->segment(2);
    $tanggalHari = date('d M Y', strtotime($uri));
    if($uri==""){
        $tgl = date('Y-m-d');
    } else {
        $tgl = $uri;
    }
    $cek_data = $this->db->query("SELECT * FROM `a_lock_mesin` WHERE DATE_FORMAT(tgl_lock, '%Y-%m-%d') = '$tgl'");
    if($cek_data->num_rows() == 0){
        $classe = 'style="color:red;"';
    } else {
        $classe = '';
    }
?>
<div style="width:100%;padding:0 15px;">
    <div style="width:100%;display:flex;align-items:center;justify-content:space-between;">
        <h2 <?=$classe;?>>Data Produksi Mesin AJL - <?=$tanggalHari;?></h2>
        <span>Tampilkan Tanggal : <input type="date" id="tanggalChange" value="<?=$uri;?>" onchange="tanggalChange(this.value)"></span>
    </div>
   <hr>
    <table id="myTable" class="table table-bordered">
        <thead>
            <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>JAM</th>
                <th>NO MESIN</th>
                <th>KONSTRUKSI</th>
                <th>PANJANG LUSI</th>
                <th>JUMLAH POTONGAN</th>
                <th>TOTAL PANJANG POTONGAN</th>
                <th>SISA LUSI</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $konstruksi_array = array(); $mc_on =0; $mc_off=0;
        if($cek_data->num_rows() > 0){
            foreach ($cek_data->result() as $key => $value) {
                $x = explode(' ', $value->tgl_lock);
                $_kons = strtoupper($value->konstruksi);
                $printTanggal = date('d M Y', strtotime($x[0]));
                if(!in_array($_kons, $konstruksi_array)){
                    array_push($konstruksi_array, $_kons);
                }
                $no = $key +1;
                echo "<tr>";
                echo "<td>".$no."</td>";
                echo "<td>".$printTanggal."</td>";
                echo "<td>".$x[1]."</td>";
                echo "<td>".$value->nomesin."</td>";
                echo "<td>".$value->konstruksi."</td>";
                echo "<td>".$value->lusi."</td>";
                echo "<td>".$value->jumlah_potongan."</td>";
                echo "<td>".$value->total_pjg_potongan."</td>";
                echo "<td>".$value->sisalusi."</td>";
                echo "<td>";
                if($value->status_mesin == "ON"){
                    $mc_on++;
                    echo "<span style='font-size:11px;background:green;color:#fff;padding:2px 10px;border-radius:3px;'>ON</span>";
                } else {
                    $mc_off++;
                    echo "<span style='font-size:11px;background:red;color:#fff;padding:2px 10px;border-radius:3px;'>OFF</span>";
                }
                echo "</td></tr>";
            }
        } else {
            
        }
        ?>
        </tbody>
    </table>
    <?php
    echo "Resume :<br>";
    echo "1. Jumlah Mesin : <strong>".$cek_data->num_rows()."</strong><br>";
   
    $m=2;
    sort($konstruksi_array);
    foreach ($konstruksi_array as $key => $value) {
        $jmlmesin = $this->db->query("SELECT * FROM `a_lock_mesin` WHERE konstruksi = '$value' AND DATE_FORMAT(tgl_lock, '%Y-%m-%d') = '$tgl'")->num_rows();
        $sisalusi = $this->db->query("SELECT SUM(sisalusi) AS jml FROM `a_lock_mesin` WHERE konstruksi = '$value' AND DATE_FORMAT(tgl_lock, '%Y-%m-%d') = '$tgl'")->row("jml");
        echo $m.". Konstruksi <strong>".$value."</strong> jalan <strong>".$jmlmesin."</strong> mesin dengan total sisa lusi : <strong>".number_format($sisalusi)."</strong>";
        echo "<br>";
        $m++;
    }
    ?>
    <div style="width:100%;height:200px;">&nbsp;</div>
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
function tanggalChange(val){
    window.location.href = "<?=base_url('lock-ajl/');?>"+val;
}
</script>
</body>
</html>
