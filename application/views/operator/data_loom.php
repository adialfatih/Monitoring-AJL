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
    <link rel="stylesheet" href="<?=base_url('assets/');?>style2.css">
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
        .button2 {
            display: inline-block;
            transition: all 0.2s ease-in;
            position: relative;
            overflow: hidden;
            z-index: 1;
            color: #090909;
            padding: 10px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 0.5em;
            background: #e8e8e8;
            border: 1px solid #e8e8e8;
            box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
        }

            .button2:active {
            color: #666;
            box-shadow: inset 4px 4px 12px #c5c5c5, inset -4px -4px 12px #ffffff;
            }

            .button2:before {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%) scaleY(1) scaleX(1.25);
            top: 100%;
            width: 140%;
            height: 180%;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 50%;
            display: block;
            transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
            z-index: -1;
            }

            .button2:after {
            content: "";
            position: absolute;
            left: 55%;
            transform: translateX(-50%) scaleY(1) scaleX(1.45);
            top: 180%;
            width: 160%;
            height: 190%;
            background-color: #009087;
            border-radius: 50%;
            display: block;
            transition: all 0.5s 0.1s cubic-bezier(0.55, 0, 0.1, 1);
            z-index: -1;
            }

            .button2:hover {
            color: #ffffff;
            border: 1px solid #009087;
            }

            .button2:hover:before {
            top: -35%;
            background-color: #009087;
            transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
            }

            .button2:hover:after {
            top: -45%;
            background-color: #009087;
            transform: translateX(-50%) scaleY(1.3) scaleX(0.8);
            }
            .contener {
                width:100%;
                padding:0 10px;
                overflow-x:auto;
                margin-top:20px;
            }
            .clsBtn {
                width:100%;
                display:flex;
                justify-content:space-between;
                padding:0 10px;
            }
            .clsBtn div {
                display:flex;
                flex-direction:column;
            }
            .clsBtnClick {
                padding:4px 10px;
                font-size:13px;
                background:#116ebf;
                color:#fff;
                border-radius:3px;
                text-decoration:none;
                display:flex;
                align-items:center;
            }
            .trbgbg:hover {
                background:#ccc;
            }
    </style>
