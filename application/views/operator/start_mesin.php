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
        $uri = $this->uri->segment(3);
        $nomesin = $this->uri->segment(4);
        $mc = strtoupper($nomesin);
        $cek1 = $this->data_model->get_byid('produksi_mesin_ajl', ['no_mesin'=>$mc,'proses'=>'onproses']);
        $cek2 = $this->data_model->get_byid('produksi_mesin_ajl', ['no_mesin'=>$mc,'proses'=>'stop']);
        if($cek1->num_rows()==0 AND $cek2->num_rows()==0){
            // echo "<div style='width:100%;background:red;color:#fff;padding:15px;'>";
            // echo "Mesin $mc tidak berjalan proses produksi";
            // echo "</div>";
        } else {
            echo "<div style='width:100%;background:red;color:#fff;padding:15px;'>";
            echo "Mesin $mc sedang berjalan proses produksi";
            echo "</div>";
        }
        
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <span style="font-size:1.2em;margin:10px;color:#1561e6;font-weight:bold;">Nomor Mesin : <?=$mc;?> </span>
        
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
        $kodeproduksi = $this->data_model->acakKode2(17);
    ?>
    <!-- <small style="margin:-5px 10px 10px 10px;"><=$newToday;?>, <strong><=$prinTgl;?></strong> User : <strong id="nmoptid"><=$nmuser;?></strong></small> -->
    <small style="margin:-5px 10px 10px 10px;">User : <strong id="nmoptid"><?=$nmuser;?></strong></small>
    
    <form action="<?=base_url('proses/proses');?>" method="post">
    <input type="hidden" value="<?=$kodeproduksi;?>" name="kd_produksi_mesin" id="kdproduksimesin">
    <input type="hidden" value="" name="id_beam_sizing" id="idbeamsizing" required>
    <div class="container" style="margin-top:10px;">
        <div style="width:100%;padding:10px 10px 0 10px;display:flex;justify-content:space-between;" id="box_status">
            <span style="font-weight:bold;">Naik Beam Mesin AJL</span>
            
            <span style="background:red;color:#fff;font-size:10px;padding:3px 10px;border-radius:4px;">Mesin Off</span>
            
        </div>
        <div style="width:100%;padding:0 10px;font-size:12px;">
           Input produksi pada mesin <strong><?=$mc;?></strong>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="nomcid" style="font-size:13px;width:30%;">No MC</label>
            <input type="text" id="nomcid" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="nomcid" placeholder="Masukan Nomor Mesin" value="<?=$mc;?>" required>
        </div>
        <div class="fl">
            <label for="autoComplete" style="font-size:13px;width:30%;">Beam</label>
            <div class="autoComplete_wrapper" style="width:70%;">
                <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="kons" style="width:110%;" onchange="tescange(this.value)">
            </div>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="optsiz23" style="font-size:13px;width:30%;">Opt Sizing</label>
            <input type="text" id="optsiz23" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="optsiz" placeholder="Operator Sizing" readonly>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="konsid" style="font-size:13px;width:30%;">Konstruksi</label>
            <input type="text" id="konsid" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="konsid" placeholder="Masukan Konstruksi" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="pjglusi" style="font-size:13px;width:30%;">Panjang Lusi</label>
            <input type="text" id="pjglusi" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="pjglusi" placeholder="Masukan Panjang Lusi" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="jmlhelai" style="font-size:13px;width:30%;">Jumlah Helai</label>
            <input type="number" id="jmlhelai" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="jmlhelai" placeholder="Masukan jumlah helai" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="opts" style="font-size:13px;width:30%;">Operator</label>
            <input type="text" id="opts" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="opts" placeholder="Masukan nama operator" required>
        </div>
        
        <input type="hidden" name="users_data" value="<?=$this->session->userdata('nama');?>">
        <span style="padding:10px;font-size:11px;color:red;display:none;" id="notifhabis">Beam Telah Habis Di Potong</span>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;" id="btnSimpanProses">
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:#417afa;color:#fff;border-radius:4px;" onclick="simpanthis()" id="pinStart">Simpan & Proses</button>
            
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:center;flex-direction:column;" id="loadng">
            <div class="loader"></div>
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:#4cb307;color:#fff;border-radius:4px;margin-top:30px;" onclick="finishnaik()" id="pinselesai">Selesai Naik Beam</button>
        </div>
    </div>
    </form>
    
    <div style="width:100%;height:50px;">&nbsp;</div>
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
    <?php
        $kdrol = $this->db->query("SELECT * FROM v_beam_sizing2 ");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $ex = explode('-',$val->tgl_produksi);
                $tgl = $ex[2]."/".$ex[1]."/".$ex[0];
                $kode_beam = $val->oka." - ".$val->kode_beam." - ".$val->konstruksi;
                $data_nama = ''.$kode_beam.'';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$data_nama.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
    <script>
      const autoCompleteJS = new autoComplete({
            placeHolder: "OKA - Beam - Konstruksi",
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
                        $.ajax({
                            url:"<?=base_url('proses/changeBeamToAJL');?>",
                            type: "POST",
                            data: {"selection" : selection},
                            cache: false,
                            success: function(dataResult){
                                console.log('berhasil_1');
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){ 
                                    $('#konsid').val(''+dataResult.kons);
                                    $('#optsiz23').val(''+dataResult.optsiz);
                                    $('#idbeamsizing').val(''+dataResult.id_beam_sizing);
                                    $('#pjglusi').val(''+dataResult.lusi);
                                    if(dataResult.sts == "oke"){
                                        $('#notifhabis').hide();
                                        $('#btnSimpanProses').show();
                                    } else {
                                        $('#notifhabis').show();
                                        $('#btnSimpanProses').hide();
                                    }
                                } else {
                                    Swal.fire(''+dataResult.psn+'');
                                    $('#autoComplete').val('');
                                }
                            }
                        });
                    }
                }
            }
        });
        function tescange(id){
            if(id==""){
                $('#konsid').val('');
                $('#idbeamsizing').val('');
                $('#pjglusi').val('');
            }
        }
        function simpanthis(){
            var kodeproses = $('#kdproduksimesin').val();
            var idbeamsizing = $('#idbeamsizing').val();
            var nomc = $('#nomcid').val();
            var kons = $('#konsid').val();
            var lusi = $('#pjglusi').val();
            var helai = $('#jmlhelai').val();
            var opts = $('#opts').val();
            if(kodeproses!="" && idbeamsizing!="" && nomc!="" && kons!="" && lusi!="" && helai!=""){
                $.ajax({
                    url:"<?=base_url('proses/addprosesnaikbeam');?>",
                    type: "POST",
                    data: {"kodeproses" : kodeproses, "idbeam":idbeamsizing, "nomc":nomc, "kons":kons, "lusi":lusi, "helai":helai, "opts":opts},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            $('#loadng').show();
                            $('#pinStart').hide();
                            $('#nomcid').prop('readonly', true);
                            $('#konsid').prop('readonly', true);
                            $('#pjglusi').prop('readonly', true);
                            $('#jmlhelai').prop('readonly', true);
                        } else {
                            Swal.fire(''+dataResult.psn);
                        }
                    }
                });
            } else {
                Swal.fire('Form harus di isi semua');
            }
        }
        function finishnaik(){
            $('#pinselesai').html('Loading...');
            var kodeproses = $('#kdproduksimesin').val();
            var nomc = $('#nomcid').val();
            Swal.fire({
                title: "Tambahkan Keterangan",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Simpan",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    try {
                        //Swal.fire('tes'+login);
                        console.log('data terkirim = '+login);
                        $.ajax({
                            url:"<?=base_url('proses/addprosesnaikbeamstart');?>",
                            type: "POST",
                            data: {"kodeproses" : kodeproses, "nomc":nomc, "txt":login},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                var id = dataResult.idp;
                                Swal.fire('Saving.. Please Wait..');
                                setTimeout(() => {
                                    window.location.href = '<?=base_url('operator/mesin/');?>'+id+'';
                                }, 3000);
                            }
                        });
                    } catch (error) {
                    Swal.showValidationMessage(`
                        Request failed: ${error}
                    `);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                //if (result.isConfirmed) {
                    //Swal.fire(''+login);
                //}
            });
        }
        $('#loadng').hide();
    </script>
</body>
</html>