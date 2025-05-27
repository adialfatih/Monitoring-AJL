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
        $cek = $this->data_model->get_byid('produksi_mesin_ajl', ['sha1(id_produksi_mesin)'=>$uri])->row_array();
        $id_produksi_mesin = $cek['id_produksi_mesin'];
        $id_beam_sizing = $cek['id_beam_sizing'];
        $kodeproduksi = $cek['kode_proses'];
        $nomesin = $cek['no_mesin'];
        $bimsizing = $this->data_model->get_byid('beam_sizing',['id_beam_sizing'=>$id_beam_sizing])->row_array();
        $nobeam = $bimsizing['kode_beam'];
        $konsbeam = $bimsizing['konstruksi'];
        $nmoptbeam = $bimsizing['nmopt'];
        if($nmoptbeam=="Samatex" OR $nmoptbeam=="Samatek" OR $nmoptbeam=="samatex" OR $nmoptbeam=="samatek" OR $nmoptbeam=="sama"){
            $_nobeam = "BSM ".$bimsizing['kode_beam']."";
        } else {
            $_nobeam = $bimsizing['kode_beam'];
        }
        $cek_rwyt11 = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_produksi_mesin, 'jenis_riwayat'=>'Naik Beam']);
        $cek_rwyt22 = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_produksi_mesin, 'jenis_riwayat'=>'Start Mesin']);
        if($cek_rwyt11->num_rows() == 1 AND $cek_rwyt22->num_rows()==0){
            $belum_start = "yes";
        } else {
            $belum_start = "no";
        }
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <span style="font-size:1.2em;margin:10px;color:#1561e6;font-weight:bold;">Nomor Mesin : <?=$nomesin;?> </span>
        
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
        $pl = explode('-', $cek['tgl_produksi']);
        $nmuser = $this->session->userdata('nama');
        $akses = $this->session->userdata('akses');
    ?>
    <!-- <small style="margin:-5px 10px 10px 10px;"><=$newToday;?>, <strong><=$prinTgl;?></strong> User : <strong id="nmoptid"><=$nmuser;?></strong></small> -->
    <small style="margin:-5px 10px 10px 10px;">User : <strong id="nmoptid"><?=$nmuser;?></strong></small>
    <?php if($belum_start=="yes"){ ?>
    <input type="hidden" value="<?=$nomesin;?>" id="nomcid">
    <input type="hidden" value="<?=$kodeproduksi;?>" name="kd_produksi_mesin" id="kdproduksimesin">
    <div style="width:100%;display:flex;flex-direction:column;justify-content:center;align-items:center;margin:50px 0;">
        <span>Sedang dalam proses naik beam</span>
        <p>&nbsp;</p>
        <div class="loader"></div>
        <button type="button" style="border:none;outline:none;padding:4px 14px;background:#4cb307;color:#fff;border-radius:4px;margin-top:30px;" onclick="finishnaik()" id="pinselesai">Selesai Naik Beam</button>
    </div>
    <?php } else { ?>
    <form action="<?=base_url('proses/simpanpotongan');?>" method="post">
    <input type="hidden" value="<?=$id_produksi_mesin;?>" name="id_produksi_mesin">
    <input type="hidden" value="<?=$id_beam_sizing;?>" name="id_beam_sizing">
    
    <div class="container" style="margin-top:10px;">
        <div style="width:100%;padding:10px 10px 0 10px;display:flex;justify-content:space-between;" id="box_status">
            <span style="font-weight:bold;">Input Potongan Kain</span>
            <?php if($cek['proses']=="onproses"){ ?>
            <span style="background:green;color:#fff;font-size:10px;padding:3px 10px;border-radius:4px;">Mesin On</span>
            <?php } else { ?>
            <span style="background:red;color:#fff;font-size:10px;padding:3px 10px;border-radius:4px;">Mesin Off</span>
            <?php } ?>
        </div>
        <div style="width:100%;padding:0 10px;font-size:12px;">
           Panjang Lusi : <strong><?=$cek['pjg_lusi'];?></strong> Awal Produksi : <strong><?=$pl[2]." ".$ar[$pl[1]]." ".$pl[0];?></strong>
        </div>
        <div class="fl">
            <label for="autoComplete" style="font-size:13px;width:30%;">Konstruksi</label>
            <div class="autoComplete_wrapper" style="width:70%;">
                <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="kons" style="width:100%;" value="<?=$konsbeam;?>">
            </div>
        </div>
        
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="beam" style="font-size:13px;width:30%;">No Beam</label>
            <input type="text" id="beam" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="beam" placeholder="Masukan Nomor Beam" value="<?=$_nobeam;?>" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="ukrptg" style="font-size:13px;width:30%;">Ukuran Potong</label>
            <input type="number" id="ukrptg" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="ukrptg" placeholder="Masukan Ukuran Potong" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="tglptg" style="font-size:13px;width:30%;">Tanggal Potong</label>
            <input type="date" id="tglptg" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="tglptg" placeholder="Masukan Ukuran Potong" value="<?=date('Y-m-d');?>" required>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="id_shift" style="font-size:13px;width:30%;">Group</label>
            <select id="id_shift" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="grub" required>
                <option value="">Pilih</option>
                <option value="A">Group A</option>
                <option value="B">Group B</option>
                <option value="C">Group C</option>
            </select>
        </div>
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <label for="keter" style="font-size:13px;width:30%;">Keterangan</label>
            <textarea id="keter" style="width:70%;padding:10px;border:1px solid #ccc;outline:none;border-radius:5px;font-size:12px;" name="keter" placeholder="Masukan keterangan / catatan"></textarea>
        </div>
        <input type="hidden" name="users_data" value="<?=$this->session->userdata('nama');?>">
        <div style="width:100%;padding:10px;display:flex;align-items:center;justify-content:space-between;">
            <?php if($cek['proses']=="onproses"){ 
                        if($akses == "cutting" OR $akses == "admin" OR $akses == "beam"){
            ?>
                        <button type="submit" style="border:none;outline:none;padding:4px 14px;background:#417afa;color:#fff;border-radius:4px;">Simpan & Potong</button>
            <?php
                        } else { ?>
                            <button type="button" onclick="noakses('Potong Kain')" style="border:none;outline:none;padding:4px 14px;background:#417afa;color:#fff;border-radius:4px;">Simpan & Potong</button>
            <?php       } 
                  } else { echo "<span>&nbsp;</span>"; } 
            
            if($akses == "beam" OR $akses == "admin"){
            ?>
            <div style="display:flex;">
            <?php if($cek['proses']=="onproses"){ ?>
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:orange;color:#fff;border-radius:4px;margin-right:5px;" onclick="tesoke('<?=$id_produksi_mesin;?>')">Stop</button>
            <?php } if($cek['proses']=="stop"){ ?>
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:green;color:#fff;border-radius:4px;margin-right:5px;" onclick="tesnyala('<?=$id_produksi_mesin;?>')">Start</button>
            <?php } ?>
            <?php if($cek['proses']=="finish"){ ?>
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:green;color:#fff;border-radius:4px;" onclick="naikanbeam('<?=$nomesin;?>')">Naik Beam</button>
            <?php } else { ?>
            <button type="button" style="border:none;outline:none;padding:4px 14px;background:#ed2630;color:#fff;border-radius:4px;" onclick="turunkanbeam('<?=$id_produksi_mesin;?>')">Turun Beam</button>
            <?php } 
            }
            ?>
            </div>
        </div>
    </div>
    </form>
    <div style="width:100%;overflow-x:auto;padding:0 10px;margin-top:20px;">
    <h2 style="font-size:16px;">Data Potongan Beam</h2>
        <table style="min-width:100%;border-collapse:collapse;margin-top:10px;border:1px solid #fff;" border="1">
            <thead>
                <tr>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Kode</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">No Beam</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Konstruksi</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Pjg</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Tgl Ptg</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Operator</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">Shift</th>
                    <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;">#</th>
                </tr>
                <tbody>
                    <?php 
                        $cek_potongan_mesin = $this->data_model->get_byid('produksi_mesin_ajl_potongan', ['id_produksi_mesin'=>$id_produksi_mesin]);
                        if($cek_potongan_mesin->num_rows() > 0){
                            $total_potongan=0;
                            $new_kons123="null";
                            foreach($cek_potongan_mesin->result() as $ke => $val){
                            $nu = $ke + 1;
                            $ex=explode('-', $val->tgl_potong);
                            $printTgl = $ex[2]."/".$ex[1];
                            $mtr = $val->ukuran_meter;
                            $id_potongan = $val->id_potongan;
                            echo "<tr style='background:#cedaf5;'>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->id_potongan."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->no_beam."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->konstruksi."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->ukuran_meter."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$printTgl."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->operator."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px 0px;'>".$val->shift."</td>";
                            if($nmuser == $val->operator){
                            ?>
                            <td style="font-size:10px;text-align:center;padding:3px 0px;color:red;" onclick="delpot('<?=$id_potongan;?>','<?=$mtr;?>')">Del</td>
                            <?php
                            } else {
                            ?>
                            <td style="font-size:10px;text-align:center;padding:3px 0px;color:red;">&nbsp;</td>
                            <?php
                            }
                            echo "</tr>";
                            $total_potongan+=$val->ukuran_meter;
                            $new_kons123 = $val->konstruksi;
                            }
                        } else {
                            echo "<tr style='background:#cedaf5;'>";
                            echo "<td colspan='8' style='padding:3px;font-size:12px;text-align:center;'>Belum ada data potongan kain</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;" colspan="3">Total Panjang</th>
                        <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;"><?=number_format($total_potongan,0,',','.');?></th>
                        <th style="font-size:12px;padding:3px 0px;background:#417afa;color:#fff;" colspan="4"></th>
                    </tr>
                </tfoot>
            </thead>
        </table>
        <input type="hidden" value="<?=$new_kons123;?>" name="konsterakhir" id="konsterakhir">
    </div>
    <h2 style="font-size:16px;margin:20px 0 0 10px;">Riwayat Produksi Mesin</h2>
    <div style="width:100%;overflow-x:auto;padding:0 10px;margin-top:5px;">
    
        <table style="min-width:100vw;border-collapse:collapse;margin-top:10px;border:1px solid #fff;" border="1">
            <thead>
                <tr>
                    <th style="font-size:12px;padding:3px;">No.</th>
                    <th style="font-size:12px;padding:3px;">Tanggal</th>
                    <th style="font-size:12px;padding:3px;">Waktu</th>
                    <th style="font-size:12px;padding:3px;">Riwayat</th>
                    <th style="font-size:12px;padding:3px;">Keterangan</th>
                    <th style="font-size:12px;padding:3px;">Login</th>
                </tr>
                <tbody>
                    <?php 
                        $cek_rwyt = $this->data_model->get_byid('produksi_mesin_ajl_rwyt', ['id_produksi_mesin'=>$id_produksi_mesin]);
                        if($cek_rwyt->num_rows() > 0){
                            foreach($cek_rwyt->result() as $ke1 => $val1){
                            $nu1 = $ke1 + 1;
                            $x=explode(' ', $val1->timestamprwyt);
                            $t = explode('-', $x[0]);
                            $yr = date('Y');
                            if($yr == $t[0]){
                                $tgl = $t[2]."/".$t[1];
                            } else {
                                $tgl = $t[2]."/".$t[1]."/".$t[0];
                            }
                            $jam = explode(':', $x[1]);
                            echo "<tr>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>$nu1</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>".$tgl."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>".$jam[0].":".$jam[1]."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>".$val1->jenis_riwayat."</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>";
                            if($val1->keterwyt == "null"){
                                echo "-";
                            } else {
                                echo $val1->keterwyt;
                            }
                            echo "</td>";
                            echo "<td style='font-size:12px;text-align:center;padding:3px;'>";
                            if($val1->operator_login == "null"){
                                echo "-";
                            } else {
                                echo $val1->operator_login;
                            }
                            echo "</td>";
                            echo "</tr>";
                            
                            }
                        } else {
                            echo "<tr>";
                            echo "<td colspan='7' style='padding:3px;font-size:12px;text-align:center;'>Belum ada data </td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
                
            </thead>
        </table>
    </div>
    <?php } ?>
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
        function showmodals(){
            $('.popupfull').addClass('active');
            $('.modals').addClass('active');
            $('#jdlmdl').html(''+msn);
        }
        function hidemodal(){
            $('.popupfull').removeClass('active');
            $('.modals').removeClass('active');
        }
        function tesnyala(id){
            $.ajax({
                    url:"<?=base_url('proses/ceknyalakanmesin');?>",
                    type: "POST",
                    data: {"id" : id},
                    cache: false,
                    success: function(dataResult){
                        location.reload();
                    }
            });
        }
        function tesoke(id){
            Swal.fire({
                title: "Masukan Alasan Mesin Stop",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Stop Mesin",
                showLoaderOnConfirm: true,
                preConfirm: async (login) => {
                    try {
                        //Swal.fire('tes'+login);
                        console.log('data terkirim = '+login);
                        $.ajax({
                            url:"<?=base_url('proses/stopmesin');?>",
                            type: "POST",
                            data: {"id" : id,"txt":login},
                            cache: false,
                            success: function(dataResult){
                                console.log('berhasil_1');
                                location.reload();
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
        function turunkanbeam(id){
            Swal.fire({
            title: "Turun Beam",
            text: "Anda akan menyelesaikan proses produksi mesin",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Lanjutkan"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url('proses/selesaiakanmesin');?>",
                    type: "POST",
                    data: {"id" : id},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                        title: "Selesai",
                        text: "Proses produksi mesin telah selesai",
                        icon: "success"
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                });
            }
            });
        }
        function delpot(id,mtr){
            Swal.fire({
            title: "Hapus Potongan?",
            text: "Anda akan menghapus potongan "+mtr+" meter",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url('proses/hapuspotongan');?>",
                    type: "POST",
                    data: {"id" : id,"mtr":mtr},
                    cache: false,
                    success: function(dataResult){
                        Swal.fire({
                        title: "Deleted!",
                        text: "Berhasil Menghapus",
                        icon: "success"
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                });
                
            }
            });
        }
        function cekkeadaanmesin(id){
            $.ajax({
                    url:"<?=base_url('proses/cekkeadaanmesin');?>",
                    type: "POST",
                    data: {"id" : id},
                    cache: false,
                    success: function(dataResult){
                        $('#box_status').html(dataResult);
                    }
            });
        }
        cekkeadaanmesin('<?=$id_produksi_mesin;?>');
        function naikanbeam(nomesin){
            var data = nomesin.toLowerCase();
            document.location.href = "<?=base_url('operator/mesin/naikbeam/');?>"+data;
        }
        function finishnaik(){
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
                        $('#pinselesai').html('Loading...');
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
        function noakses(txt){
            Swal.fire({
                title: "Akses diblokir",
                text: "Anda tidak memiliki akses ke "+txt+"",
                icon: "error"
            });
        }
        var konsTerakhir = document.getElementById('konsterakhir').value;
        document.getElementById('autoComplete').value = ''+konsTerakhir;
    </script>
</body>
</html>