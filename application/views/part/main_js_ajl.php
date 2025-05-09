<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM v_beam_sizing2 WHERE kode_proses_ajl='null'");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $ex = explode('-',$val->tgl_produksi);
                $tgl = $ex[2]."/".$ex[1]."/".$ex[0];
                $kode_beam = $tgl." - ".$val->kode_beam." - ".$val->konstruksi;
                $data_nama = ''.$kode_beam.'';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$data_nama.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Beam Sizing",
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
                            url:"<?=base_url('prosesajax/changeBeamSizing');?>",
                            type: "POST",
                            data: {"kode":selection},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){
                                    $('#id_beam_sizing').val(''+dataResult.psn);
                                    $('#konsid').val(''+dataResult.kons);
                                    $('#lusiid2').val(''+dataResult.pjgLusi);
                                } else {
                                    Swal.fire({
                                        title: "Info",
                                        text: "Kode Beam Sizing Tidak Ditemukan"+dataResult.psn,
                                        icon: "info"
                                    });
                                    $('#id_beam_sizing').val('0');
                                    $('#autoComplete').val('');
                                }
                            }
                        });
                    }
                }
            }
        });
        function showBeamWarping(){
            var kode = $('#kode_proses').val();
            $.ajax({
                url:"<?=base_url('prosesajax/showBeamWarpingOnSizing');?>",
                type: "POST",
                data: {"kode":kode},
                cache: false,
                success: function(dataResult){
                    $('#tesIdWarping').html(dataResult);
                }
            });
        }
        showBeamWarping();
        function delbeam(id){
            //alert(''+id);
            $.ajax({
                url:"<?=base_url('prosesajax/delBeamWarpingOnSizing');?>",
                type: "POST",
                data: {"kode":id},
                cache: false,
                success: function(dataResult){
                    showBeamWarping();
                }
            });
        }
        $( "#mcid" ).on( "change", function() {
            var txt = $('#mcid').val();
            $.ajax({
                url:"<?=base_url('prosesajax/ceknomormesin');?>",
                type: "POST",
                data: {"kode":txt},
                cache: false,
                success: function(dataResult){
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){ } else {
                        Swal.fire({
                            title: ""+txt+"",
                            text: "Mesin Sedang Dalam Proses Produksi",
                            icon: "warning"
                        });
                    }
                }
            });
            
        } );
        document.addEventListener('DOMContentLoaded', function () {
            // Temukan semua elemen dengan class "dataClick"
            var dataClickElements = document.querySelectorAll('.dataClick');

            // Loop melalui setiap elemen dan tambahkan event listener untuk menangani klik
            dataClickElements.forEach(function (element) {
                element.addEventListener('click', function () {
                // Dapatkan id dari elemen yang diklik
                var id = element.id.split('-')[1];

                // Buat URL dengan id dan arahkan ke halaman yang diinginkan
                var url = '<?=base_url('data/produksi/mesin/');?>' + id;
                window.location.href = url;
                });
            });
        });
        function showPotonganKain(){
            var kode = $('#id_prod_mesin').val();
            $.ajax({
                url:"<?=base_url('prosesajax/cekpotongan');?>",
                type: "POST",
                data: {"kode":kode},
                cache: false,
                success: function(dataResult){
                    $('#bodytablePotongan').html(dataResult);
                }
            });
        }
        showPotonganKain();
        
        $( "#potongKain" ).on( "click", function() {
            var beams = $('#id_beams').val();
            var id_beam_sizing = $('#id_beam_sizing').val();
            var kons = $('#id_kons').val();
            var tgl = $('#id_tglptg').val();
            var sift = $('#id_shift').val();
            var ukr = $('#id_ukr').val();
            var opt = $('#id_opt').val();
            var id = $('#id_prod_mesin').val();
            if(id!="" && kons!="" && tgl!="" && sift!="" && ukr!="" && opt!="" && beams!=""){
                $.ajax({
                    url:"<?=base_url('prosesajax/addpotongan');?>",
                    type: "POST",
                    data: {"id":id, "kons":kons, "tgl":tgl, "sift":sift, "ukr":ukr, "opt":opt, "beams":beams, "id_beam_sizing":id_beam_sizing},
                    cache: false,
                    success: function(dataResult){
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){ 
                            showPotonganKain();
                            Swal.fire({
                                title: "Berhasil Menyimpan",
                                text: "Menyimpan potongan Kain pada masin",
                                icon: "success"
                            });
                            $('#id_shift').val('');
                            $('#id_ukr').val('');
                            $('#id_ukr').focus();
                            $('#id_opt').val('');
                        } else {
                            Swal.fire({
                                title: "Gagal Menyimpan",
                                text: "Proses Simpan Potongan Kain Gagal",
                                icon: "warning"
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: "Gagal",
                    text: "Isi semua data dengan benar!!",
                    icon: "error"
                });
            }
        } );
        function delpot(id){
            Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"<?=base_url('prosesajax/potongandel');?>",
                    type: "POST",
                    data: {"id":id},
                    cache: false,
                    success: function(dataResult){
                        // var dataResult = JSON.parse(dataResult);
                        // if(dataResult.statusCode==200){ } else {
                            Swal.fire({
                                title: "Delete",
                                text: "Potongan Kain Telah Di Hapus dari Sistem",
                                icon: "success"
                            });
                            showPotonganKain();
                        //}
                    }
                });
            }
            });
        }
        $( "#setCones" ).on( "change", function() {
                var dt = $('#setCones').val();
                if(dt == "new"){
                    $('#conesBaruBekas1').hide();
                    $('#conesBaruField1').show();
                    $('#conesBaruField2').show();
                } else {
                    if(dt == "old"){
                        $('#conesBaruBekas1').show();
                        $('#conesBaruField1').hide();
                        $('#conesBaruField2').hide();
                    } else {
                        $('#conesBaruBekas1').hide();
                        $('#conesBaruField1').hide();
                        $('#conesBaruField2').hide();
                    }
                }
        } );
        
        $( "#iptPakanSaved" ).on( "click", function() {
                var idprod = $('#id_prod_mesin').val();
                var setCones = $('#setCones').val();
                var jmlNewCones = $('#jmlNewCones').val();
                var brtCones = $('#brtCones').val();
                if(setCones == ""){
                    Swal.fire({
                        title: "Gagal Menyimpan",
                        text: "Anda belum memilih jenis cones yang digunakan",
                        icon: "warning"
                    });
                } else {
                    if(setCones == "new"){
                        var codeAll = $('#cdCones21new').val();
                    }
                    if(setCones == "old"){
                        var codeAll = $('#cdCones29old').val();
                    }
                    $.ajax({
                        url:"<?=base_url('prosesajax/savedBakuAjl');?>",
                        type: "POST",
                        data: {"idprod":idprod, "codeAll":codeAll, "setCones":setCones, "jmlNewCones":jmlNewCones, "brtCones":brtCones},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){
                                showbakuajl();
                            } else {
                                Swal.fire({
                                    title: "Gagal Menyimpan",
                                    text: ""+dataResult.fix+"",
                                    icon: "warning"
                                });
                            }
                        }
                    });
                }
        } );
        function showbakuajl(){
            var kode = $('#id_prod_mesin').val();
            $.ajax({
                url:"<?=base_url('prosesajax/cekBakuAjl');?>",
                type: "POST",
                data: {"kode":kode},
                cache: false,
                success: function(dataResult){
                    $('#bodytableBakuAjl').html(dataResult);
                }
            });
        }
        showbakuajl();
        function delbakupakan(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"<?=base_url('prosesajax/hapusBakuAjl');?>",
                        type: "POST",
                        data: {"id":id},
                        cache: false,
                        success: function(dataResult){
                            Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                            });
                            showbakuajl();
                        }
                    });
                }
            });
        }
        function tanyakan(id){
            Swal.fire({
                title: "Turunkan Beam?",
                text: "Anda akan mengakhiri proses produksi pada mesin ini",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Batal"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"<?=base_url('proses/turunkanbeam');?>",
                        type: "POST",
                        data: {"id":id},
                        cache: false,
                        success: function(dataResult){
                            Swal.fire({
                            title: "Berhasil Menyimpan",
                            text: "Proses Produksi Berhasil Disimpan",
                            icon: "success"
                            });
                            showbakuajl();
                        }
                    });
                }
            });
        }
</script>
</body>
</html>