</head>
<body>
    <!-- Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aspernatur impedit delectus ipsum eligendi qui ut laborum, similique, doloremque assumenda itaque est voluptatem illum. Necessitatibus consequatur optio sapiente molestiae, architecto quae.
    <div class="loading-container">
        <div class="loading-skeleton"></div>
        <div class="loading-skeleton"></div>
        <div class="loading-skeleton"></div>
    </div> -->
    <!-- <form action="" method="post" class="formlogin">
    <div class="login-container">
        <div class="login-box">
            <img src="http://localhost:8080/rjsweaving/assets/logo_rjs2.jpg" alt="Logo RJS"> 
            <h1>Login Operator</h1>
            <label for="username">Username Operator</label>
            <input type="text" id="username" placeholder="Masukan Username" required>
            <label for="password" class="mt-15">Password Login</label>
            <input type="password" id="password" placeholder="Masukan Password" required>
        </div>
        
    </div>
    </form> -->
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;"> 
        <h1 style="font-size:1.2em;margin:10px;color:#1561e6;">Data Loom Counter</h1>
        
        <input id="checkbox" type="checkbox">
        <label class="toggle" for="checkbox">
            <div id="bar1" class="bars"></div>
            <div id="bar2" class="bars"></div>
            <div id="bar3" class="bars"></div>
        </label>
    </div>
    <a href="<?=base_url('operator/mesin');?>">
    <div class="floating-btn" id="returnToHomessd">
        <img src="<?=base_url('assets/home.png');?>" alt="Home">
    </div>
    </a>
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
        $nama = $this->session->userdata('nama');
    ?>
    
    <div class="clsBtn">
        <div>
            <small><?=$newToday;?>, <strong><?=$prinTgl;?></strong></small>
            <small>User : <strong><?=$nama;?></strong></small>
        </div>
        <a href="<?=base_url('input-loom');?>" class="clsBtnClick">+ Input Loom</a>
    </div>
    <input type="hidden" id="namaorang" value="<?=$nama;?>">
    <div class="contener">
        <table border="1" style="width:100%;border:1px solid #ccc;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:3px;">No.</th>
                <th style="padding:3px;">Tgl</th>
                <th style="padding:3px;">No.MC</th>
                <th style="padding:3px;">Shift</th>
                <th style="padding:3px;">Konstruksi</th>
                <th style="padding:3px;">Status</th>
            </tr>
        </thead>
        <tbody id="tbodyTable">
            <tr>
                <td colspan="6" style="padding:3px;">Loading...</td>
            </tr>
        </tbody>
        </table>
    </div>
    <div style="width:100%;padding:10px;">
    <div style="width:100%;background:#ccc;font-size:12px;padding:15px;border-radius:4px;">
    <?php
        $currentTime = date('H:i');
        if (strtotime($currentTime) >= strtotime('14:00') && strtotime($currentTime) < strtotime('22:01')) { 
            $sesuai = "1";
            $tgl_cetak = date('Y-m-d');
        } elseif(strtotime($currentTime) >= strtotime('22:01') && strtotime($currentTime) < strtotime('23:59')) { 
            $sesuai = "2"; 
            $tgl_cetak = date('Y-m-d');
        } elseif(strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('05:59')){
            $sesuai = "2"; 
            $tgl_now = date('Y-m-d');
            $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        } elseif(strtotime($currentTime) >= strtotime('06:00') && strtotime($currentTime) < strtotime('13:59')){
            $sesuai = "3";
            $tgl_now = date('Y-m-d');
            $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        }
        $_ex = explode('-', $tgl_cetak);
        $_print = $_ex[2]." ".$ar[$_ex[1]]." ".$_ex[0];
        //echo $_print."<br>";
        echo "Laporan Hasil Shift : <strong>".$sesuai."</strong><br>";
        $cek_a = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'A%' AND mati_or_not='1' ");
        $cek_b = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'B%' AND mati_or_not='1' ");
        $cek_c = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'C%' AND mati_or_not='1' ");
        $cek_d = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND no_mesin LIKE 'D%' AND mati_or_not='1' ");
        $cek_all = $this->db->query("SELECT COUNT(no_mesin) AS jml_mesin, SUM(produksi) AS prod, SUM(eff) AS eff FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND mati_or_not='1' ");

        $grub = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND yg_input='$nama' ORDER BY id_loom DESC LIMIT 1 ")->row("group_shift");
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
        
        $mc_jalan = intval($cek_a->row("jml_mesin")) + intval($cek_b->row("jml_mesin")) + intval($cek_c->row("jml_mesin")) + intval($cek_d->row("jml_mesin"));

        $eff_a = round($cek_a->row("eff"),2) / intval($cek_a->row("jml_mesin"));
        $eff_b = round($cek_b->row("eff"),2) / intval($cek_b->row("jml_mesin"));
        $eff_c = round($cek_c->row("eff"),2) / intval($cek_c->row("jml_mesin"));
        $eff_d = round($cek_d->row("eff"),2) / intval($cek_d->row("jml_mesin"));
        if(is_nan($eff_a)){ $eff_a="0"; } else { $eff_a = round($eff_a, 1); }
        if(is_nan($eff_b)){ $eff_b="0"; } else { $eff_b = round($eff_b, 1); }
        if(is_nan($eff_c)){ $eff_c="0"; } else { $eff_c = round($eff_c, 1); }
        if(is_nan($eff_d)){ $eff_d="0"; } else { $eff_d = round($eff_d, 1); }
        $_rata_eff = ($eff_a + $eff_b + $eff_c + $eff_d) / 4;
        $_rata_eff2 = $cek_all->row("eff") / $cek_all->row("jml_mesin");
        $_total_produksi = round($cek_a->row("prod"),2) + round($cek_b->row("prod"),2) + round($cek_c->row("prod"),2) + round($cek_d->row("prod"),2);
        $_total_prod = round($_total_produksi,2);
        $to_copy = "*".$_print."*<br>Laporan Hasil Shift : *".$sesuai."*<br>Group : *".$grub."*<br>Kashift : *".$kasif."*<br>Karu : *".$karu."*<br><br>MC Jalan = *".$mc_jalan."*<br>RAYON<br>Total Produksi Rayon<br>Line A ( *".round($cek_a->row("jml_mesin"), 2)."* MC )<br>Produksi = *".round($cek_a->row("prod"),2)."* | Efisiensi = *".$eff_a."*<br>Line B ( *".round($cek_b->row("jml_mesin"),2)."* MC )<br>Produksi = *".round($cek_b->row("prod"),2)."* | Efisiensi = *".$eff_b."*<br>Line C ( *".$cek_c->row("jml_mesin")."* MC )<br>Produksi = *".round($cek_c->row("prod"),2)."* | Efisiensi = *".$eff_c."*<br>Line D ( *".$cek_d->row("jml_mesin")."* MC )<br>Produksi = *".round($cek_d->row("prod"),2)."* | Efisiensi = *".$eff_d."*<br>--------------------------------------------<br>*Total  ".$mc_jalan." MC :*<br>Produksi = *".$_total_prod."* Meter, Efisiensi = *".round($_rata_eff,1)."*";
        $mc_mati = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$tgl_cetak' AND shift_mesin='$sesuai' AND mati_or_not!='1' ORDER BY no_mesin");
        $jml_mcmati = $mc_mati->num_rows();
        
    ?>
        <br>
        MC Jalan = <strong><?=$mc_jalan;?></strong><br>
        <?php if($mc_mati > 0){ ?>
        MC Mati = <strong><?=$jml_mcmati;?></strong><br>
        <?php 
        $mc_mati_txt = "";
            foreach($mc_mati->result() as $tul){
                echo "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
                $mc_mati_txt .= "".$tul->no_mesin." = ". ucwords( $tul->xket )."<br>";
            }
        $to_copy = "*".$_print."*<br>Laporan Hasil Shift : *".$sesuai."*<br>Group : *".$grub."*<br>Kashift : *".$kasif."*<br>Karu : *".$karu."*<br><br>MC Jalan = *".$mc_jalan."*<br>MC Mati = *".$jml_mcmati."*<br>".$mc_mati_txt."<br>RAYON<br>Total Produksi Rayon<br>Line A ( *".round($cek_a->row("jml_mesin"), 2)."* MC )<br>Produksi = *".round($cek_a->row("prod"),2)."* | Efisiensi = *".$eff_a."*<br>Line B ( *".round($cek_b->row("jml_mesin"),2)."* MC )<br>Produksi = *".round($cek_b->row("prod"),2)."* | Efisiensi = *".$eff_b."*<br>Line C ( *".$cek_c->row("jml_mesin")."* MC )<br>Produksi = *".round($cek_c->row("prod"),2)."* | Efisiensi = *".$eff_c."*<br>Line D ( *".$cek_d->row("jml_mesin")."* MC )<br>Produksi = *".round($cek_d->row("prod"),2)."* | Efisiensi = *".$eff_d."*<br>--------------------------------------------<br>*Total  ".$mc_jalan." MC :*<br>Produksi = *".$_total_prod."* Meter, Efisiensi = *".round($_rata_eff,1)."*";
        }
        ?>
        <br>

        RAYON<br>
        Total Produksi Rayon<br>
        Line A (<strong><?=round($cek_a->row("jml_mesin"));?></strong> MC)<br>
        Produksi = <strong><?=round($cek_a->row("prod"),2);?></strong> | Efisiensi = <strong><?php if(is_nan($eff_a)){ echo "-"; } else { echo $eff_a; };?></strong><br>
        Line B (<strong><?=round($cek_b->row("jml_mesin"));?></strong> MC)<br>
        Produksi = <strong><?=round($cek_b->row("prod"),2);?></strong> | Efisiensi = <strong><?php if(is_nan($eff_b)){ echo "-"; } else { echo $eff_b; };?></strong><br>
        Line C (<strong><?=$cek_c->row("jml_mesin");?></strong> MC)<br>
        Produksi = <strong><?=round($cek_c->row("prod"),2);?></strong> | Efisiensi = <strong><?php if(is_nan($eff_c)){ echo "-"; } else { echo $eff_c; };?></strong><br>
        Line D (<strong><?=$cek_d->row("jml_mesin");?></strong> MC)<br>
        Produksi = <strong><?=round($cek_d->row("prod"),2);?></strong> | Efisiensi = <strong><?php if(is_nan($eff_d)){ echo "-"; } else { echo $eff_d; };?></strong><br>
        --------------------------------------------<br>
        <strong>Total  <?=$mc_jalan;?> MC : </strong><br>
        Produksi = <strong><?=$_total_prod;?></strong> Meter, Efisiensi = <strong><?=round($_rata_eff2,1);?></strong>
    </div>
    <div style="width:100%;display:flex;justify-content:center;align-items:center;background:#3498eb;font-size:12px;margin-top:10px;padding:5px;border-radius:4px;" onclick="copyToClipboardWithHTML('<?=$to_copy;?>');">Copy Text to Clipboard</div>
    </div>
    

    <div style="width:100%;height:100px;">&nbsp;</div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <?php
        // $kdrol = $this->db->query("SELECT * FROM loom_counter GROUP BY no_mesin");
        // if($kdrol->num_rows() > 0){
        //     $ar_kdrol = array();
        //     foreach($kdrol->result() as $val){
        //         if(in_array($data_nama, $ar_kdrol)){} else {
        //         $ar_kdrol[] = '"'.$val->no_mesin.'"'; }
        //     }
        //     $im_kons = implode(',', $ar_kdrol);
        // } else {
        //     $im_kons = '"Belum ada data"';
        // }
    ?>
    <script>
    //   const autoCompleteJS = new autoComplete({
    //         placeHolder: "Cari Nomor Mesin",
    //         data: {
    //             src: [<=$im_kons;?>],
    //             cache: true,
    //         },
    //         resultItem: {
    //             highlight: true
    //         },
    //         events: {
    //             input: {
    //                 selection: (event) => {
    //                     const selection = event.detail.selection.value;
    //                     autoCompleteJS.input.value = selection;
    //                 }
    //             }
    //         }
    //     });
        function loadDataLoom(){
            var namas = $('#namaorang').val();
            $.ajax({
                url:"<?=base_url('prosesajax/showLoomCounter');?>",
                type: "POST",
                data: {"namas":namas},
                cache: false,
                success: function(dataResult){
                    $('#tbodyTable').html(dataResult);
                }
            });
        }
        loadDataLoom();       
        function copyToClipboardWithHTML(htmlText) {
        // Buat elemen div sementara untuk menampung teks HTML
        var div = document.createElement("div");
        div.innerHTML = htmlText;

        // Sembunyikan elemen div dari tampilan pengguna
        div.style.position = "fixed";
        div.style.opacity = 0;

        // Masukkan elemen div ke dalam dokumen
        document.body.appendChild(div);

        // Pilih teks di dalam elemen div
        var range = document.createRange();
        range.selectNodeContents(div);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        // Salin teks yang dipilih ke clipboard
        document.execCommand("copy");

        // Hapus elemen div yang tidak diperlukan lagi
        document.body.removeChild(div);
        Swal.fire({
            title: "Success",
            text: "Copy Text To Clipboard",
            icon: "success"
        });
        }
        $( "#returnToHomes" ).on( "click", function() {
            document.location.href = "<?=base_url('operator/mesin');?>";
        } );
    </script>
</body>
</html>