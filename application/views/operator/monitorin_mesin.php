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
        .modal2{display:none;position:fixed;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgb(0 0 0 / .5);z-index:1;animation:fadeIn 0.3s ease-in-out;padding:10px}.modal2-content{background-color:#FFFFFF;margin:15% auto;padding:20px 10px;border:1px solid #888;border-radius:10px;width:100%;max-width:500px;animation:slideIn 0.3s ease-in-out;display:flex;flex-direction:column;align-items:center;color:#000;}.close{color:#aaa;float:right;font-size:28px;font-weight:700}.close:hover,.close:focus{color:#000;text-decoration:none;cursor:pointer}@keyframes fadeIn{from{opacity:0}to{opacity:1}}@keyframes slideIn{from{transform:translateY(-50px);opacity:0}to{transform:translateY(0);opacity:1}}.loader{width:50px;margin-bottom:10px;backgorund:#FFFFFF;aspect-ratio:1;border-radius:50%;background:radial-gradient(farthest-side,#ffa516 94%,#0000) top/8px 8px no-repeat,conic-gradient(#0000 30%,#ffa516);-webkit-mask:radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);animation:l13 1s infinite linear}@keyframes l13{100%{transform:rotate(1turn)}}div.tables{width:100%;overflow-x:auto}.tables table{width:100%;border:1px solid #292a2b;border-collapse:collapse}.tables table tr th,.tables table tr td{padding:6px;white-space:nowrap}.tables table tr th{text-align:center;background:#5d5f61;color:#fff;border:1px solid #fff}.tables table tr td{text-align:center;border:1px solid #000}.autopop2{width:100%;min-height:50px;background:#1955b5;color:#FFF;position:absolute;top:0;left:0;z-index:9999;display:flex;text-align:center;padding:20px;position:fixed;display:none}.closePop2{width:30px;height:30px;background:#e70909;color:#FFF;border-radius:50%;display:flex;justify-content:center;align-items:center;position:absolute;top:10px;right:10px;display:none;position:fixed;z-index:99999}
        .table-container{width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border:1px solid #ddd;border-radius:8px;box-shadow:0 2px 8px rgb(0 0 0 / .05);background-color:#fff;margin:16px 0}table{width:100%;border-collapse:collapse;min-width:600px;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;font-size:14px;color:#333}thead{background-color:#f4f6f8}thead th{padding:12px 16px;text-align:left;font-weight:600;border-bottom:2px solid #ddd}tbody td{padding:12px 16px;border-bottom:1px solid #eee}tbody tr:hover{background-color:#f9f9f9;transition:background-color 0.2s ease}.table-container::-webkit-scrollbar{height:8px}.table-container::-webkit-scrollbar-track{background:#f1f1f1}.table-container::-webkit-scrollbar-thumb{background:#bbb;border-radius:4px}
        .btn-simpan{position:relative;display:inline-flex;align-items:center;justify-content:center;padding:12px 24px;background-color:#007bff;color:#fff;font-weight:600;border-radius:8px;cursor:pointer;overflow:hidden;user-select:none;transition:background-color 0.3s}.btn-simpan:hover{background-color:#0069d9}.btn-simpan::after{content:'';position:absolute;border-radius:50%;transform:scale(0);background:rgb(255 255 255 / .6);animation:ripple 0.6s linear;pointer-events:none;display:none}@keyframes ripple{to{transform:scale(4);opacity:0}}.spinner{width:20px;height:20px;border:2px solid #fff;border-top:2px solid #fff0;border-radius:50%;animation:spin 1s linear infinite;display:none}@keyframes spin{to{transform:rotate(360deg)}}.btn-simpan.loading .btn-text{display:none}.btn-simpan.loading .spinner{display:inline-block}
    </style>
</head>
<body>
    <?php 
    $nmuser = $this->session->userdata('nama');
    $akses = $this->session->userdata('akses');
    ?>
    <div style="width:100%;display:flex;justify-content:space-between;align-items:center;"> 
        <h1 style="font-size:1.2em;margin:10px;color:#1561e6;z-index: 9999;">Monitoring Mesin AJL</h1>
        
        <input id="checkbox" type="checkbox">
        <label class="toggle" for="checkbox">
            <div id="bar1" class="bars"></div>
            <div id="bar2" class="bars"></div>
            <div id="bar3" class="bars"></div>
        </label>
    </div>
    <div class="mobile-menu2">
        <a href="<?=base_url('mesin');?>">
            <div class="menu-btn type1">Monitoring Mesin</div>
        </a>
        <a href="<?=base_url('produksi-mesin');?>">
            <div class="menu-btn type3">Produksi Mesin</div>
        </a>
        <a href="<?=base_url('operator-loom');?>">
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
    <div class="container" style="margin-top:10px;">
    <div class="fl">
        <label for="autoComplete">Cari</label>
        <div class="autoComplete_wrapper">
            <input id="autoComplete" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" name="beamwarping">
            
        </div>
    </div>
    <div class="box-mesin2" id="isi_mesin">
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
    <!-- <div class="popupfull">
        <div class="modals">
            <div class="modalsheader">
                <h1 id="jdlmdl">Judul Modal</h1>
                <span onclick="hidemodal()">x</span>
            </div>
            <div class="modalsbody">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Labore aliquid a et. Non unde nisi rerum nostrum illo, reprehenderit assumenda inventore recusandae hic odit odio, rem voluptatem natus iure ea!
            </div>
            
        </div>
    </div> -->
    <div class="closePop2" id="klikMerah" onclick="closeModal()">x</div>
    <div class="autopop2" id="modalBiru">
    <div id="myModal" class="modal2">
      <!-- Modal content -->
      <div class="modal2-content" id="isiModals">
          <div class="loader"></div>
          Please Wait...
      </div>
    </div>
    <div style="width:100%;height:100px;">&nbsp;</div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM table_mesin");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                if(in_array($val->no_mesin, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->no_mesin.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
    <script>
        var modal = document.getElementById("myModal");
        var modalBiru = document.getElementById("modalBiru");
        var klikMerah = document.getElementById("klikMerah");
        var btn = document.getElementById("openModalBtn");
        var span = document.getElementsByClassName("close")[0];
        //let cacheData = null;
        
        <?php if($nmuser=="anding" OR $nmuser=="admin" OR $nmuser=="septi" OR $nmuser=="septi diah" OR $nmuser=="Arrum" OR $nmuser=="adish"){ ?>
        
            function showmodals(msn,id,idasli) { 
                console.log('open modal 1 {'+msn+'} - {'+id+'} {'+idasli+'}');
                $('#isiModals').html('<div class="loader"></div>Waiting Data From '+msn);
                modal.style.display = "block"; 
                modalBiru.style.display = "block"; 
                klikMerah.style.display = "flex"; 
                $.ajax({
                    url:"<?=base_url('proses/updateKonstruksi');?>",
                    type: "POST",
                    data: {"id":idasli,"msn":msn, "idsha":id},
                    cache: false,
                    success: function(dataResult){
                        setTimeout(() => {
                            $('#isiModals').html(dataResult);
                        }, 900);
                        
                    }
                });
            }
        
        <?php } else { ?>
        function showmodals(msn,id,idasli){
            document.location.href="<?=base_url('operator/mesin/');?>"+id;
        }
        <?php } ?>
        function hidemodal(){
            $('.popupfull').removeClass('active');
            $('.modals').removeClass('active');
        }
      const autoCompleteJS = new autoComplete({
            placeHolder: "Cari Nomor Mesin",
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
                        loaddatamesin(selection);
                    }
                }
            }
        });
        $( "#returnToHome" ).on( "click", function() {
            document.location.href = "<?=base_url('operator/mesin');?>";
        } );
        $( "#autoComplete" ).on( "change", function() {
            var kode = $( "#autoComplete" ).val();
            if(kode == ""){
                loaddatamesin('null');
            } else {
            loaddatamesin(kode); }
        } );
        function loaddatamesin(id){
            $.ajax({
                url:"<?=base_url('prosesajax/showmesin');?>",
                type: "POST",
                data: {"kode":id},
                cache: false,
                success: function(dataResult){
                    $('#isi_mesin').html(dataResult);
                }
            });
        }
        loaddatamesin('null');
        function noakses(txt){
            Swal.fire({
                title: "Akses diblokir",
                text: "Anda tidak memiliki akses ke "+txt+"",
                icon: "error"
            });
        }
        $('#checkbox').on('change', function() {
            if (this.checked) {
                $('.mobile-menu2').addClass('active');
            } else {
                $('.mobile-menu2').removeClass('active');
            }
        });
        function closeModal() {
            modal.style.display = "none";
            modalBiru.style.display = "none"; 
            klikMerah.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                modalBiru.style.display = "none"; 
                klikMerah.style.display = "none";
            }
        }
        function handleClick(button) {
        // Ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            const rect = button.getBoundingClientRect();
            ripple.style.left = `${event.clientX - rect.left}px`;
            ripple.style.top = `${event.clientY - rect.top}px`;
            button.appendChild(ripple);

            // Activate ripple effect
            button.style.setProperty('--ripple-x', ripple.style.left);
            button.style.setProperty('--ripple-y', ripple.style.top);
            button.querySelector('::after')?.remove(); // optional

            // Show ripple animation
            button.style.setProperty('--ripple', '1');
            button.classList.add('ripple-active');
            button.style.setProperty('--ripple-time', '0.4s');

            // Remove ripple after animation
            setTimeout(() => {
                ripple.remove();
                // Show loading spinner
                button.classList.add('loading');
            }, 400);
            var idAsli = document.getElementById('idReal').value;
            var kons = document.getElementById('konstruksiID').value;
            var pick = document.getElementById('pickID').value;
            $.ajax({
                url:"<?=base_url('proses/saveUpdatePick');?>",
                type: "POST",
                data: {"idAsli":idAsli,"kons":kons,"pick":pick},
                cache: false,
                success: function(dataResult){
                    setTimeout(() => {
                        resetToSimpan(document.querySelector('.btn-simpan'));
                    }, 900);
                }
            });
        }

        // Fungsi untuk reset ke "Simpan"
        function resetToSimpan(button) {
            button.classList.remove('loading');
        }
        //resetToSimpan(document.querySelector('.btn-simpan'));
    </script>
</body>
</html>