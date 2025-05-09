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
        .container {padding:20px 10px 0 10px; font-size:15px;display:flex;flex-direction:column;}
        div.btn {
            width:100%;display:flex;justify-content:flex-end;
            margin-top:15px;
            
        }
        .btn button {
            padding:10px;
            border:none;
            outline:none;
            background:#2f6fed;
            color:#fff;
            border-radius:3px;
        }
        .btn button.bk {
            background:#dcdee3;
            color:#7a7b7d;
            margin-right:10px;
        }
        .btn button:hover{
            background:#0e9fc7;
        }
        .flx {
            width:100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:10px;
        }
        .flx select, .flx input{
            padding:7px;
            border-radius:3px;
            outline:none;
            border:1px solid #ccc;
            width:200px;
        }
    </style>
</head>
<body>
    <?php 
    $nmuser = $this->session->userdata('nama');
    $akses = $this->session->userdata('akses');
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <h1 style="font-size:1.2em;margin:10px;color:#1561e6;z-index: 9999;">Input Operator Mesin</h1>
        
        <input id="checkbox" type="checkbox">
        <label class="toggle" for="checkbox">
            <div id="bar1" class="bars"></div>
            <div id="bar2" class="bars"></div>
            <div id="bar3" class="bars"></div>
        </label>
    </div>
    <div class="mobile-menu2">
        <a href="<?=base_url('show-mesin');?>">
            <div class="menu-btn type1">Monitoring Mesin</div>
        </a>
        <a href="<?=base_url('operator-loom');?>" >
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
    <form action="<?=base_url('operator-loom') ?>" method="post">
        <div class="container" style="margin-top:10px;" id="kontener">
            <div class="flx">
                <label for="group">Group</label>
                <select name="grb" id="group">
                    <option value="">Pilih Group</option>
                    <option value="A">Group A</option>
                    <option value="B">Group B</option>
                    <option value="C">Group C</option>
                </select>
            </div>
            <div class="flx">
                <label for="nmopt">Nama Operator</label>
                <input type="text" name="nmopt" id="nmopt" placeholder="Masukan nama operator">
            </div>
            <div class="flx">
                <label for="reliver">Reliver ?</label>
                <select name="reliver" id="reliver">
                    <option value="n">Bukan Reliver</option>
                    <option value="y">Reliver</option>
                </select>
            </div>
            <div class="btn">
                <button type="button" class="bk" onclick="backtoop()">Kembali</button>
                <button type="button" onclick="simpanop()">Simpan</button>
            </div>
        </div>
    </form>
    
    <div style="width:100%;height:100px;">&nbsp;</div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    
    <script>
        $(window).on('load', function() {
            console.log("Semua dokumen html telah di muat 100%");
        });
        $('#checkbox').on('change', function() {
            if (this.checked) {
                $('.mobile-menu2').addClass('active');
            } else {
                $('.mobile-menu2').removeClass('active');
            }
        });
        function simpanop(){
            var group = document.getElementById('group').value;
            var nmopt = document.getElementById('nmopt').value;
            var reliver = document.getElementById('reliver').value;
            if(group == '' || nmopt == '' || reliver == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tolong diisi semua!'
                });
            } else {
                $.ajax({
                    url:"<?=base_url('proses2/addoptmsn');?>",
                    type: "POST",
                    data: {"group":group, "nmopt":nmopt, "reliver":reliver},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Menyimpan Operator Group '+group+''
                        });
                        document.getElementById('group').value = '';
                        document.getElementById('nmopt').value = '';
                    }
                });
            }
        
        }
        function backtoop(){
            window.location.href = "<?=base_url('operator-loom');?>";
        }
    </script>
</body>
</html>