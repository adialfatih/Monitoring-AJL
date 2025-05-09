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
        .clsBtn {
                width:100%;
                display:flex;
                justify-content:space-between;
                padding:0 10px;
                margin-top:20px;
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
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <h1 style="font-size:1.2em;margin:10px;color:#1561e6;">Produksi Sizing</h1>
        
        <input id="checkbox" type="checkbox">
        <label class="toggle" for="checkbox">
            <div id="bar1" class="bars"></div>
            <div id="bar2" class="bars"></div>
            <div id="bar3" class="bars"></div>
        </label>
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
        $nmuser = $this->session->userdata('nama');
    ?>
    
    <div class="clsBtn">
        <div>
            <small><?=$newToday;?>, <strong><?=$prinTgl;?></strong></small>
            <small>User : <strong><?=$nmuser;?></strong></small>
        </div>
        <a href="<?=base_url('input-sizing-form');?>" class="clsBtnClick">+ Produksi</a>
    </div>
    <!-- <div style="padding:10px;"><a href="<=base_url('input-loom');?>" style="text-decoration:none;background:#0e9fc7;color:#fff;font-size:12px;padding:3px 10px;border-radius:4px 7px;">Loom Counter</a></div> -->
    <div class="container" style="margin-top:10px;">
    <div class="fl">
        <label for="autoComplete">Cari OK</label>
        <div class="autoComplete_wrapper">
            <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="beamwarping">
            
        </div>
    </div>
    <div class="box-mesin2" id="isi_table">
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
    <a href="<?=base_url('operator/mesin');?>">
    <div class="floating-btn" id="returnToHomessd">
        <img src="<?=base_url('assets/home.png');?>" alt="Home">
    </div>
    </a>
    <div style="width:100%;height:100px;">&nbsp;</div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM produksi_sizing GROUP BY oka");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                if(in_array($val->oka, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->oka.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
    <script>
      const autoCompleteJS = new autoComplete({
            placeHolder: "Cari Nomor OKA",
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
                        loadData(selection);
                    }
                }
            }
        });
        $( "#autoComplete" ).on( "change", function() {
            var dt = $('#autoComplete').val();
            if(dt==""){
                loadData('null');
            }
        });
        function loadData(id){
            $.ajax({
                url:"<?=base_url('proses/showsizingtable');?>",
                type: "POST",
                data: {"id" : id},
                cache: false,
                success: function(dataResult){
                    $('#isi_table').html(dataResult);
                }
            });
        }
        loadData('null');
    </script>
    
</body>
</html>