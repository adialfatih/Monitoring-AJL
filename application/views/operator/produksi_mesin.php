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
        table {
            width:100%;border-collapse:collapse;border:1px solid #ccc;
        }
        table tr td {padding:5px;}
        .pl {
        width: 6em;
        height: 6em;
        }

        .pl__ring {
        animation: ringA 2s linear infinite;
        }

        .pl__ring--a {
        stroke: #f42f25;
        }

        .pl__ring--b {
        animation-name: ringB;
        stroke: #f49725;
        }

        .pl__ring--c {
        animation-name: ringC;
        stroke: #255ff4;
        }

        .pl__ring--d {
        animation-name: ringD;
        stroke: #f42582;
        }

        /* Animations */
        @keyframes ringA {
        from, 4% {
            stroke-dasharray: 0 660;
            stroke-width: 20;
            stroke-dashoffset: -330;
        }

        12% {
            stroke-dasharray: 60 600;
            stroke-width: 30;
            stroke-dashoffset: -335;
        }

        32% {
            stroke-dasharray: 60 600;
            stroke-width: 30;
            stroke-dashoffset: -595;
        }

        40%, 54% {
            stroke-dasharray: 0 660;
            stroke-width: 20;
            stroke-dashoffset: -660;
        }

        62% {
            stroke-dasharray: 60 600;
            stroke-width: 30;
            stroke-dashoffset: -665;
        }

        82% {
            stroke-dasharray: 60 600;
            stroke-width: 30;
            stroke-dashoffset: -925;
        }

        90%, to {
            stroke-dasharray: 0 660;
            stroke-width: 20;
            stroke-dashoffset: -990;
        }
        }

        @keyframes ringB {
        from, 12% {
            stroke-dasharray: 0 220;
            stroke-width: 20;
            stroke-dashoffset: -110;
        }

        20% {
            stroke-dasharray: 20 200;
            stroke-width: 30;
            stroke-dashoffset: -115;
        }

        40% {
            stroke-dasharray: 20 200;
            stroke-width: 30;
            stroke-dashoffset: -195;
        }

        48%, 62% {
            stroke-dasharray: 0 220;
            stroke-width: 20;
            stroke-dashoffset: -220;
        }

        70% {
            stroke-dasharray: 20 200;
            stroke-width: 30;
            stroke-dashoffset: -225;
        }

        90% {
            stroke-dasharray: 20 200;
            stroke-width: 30;
            stroke-dashoffset: -305;
        }

        98%, to {
            stroke-dasharray: 0 220;
            stroke-width: 20;
            stroke-dashoffset: -330;
        }
        }

        @keyframes ringC {
        from {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: 0;
        }

        8% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -5;
        }

        28% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -175;
        }

        36%, 58% {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: -220;
        }

        66% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -225;
        }

        86% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -395;
        }

        94%, to {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: -440;
        }
        }

        @keyframes ringD {
        from, 8% {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: 0;
        }

        16% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -5;
        }

        36% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -175;
        }

        44%, 50% {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: -220;
        }

        58% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -225;
        }

        78% {
            stroke-dasharray: 40 400;
            stroke-width: 30;
            stroke-dashoffset: -395;
        }

        86%, to {
            stroke-dasharray: 0 440;
            stroke-width: 20;
            stroke-dashoffset: -440;
        }
        }
        .clsBtnClick {
            text-decoration:none;
            background:#067bc9;
            color:#fff;
            padding:10px 15px;
            border-radius:4px;
            outline:none;
            border:none;
            margin:10px;
        }
        .clsBtnClick:hover{
            background:#ccc;
            color:#fff;
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
            '00' => 'NaN', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        );
        $prinTgl = $ex_tgl[2]." ".$ar[$ex_tgl[1]]." ".$ex_tgl[0];
        //$pl = explode('-', $cek['tgl_produksi']);
        $currentTime = date('H:i');
        if (strtotime($currentTime) >= strtotime('14:00') && strtotime($currentTime) < strtotime('22:00')) { 
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
        $xx = explode('-', $tgl_cetak);
        $printTglCetak = $xx[2]." ".$ar[$xx[1]]." ".$xx[0];
    ?>
    <!-- <small style="margin:-5px 10px 10px 10px;"><=$newToday;?>, <strong><=$prinTgl;?></strong> User : <strong id="nmoptid"><=$nmuser;?></strong></small> -->
    <small style="margin:-5px 10px 10px 10px;z-index:9999;">User : <strong id="nmoptid"><?=$nmuser;?></strong></small>
    
    <div class="container" style="margin-top:10px;">
        <p style="width:100%;padding:10px;">Input data produksi mesin dan operator yang bertanggungjawab pada mesin.</p>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="id_grub" style="font-size:13px;width:30%;">Group</label>
            <select id="id_grub" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="grub" required>
                <option value="">Pilih</option>
                <option value="A">Group A</option>
                <option value="B">Group B</option>
                <option value="C">Group C</option>
            </select>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="id_tgl" style="font-size:13px;width:30%;">Tanggal</label>
            <input id="id_tgl" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="tgl" value="<?=$printTglCetak;?>" readonly>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="id_shift" style="font-size:13px;width:50%;">Shift</label>
            <input id="id_shift" style="width:50%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="shift" value="<?=$sesuai;?>" readonly>
        </div>
        <input type="hidden" id="tglid" value="<?=$tgl_cetak;?>">
        <input type="hidden" id="optlogin" value="<?=$nmuser;?>">
        <input type="hidden" id="idzpmoke" value="0">
        <div style="width:100%;display:flex;flex-direction:column;" id="autoColom">
            
        </div>
    </div>
    </form>
    
    
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
        $(document).ready(function() {
            
            $('#id_grub').on('change', function() {
                var grub = $('#id_grub').val();
                var tgl = $('#tglid').val();
                var shift = $('#id_shift').val();
                var opt = $('#optlogin').val();
                $('#autoColom').html('<div style="width:100%;height:300px;display:flex;align-items:center;justify-content:center;"><svg class="pl" width="240" height="240" viewBox="0 0 240 240"><circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"></circle><circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"></circle><circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle><circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle></svg></div>');
                $.ajax({
                    url:"<?=base_url('proses2/inputproduksimesin');?>",
                    type: "POST",
                    data: {"grub" : grub, "tgl":tgl, "shift":shift, "opt":opt},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $('#idzpmoke').val(''+dataResult.psn);
                            var idzpmokesd = dataResult.psn;
                            $.ajax({
                                url:"<?=base_url('proses2/showopt');?>",
                                type: "POST",
                                data: {"grub" : grub, "idzpmokesd":idzpmokesd},
                                cache: false,
                                success: function(dataResult){
                                    //$('#autoColom').html(dataResult);
                                    setTimeout(function(){
                                        $('#autoColom').html(dataResult);
                                    },2000);
                                }
                            });
                        } else {
                            $('#idzpmoke').val(''+dataResult.psn);
                            var gs = dataResult.fix;
                            //Swal.fire('Sudah di input grub '+gs);
                            setTimeout(function(){
                                $('#autoColom').html('Sudah di input grub '+gs);
                            },2000);
                            
                        }
                    }
                });
            });
        
            $('#checkbox').on('change', function() {
                if (this.checked) {
                    $('.mobile-menu2').addClass('active');
                } else {
                    $('.mobile-menu2').removeClass('active');
                }
            });
        });
    </script>
</body>
</html>