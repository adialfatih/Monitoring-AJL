<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT * FROM v_beam_warping2 WHERE status_beam='masih'");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $ex = explode('-',$val->tgl_produksi);
                $tgl = $ex[2]."/".$ex[1]."/".$ex[0];
                $kode_beam = $tgl." - ".$val->kode_beam." - ".$val->id_produksi_warping;
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
            placeHolder: "Masukan Kode Beam",
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
                        var kode2 = $('#kode_proses').val();
                        var okaid = $('#okaid').val();
                        var konsid = $('#konsid').val();
                        var tgl_prod = $('#tgl_prod').val();
                        if(tgl_prod!=""){
                        $.ajax({
                            url:"<?=base_url('prosesajax/changeBeamWarping');?>",
                            type: "POST",
                            data: {"kode":selection, "kode2":kode2},
                            cache: false,
                            success: function(dataResult){
                                var dataResult = JSON.parse(dataResult);
                                if(dataResult.statusCode==200){ 
                                    showBeamWarping();
                                    $('#autoComplete').val('');
                                } else {
                                    Swal.fire({
                                        title: "Erorr",
                                        text: ""+dataResult.psn+"",
                                        icon: "error"
                                    });
                                    $('#autoComplete').val('');
                                }
                            }
                        });
                        } else {
                            Swal.fire({
                                title: "Info",
                                text: "Mohon di isi tanggal dahulu",
                                icon: "info"
                            });
                            $('#autoComplete').val('');
                        }
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
        function delbeam(id,siz){
            //alert(''+id);
            $.ajax({
                url:"<?=base_url('prosesajax/delBeamWarpingOnSizing');?>",
                type: "POST",
                data: {"kode":id, "siz":siz},
                cache: false,
                success: function(dataResult){
                    showBeamWarping();
                }
            });
        }
        function delop(id){
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
                        url:"<?=base_url('prosesajax/deloper');?>",
                        type: "POST",
                        data: {"id":id},
                        cache: false,
                        success: function(dataResult){
                            location.reload();
                        }
                    });
                    
                }
            });
        }
        function updlop(id,pas,nm,us){
            document.getElementById('idop').value = ''+id;
            document.getElementById('oppas').value = ''+pas;
            document.getElementById('opnm').value = ''+nm;
            document.getElementById('psnm').value = ''+us;
        }
        function chngsue(id,sisa,kode,kodebeam){
            document.getElementById('kdbeam23').value = ''+kodebeam;
            document.getElementById('kodesiz').value = ''+kode;
            document.getElementById('sisa2').value = ''+sisa;
            document.getElementById('idbeamwar988').value = ''+id;
        }
        function simpansisa(){
            var idbeamwar = document.getElementById('idbeamwar988').value;
            var kodebeam = document.getElementById('kdbeam23').value;
            var kodepros = document.getElementById('kodesiz').value;
            var sisa = document.getElementById('sisa2').value;
            if(idbeamwar!="" && kodebeam!="" && kodepros!="" && sisa!=""){
                $.ajax({
                        url:"<?=base_url('proses/updatesisa');?>",
                        type: "POST",
                        data: {"idbeamwar":idbeamwar,"kodebeam":kodebeam,"kodepros":kodepros,"sisa":sisa},
                        cache: false,
                        success: function(dataResult){
                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode==200){ 
                                showBeamWarping();
                                Swal.fire(''+dataResult.psn+'');
                            } else {
                                Swal.fire(''+dataResult.psn+'');
                            }
                            
                        }
                });
            } else {
                Swal.fire('Klik Kode beam dan Isi data dengan benar');
            }
        }
        
</script>
</body>
</html>