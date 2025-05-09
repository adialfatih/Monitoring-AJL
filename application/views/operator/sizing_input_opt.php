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
    <h1 style="font-size:1.2em;margin:10px;color:#1561e6;">Input Produksi Sizing</h1>
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

        $currentTime = date('H:i');
        // if (strtotime($currentTime) >= strtotime('14:00') && strtotime($currentTime) < strtotime('22:01')) { 
        //     $sesuai = "1";
        //     $tgl_cetak = date('Y-m-d');
        // } elseif(strtotime($currentTime) >= strtotime('22:01') && strtotime($currentTime) < strtotime('23:59')) { 
        //     $sesuai = "2"; 
        //     $tgl_cetak = date('Y-m-d');
        // } elseif(strtotime($currentTime) >= strtotime('00:00') && strtotime($currentTime) < strtotime('05:59')){
        //     $sesuai = "2"; 
        //     $tgl_now = date('Y-m-d');
        //     $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        // } elseif(strtotime($currentTime) >= strtotime('06:00') && strtotime($currentTime) < strtotime('13:59')){
        //     $sesuai = "3";
        //     $tgl_now = date('Y-m-d');
        //     $tgl_cetak = date('Y-m-d', strtotime('-1 day', strtotime($tgl_now)));
        // }
        // $_ex = explode('-', $tgl_cetak);
        // $_printTglCetak = $_ex[2]." ".$ar[$_ex[1]]." ".$_ex[0];
        $namas = $this->session->userdata('nama');
        $kodeproses = $this->data_model->acakKode2(19);
    ?>
    <form action="<?=base_url('prosesajax/savedLoomCounter');?>" method="post">
    <small style="margin:-5px 10px 10px 10px;"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> User : <strong id="nmoptid"><?=$namas;?></strong></small>
    <input type="hidden" id="userlogin" value="<?=$namas;?>" name="operator" required>
    <div class="container" style="margin-top:10px;">
    <div class="fl">
        <label for="okid" style="width:30%;">OKA</label>
        <input type="tel" id="okid" name="oka" placeholder="Masukan Nomor OK" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" oninput="formatInput(this)" required>
    </div>
    <div class="fl" style="display:none;" id="xketid">
        <textarea name="ketx" id="ketx" cols="30" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" placeholder="Masukan Keterangan Alasan Mesin Mati"></textarea>
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="id_tgl" style="font-size:12px;width:30%;">Tanggal</label>
        <input type="hidden" value="<?=$tgl_cetak;?>" name="tgl">
        <?php //if($namas == "Septi Diah"){ ?>
            <input type="date" value="<=date('Y-m-d');?>" id="id_tgl" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="tgl" required>
        <?php //} else { ?>
        
        <?php //} ?>
        <!-- <input type="date" value="<=date('Y-m-d');?>" id="id_tgl" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="tgl" required> -->
    </div>
    
     <div class="fl">
        <label for="autoComplete" style="width:30%;font-size:12px;">Konstruksi</label>
        <div class="autoComplete_wrapper" style="width:70%;">
            <input id="autoComplete" style="width:120%;" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="kons" required>
        </div>
    </div>
    <input type="hidden" value="<?=$kodeproses;?>" id="kdproses">
    <div style="width:100%;padding:10px;height:200px;">
    <button type="button" class="button2" onclick="simpansizing()">Simpan Produksi</button></div>
    
    </form>
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
            placeHolder: "Ketik Konstruksi",
            data: {
                src: ["SM02","SM03","SM04","SM05A","SM05B","SM05C","SM05D","SM05F","SM05G","SM05H","SM13","SM13A","SM13B","SM13C","SM13C","SM15","SM15A","SM15B","SM15C","SM15D","SM15H","SM15J","SM15K","SM15L","SM16","SM17","SM17A","SM18","SM16A","SM16B","SM19","SM05K","SM13D","SM09A","SM03A","SM05J","SM20","SM13E","SM05L","SM05L_RJS","SM13F","SM13G","SM13H","SM13J","SM05M","SM17B","SM05B_RJS","SM13K","SM05GS","SM13L",],
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
                    }
                }
            }
        });
        function formatInput(input) {
            input.value = input.value.replace(/\s/g, '');
            input.value = input.value.toUpperCase();
        }
        function simpansizing(){
            var kodeproses = document.getElementById('kdproses').value;
            var kons = document.getElementById('autoComplete').value;
            var tgl = document.getElementById('id_tgl').value;
            var oka = document.getElementById('okid').value;
            var user = document.getElementById('userlogin').value;
            if(kodeproses!="" && kons!="" && tgl!="" && oka!="" && user!=""){
                $.ajax({
                    url:"<?=base_url('proses/prosesSizingOpt');?>",
                    type: "POST",
                    data: {"kodeproses" : kodeproses, "kons":kons, "tgl":tgl, "oka":oka, "user":user},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        var sc = dataResult.statusCode;
                        var psn = dataResult.psn;
                        if(sc == 200){
                            Swal.fire({
                                title: "Saved Data",
                                text: "Please Wait ...",
                                icon: "success"
                            });
                            setTimeout(function(){
                                window.location.href = "<?=base_url('operator/produksi/sizing/');?>"+psn+"";
                            }, 2500);
                        } else {
                            Swal.fire({
                                title: "Error!!",
                                text: ""+psn+"",
                                icon: "error"
                            });
                        }
                    }
                });
            } else {
                Swal.fire('Anda harus mengisi semua data');
            }
        }
    </script>
</body>
</html>