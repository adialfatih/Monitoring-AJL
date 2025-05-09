<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
    <title>Monitoring Mesin AJL (Loom Counter)</title>
    <style>
        * {
            margin:0;
            padding:0;
            box-sizing:border-box;
            /* font-family: 'Playfair Display', serif; */
            font-family: 'Roboto', sans-serif;
            position: relative;
        }
        h1, h2 { font-family: 'Playfair Display', serif;}
        html, body {
            scroll-behavior:smooth;
        }
        .full, .full-right {
            width:100%;
            display:flex;
            align-items:center;
            padding:30px;
        }
        .full-right { justify-content:flex-end;}
        .back {
            width:30px;
            height:30px;
            cursor: pointer;
        }
        .back img {width:100%;}
        .full h1 {
            font-size : 1.5em;
            margin-left:20px;
        }
        .boxfilter {
            display:flex;
            align-items:center;
        }
        .full input {
            width:250px;
            outline:none;
            border:1px solid #ccc;
            padding: 10px 8px;
            margin-left:10px;
            border-radius:5px;
        }
        .full.nopad {padding:0 30px;}
        .table-responsive {
            width:100%;
            padding:15px 30px;
            overflow-x:auto;
        }
        .table-responsive table {width:100%;}
        .table-responsive table thead tr th {font-size:14px;padding:5px;}
        .table-responsive table tbody tr td {font-size:14px;padding:5px;}
        .table-responsive table thead tr th sup {font-size:8px;}
        .btnbtn {
            margin-left:15px;background:#dadee3;padding:10px 20px;border-radius:5px;text-decoration:none;color:#000;
        }
        .btnbtn:hover, .btnbtn.active {background:#2473d6;color:#fff;}
        #daterange:hover {
            border:1px solid #2473d6;
        }
        .trbgbg {background:#ebebeb;border:1px solid #000;}
        .trbgbg:hover {background:#949494;border:1px solid #000;}
        .tooltip {
            display: none;
            position: absolute;
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            z-index: 1;
            font-size:12px;
        }

        .tooltip.show {
        display: block;
        }


    </style>
</head>
<body>
    <?php
        $hariIni = new DateTime();
        $sf = $hariIni->format('l F Y, H:i');
        $ex = explode(' ', $sf);
        $nToday = $ex[0];
        //echo $nToday;
        $hariIndo = ["Sunday"=>"Minggu", "Monday"=>"Senin", "Tuesday"=>"Selasa", "Wednesday"=>"Rabu", "Thursday"=>"Kamis", "Friday"=>"Jumat", "Saturday"=>"Sabtu"];
        $newToday = $hariIndo[$nToday];
        //echo $newToday;
        $tgl = date('Y-m-d');
        $ex_tgl = explode('-', $tgl);
        //echo $tgl;
        $ar = array(
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $prinTgl = $ex_tgl[2]." ".$ar[$ex_tgl[1]]." ".$ex_tgl[0];
        $uri3 = $this->uri->segment(3);
        //$uri4 = $this->uri->segment(4);
        if($uri3!="") {
            
                $ex1 = explode('-', $uri3);
                $txt1 = $ex1[2]." ".$ar[$ex1[1]]." ".$ex1[0];
                $txt = "Menampilkan data loom counter mesin AJL Tanggal <strong>".$txt1."</strong>";
                $val_range = "".$uri3."";
                //$val_range = "".$ex1[1]."/".$ex1[2]."/".$ex1[0]." - ".$ex1[1]."/".$ex1[2]."/".$ex1[0]."";
                $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$uri3' GROUP BY no_mesin");
            
        } else {
            $txt = "Menampilkan data loom counter mesin AJL hari ini <strong>".$newToday."</strong>,  <strong>".$prinTgl."</strong>";
            $val_range = date('Y-m-d');
            //$val_range = "".$ex_tgl[1]."/".$ex_tgl[2]."/".$ex_tgl[0]." - ".$ex_tgl[1]."/".$ex_tgl[2]."/".$ex_tgl[0]."";
            $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$tgl' GROUP BY no_mesin");
        }
    ?>
    <div class="full">
        <div class="back" id="backid"><img src="<?=base_url('logo/back.svg');?>" alt="Kembali"></div>
        <h1>Monitoring Mesin AJL</h1>
    </div>
    <div class="full nopad">
        <label for="daterange">Tanggal</label>
        <input type="date" id="daterange" name="daterange" value="<?=$val_range;?>" />
        <a href="javascript:;" id="klikShift1" class="btnbtn">Shift 1</a>
        <a href="javascript:;" id="klikShift2" class="btnbtn">Shift 2</a>
        <a href="javascript:;" id="klikShift3" class="btnbtn">Shift 3</a>
        <a href="javascript:;" id="klikAkumulasi" class="btnbtn active">Akumulasi 3 Shift</a>
        <a href="<?=base_url('monitoring/loom/month');?>" id="reportbulanan" class="btnbtn">Report Bulanan</a>
    </div>
    
    <p style="width:100%;padding:10px 30px;" id="textID"><?=$txt;?></p>
    <div style="width:100%;padding:0 30px;display:flex;" id="textID2">
    <?php 
    $tgl_cetak = $val_range;
    for ($i=1; $i <4 ; $i++) { 
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND mati_or_not='1' ");
            $mc_jalan = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati = $mc_mati->num_rows();
            $cek_msna = $cek_a->row("jml_mesin");
            $cek_msnb = $cek_b->row("jml_mesin");
            $cek_msnc = $cek_c->row("jml_mesin");
            $cek_msnd = $cek_d->row("jml_mesin");
            $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
            $eff_b = round($cek_a->row("eff"),2) / intval($cek_b->row("jml_mesin"));
            $eff_c = round($cek_b->row("eff"),2) / intval($cek_c->row("jml_mesin"));
            $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
            if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a,1); }
            if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b,1); }
            if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c,1); }
            if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d,1); }
            $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
            $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
            $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
            $_total_prod = round($_total_produksi,2);
            echo '<div style="width:25%;padding:10px;border-radius:2px;background:#ccc;font-size:14px;margin-right:10px;" id="textID'.$i.'">';
            echo "<strong>Shift ".$i."</strong><br>";
            echo "<div style='width:100%;height:1px;background:#000;margin-top:5px;'>&nbsp;</div><br>";
            $grub = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$i' AND yg_input='$nama' ORDER BY id_loom DESC LIMIT 1 ")->row("group_shift");
            
            echo "Group : <strong>".$grub."</strong><br>";
            if($grub=="A"){
                $kasif = $this->db->query("SELECT kasif_a FROM data_kabag WHERE id_kabag='1'")->row("kasif_a");
                $karu = $this->db->query("SELECT karu_a FROM data_kabag WHERE id_kabag='1'")->row("karu_a");
            }
            if($grub=="B"){
                $kasif = $this->db->query("SELECT kasif_b FROM data_kabag WHERE id_kabag='1'")->row("kasif_b");
                $karu = $this->db->query("SELECT karu_b FROM data_kabag WHERE id_kabag='1'")->row("karu_b");
            }
            if($grub=="C"){
                $kasif = $this->db->query("SELECT kasif_c FROM data_kabag WHERE id_kabag='1'")->row("kasif_c");
                $karu = $this->db->query("SELECT karu_c FROM data_kabag WHERE id_kabag='1'")->row("karu_c");
            }
            echo "Kashift : <strong>".$kasif."</strong><br>";
            echo "Karu : <strong>".$karu."</strong><br>";
            ?>
            MC Jalan = <strong><?=$mc_jalan;?></strong><br>
            <?php if($jml_mcmati>0){ ?>
            MC Mati = <strong><?=$jml_mcmati;?></strong><br>
            <?php 
            
            $mc_mati_txt = "";
                foreach($mc_mati->result() as $tul){
                    echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                    $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                }
            } 
            ?>
            <br>
            RAYON<br>
            Total Produksi Rayon<br>
            Line A (<strong><?=round($cek_msna);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
            Line B (<strong><?=round($cek_msnb);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
            Line C (<strong><?=round($cek_msnc);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
            Line D (<strong><?=round($cek_msnd);?></strong> MC)<br>
            Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
            --------------------------------------------<br>
            <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
            Produksi = <strong><?=number_format($_total_prod,1,',','.');?></strong> Meter, Effisiensi = <strong><?=round($_rata_eff2,1);?></strong>
            <?php
            
            echo "</div>";
        }
            $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
            $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
            $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
            $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
            $cek_msna = $cek_a->row("jml_mesin") / 3;
            $cek_msnb = $cek_b->row("jml_mesin") / 3;
            $cek_msnc = $cek_c->row("jml_mesin") / 3;
            $cek_msnd = $cek_d->row("jml_mesin") / 3;
            $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not='1' ");
            $mc_jalan2 = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));
            $mc_jalan3 = $mc_jalan2 / 3; 
            $mc_jalan = round($mc_jalan3);
            $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND mati_or_not!='1' ORDER BY no_mesin");
            $jml_mcmati2 = $mc_mati->num_rows() / 3;
            $jml_mcmati = round($jml_mcmati2);
            $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
            $eff_b = round($cek_a->row("eff"),2) / intval($cek_b->row("jml_mesin"));
            $eff_c = round($cek_b->row("eff"),2) / intval($cek_c->row("jml_mesin"));
            $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
            if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a,1); }
            if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b,1); }
            if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c,1); }
            if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d,1); }
            $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
            $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
            $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
            $_total_prod = round($_total_produksi,2);
        echo '<div style="width:25%;padding:10px;border-radius:2px;background:#ccc;font-size:14px;margin-right:10px;" id="textID'.$i.'">';
        echo "<strong>Akumulasi 3 Shift</strong><br>";
        echo "<div style='width:100%;height:1px;background:#000;margin-top:5px;'>&nbsp;</div><br>";
        ?>
        MC Jalan = <strong><?=$mc_jalan;?></strong><br>
        MC Mati = <strong><?=$jml_mcmati;?></strong><br>
        <?php 
        // $mc_mati_txt = "";
        //     foreach($mc_mati->result() as $tul){
        //         echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
        //         $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
        //     }
        //  }
        ?>
        <br>
        RAYON<br>
        Total Produksi Rayon<br>
        Line A (<strong><?=round($cek_msna);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
        Line B (<strong><?=round($cek_msnb);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
        Line C (<strong><?=round($cek_msnc);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
        Line D (<strong><?=round($cek_msnd);?></strong> MC)<br>
        Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Effisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
        --------------------------------------------<br>
        <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
        Produksi = <strong><?=number_format($_total_prod,1,',','.');?></strong> Meter, Effisiensi = <strong><?=round($_rata_eff2,1);?></strong>
        <?php
        echo "</div>";
        ?>
    </div>
    <div class="table-responsive">
        <table id="tableBody">
            <thead>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No Mesin</th>
                    <th colspan="6">Putus Benang 3 Shift</th>
                    <th rowspan="2">EFF %</th>
                    <th rowspan="2">Produksi</th>
                    <th rowspan="2">Prod Teoritis 3 Shift</th>
                    <th rowspan="2">EFF % Rill 3 Shift</th>
                    <th rowspan="2">Prod 100%</th>
                    <th colspan="2">Selisih</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th>LS</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>PKN</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>Produksi 3 Shift</th>
                    <th>EFF% counter VS EFF% Rill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n=1;
                    foreach($_query->result() as $val):
                    $_tgl = $val->tgl;
                    $_mc = $val->no_mesin;
                    $cekshif1 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='1' AND no_mesin='$_mc'");
                    if($cekshif1->num_rows()>0){
                        $_idsf1 = $cekshif1->row("id_loom");
                        $cek_cbng1 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf1'");
                        if($cek_cbng1->num_rows() > 0){
                            $bagi1 = $cek_cbng1->num_rows();
                            $_ls_cbg1 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $lsc_mnt1 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $pkn_cb1 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $mnt_cb1 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $eff_cb1 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $rpm_cb1 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $pick_cb1 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                            $prod_cb1 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf1'")->row("lsc1");
                        } else {
                            $bagi1 = 0;
                        }
                    }
                    $cekshif2 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='2' AND no_mesin='$_mc'");
                    if($cekshif2->num_rows()>0){
                        $_idsf2 = $cekshif2->row("id_loom");
                        $cek_cbng2 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf2'");
                        if($cek_cbng2->num_rows() > 0){
                            $bagi2 = $cek_cbng2->num_rows();
                            $_ls_cbg2 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $lsc_mnt2 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $pkn_cb2 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $mnt_cb2 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $eff_cb2 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $rpm_cb2 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $pick_cb2 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                            $prod_cb2 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf2'")->row("lsc1");
                        } else {
                            $bagi2 = 0;
                        }
                    }
                    $cekshif3 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='3' AND no_mesin='$_mc'");
                    if($cekshif3->num_rows()>0){
                        $_idsf3 = $cekshif3->row("id_loom");
                        $cek_cbng3 = $this->db->query("SELECT * FROM loom_counter2 WHERE id_loom_asli='$_idsf3'");
                        if($cek_cbng3->num_rows() > 0){
                            $bagi3 = $cek_cbng3->num_rows();
                            $_ls_cbg3 = $this->db->query("SELECT SUM(ls) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $lsc_mnt3 = $this->db->query("SELECT SUM(ls_mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $pkn_cb3 = $this->db->query("SELECT SUM(pkn) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $mnt_cb3 = $this->db->query("SELECT SUM(mnt) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $eff_cb3 = $this->db->query("SELECT SUM(eff) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $rpm_cb3 = $this->db->query("SELECT SUM(rpm) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $pick_cb3 = $this->db->query("SELECT SUM(pick) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                            $prod_cb3 = $this->db->query("SELECT SUM(produksi) AS lsc1 FROM loom_counter2 WHERE id_loom_asli='$_idsf3'")->row("lsc1");
                        } else {
                            $bagi3 = 0;
                        }
                    }

                    if($cekshif1->num_rows() == 1 AND $cekshif2->num_rows() == 1 AND $cekshif3->num_rows() == 1){
                        $_status = "oke";
                    } else {
                        $_status = "erorr";
                    }
                    if($cekshif1->row("ls")==""){ $ls1_all = 0; } else { 
                        $ls1=$cekshif1->row("ls"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $ls1_all = ($ls1 + $_ls_cbg1) / $pembagi1;
                        } else {
                            $ls1_all = $cekshif1->row("ls"); 
                        }
                    }
                    if($cekshif2->row("ls")==""){ $ls2_all = 0; } else { 
                        $ls2=$cekshif2->row("ls"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $ls2_all = ($ls2 + $_ls_cbg2) / $pembagi2;
                        } else {
                            $ls2_all = $cekshif2->row("ls"); 
                        }
                    }
                    if($cekshif3->row("ls")==""){ $ls3_all = 0; } else { 
                        $ls3=$cekshif3->row("ls"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $ls3_all = ($ls3 + $_ls_cbg3) / $pembagi3;
                        } else {
                            $ls3_all = $cekshif3->row("ls"); 
                        }
                    }
                    if($cekshif1->row("ls_mnt")==""){ $ls_mnt1_all = 0; } else { 
                        $ls_mnt1=$cekshif1->row("ls_mnt"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $ls_mnt1_all = ($ls_mnt1 + $lsc_mnt1) / $pembagi1;
                        } else {
                            $ls_mnt1_all = $cekshif1->row("ls_mnt"); 
                        }
                    }
                    if($cekshif2->row("ls_mnt")==""){ $ls_mnt2_all = 0; } else { 
                        $ls_mnt2=$cekshif2->row("ls_mnt"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $ls_mnt2_all = ($ls_mnt2 + $lsc_mnt2) / $pembagi2;
                        } else {
                            $ls_mnt2_all = $cekshif2->row("ls_mnt"); 
                        }
                    }
                    if($cekshif3->row("ls_mnt")==""){ $ls_mnt3_all = 0; } else { 
                        $ls_mnt3=$cekshif3->row("ls_mnt");
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $ls_mnt3_all = ($ls_mnt3 + $lsc_mnt3) / $pembagi3;
                        } else {
                            $ls_mnt3_all = $cekshif3->row("ls_mnt"); 
                        }
                    }
                    $all_LS = intval($ls1_all) + intval($ls2_all) + intval($ls3_all);
                    $all_mntLS = intval($ls_mnt1_all) + intval($ls_mnt2_all) + intval($ls_mnt3_all);
                    $rtlost1 = $all_mntLS / $all_LS;
                    if(is_nan($rtlost1)){
                        $rtlost1 = "0";
                    }
                    if($cekshif1->row("pkn")==""){ $pkn1_all = 0; } else { 
                        $pkn1=$cekshif1->row("pkn"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $pkn1_all = ($pkn1 + $pkn_cb1) / $pembagi1;
                        } else {
                            $pkn1_all = $cekshif1->row("pkn"); 
                        }
                    }
                    if($cekshif2->row("pkn")==""){ $pkn2_all = 0; } else { 
                        $pkn2=$cekshif2->row("pkn"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $pkn2_all = ($pkn2 + $pkn_cb2) / $pembagi2;
                        } else {
                            $pkn2_all = $cekshif2->row("pkn"); 
                        }
                    }
                    if($cekshif3->row("pkn")==""){ $pkn3_all = 0; } else { 
                        $pkn3=$cekshif3->row("pkn"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $pkn3_all = ($pkn3 + $pkn_cb3) / $pembagi3;
                        } else {
                            $pkn3_all = $cekshif3->row("pkn"); 
                        }
                    }
                    if($cekshif1->row("mnt")==""){ $mnt1_all = 0; } else { 
                        $mnt1=$cekshif1->row("mnt"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $mnt1_all = ($mnt1 + $mnt_cb1) / $pembagi1;
                        } else {
                            $mnt1_all = $cekshif1->row("mnt"); 
                        }
                    }
                    if($cekshif2->row("mnt")==""){ $mnt2_all = 0; } else { 
                        $mnt2=$cekshif2->row("mnt"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $mnt2_all = ($mnt2 + $mnt_cb2) / $pembagi2;
                        } else {
                            $mnt2_all = $cekshif2->row("mnt"); 
                        }
                    }
                    if($cekshif3->row("mnt")==""){ $mnt3_all = 0; } else { 
                        $mnt3=$cekshif3->row("mnt"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $mnt3_all = ($mnt3 + $mnt_cb3) / $pembagi3;
                        } else {
                            $mnt3_all = $cekshif3->row("mnt"); 
                        }
                    }
                    $all_Pkn = intval($pkn1_all) + intval($pkn2_all) + intval($pkn3_all);
                    $all_mntPkn = intval($mnt1_all) + intval($mnt2_all) + intval($mnt3_all);
                    $rtlost2 = $all_mntPkn / $all_Pkn;
                    if(is_nan($rtlost2)){
                        $rtlost2 = "0";
                    }
                    if($cekshif1->row("eff")==""){ $eff_sif1_all = 0; } else { 
                        $eff_sif1=$cekshif1->row("eff"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $eff_sif1_all = ($eff_sif1 + $eff_cb1) / $pembagi1;
                        } else {
                            $eff_sif1_all = $cekshif1->row("eff"); 
                        }
                    }
                    if($cekshif2->row("eff")==""){ $eff_sif2_all = 0; } else { 
                        $eff_sif2=$cekshif2->row("eff"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $eff_sif2_all = ($eff_sif2 + $eff_cb2) / $pembagi2;
                        } else {
                            $eff_sif2_all = $cekshif2->row("eff"); 
                        }
                    }
                    if($cekshif3->row("eff")==""){ $eff_sif3_all = 0; } else { 
                        $eff_sif3=$cekshif3->row("eff"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $eff_sif3_all = ($eff_sif3 + $eff_cb3) / $pembagi3;
                        } else {
                            $eff_sif3_all = $cekshif3->row("eff"); 
                        }
                    }
                    if($cekshif1->row("produksi")==""){ $prod1_all = 0; } else { 
                        $prodsif1=$cekshif1->row("produksi"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $prod1_all = ($prodsif1 + $prod_cb1) / $pembagi1;
                        } else {
                            $prod1_all = $cekshif1->row("produksi"); 
                        }
                    }
                    if($cekshif2->row("produksi")==""){ $prod2_all = 0; } else { 
                        $prodsif2=$cekshif2->row("produksi"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $prod2_all = ($prodsif2 + $prod_cb2) / $pembagi2;
                        } else {
                            $prod2_all = $cekshif2->row("produksi"); 
                        }
                    }
                    if($cekshif3->row("produksi")==""){ $prod3_all = 0; } else { 
                        $prodsif3=$cekshif3->row("produksi"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $prod3_all = ($prodsif3 + $prod_cb3) / $pembagi3;
                        } else {
                            $prod3_all = $cekshif3->row("produksi"); 
                        }
                    }

                    $eff = $eff_sif1_all + $eff_sif2_all + $eff_sif3_all;
                    $produksi = $prod1_all + $prod2_all + $prod3_all;
                    $jml_eff = round($eff,1);
                    $jml_effbagi3 = $jml_eff / 3;
                    $jml_effbagi3_koma1 = round($jml_effbagi3,1);
                    $eff_percen = $jml_effbagi3_koma1 / 100;

                    if($cekshif1->row("rpm")==""){ $rpm1_all = 0; } else { 
                        $rpm1=$cekshif1->row("rpm"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $rpm1_all = ($rpm1 + $rpm_cb1) / $pembagi1;
                        } else {
                            $rpm1_all = $cekshif1->row("rpm"); 
                        }
                    }
                    if($cekshif2->row("rpm")==""){ $rpm2_all = 0; } else { 
                        $rpm2=$cekshif2->row("rpm"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $rpm2_all = ($rpm2 + $rpm_cb2) / $pembagi2;
                        } else {
                            $rpm2_all = $cekshif2->row("rpm"); 
                        }
                    }
                    if($cekshif3->row("rpm")==""){ $rpm3_all = 0; } else { 
                        $rpm3=$cekshif3->row("rpm"); 
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $rpm3_all = ($rpm3 + $rpm_cb3) / $pembagi3;
                        } else {
                            $rpm3_all = $cekshif3->row("rpm"); 
                        }
                    }
                    
                    $rpm4 = ($rpm1_all + $rpm2_all + $rpm3_all) / 3;
                    $rpm = round($rpm4);

                    if($cekshif1->row("pick")==""){ $pick1_all = 0; } else { 
                        $pick1=$cekshif1->row("pick"); 
                        if($bagi1!=0){
                            $pembagi1 = 1 + $bagi1;
                            $pick1_all = ($pick1 + $pick_cb1) / $pembagi1;
                        } else {
                            $pick1_all = $cekshif1->row("pick"); 
                        }
                    }
                    if($cekshif2->row("pick")==""){ $pick2_all = 0; } else { 
                        $pick2=$cekshif2->row("pick"); 
                        if($bagi2!=0){
                            $pembagi2 = 1 + $bagi2;
                            $pick2_all = ($pick2 + $pick_cb2) / $pembagi2;
                        } else {
                            $pick2_all = $cekshif2->row("pick"); 
                        }
                    }
                    if($cekshif3->row("pick")==""){ $pick3 = 0; } else { 
                        $pick3=$cekshif3->row("pick");
                        if($bagi3!=0){
                            $pembagi3 = 1 + $bagi3;
                            $pick3_all = ($pick3 + $pick_cb3) / $pembagi3;
                        } else {
                            $pick3_all = $cekshif3->row("pick"); 
                        } 
                    }
                    $pick4 = ($pick1_all + $pick2_all + $pick3_all) / 3;
                    $pick = round($pick4);
                    
                    
                    $protis3 = ($rpm / $pick) * 60 * 24 * 0.0254 * $eff_percen;
                    if(is_nan($protis3)){
                        $protis3 = "0";
                    }
                    $eff_percen_riil = ($produksi * $pick * 39.37) / $rpm / 60 / 24;
                    $percen_kan = $eff_percen_riil * 100;
                    if(is_nan($percen_kan)){
                        $percen_kan2 = 0;
                    } else {
                        $percen_kan2 = round($percen_kan,2); 
                    }
                    $produksi_100 = ($rpm / $pick) * 60 * 24 * 0.0254;
                    if(is_nan($produksi_100)){ $produksi_100=0; }
                    $selisih1 = $produksi - round($protis3,1);
                    $selisih2 = round($jml_effbagi3,2) - $percen_kan2;
                ?>
                <tr class="trbgbg" onmouseover="showPopup(this)" onmouseout="hidePopup()">
                    <th><?=$n;?></th>
                    <td style="text-align:center;">
                        <?=$val->no_mesin;?>
                        <!-- <sup style="background:#f7a520;color:#f20707;width:15px;height:15px;display:flex;align-items:center;justify-content:center;border-radius:50%;">i</sup> -->
                    </td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>"><?=$all_LS;?></td>
                    <td style="text-align:center;<?=$all_mntLS==0?'background:red;color:#fff;':'';?>"><?=$all_mntLS;?></td>
                    <td style="text-align:center;<?=$rtlost1==0?'background:red;color:#fff;':'';?>"><?=round($rtlost1,1);?></td>
                    <td style="text-align:center;<?=$all_Pkn==0?'background:red;color:#fff;':'';?>"><?=$all_Pkn;?></td>
                    <td style="text-align:center;<?=$all_mntPkn==0?'background:red;color:#fff;':'';?>"><?=$all_mntPkn;?></td>
                    <td style="text-align:center;<?=$rtlost2==0?'background:red;color:#fff;':'';?>"><?=round($rtlost2,1);?></td>
                    <td style="text-align:center;<?=$jml_effbagi3<1 ?'background:red;color:#000;':'';?><?=$jml_effbagi3>0 && $jml_effbagi3<75 ?'background:orange;color:#000;':'';?>"><?=round($jml_effbagi3,1);?> %</td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>"><?=$produksi;?></td>
                    <td style="text-align:center;<?=$protis3==0?'background:red;color:#fff;':'';?>"><?=round($protis3,1);?></td>
                    <td style="text-align:center;<?=$percen_kan2==0?'background:red;color:#fff;':'';?>"><?=$percen_kan2;?> %</td>
                    
                    <td style="text-align:center;<?=$produksi_100==0?'background:red;color:#fff;':'';?>"><?=round($produksi_100,2);?></td>
                    <td style="text-align:center;<?=$selisih1>5 ?'background:orange;color:#000;':'';?><?=$selisih1 < -5 ?'background:orange;color:#000;':'';?>"><?=round($selisih1,2);?></td>
                    <td style="text-align:center;<?=$selisih2>5?'background:orange;color:#000;':'';?>"><?=round($selisih2,2);?> %</td>
                    <td style="text-align:center;">
                        <?php if($_status == "oke"){ ?>
                        <span class="material-symbols-outlined" style="color:green;font-size:18px;">done_all</span>
                        <?php } else { ?>
                        <span class="material-symbols-outlined" style="color:red;font-size:18px;cursor:pointer;" title="Data belum lengkap 3 shift" onclick="infonot3shift()">info</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                        $n++;
                    endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <div id="tooltip" class="tooltip">
  Tooltip Content
</div>

    <input type="hidden" id="tglNow" value="<?=date('Y-m-d');?>">
    <input type="hidden" id="shiftNow" value="0">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    
    <script>
        $(function() {
            $('input[name="daterange2"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                var tglawal = start.format('YYYY-MM-DD');
                var tglakir = end.format('YYYY-MM-DD');
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                window.location.href = '<?=base_url('monitoring/loom/');?>'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD');
            });
        });
        $( "#backid" ).on( "click", function() {
            window.location.href = "<?=base_url('beranda');?>";
        } );
        $( "#klikAkumulasi" ).on( "click", function() {
            $('#shiftNow').val('0');
            $('#klikAkumulasi').addClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift2').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift1" ).on( "click", function() {
            $('#shiftNow').val('1');
            $('#klikShift1').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift2').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift2" ).on( "click", function() {
            $('#shiftNow').val('2');
            $('#klikShift2').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift3" ).on( "click", function() {
            $('#shiftNow').val('3');
            $('#klikShift3').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift2').removeClass('active');
            loadData();
        } );
        $( "#daterange" ).on( "change", function() {
            loadData();
        } );
        $(".error12").on( "click", function() {
            Swal.fire("Data tidak lengkap 3 Shift");
        } );
        function loadData(){
            var ch = $('#daterange').val();
            var sif = $('#shiftNow').val();
            $.ajax({
                url:"<?=base_url('prosesajax/showTable');?>",
                type: "POST",
                data: {"ch" : ch, "sif" : sif},
                cache: false,
                success: function(dataResult){ 
                    $('#tableBody').html(dataResult);
                }
            });
            $.ajax({
                url:"<?=base_url('prosesajax/showTableText');?>",
                type: "POST",
                data: {"ch" : ch, "sif" : sif},
                cache: false,
                success: function(dataResult){ 
                    $('#textID').html(dataResult);
                }
            });
            $('#textID2').html('<span style="color:red">Loading... Sedang memuat data...</span>');
            if(sif != 0){
                $.ajax({
                    url:"<?=base_url('prosesajax/showTableText2');?>",
                    type: "POST",
                    data: {"ch" : ch, "sif" : sif},
                    cache: false,
                    success: function(dataResult){ 
                        $('#textID2').html(dataResult);
                    }
                });
            } else {
                $.ajax({
                    url:"<?=base_url('prosesajax/showTableText3');?>",
                    type: "POST",
                    data: {"ch" : ch, "sif" : sif},
                    cache: false,
                    success: function(dataResult){ 
                        $('#textID2').html(dataResult);
                    }
                });
            }
            let timerInterval;
            Swal.fire({
            title: "Loading Data",
            html: "Please Wait ....",
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                //timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
            }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                //console.log("I was closed by the timer");
            }
            });
        }
        function infonot3shift(){
            Swal.fire({
            title: "Peringatan",
            text: "Data tidak lengkap dari 3 shift",
            icon: "warning"
            });
        }
        function notsesuai(){
            Swal.fire({
                title: "Peringatan",
                text: "Operator tidak menginput tepat waktu",
                icon: "warning"
            });
        }
        function auto_rata(msin,kons){
            Swal.fire({
                title: "INFO",
                text: "Nilai tersebut di ambil dari rata-rata "+msin+" Mesin produksi "+kons+"",
                icon: "info"
            });
        }
        function auto_rata2(){
            Swal.fire({
                title: "INFO",
                text: "Nilai tersebut merupakan hasil dari perhitungan otomatis.",
                icon: "info"
            });
        }
        
        function showTooltip(element, txt) {
            console.log(''+txt);
            if(txt=="" || txt=="null" || txt=="NULL"){} else {
            var tooltip = document.getElementById("tooltip");
            tooltip.textContent = "Keterangan : "+txt;
            var eleft = element.offsetLeft + 200;
            tooltip.style.left = eleft + "px";
            var etop = element.offsetTop + 600;
            tooltip.style.top = (etop + element.offsetHeight) + "px";
            tooltip.classList.add("show"); }
        }
        

        function hideTooltip() {
            var tooltip = document.getElementById("tooltip");
            tooltip.classList.remove("show");
        }
    </script>
</body>
</html>