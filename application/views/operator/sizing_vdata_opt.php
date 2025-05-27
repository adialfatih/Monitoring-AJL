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
        $username = $this->session->userdata('username');
        $akses = $this->session->userdata('akses');
        $idsizing = $this->uri->segment(4);
        $srow = $this->data_model->get_byid('produksi_sizing', ['id_sizing'=>$idsizing])->row_array();
        $x = explode('-', $srow['tgl_produksi']);
        $printTglSizing = $x[2]." ".$ar[$x[1]]." ".$x[0];
    ?>
    
    <div class="clsBtn">
        <table style="font-size:13px;">
            <tr>
                <td style="width:70px;">OKA</td>
                <td style="width:10px;">:</td>
                <td><strong><?=$srow['oka'];?></strong></td>
            </tr>
            <tr>
                <td style="width:70px;">Tanggal</td>
                <td style="width:10px;">:</td>
                <td><strong><?=$printTglSizing;?></strong></td>
            </tr>
            <tr>
                <td style="width:70px;">Konstruksi</td>
                <td style="width:10px;">:</td>
                <td><strong><?=$srow['konstruksi'];?></strong></td>
            </tr>
            <tr>
                <td style="width:70px;">Beam</td>
                <td style="width:10px;">:</td>
                <td><strong><?=$srow['jumlah_beam_sizing'];?></strong></td>
            </tr>
        </table>
        
    </div>
    <?php if($akses=="admin" OR $akses=="sizing"){ ?>
    <div style="width:100%;padding:10px;font-size:13px;display:flex;flex-direction:column;margin-top:15px;">
        <div style="width:100%;border:1px solid #ccc;padding:15px 10px 5px 10px;position:relative;">
            <span style="background:#fff;position:absolute;top:-9px;left:5px;padding:0 10px;">Input Beam</span>
            <div style="width:100%;display:flex;align-items:center;">
                <label for="nobeamid" style="font-weight:bold;width:100px;">No Beam</label>
                <input type="text" style="width:200px;font-size:11px;padding:8px;border:1px solid #ccc;border-radius:3px;outline:none;" placeholder="Masukan no beam" id="nobeamid">
            </div>
            <div style="width:100%;display:flex;align-items:center;margin-top:10px;">
                <label for="ukrid" style="font-weight:bold;width:100px;">Ukuran</label>
                <input type="number" style="width:200px;font-size:11px;padding:8px;border:1px solid #ccc;border-radius:3px;outline:none;" placeholder="Masukan ukuran" id="ukrid">
            </div>
            <div style="width:100%;display:flex;align-items:center;margin-top:10px;">
                <label for="autoComplete" style="width:100px;font-weight:bold;">Konstruksi</label>
                <div class="autoComplete_wrapper" style="width:250px;margin-left:7px;x">
                    <input id="autoComplete" style="width:150%;font-size:11px;transform:translateX(4px);" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="kons" required>
                </div>
            </div>
            <div style="width:100%;display:flex;align-items:center;margin-top:10px;">
                <label for="draftid" style="font-weight:bold;width:100px;">Draft</label>
                <input type="number" style="font-size:11px;padding:8px;border:1px solid #ccc;border-radius:3px;outline:none;" placeholder="Masukan draft" id="draftid">
            </div>
            <!-- <div style="width:100%;display:flex;align-items:center;margin-top:10px;">
                <label for="tglid" style="font-weight:bold;width:100px;">Tanggal</label>
                <input type="date" style="width:200px;font-size:11px;padding:8px;border:1px solid #ccc;border-radius:3px;outline:none;" placeholder="Masukan draft" id="tglid2" value="<?=$tgl;?>">
            </div> -->
            <input type="hidden" id="tglid" value="<?=$tgl;?>">
            <div style="width:100%;display:flex;align-items:center;margin-top:10px;">
                <label for="opts" style="font-weight:bold;width:100px;">Operator</label>
                <input type="text" style="width:200px;font-size:11px;padding:8px;border:1px solid #ccc;border-radius:3px;outline:none;" placeholder="Masukan operator" id="opts">
            </div>
            <input type="hidden" id="idbeamsizing9" value="<?=$idsizing;?>">
            <button style="background:#5881db;color:#fff;border-radius:4px;display:flex;align-items:center;outline:none;border:none;padding:10px 20px;font-size:12px;margin:15px 0 10px 0;" onclick="simpanbeamopt()">+ Simpan</button>
        </div>
    </div>
    <?php } ?>
    <div style="width:100%;padding:10px;font-size:13px;display:flex;flex-direction:column;margin-top:10px;">
        <div style="width:100%;border:1px solid #ccc;padding:15px 10px 5px 10px;position:relative;">
            <span style="background:#fff;position:absolute;top:-9px;left:5px;padding:0 10px;">Beam Sizing</span>
            <table style="width:100%;font-size:12px;border:1px solid #000;border-collapse:collapse;margin-bottom:10px;" border="1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Beam</th>
                        <th>Ukuran</th>
                        <th>Konstruksi</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Opt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $dtbeam = $this->data_model->get_byid('beam_sizing', ['id_sizing'=>$idsizing]);
                        if($dtbeam->num_rows() > 0){
                            $no=1;
                            foreach($dtbeam->result() as $pl):
                            if($pl->tgl_beam != "null"){
                                $o = explode('-',$pl->tgl_beam);
                                $tglbeam = $o[2]."/".$o[1];
                            } else { $tglbeam = ''; }
                    ?>
                    <tr>
                        <th><?=$no;?></th>
                        <td style="text-align:center;"><?=$pl->kode_beam;?></td>
                        <td style="text-align:center;"><?=$pl->ukuran_panjang;?></td>
                        <td style="text-align:center;"><?=$pl->konstruksi;?></td>
                        <td style="text-align:center;"><?=$tglbeam;?></td>
                        <?php //if($username == $pl->usersr){ ?>
                            <!-- <td style="text-align:center;color:red;" onclick="delbeam('<=$pl->id_beam_sizing;?>','<=$pl->kode_beam;?>','<=$pl->id_sizing;?>')"><=$pl->usersr;?></td> -->
                        <?php //} else { ?>
                            <td style="text-align:center;"><?=$pl->usersr;?></td>
                        <?php // } ?>
                        <td style="text-align:center;"><?=$pl->nmopt=="null" ? '':$pl->nmopt;?></td>
                    </tr>
                    <?php   $no++;
                            endforeach;
                    } else {
                        echo "<tr><td colspan='6' style='padding:5px;color:red;'>Belum ada data beam</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <span style="color:red;font-size:11px;">Anda sudah di larang untuk menghapus produksi sizing. Hubungi admin jika ada kesalahan penginputan.<br>Tanggal sudah terisi secara otomatis. <strong>Hati-hati dalam penginputan.!!</strong></span>
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
        function simpanbeamopt(){
            var kodebeam = document.getElementById('nobeamid').value;
            var ukr = document.getElementById('ukrid').value;
            var kons = document.getElementById('autoComplete').value;
            var draftid = document.getElementById('draftid').value;
            var tglid = document.getElementById('tglid').value;
            var id = document.getElementById('idbeamsizing9').value;
            var opts = document.getElementById('opts').value;
            if(kodebeam!="" && ukr!="" && kons!="" && draftid!="" && tglid!=""){
                $.ajax({
                url:"<?=base_url('proses/savedoptbeamsizing');?>",
                type: "POST",
                data: {"id" : id, "kodebeam":kodebeam, "ukr":ukr, "kons":kons,"draftid":draftid, "tgl":tglid, "opts":opts},
                cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        var sc = dataResult.statusCode;
                        var psn = dataResult.psn;
                        if(sc == 200){
                            let timerInterval;
                            Swal.fire({
                            title: "Menyimpan proses",
                            html: "Please wait <b></b> milliseconds.",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                            }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                                location.reload();
                            }
                            });
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
                Swal.fire('Isi semua data!');
            }
        }
        function delbeam(id,beam,idsizing){
            Swal.fire({
            text: "Anda yakin akan menghapus beam sizing "+beam+"",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Tidak"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url('proses/delbeamsizingopt');?>",
                    type: "POST",
                    data: {"id" : id,"idsizing":idsizing},
                    cache: false,
                    success: function(dataResult){
                        location.reload();
                    }
                });
            }
            });
        }
    </script>
    
</body>
</html>