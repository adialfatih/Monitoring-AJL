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
        .full input {
            width:250px;
            outline:none;
            border:1px solid #ccc;
            padding: 10px 8px;
            margin-left:10px;
            border-radius:5px;
        }
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
            margin-left:15px;background:#dadee3;padding:10px 20px;border-radius:5px;text-decoration:none;color:#000;
        }
        .btnbtn:hover, .btnbtn.active {background:#2473d6;color:#fff;}
        #daterange:hover {
            border:1px solid #2473d6;
        }
        .trbgbg {background:#ebebeb;border:1px solid #000;}
        .trbgbg:hover {background:#949494;border:1px solid #000;}
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
        //$uri4 = $this->uri->segment(4);
        if($uri3!="") {
                $ex1 = explode('-', $uri3);
                $txt1 = $ex1[2]." ".$ar[$ex1[1]]." ".$ex1[0];
                $txt = "Menampilkan data loom counter mesin AJL Tanggal <strong>".$txt1."</strong>";
                $val_range = "".$uri3."";
                //$val_range = "".$ex1[1]."/".$ex1[2]."/".$ex1[0]." - ".$ex1[1]."/".$ex1[2]."/".$ex1[0]."";
                $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$uri3' GROUP BY no_mesin");
            // } else {
            //     $ex1 = explode('-', $uri3);
            //     $txt1 = $ex1[2]." ".$ar[$ex1[1]]." ".$ex1[0];
            //     $ex2 = explode('-', $uri4);
            //     $txt2 = $ex2[2]." ".$ar[$ex2[1]]." ".$ex2[0];
            //     $txt = "Menampilkan data loom counter mesin AJL dari tanggal <strong>".$txt1."</strong> s/d <strong>".$txt2."</strong>";
            //     $val_range = "".$ex1[1]."/".$ex1[2]."/".$ex1[0]." - ".$ex2[1]."/".$ex2[2]."/".$ex2[0]."";
            //     $_query = $this->db->query("SELECT *  FROM loom_counter WHERE tgl BETWEEN '$uri3' AND '$uri4' GROUP BY no_mesin");
            
        } else {
            $txt = "Menampilkan data loom counter mesin AJL hari ini <strong>".$newToday."</strong>,  <strong>".$prinTgl."</strong>";
            $val_range = date('Y-m-d');
            //$val_range = "".$ex_tgl[1]."/".$ex_tgl[2]."/".$ex_tgl[0]." - ".$ex_tgl[1]."/".$ex_tgl[2]."/".$ex_tgl[0]."";
            $_query = $this->db->query("SELECT * FROM loom_counter WHERE tgl = '$tgl' GROUP BY no_mesin");
        }
    ?>
    <div class="full">
        <div class="back" id="backid"><img src="<?=base_url('logo/back.svg');?>" alt="Kembali"></div>
        <h1>Monitoring Mesin AJL </h1>
    </div>
    <div class="full nopad">
        <label for="daterange">Tanggal</label>
        <input type="date" id="daterange" name="daterange" value="<?=$val_range;?>" />
        <a href="javascript:;" id="klikShift1" class="btnbtn">Shift 1</a>
        <a href="javascript:;" id="klikShift2" class="btnbtn">Shift 2</a>
        <a href="javascript:;" id="klikShift3" class="btnbtn">Shift 3</a>
        <a href="javascript:;" id="klikAkumulasi" class="btnbtn active">Akumulasi 3 Shift</a>
    </div>
    
    <p style="width:100%;padding:10px 30px;" id="textID"><?=$txt;?></p>
    <div class="table-responsive">
        <table id="tableBody">
            <thead>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No Mesin</th>
                    <th colspan="6">Putus Benang 3 Shift</th>
                    <th rowspan="2">EFF %</th>
                    <th rowspan="2">Produksi</th>
                    <th rowspan="2">Prod Teoritis 3 Shift</th>
                    <th rowspan="2">EFF % Rill 3 Shift</th>
                    <th rowspan="2">Prod 100%</th>
                    <th colspan="2">Selisih</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th>LS</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>PKN</th>
                    <th>Mnt</th>
                    <th>Rata<sup>2</sup> Lost</th>
                    <th>Produksi 3 Shift</th>
                    <th>EFF% counter VS EFF% Rill</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n=1;
                    foreach($_query->result() as $val):
                    $_tgl = $val->tgl;
                    $_mc = $val->no_mesin;
                    $cekshif1 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='1' AND no_mesin='$_mc'");
                    $cekshif2 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='2' AND no_mesin='$_mc'");
                    $cekshif3 = $this->db->query("SELECT * FROM loom_counter WHERE tgl='$_tgl' AND shift_mesin='3' AND no_mesin='$_mc'");
                    
                    if($cekshif1->num_rows() == 1 AND $cekshif2->num_rows() == 1 AND $cekshif3->num_rows() == 1){
                        $_status = "oke";
                    } else {
                        $_status = "erorr";
                    }
                    if($cekshif1->row("ls")==""){ $ls1 = 0; } else { $ls1=$cekshif1->row("ls"); }
                    if($cekshif2->row("ls")==""){ $ls2 = 0; } else { $ls2=$cekshif2->row("ls"); }
                    if($cekshif3->row("ls")==""){ $ls3 = 0; } else { $ls3=$cekshif3->row("ls"); }
                    if($cekshif1->row("ls_mnt")==""){ $ls_mnt1 = 0; } else { $ls_mnt1=$cekshif1->row("ls_mnt"); }
                    if($cekshif2->row("ls_mnt")==""){ $ls_mnt2 = 0; } else { $ls_mnt2=$cekshif2->row("ls_mnt"); }
                    if($cekshif3->row("ls_mnt")==""){ $ls_mnt3 = 0; } else { $ls_mnt3=$cekshif3->row("ls_mnt"); }
                    $all_LS = intval($ls1) + intval($ls2) + intval($ls3);
                    $all_mntLS = intval($ls_mnt1) + intval($ls_mnt2) + intval($ls_mnt3);
                    $rtlost1 = $all_mntLS / $all_LS;
                    if(is_nan($rtlost1)){
                        $rtlost1 = "0";
                    }
                    if($cekshif1->row("pkn")==""){ $pkn1 = 0; } else { $pkn1=$cekshif1->row("pkn"); }
                    if($cekshif2->row("pkn")==""){ $pkn2 = 0; } else { $pkn2=$cekshif2->row("pkn"); }
                    if($cekshif3->row("pkn")==""){ $pkn3 = 0; } else { $pkn3=$cekshif3->row("pkn"); }
                    if($cekshif1->row("mnt")==""){ $mnt1 = 0; } else { $mnt1=$cekshif1->row("mnt"); }
                    if($cekshif2->row("mnt")==""){ $mnt2 = 0; } else { $mnt2=$cekshif2->row("mnt"); }
                    if($cekshif3->row("mnt")==""){ $mnt3 = 0; } else { $mnt3=$cekshif3->row("mnt"); }
                    $all_Pkn = intval($pkn1) + intval($pkn2) + intval($pkn3);
                    $all_mntPkn = intval($mnt1) + intval($mnt2) + intval($mnt3);
                    $rtlost2 = $all_mntPkn / $all_Pkn;
                    if(is_nan($rtlost2)){
                        $rtlost2 = "0";
                    }
                    if($cekshif1->row("eff")==""){ $eff_sif1 = 0; } else { $eff_sif1=$cekshif1->row("eff"); }
                    if($cekshif2->row("eff")==""){ $eff_sif2 = 0; } else { $eff_sif2=$cekshif2->row("eff"); }
                    if($cekshif3->row("eff")==""){ $eff_sif3 = 0; } else { $eff_sif3=$cekshif3->row("eff"); }

                    $eff = $eff_sif1 + $eff_sif2 + $eff_sif3;
                    $produksi = $cekshif1->row("produksi") + $cekshif2->row("produksi") + $cekshif3->row("produksi");
                    $jml_eff = round($eff,1);
                    $jml_effbagi3 = $jml_eff / 3;
                    $jml_effbagi3_koma1 = round($jml_effbagi3,1);
                    $eff_percen = $jml_effbagi3_koma1 / 100;

                    if($cekshif1->row("rpm")==""){ $rpm1 = 0; } else { $rpm1=$cekshif1->row("rpm"); }
                    if($cekshif2->row("rpm")==""){ $rpm2 = 0; } else { $rpm2=$cekshif2->row("rpm"); }
                    if($cekshif3->row("rpm")==""){ $rpm3 = 0; } else { $rpm3=$cekshif3->row("rpm"); }
                    $rpm4 = ($rpm1 + $rpm2 + $rpm3) / 3;
                    $rpm = round($rpm4);

                    if($cekshif1->row("pick")==""){ $pick1 = 0; } else { $pick1=$cekshif1->row("pick"); }
                    if($cekshif2->row("pick")==""){ $pick2 = 0; } else { $pick2=$cekshif2->row("pick"); }
                    if($cekshif3->row("pick")==""){ $pick3 = 0; } else { $pick3=$cekshif3->row("pick"); }
                    $pick4 = ($pick1 + $pick2 + $pick3) / 3;
                    $pick = round($pick4);
                    
                    
                    $protis3 = ($rpm / $pick) * 60 * 24 * 0.0254 * $eff_percen;
                    if(is_nan($protis3)){
                        $protis3 = "0";
                    }
                    $eff_percen_riil = ($produksi * $pick * 39.37) / $rpm / 60 / 24;
                    $percen_kan = $eff_percen_riil * 100;
                    if(is_nan($percen_kan)){
                        $percen_kan2 = 0;
                    } else {
                        $percen_kan2 = round($percen_kan,2); 
                    }
                    $produksi_100 = ($rpm / $pick) * 60 * 24 * 0.0254;
                    if(is_nan($produksi_100)){ $produksi_100=0; }
                    $selisih1 = $produksi - round($protis3,1);
                    $selisih2 = round($jml_effbagi3,2) - $percen_kan2;
                ?>
                <tr class="trbgbg">
                    <th><?=$n;?></th>
                    <td style="text-align:center;">
                        <?=$val->no_mesin;?>
                        <!-- <sup style="background:#f7a520;color:#f20707;width:15px;height:15px;display:flex;align-items:center;justify-content:center;border-radius:50%;">i</sup> -->
                    </td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>"><?=$all_LS;?></td>
                    <td style="text-align:center;<?=$all_mntLS==0?'background:red;color:#fff;':'';?>"><?=$all_mntLS;?></td>
                    <td style="text-align:center;<?=$rtlost1==0?'background:red;color:#fff;':'';?>"><?=round($rtlost1,1);?></td>
                    <td style="text-align:center;<?=$all_Pkn==0?'background:red;color:#fff;':'';?>"><?=$all_Pkn;?></td>
                    <td style="text-align:center;<?=$all_mntPkn==0?'background:red;color:#fff;':'';?>"><?=$all_mntPkn;?></td>
                    <td style="text-align:center;<?=$rtlost2==0?'background:red;color:#fff;':'';?>"><?=round($rtlost2,1);?></td>
                    <td style="text-align:center;<?=$jml_effbagi3<1 ?'background:red;color:#000;':'';?><?=$jml_effbagi3>0 && $jml_effbagi3<80 ?'background:orange;color:#000;':'';?>"><?=round($jml_effbagi3,1);?> %</td>
                    <td style="text-align:center;<?=$produksi==0?'background:red;color:#fff;':'';?>"><?=$produksi;?></td>
                    <td style="text-align:center;<?=$protis3==0?'background:red;color:#fff;':'';?>"><?=round($protis3,1);?></td>
                    <td style="text-align:center;<?=$percen_kan2==0?'background:red;color:#fff;':'';?>"><?=$percen_kan2;?> %</td>
                    
                    <td style="text-align:center;<?=$produksi_100==0?'background:red;color:#fff;':'';?>"><?=round($produksi_100,2);?></td>
                    <td style="text-align:center;<?=$selisih1>5 ?'background:orange;color:#000;':'';?><?=$selisih1 < -5 ?'background:orange;color:#000;':'';?>"><?=round($selisih1,2);?></td>
                    <td style="text-align:center;<?=$selisih2>5?'background:orange;color:#000;':'';?>"><?=round($selisih2,2);?> %</td>
                    <td style="text-align:center;">
                        <?php if($_status == "oke"){ ?>
                        <span class="material-symbols-outlined" style="color:green;font-size:18px;">done_all</span>
                        <?php } else { ?>
                        <span class="material-symbols-outlined" style="color:red;font-size:18px;cursor:pointer;" title="Data belum lengkap 3 shift" onclick="infonot3shift()">info</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                        $n++;
                    endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="tglNow" value="<?=date('Y-m-d');?>">
    <input type="hidden" id="shiftNow" value="0">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    
    <script>
        $(function() {
            $('input[name="daterange2"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                var tglawal = start.format('YYYY-MM-DD');
                var tglakir = end.format('YYYY-MM-DD');
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                window.location.href = '<?=base_url('monitoring/loom/');?>'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD');
            });
        });
        $( "#backid" ).on( "click", function() {
            window.location.href = "<?=base_url('beranda');?>";
        } );
        $( "#klikAkumulasi" ).on( "click", function() {
            $('#shiftNow').val('0');
            $('#klikAkumulasi').addClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift2').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift1" ).on( "click", function() {
            $('#shiftNow').val('1');
            $('#klikShift1').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift2').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift2" ).on( "click", function() {
            $('#shiftNow').val('2');
            $('#klikShift2').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift3').removeClass('active');
            loadData();
        } );
        $( "#klikShift3" ).on( "click", function() {
            $('#shiftNow').val('3');
            $('#klikShift3').addClass('active');
            $('#klikAkumulasi').removeClass('active');
            $('#klikShift1').removeClass('active');
            $('#klikShift2').removeClass('active');
            loadData();
        } );
        $( "#daterange" ).on( "change", function() {
            loadData();
        } );
        $(".error12").on( "click", function() {
            Swal.fire("Data tidak lengkap 3 Shift");
        } );
        function loadData(){
            var ch = $('#daterange').val();
            var sif = $('#shiftNow').val();
            $.ajax({
                url:"<?=base_url('prosesajax/showTable');?>",
                type: "POST",
                data: {"ch" : ch, "sif" : sif},
                cache: false,
                success: function(dataResult){ 
                    $('#tableBody').html(dataResult);
                }
            });
            $.ajax({
                url:"<?=base_url('prosesajax/showTableText');?>",
                type: "POST",
                data: {"ch" : ch, "sif" : sif},
                cache: false,
                success: function(dataResult){ 
                    $('#textID').html(dataResult);
                }
            });
            let timerInterval;
            Swal.fire({
            title: "Loading Data",
            html: "Please Wait ....",
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                //timer.textContent = `${Swal.getTimerLeft()}`;
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
            }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log("I was closed by the timer");
            }
            });
        }
        function infonot3shift(){
            Swal.fire({
            title: "Peringatan",
            text: "Data tidak lengkap dari 3 shift",
            icon: "warning"
            });
        }
        function notsesuai(){
            Swal.fire({
                title: "Peringatan",
                text: "Operator tidak menginput tepat waktu",
                icon: "warning"
            });
        }
    </script>
</body>
</html>