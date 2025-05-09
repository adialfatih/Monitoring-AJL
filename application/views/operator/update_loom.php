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
    <h1 style="font-size:1.2em;margin:10px;color:#1561e6;">Update Loom Counter</h1>
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
        $uri = $this->uri->segment(3);
        $dt = $this->data_model->get_byid('loom_counter', ['sha1(id_loom)'=>$uri])->row_array();
        // echo "<pre>";
        // print_r($dt);
        // echo "</pre>";
    ?>
    <form action="<?=base_url('prosesajax/savedLoomCounter23');?>" method="post">
    <input type="hidden" name="id_loom" value="<?=$dt['id_loom'];?>">
    <small style="margin:-5px 10px 10px 10px;"><?=$newToday;?>, <strong><?=$prinTgl;?></strong> User : <strong id="nmoptid"><?=$this->session->userdata('nama');?></strong></small>
    <input type="hidden" id="userlogin" value="<?=$this->session->userdata('nama');?>" name="operator" required>
    <div class="container" style="margin-top:10px;">
    <!-- <div class="fl">
        <label for="autoComplete" style="width:30%;">MC</label>
        <div class="autoComplete_wrapper" style="width:70%;">
            <input id="autoComplete" style="width:120%;" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="nomesin" required>
        </div>
    </div> -->
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="nomesin" style="font-size:12px;width:30%;">MC</label>
        <input type="text" value="<?=$dt['no_mesin'];?>" id="nomesin" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="nomesin" required>
        <input type="hidden" name="nomesin_old" value="<?=$dt['no_mesin'];?>">
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="id_tgl" style="font-size:12px;width:30%;">Tanggal</label>
        <input type="date" value="<?=$dt['tgl'];?>" id="id_tgl" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="tgl" required>
        <input type="hidden" name="tgl_old" value="<?=$dt['tgl'];?>">
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="id_shift" style="font-size:12px;width:30%;">Shift</label>
        <select id="id_shift" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="shift" required>
            <option value="">Pilih</option>
            <option value="1" <?=$dt['shift_mesin']=='1' ? 'selected':'';?>>Shift 1</option>
            <option value="2" <?=$dt['shift_mesin']=='2' ? 'selected':'';?>>Shift 2</option>
            <option value="3" <?=$dt['shift_mesin']=='3' ? 'selected':'';?>>Shift 3</option>
        </select>
        <input type="hidden" name="shift_old" value="<?=$dt['shift_mesin'];?>">
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="group" style="font-size:12px;width:30%;">Group</label>
        <select id="group" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="groupid" required>
            <option value="">Pilih</option>
            <option value="A" <?=$dt['group_shift']=='A' ? 'selected':'';?>>Group A</option>
            <option value="B" <?=$dt['group_shift']=='B' ? 'selected':'';?>>Group B</option>
            <option value="C" <?=$dt['group_shift']=='C' ? 'selected':'';?>>Group C</option>
        </select>
        <input type="hidden" name="groupid_old" value="<?=$dt['group_shift'];?>">
    </div>
    
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="kons" style="font-size:12px;width:30%;">Konstruksi</label>
        <input type="hidden" name="kons_old" value="<?=$dt['konstruksi'];?>">
        <input type="text" list="listkons" id="kons" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['konstruksi'];?>" name="kons" required>
        <datalist id="listkons">
            <option value="SM02">
            <option value="SM03">
            <option value="SM04">
            <option value="SM05A">
            <option value="SM05B">
            <option value="SM05C">
            <option value="SM05D">
            <option value="SM05F">
            <option value="SM05G">
            <option value="SM05H">
            <option value="SM13">
            <option value="SM13A">
            <option value="SM13B">
            <option value="SM13C">
            <option value="SM13C">
            <option value="SM15">
            <option value="SM15A">
            <option value="SM15B">
            <option value="SM15C">
            <option value="SM15D">
            <option value="SM15H">
            <option value="SM15J">
            <option value="SM15K">
            <option value="SM15L">
            <option value="SM16">
            <option value="SM17">
            <option value="SM17A">
            <option value="SM18">
            <option value="SM16A">
            <option value="SM16B">
            <option value="SM19">
            <option value="SM05K">
            <option value="SM13D">
            <option value="SM09A">
            <option value="SM03A">
            <option value="SM05J">
            <option value="SM20">
            <option value="SM13E">
            <option value="SM05L">
            <option value="SM05L_RJS">
            <option value="SM13F">
            <option value="SM13G">
            <option value="SM13H">
            <option value="SM13J">
            <option value="SM05M">
            <option value="SM17B">
            <option value="SM05B_RJS">
            <option value="SM13K">
            <option value="SM05GS">
            <option value="SM13L">
            <option value="SM116C">
            <option value="TR20">
        </datalist>
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="rpm" style="font-size:12px;margin-bottom:5px;">RPM</label>
            <input type="number" id="rpm" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['rpm'];?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" name="rpm" required>
            <input type="hidden" name="rpm_old" value="<?=$dt['rpm'];?>">
        </div>
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="pick" style="font-size:12px;margin-bottom:5px;">PICK</label>
            <input type="number" id="pick" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['pick'];?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" name="pick" required>
            <input type="hidden" name="pick_old" value="<?=$dt['pick'];?>">
        </div>
    </div>
    <div style="width:100%;padding:10px;"><hr></div>
    <span style="width:100%;padding:10px;font-size:14px;font-weight:bold;">Putus Benang</span>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="lusi" style="font-size:12px;margin-bottom:5px;">Lusi</label>
            <input type="number" id="lusi" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['ls'];?>" onchange="hitungratalusi()" onkeyup="hitungratalusi()" name="lusi" required>
            <input type="hidden" name="lusi_old" value="<?=$dt['ls'];?>">
        </div>
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="mnt_lusi" style="font-size:12px;margin-bottom:5px;">Menit</label>
            <input type="number" id="mnt_lusi" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['ls_mnt'];?>"  onchange="hitungratalusi()" onkeyup="hitungratalusi()" name="mnt_lusi" required>
            <input type="hidden" name="mnt_lusi_old" value="<?=$dt['ls_mnt'];?>">
        </div>
    </div>
    <div style="width:100%;display:flex;flex-direction:column;padding:0 10px;">
        <label for="rtrtlusi" style="font-size:12px;margin-bottom:5px;">Rata-rata Lost (Detik)</label>
        <input type="number" id="rtrtlusi" style="width:100%;padding:10px;border:1px solid #0cb8e8;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['rt_lost_ls'];?>" name="rtrt_lusi" readonly>
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="pakan" style="font-size:12px;margin-bottom:5px;">Pakan</label>
            <input type="number" id="pakan" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['pkn'];?>" onchange="hitungratapakan()" onkeyup="hitungratapakan()" name="pakan" required>
            <input type="hidden" name="pakan_old" value="<?=$dt['pkn'];?>">
        </div>
        <div style="width:49%;display:flex;flex-direction:column;">
            <label for="mnt_pakan" style="font-size:12px;margin-bottom:5px;">Menit</label>
            <input type="number" id="mnt_pakan" style="width:100%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['mnt'];?>" onchange="hitungratapakan()" onkeyup="hitungratapakan()" name="mnt_pakan" required>
            <input type="hidden" name="mnt_pakan_old" value="<?=$dt['mnt'];?>">
        </div>
    </div>
    <div style="width:100%;display:flex;flex-direction:column;padding:0 10px;">
        <label for="rtrt_pakan" style="font-size:12px;margin-bottom:5px;">Rata-rata Lost (Detik)</label>
        <input type="number" id="rtrt_pakan" style="width:100%;padding:10px;border:1px solid #0cb8e8;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['rt_lost_pkn'];?>" name="rtrt_pakan" readonly>
        
    </div>
    <div style="width:100%;padding:10px;"><hr></div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="eff" style="font-size:12px;width:30%;">EFF %</label>
        <input type="text" id="eff" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['eff'];?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" name="eff" required>
        <input type="hidden" name="eff_old" value="<?=$dt['eff'];?>">
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="produksi" style="font-size:12px;width:30%;" id="tes1produksi">Produksi </label>
        <input type="text"  id="produksi" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['produksi'];?>" name="prod" required>
        <input type="hidden" name="prod_old" value="<?=$dt['produksi'];?>">
    </div>

    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="produksi_teoritis" style="font-size:12px;width:30%;" id="tes1produksi">Produksi Teoritis</label>
        <input type="text"  id="produksi_teoritis" style="width:70%;padding:10px;border:1px solid #0cb8e8;outline:none;border-radius:5px;font-size:12px;" name="protis" value="<?=$dt['produksi_teoritis'];?>" readonly>
    </div>
    <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
        <label for="presentase_teoritis" style="font-size:12px;width:30%;" id="tes2produksi">75%</label>
        <input type="text" id="presentase_teoritis" name="pres" style="width:70%;padding:10px;border:1px solid #0cb8e8;outline:none;border-radius:5px;font-size:12px;" value="<?=$dt['presentase_teoritis'];?>" readonly>
    </div>
    <?php $idsd = $dt['id_loom']; ?>
    <div style="width:100%;padding:10px;height:200px;display:flex;align-items:center;gap:20px;">
    <button type="button" class="button2" id="btnSimpan2387">Update Hasil</button>
    <a href="<?=base_url('input-loom-produksi/'.$idsd);?>" style="text-decoration:none;font-size:10px;background:#ccc;color:#000;padding:12px 5px;border-radius:3px;">Tambahkan Produksi Lain</a>
    
    <a href="javascript:;" onclick="delet('<?=$idsd;?>')" style="text-decoration:none;font-size:10px;background:red;color:#fff;padding:12px 5px;border-radius:3px;">Hapus Data Ini</a>
    </div>
    <?php
        $nilaitoleransi = $this->data_model->get_byid('loom_toleransi',['id_tol'=>1])->row("nilaitoleransi");
    ?>
    <input type="hidden" id="toleransi" value="<?=$nilaitoleransi;?>">
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
        $( "#btnSimpan23" ).on( "click", function() {
            var operator = $('#userlogin').val();
            var tgl = $('#id_tgl').val();
            var shift = $('#id_shift').val();
            var nomesin = $('#autocomplete').val();
            var kons = $('#kons').val();
            var rpm = $('#rpm').val();
            var pick = $('#pick').val();
            var lusi = $('#lusi').val();
            var mnt_lusi = $('#mnt_lusi').val();
            var rtrt_lusi = $('#rtrtlusi').val();
            var pakan = $('#pakan').val();
            var mnt_pakan = $('#mnt_pakan').val();
            var rtrt_pakan = $('#rtrt_pakan').val();
            var eff = $('#eff').val();
            var prod = $('#produksi').val();
            var protis = $('#produksi_teoritis').val();
            var pres = $('#presentase_teoritis').val();
            console.log(''+tgl);
            if(operator!="" && tgl!="" && shift!="" && nomesin!="" && kons!="" && rpm!="" && pick!="" && lusi!="" && mnt_lusi!="" && rtrt_lusi!="" && pakan!="" && mnt_pakan!="" && rtrt_pakan!="" && eff!="" && prod!="" && protis!="" && pres!=""){
                $.ajax({
                    url:"<?=base_url('prosesajax/savedLoomCounter');?>",
                    type: "post",
                    data: {"operator":operator, "tgl":tgl, "shift":shift, "nomesin":nomesin, "kons":kons, "rpm":rpm, "pick":pick, "lusi":lusi, "mnt_lusi":mnt_lusi, "rtrt_lusi":rtrt_lusi, "pakan":pakan, "mnt_pakan":mnt_pakan, "rtrt_pakan":rtrt_pakan, "eff":eff, "prod":prod, "protis":protis, "pres":pres},
                    cache: false,
                    success: function(dataResult){
                        // var dataResult = JSON.parse(dataResult);
                        // if(dataResult.statusCode==200){
                        //     Swal.fire({
                        //         title: "Berhasil",
                        //         text: "Berhasil menyimpan loom counter",
                        //         icon: "success"
                        //     });
                        //  } else {
                            Swal.fire({
                                title: "Warning",
                                text: ""+dataResult+"",
                                icon: "warning"
                            });
                        // }

                    }
                });
                
            } else {
                Swal.fire({
                    title: "Peringatan!!",
                    text: "Anda tidak mengisi semua data dengan benar",
                    icon: "warning"
                });
            }
            
        } );

        function hitungratalusi(){
            var lusi = $('#lusi').val();
            var mnt_lusi = $('#mnt_lusi').val();
            var rtrt_lusi = $('#rtrtlusi').val();
            if(lusi!="" && mnt_lusi!=""){
                var ratarata = (parseInt(mnt_lusi) / parseInt(lusi)) * 60;
                var nilaiAkhir = parseFloat(ratarata.toFixed(1));
                $('#rtrtlusi').val(''+nilaiAkhir);
            } else {
                $('#rtrtlusi').val('');
            }
        }
        function hitungratapakan(){
            var pakan = $('#pakan').val();
            var mnt_pakan = $('#mnt_pakan').val();
            var rtrt_pakan = $('#rtrt_pakan').val();
            if(pakan!="" && mnt_pakan!=""){
                var ratarata = (parseInt(mnt_pakan) / parseInt(pakan)) * 60;
                var nilaiAkhir = parseFloat(ratarata.toFixed(1));
                $('#rtrt_pakan').val(''+nilaiAkhir);
            } else {
                $('#rtrt_pakan').val('');
            }
        }
        function produksiteoritis(){
            var eff_mesin = $('#eff').val();
            var rpm = $('#rpm').val();
            var pick = $('#pick').val();
            var toleransi = $('#toleransi').val();
            var produksi_teoritis = $('#produksi_teoritis').val();
            var nilai_tole = parseFloat(toleransi) / 100;
            var nilai_tole2 = parseFloat(nilai_tole.toFixed(2));
            //console.log(''+nilai_tole2);
            if(eff_mesin!="" && rpm!="" && pick!=""){
                var ratarata = (parseFloat(eff_mesin)/100) * parseInt(rpm) * 60 * 0.0254 * 8 / parseInt(pick);
                var nilaiAkhir = parseFloat(ratarata.toFixed(1));
                $('#produksi_teoritis').val(''+nilaiAkhir);

                var rata75 = (parseInt(rpm) / parseInt(pick)) * 60 * 8 * 0.0254 * nilai_tole2;
                var nilaiAkhir2 = parseFloat(rata75.toFixed(1));
                $('#presentase_teoritis').val(''+nilaiAkhir2);
            } else {
                $('#produksi_teoritis').val('');
            }
        }
        $( "#input_a" ).on( "click", function() {
            document.getElementById("input_a").checked = true;
            document.getElementById("input_b").checked = false;
            document.getElementById("input_c").checked = false;
            console.log('tesab');
            $('#groupid').val('A');
        } );
        $( "#input_b" ).on( "click", function() {
            document.getElementById("input_b").checked = true;
            document.getElementById("input_a").checked = false;
            document.getElementById("input_c").checked = false;
            console.log('tesb');
            $('#groupid').val('B');
        } );
        $( "#input_c" ).on( "click", function() {
            document.getElementById("input_c").checked = true;
            document.getElementById("input_b").checked = false;
            document.getElementById("input_a").checked = false;
            console.log('tesc');
            $('#groupid').val('C');
        } );
        $( "#btnSimpan2387" ).on( "click", function() {
            Swal.fire('Anda tidak memiliki akses edit. Hubungi admin');
        } );

        function delet(id){
            Swal.fire({
            title: "Anda yakin akan Hapus?",
            text: "Menghapus proses data loom",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?=base_url('proses/deleteLoom/');?>"+id+"";                
            }
            });
        }
    </script>
</body>
</html>