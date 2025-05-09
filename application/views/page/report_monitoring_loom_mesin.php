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
        .tooltip {
            display: none;
            position: absolute;
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            z-index: 1;
            font-size:12px;
        }

        .tooltip.show {
        display: block;
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
    ?>
    <div class="full">
        <div class="back" id="backid"><img src="<?=base_url('logo/back.svg');?>" alt="Kembali"></div>
        <h1>Report Monitoring Mesin AJL</h1>
    </div>
    <div class="full nopad">
        <label for="daterange">Pilih Bulan : </label>
        <input type="month" id="daterange" name="daterange" name="" />
        <a href="javascript:void(0)" id="reportbulanan" class="btnbtn">Export Excel</a>
    </div>
    
    <p style="width:100%;padding:10px 30px;" id="textID"><?=$txt;?></p>
    <div style="width:100%;padding:0 30px;display:flex;" id="textID2">
    
    </div>
    <div class="table-responsive">
        <table id="tableBody">
            <thead>
                <tr style="background:#aeb1b5;color:#000;border:1px solid #000;">
                    <th rowspan="2">No.</th>
                    <th rowspan="2">No Mesin</th>
                    <th rowspan="2">Rata2 RPM</th>
                    <th colspan="6">Putus Benang 3 Shift</th>
                    <th rowspan="2">EFF %</th>
                    <th rowspan="2">Produksi</th>
                    <th rowspan="2">Prod Teoritis 3 Shift</th>
                    <th rowspan="2">EFF % Rill 3 Shift</th>
                    <th rowspan="2">Prod 100%</th>
                    <th colspan="2">Selisih</th>
                    <th rowspan="2">Jumlah Data</th>
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
            <tbody id="bodyTableID">
                <tr class="trbgbg">
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                    <td style="text-align:center;<?=$all_LS==0?'background:red;color:#fff;':'';?>">Loading</td>
                </tr>
                
            </tbody>
        </table>
    </div>
<div id="tooltip" class="tooltip">
  Tooltip Content
</div>

    <input type="hidden" id="tglNow" value="<?=date('Y-m-d');?>">
    <input type="hidden" id="shiftNow" value="0">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script>
        $("#daterange").change(function(){
            var daterange = $(this).val();
            var tahun = daterange.split("-")[0];
            var bulan = daterange.split("-")[1];
            var bulantahun = bulan+"-"+tahun;
            loaddata(bulan,tahun);
        });
        
        

        function loaddata(bln,thn){
            $('#bodyTableID').html('<tr class="trbgbg"><td style="color:red" colspan="17">Loading... Sedang memuat data...</td></tr>');
            $.ajax({
                    url:"<?=base_url('prosesajax/showAkumulasi');?>",
                    type: "POST",
                    data: {"bln" : bln, "thn" : thn},
                    cache: false,
                    success: function(dataResult){
                        $('#bodyTableID').html(dataResult);
                    }
            });
        }

        var today = new Date();
        var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        var bulan = ("0" + (today.getMonth() + 1)).slice(-2); // Format "01", "02", dst.
        var namaBulan = months[today.getMonth()]; // Nama bulan dalam bahasa Indonesia

        var tahun = today.getFullYear();
        var bulanSekarang = bulan + " " + namaBulan;
        loaddata(bulan,tahun);
        $("#reportbulanan").click(function(){
            var daterange = $("#daterange").val();
            var tahun = daterange.split("-")[0];
            var bulan = daterange.split("-")[1];
            window.location.href = "<?=base_url('phpsp/export/');?>"+bulan+"/"+tahun;
        });

    </script>
</body>
</html>