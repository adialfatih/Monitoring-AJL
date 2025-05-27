<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Operator Mesin AJL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=base_url('assets/');?>style3.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>hamb.css">
    <style>
        .floating-btn {
            position: absolute;
            right:20px;
            bottom:20px;
            position: fixed;
            z-index: 9999;
            width:50px;
            height:50px;
            overflow:hidden;
            background:#fff;
            box-shadow:1px 2px 10px #ccc, 1px -2px 10px #ccc;
            padding:5px;
            border-radius:50%;
        }
        .floating-btn img {
            width:100%;
        }
        .mobile-menu2 {
            width:100%;
            height:100vh;
            position: absolute;
            left:0;
            top:0;
            background:#fff;
            z-index: 999;
            position: fixed;
            transition: all .5s ease;
            transform: translateX(100%);
            color:#000;
            padding-top : 100px;
        }
        .mobile-menu2.active {
            transform: translateX(0);
        }
        .toggle { z-index: 9999; }
        .mobile-menu2 a{text-decoration:none;}
        .menu-btn {
            width:100%;
            height:50px;
            padding:10px;
            color:#000;
            border-bottom:1px solid #e2e2e2;
        }
        .type1:hover {
            background:#7eb4f7;color:#fff;border-radius:4px 7px;
        }
        .type2:hover {
            background:#0e9fc7;color:#fff;border-radius:4px 7px;
        }
        .type3:hover {
            background:#2c63d1;color:#fff;border-radius:4px 7px;
        }
        .type4:hover {
            background:red;color:#fff;border-radius:4px 7px;
        }
        table tr td, table tr th {padding:5px;}
        table tr th {background:#333333;color:#fff;}
        table tr:hover {background:#d1d1d1;color:#333;}
        .clsBtnClick {
            text-decoration:none;
            background:#067bc9;
            color:#fff;
            padding:10px 15px;
            border-radius:4px;
        }
    </style>
</head>
<body>
    
    <?php 
        $uri = $this->uri->segment(3); 
        $nmuser = $this->session->userdata('nama');
        $akses = $this->session->userdata('akses');
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;z-index:9999;"> 
        <span style="font-size:1.2em;margin:10px;color:#1561e6;font-weight:bold;">Produksi Mesin</span>
        
        <input id="checkbox" type="checkbox">
        <label class="toggle" for="checkbox">
            <div id="bar1" class="bars"></div>
            <div id="bar2" class="bars"></div>
            <div id="bar3" class="bars"></div>
        </label>
    </div>
    <div class="mobile-menu2">
        <a href="<?=base_url('mesin');?>">
            <div class="menu-btn type1">Monitoring Mesin</div>
        </a>
        <a href="<?=base_url('produksi-mesin');?>">
            <div class="menu-btn type3">Produksi Mesin</div>
        </a>
        <a href="<?=base_url('operator-loom');?>">
            <div class="menu-btn type1">Operator Mesin</div>
        </a>
        
        <?php if($akses == "loom" OR $akses == "admin"){ ?>
        <a href="<?=base_url('data-loom');?>">
            <div class="menu-btn type2">Loom Counter</div>
        </a>
        <?php } else { ?>
        <a href="#" onclick="noakses('Loom Counter')">
            <div class="menu-btn type2">Loom Counter</div>
        </a>
        <?php } ?>


        <?php if($akses == "sizing" OR $akses == "admin" OR $akses == "beam"){ ?>
        <a href="<?=base_url('input-sizing-opt');?>">
            <div class="menu-btn type3">Produksi Sizing</div>
        </a>
        <?php } else { ?>
        <a href="#" onclick="noakses('Produksi Sizing')">
            <div class="menu-btn type3">Produksi Sizing</div>
        </a>
        <?php } ?>

        <a href="<?=base_url('login-operator');?>">
            <div class="menu-btn type4">Logout</div>
        </a>
        <div></div>
    </div>
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
            '00' => 'NaN', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        );
        $prinTgl = $ex_tgl[2]." ".$ar[$ex_tgl[1]]." ".$ex_tgl[0];
        //$pl = explode('-', $cek['tgl_produksi']);
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulanIni, $tahunIni);
        
    ?>
    <!-- <small style="margin:-5px 10px 10px 10px;"><=$newToday;?>, <strong><=$prinTgl;?></strong> User : <strong id="nmoptid"><=$nmuser;?></strong></small> -->
    
    <small style="margin:-5px 10px 10px 10px;z-index:9999;">User : <strong id="nmoptid"><?=$nmuser;?></strong></small>
    <div style="width:100%;display:flex;justify-content:flex-end;padding:0 10px 0 0;">
    <a href="<?=base_url('add-produksi-mesin');?>" class="clsBtnClick">+ Input Produksi</a>
    </div>
    
    
    <div class="container" style="min-width:100%;overflow-x:auto;margin-top:10px;padding:10px;">
        <table border="1" style="min-width:100%;border-collapse:collapse;">
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Group</th>
                <th>Produksi</th>
            </tr>
        
        <?php
        for ($tanggal = 1; $tanggal <= $jumlahHari; $tanggal++) {
            $tanggalFormat = sprintf('%d-%02d-%02d', $tahunIni, $bulanIni, $tanggal);
            //echo $tanggalFormat . "<br>";
            $x = explode('-', $tanggalFormat);
            $showTgl = $x[2]."-".$ar[$x[1]]."";
            echo "<tr>";
            echo "<td rowspan='3' style='text-align:center;'>".$showTgl."</td>";
            //shift 1 
            $cekProd1 = $this->db->query("SELECT * FROM z_prodmesin WHERE tgl_produksi='$tanggalFormat' AND shift='1'");
            if($cekProd1->num_rows() == 1){
                $idzpm1 = $cekProd1->row("id_zpm");
                $produksi1 = $this->db->query("SELECT SUM(produksi) AS pr FROM z_report_produksi WHERE id_zpm='$idzpm1'")->row("pr");
                echo "<td style='text-align:center;'>".$cekProd1->row('shift')."</td>";
                echo "<td style='text-align:center;'>".$cekProd1->row('grub_op')."</td>";
                echo "<td style='text-align:center;'>".round($produksi1,2)."</td>";
            } else {
                echo "<td>-</td>";
                echo "<td>-</td>";
                echo "<td>-</td>";
            }
            echo "</tr>";

            echo "<tr>";
            $cekProd2 = $this->db->query("SELECT * FROM z_prodmesin WHERE tgl_produksi='$tanggalFormat' AND shift='2'");
            if($cekProd2->num_rows() == 1){
                $idzpm2 = $cekProd2->row("id_zpm");
                $produksi2 = $this->db->query("SELECT SUM(produksi) AS pr FROM z_report_produksi WHERE id_zpm='$idzpm2'")->row("pr");
                echo "<td style='text-align:center;'>".$cekProd2->row('shift')."</td>";
                echo "<td style='text-align:center;'>".$cekProd2->row('grub_op')."</td>";
                echo "<td style='text-align:center;'>".round($produksi2,2)."</td>";
            } else {
                echo "<td>-</td>";
                echo "<td>-</td>";
                echo "<td>-</td>";
            }
            echo "</tr>";
            echo "<tr>";
            $cekProd3 = $this->db->query("SELECT * FROM z_prodmesin WHERE tgl_produksi='$tanggalFormat' AND shift='3'");
            if($cekProd3->num_rows() == 1){
                $idzpm3 = $cekProd3->row("id_zpm");
                $produksi3 = $this->db->query("SELECT SUM(produksi) AS pr FROM z_report_produksi WHERE id_zpm='$idzpm3'")->row("pr");
                echo "<td style='text-align:center;'>".$cekProd3->row('shift')."</td>";
                echo "<td style='text-align:center;'>".$cekProd3->row('grub_op')."</td>";
                echo "<td style='text-align:center;'>".round($produksi3,2)."</td>";
            } else {
                echo "<td>-</td>";
                echo "<td>-</td>";
                echo "<td>-</td>";
            }
            echo "</tr>";
        }
        ?>
        </table>
    </div>
    
    
    
    <div style="width:100%;height:250px;">&nbsp;</div>
    <div class="popupfull">
        <div class="modals">
            <div class="modalsheader">
                <h1 id="jdlmdl">Judul Modal</h1>
                <span onclick="hidemodal()">x</span>
            </div>
            <div class="modalsbody">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Labore aliquid a et. Non unde nisi rerum nostrum illo, reprehenderit assumenda inventore recusandae hic odit odio, rem voluptatem natus iure ea!
            </div>
            
        </div>
    </div>
    <a href="<?=base_url('operator/mesin');?>">
    <div class="floating-btn" id="returnToHomessd">
        <img src="<?=base_url('assets/home.png');?>" alt="Home">
    </div>
    </a>


    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    
    <script>
      const autoCompleteJS = new autoComplete({
            placeHolder: "Konstruksi",
            data: {
                src: [ "RJ02", "RJ03", "RJ04", "RJ05A", "RJ05B", "RJ05C", "RJ05D", "RJ05F", "RJ05G", "RJ05H", "RJ13", "RJ13A", "RJ13B", "RJ13C", "RJ13C", "RJ15", "RJ15A", "RJ15B", "RJ15C", "RJ15D", "RJ15H", "RJ15J", "RJ15K", "RJ15L", "RJ16", "RJ17", "RJ17A", "RJ18", "RJ16A", "RJ16B", "RJ19", "RJ05K", "RJ13D", "RJ09A", "RJ03A", "RJ05J", "RJ20","RJ13E", "RJ05L", "RJ05L_RJS", "RJ13F", "RJ13G", "RJ13H", "RJ13J", "RJ05M", "RJ17B", "RJ05B_RJS", "RJ13K", "RJ13L", "GS15", "GS05", "GS06", "LC140", "LC158", "RJ16C"
                ],
                cache: true,
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const selection = event.detail.selection.value;
                        autoCompleteJS.input.value = selection;
                        loaddatamesin(selection);
                    }
                }
            }
        });
        $('#checkbox').on('change', function() {
            if (this.checked) {
                $('.mobile-menu2').addClass('active');
            } else {
                $('.mobile-menu2').removeClass('active');
            }
        });
    </script>
</body>
</html>