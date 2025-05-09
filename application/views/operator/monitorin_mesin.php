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
    <?php 
    $nmuser = $this->session->userdata('nama');
    $akses = $this->session->userdata('akses');
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <h1 style="font-size:1.2em;margin:10px;color:#1561e6;z-index: 9999;">Monitoring Mesin AJL</h1>
        
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
        
    ?>
    <small style="margin:-5px 10px 10px 10px;z-index: 9999;"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> User : <strong id="nmoptid"><?=$nmuser;?></strong></small>
    
    <!-- <div style="padding:10px;"><a href="<=base_url('input-loom');?>" style="text-decoration:none;background:#0e9fc7;color:#fff;font-size:12px;padding:3px 10px;border-radius:4px 7px;">Loom Counter</a></div> -->
    <div class="container" style="margin-top:10px;">
    <div class="fl">
        <label for="autoComplete">Cari</label>
        <div class="autoComplete_wrapper">
            <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="beamwarping">
            
        </div>
    </div>
    <div class="box-mesin2" id="isi_mesin">
        <div class="loading-container">
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
            <div class="loading-skeleton"></div>
        </div>
        
    </div>
    </div>
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
    <div style="width:100%;height:100px;">&nbsp;</div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM table_mesin");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                if(in_array($val->no_mesin, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->no_mesin.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
    <script>
        <?php if($nmuser=="anding"){ ?>
        function showmodals(msn,id){
            $('.popupfull').addClass('active');
            $('.modals').addClass('active');
            $('#jdlmdl').html(''+msn);
        }
        <?php } else { ?>
        function showmodals(msn,id){
            document.location.href="<?=base_url('operator/mesin/');?>"+id;
        }
        <?php } ?>
        function hidemodal(){
            $('.popupfull').removeClass('active');
            $('.modals').removeClass('active');
        }
      const autoCompleteJS = new autoComplete({
            placeHolder: "Cari Nomor Mesin",
            data: {
                src: [<?=$im_kons;?>],
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
        $( "#returnToHome" ).on( "click", function() {
            document.location.href = "<?=base_url('operator/mesin');?>";
        } );
        $( "#autoComplete" ).on( "change", function() {
            var kode = $( "#autoComplete" ).val();
            if(kode == ""){
                loaddatamesin('null');
            } else {
            loaddatamesin(kode); }
        } );
        function loaddatamesin(id){
            $.ajax({
                url:"<?=base_url('prosesajax/showmesin');?>",
                type: "POST",
                data: {"kode":id},
                cache: false,
                success: function(dataResult){
                    $('#isi_mesin').html(dataResult);
                }
            });
        }
        loaddatamesin('null');
        function noakses(txt){
            Swal.fire({
                title: "Akses diblokir",
                text: "Anda tidak memiliki akses ke "+txt+"",
                icon: "error"
            });
        }
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