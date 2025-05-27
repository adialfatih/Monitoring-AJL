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
            margin-bottom:20px;
        }
        .fullowek {
            width:100%;
            padding:0 30px;
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
        .full label {
            width:150px;
        }
        .full input, .full select {
            width:250px;
            outline:none;
            border:1px solid #ccc;
            padding: 10px 8px;
            border-radius:5px;
        }
        .ml-10{margin-left:10px;}
        .ml-20{margin-left:20px;}
        .ml-30{margin-left:30px;}
        .ml-40{margin-left:40px;}
        .ml-50{margin-left:50px;}
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
            margin-left:35px;background:#089c08;padding:10px 20px;border-radius:5px;text-decoration:none;color:#fff;
            outline:none;border:none;cursor:pointer;
        }
        .btnbtn:hover, .btnbtn.active {background:#055c05;color:#fff;}
        #daterange:hover {
            border:1px solid #2473d6;
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
        $cek = $this->data_model->get_byid('loom_counter', ['sha1(id_loom)'=>$uri3]);
        // if($cek->num_rows() == 1){
        //     $txt = "<div class='full nopad'>Ditemukan</div>";
        // } else {
        //     $txt = "<div class='full nopad'>Tidak</div>";
        // }
        
    ?>
    
    <div class="full">
        <div class="back" id="backid" onclick="history.back()"><img src="<?=base_url('logo/back.svg');?>" alt="Kembali"></div>
        <h1>Update Data Loom </h1>
    </div>
    <?php
        if($cek->num_rows() == 1){
        $ex = explode(' ', $cek->row("tm_input"));
        $ex2 = explode('-', $ex[0]);
        $prinTgl = $ex2[2]." ".$ar[$ex2[1]]." ".$ex2[0];
        $ex3 = explode(':', $ex[1]);
    ?>
    <div class="full nopad">
        Di input Oleh : <strong>&nbsp;<?=$cek->row('yg_input');?></strong>, Pada Tanggal <strong>&nbsp;<?=$prinTgl;?></strong>&nbsp;Pukul <strong>&nbsp;<?=$ex3[0].":".$ex3[1];?></strong>
    </div>
    <?php } else { ?>
    <div class="full nopad" style="color:red;">
        Data Tidak ditemukan
    </div>
    <?php } 
    $idid = $cek->row('id_loom');
    ?>
    <form action="<?=base_url('proses/savedLoomCounter');?>" method="post">
    <input type="hidden" value="<?=$this->session->userdata('nama');?>" name="users_edit">
    <input type="hidden" value="<?=$cek->row('id_loom');?>" name="idid">
    <div class="full nopad">
        <label for="nomc">No Mesin</label>
        <input type="text" id="nomc" name="nomesin" value="<?=$cek->row('no_mesin');?>" style="border:1px solid #b00202" readonly/>
        <!-- <a href="javascript:;" id="klikShift1" class="btnbtn">Shift 1</a>
        <a href="javascript:;" id="klikShift2" class="btnbtn">Shift 2</a>
        <a href="javascript:;" id="klikShift3" class="btnbtn">Shift 3</a>
        <a href="javascript:;" id="klikAkumulasi" class="btnbtn active">Akumulasi 3 Shift</a> -->
    </div>
    <div class="full nopad">
        <label for="daterange">Tanggal</label>
        <input type="hidden" name="tgl_old" value="<?=$cek->row('tgl');?>" >
        <input type="date" id="daterange" name="tgl" value="<?=$cek->row('tgl');?>" />
        <label for="Shift" class="ml-20">Shift</label>
        <input type="hidden" name="shift_old" value="<?=$cek->row('shift_mesin');?>" >
        <select name="shift" id="Shift">
            <option value="1" <?=$cek->row('shift_mesin')=="1" ? 'selected':'';?>>Shift 1</option>
            <option value="2" <?=$cek->row('shift_mesin')=="2" ? 'selected':'';?>>Shift 2</option>
            <option value="3" <?=$cek->row('shift_mesin')=="3" ? 'selected':'';?>>Shift 3</option>
        </select>
        <label for="groupid" class="ml-20">Group</label>
        <input type="hidden" name="groupid_old" value="<?=$cek->row('group_shift');?>" >
        <select name="groupid" id="groupid">
            <option value="A" <?=$cek->row('group_shift')=="A" ? 'selected':'';?>>Group A</option>
            <option value="B" <?=$cek->row('group_shift')=="B" ? 'selected':'';?>>Group B</option>
            <option value="C" <?=$cek->row('group_shift')=="C" ? 'selected':'';?>>Group C</option>
        </select>
    </div>
    <div class="full nopad">
        <label for="kons">Konstruksi</label>
        <input type="hidden" name="kons_old" value="<?=$cek->row('konstruksi');?>" >
        <input type="text" id="kons" name="kons" value="<?=$cek->row('konstruksi');?>" list="listkons" required/>
        <datalist id="listkons">
                    <option value="RJ02">
                    <option value="RJ03">
                    <option value="RJ04">
                    <option value="RK05A">
                    <option value="RJ05B">
                    <option value="RJ05C">
                    <option value="RJ05D">
                    <option value="RJ05F">
                    <option value="RJ05G">
                    <option value="RJ05H">
                    <option value="RJ13">
                    <option value="RJ13A">
                    <option value="RJ13B">
                    <option value="RJ13C">
                    <option value="RJ13C">
                    <option value="RJ15">
                    <option value="RJ15A">
                    <option value="RJ15B">
                    <option value="RJ15C">
                    <option value="RJ15D">
                    <option value="RJ15H">
                    <option value="RJ15J">
                    <option value="RJ15K">
                    <option value="RJ15L">
                    <option value="RJ16">
                    <option value="RJ17">
                    <option value="RJ17A">
                    <option value="RJ18">
                    <option value="RJ16A">
                    <option value="RJ16B">
                    <option value="RJ19">
                    <option value="RJ05K">
                    <option value="RJ13D">
                    <option value="RJ09A">
                    <option value="RJ03A">
                    <option value="RJ05J">
                    <option value="RJ20">
                    <option value="RJ13E">
                    <option value="RJ05L">
                    <option value="RJ05L_RJS">
                    <option value="RJ13F">
                    <option value="RJ13G">
                    <option value="RJ13H">
                    <option value="RJ13J">
                    <option value="RJ05M">
                    <option value="RJ17B">
                    <option value="RJ05B_RJS">
                    <option value="RJ13K">
                    <option value="RJ05GS">
                    <option value="RJ13L">
                </datalist>
    </div>
    <div class="full nopad">
        <label for="rpm">RPM</label>
        <input type="hidden" name="rpm_old" value="<?=$cek->row('rpm');?>" >
        <input type="text" id="rpm" name="rpm" value="<?=$cek->row('rpm');?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" required/>
        <label for="pick" class="ml-20">PICK</label>
        <input type="hidden" name="pick_old" value="<?=$cek->row('pick');?>" >
        <input type="text" id="pick" name="pick" value="<?=$cek->row('pick');?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" required/>
    </div>
    <div class="full nopad">
        <span style="width:100%;height:1px;background:#000;"></span>
    </div>
    <div class="full nopad">
        <strong>Putus Benang</strong>
    </div>
    <div class="full nopad">
        <label for="lusi">Lusi</label>
        <input type="hidden" name="lusi_old" value="<?=$cek->row('ls');?>" >
        <input type="text" id="lusi" name="lusi" value="<?=$cek->row('ls');?>" onchange="hitungratalusi()" onkeyup="hitungratalusi()" required/>
        <label for="mnt_lusi" class="ml-20">Menit</label>
        <input type="hidden" name="mnt_lusi_old" value="<?=$cek->row('ls_mnt');?>" >
        <input type="text" id="mnt_lusi" name="mnt_lusi" value="<?=$cek->row('ls_mnt');?>"  onchange="hitungratalusi()" onkeyup="hitungratalusi()" required/>
        <label for="rtrtlusi" class="ml-20">Rata2 Lost <small>(detik)</small></label>
        <input type="text" id="rtrtlusi" name="rtrt_lusi" value="<?=$cek->row('rt_lost_ls');?>" style="border:1px solid #b00202" readonly />
    </div>
    <div class="full nopad">
        <label for="pakan">Pakan</label>
        <input type="hidden" name="pakan_old" value="<?=$cek->row('pkn');?>" >
        <input type="text" id="pakan" name="pakan" value="<?=$cek->row('pkn');?>" onchange="hitungratapakan()" onkeyup="hitungratapakan()" />
        <label for="mnt_pakan" class="ml-20">Menit</label>
        <input type="hidden" name="mnt_pakan_old" value="<?=$cek->row('mnt');?>" >
        <input type="text" id="mnt_pakan" name="mnt_pakan" value="<?=$cek->row('mnt');?>"  onchange="hitungratapakan()" onkeyup="hitungratapakan()" required/>
        <label for="rtrt_pakan" class="ml-20">Rata2 Lost <small>(detik)</small></label>
        <input type="text" id="rtrt_pakan" name="rtrt_pakan" value="<?=$cek->row('rt_lost_pkn');?>" style="border:1px solid #b00202" readonly />
    </div>
    <div class="full nopad">
        <span style="width:100%;height:1px;background:#000;"></span>
    </div>
    <div class="full nopad">
        <label for="eff">EFF %</label>
        <input type="hidden" name="eff_old" value="<?=$cek->row('eff');?>" >
        <input type="text" id="eff" name="eff" value="<?=$cek->row('eff');?>" onchange="produksiteoritis()" onkeyup="produksiteoritis()" required />
        <label for="produksi" class="ml-20">Produksi</label>
        <input type="hidden" name="prod_old" value="<?=$cek->row('produksi');?>" >
        <input type="text" id="produksi" name="prod" value="<?=$cek->row('produksi');?>" required />
    </div>
    <div class="full nopad">
        <label for="produksi_teoritis">Produksi Teoritis</label>
        <input type="text" id="produksi_teoritis" name="protis" value="<?=$cek->row('produksi_teoritis');?>" style="border:1px solid #b00202" onchange="produksiteoritis()" onkeyup="produksiteoritis()" readonly />
        <label for="presentase_teoritis" class="ml-20">75 %</label>
        <input type="text" id="presentase_teoritis" name="pres" value="<?=$cek->row('presentase_teoritis');?>" style="border:1px solid #b00202" readonly/>
        
        <button type="submit" id="klikShift2" class="btnbtn ml-50">Update & Simpan</button>
    </div>
    <div class="full nopad">
        <span style="width:100%;height:1px;background:#000;"></span>
    </div>
    <div class="full nopad">
        <strong>Riwayat Editing</strong>
    </div>
    <div class="fullowek">
        <?php 
        $cek_editing = $this->data_model->get_byid('loom_counter_rwytupdt', ['id_loom'=>$idid]);
        $no=1;
        if($cek_editing->num_rows() > 0){
        echo "<table>";
        foreach($cek_editing->result() as $bal):
            $op = explode(' ', $bal->tm_stmp);
            $op1 = explode('-', $op[0]);
            $print_op = $op1[2]." ".$ar[$op1[1]]." ".$op1[0]; 
            $op2 = explode(':', $op[1]);

            echo "<tr>";
            echo "<td>".$no.".&nbsp;&nbsp;</td>";
            echo "<td><strong>".$bal->yg_edit."</strong> Mengupdate ".$bal->txt." Pada Tanggal <strong>".$print_op."</strong> Jam <strong>".$op2[0].":".$op2[1]."</strong></td>";
            echo "</tr>";
            $no++;
        endforeach;
        echo "</table>";
        } else {
            echo "<small>Belum Pernah Di Edit</small>";
        }
        ?>
    </div>
    <div style="width:100%;height:100px;">&nbsp;</div>
    <?php
        $nilaitoleransi = $this->data_model->get_byid('loom_toleransi',['id_tol'=>1])->row("nilaitoleransi");
    ?>
    <input type="hidden" id="toleransi" value="<?=$nilaitoleransi;?>">
    </form>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    
    <script>
        function infonot3shift(){
            Swal.fire({
            title: "Peringatan",
            text: "Data tidak lengkap dari 3 shift",
            icon: "warning"
            });
        }
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
    </script>
</body>
</html>