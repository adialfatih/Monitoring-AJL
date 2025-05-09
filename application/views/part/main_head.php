<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title;?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .trtrbgoke {
            background:#f0f6fc;
        }
        .trtrbgoke:hover{
            background:#d3e4f5;
        }
    </style>
</head>
<body>
    <div class="headers">
        <div class="logoheader">
            <img src="<?=base_url();?>assets/logo_rjs2.jpg" alt="Logo RJS">
        </div>
        <h1>Dashboard Aplikasi</h1>
    </div>
    <div class="mainkonten">
        <div class="menu-konten">
            <?php 
            $mm = date('m');
            if($sess_user == "septi" OR $sess_user == "admin" OR $sess_user == "adel"){ ?>
            <nav>
                <ul>
                    <a href="<?=base_url('beranda');?>"><li <?=$urli=="beranda"? 'class="active"':'';?>>Home</li></a>
                    <a href="<?=base_url('data-pembelian-benang');?>"><li <?=$urli=="benang"? 'class="active"':'';?>>Pembelian Benang</li></a>
                    <a href="<?=base_url('data-bahan-baku');?>"><li <?=$urli=="baku"? 'class="active"':'';?>>Bahan Baku</li></a>
                    <a href="<?=base_url('stok-benang');?>"><li <?=$urli=="stokbenang"? 'class="active"':'';?>>Stok Benang</li></a>
                    <a href="<?=base_url('data-produksi-warping');?>"><li <?=$urli=="warping"? 'class="active"':'';?>>Produksi Warping</li></a>
                    <a href="<?=base_url('data-beam-warping');?>"><li <?=$urli=="beamwarping"? 'class="active"':'';?>>Beam Warping</li></a>
                    <a href="<?=base_url('data-produksi-sizing');?>"><li <?=$urli=="sizing"? 'class="active"':'';?>>Produksi Sizing</li></a>
                    <a href="<?=base_url('data-beam-sizing');?>"><li <?=$urli=="beamsizing"? 'class="active"':'';?>>Beam Sizing</li></a>
                    <a href="<?=base_url('data-produksi-ajl');?>"><li <?=$urli=="ajl"? 'class="active"':'';?>>Produksi AJL</li></a>
                    
                    <a href="<?=base_url('monitoring/loom');?>"><li <?=$urli=="loom"? 'class="active"':'';?>>Monitoring Loom</li></a>
                    <?php if($sess_user=="admin"){ ?>
                    <a href="<?=base_url('data-operator');?>"><li <?=$urli=="opr"? 'class="active"':'';?>>Operator Produksi</li></a><?php } ?>
                    <a href="<?=base_url('login');?>"><li>Logout</li></a>
                </ul>
            </nav>
            <?php } else { ?>
            <nav>
                <ul>
                    <a href="<?=base_url('beranda');?>"><li <?=$urli=="beranda"? 'class="active"':'';?>>Home</li></a>
                    
                    <a href="<?=base_url('warping-pershift/'.$mm);?>"><li <?=$urli=="warping"? 'class="active"':'';?>>Produksi Warping</li></a>
                    
                    <a href="<?=base_url('sizing-pershift/'.$mm);?>"><li <?=$urli=="sizing"? 'class="active"':'';?>>Produksi Sizing</li></a>
                    
                    <a href="<?=base_url('data-operator');?>"><li <?=$urli=="opr"? 'class="active"':'';?>>Operator Produksi</li></a>
                    <a href="<?=base_url('monitoring/loom');?>"><li <?=$urli=="loom"? 'class="active"':'';?>>Monitoring Loom</li></a>
                    <a href="<?=base_url('login');?>"><li>Logout</li></a>
                </ul>
            </nav>
            <?php } ?>
            
        </div>
        <div class="data-konten">