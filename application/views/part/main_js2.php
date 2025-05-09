<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>
    <?php
        $kdrol = $this->db->query("SELECT jenis_mesin FROM produksi_warping ORDER BY jenis_mesin");
        if($kdrol->num_rows() > 0){
            $ar_kdrol = array();
            foreach($kdrol->result() as $val){
                $data_nama = '"'.$val->jenis_mesin.'"';
                if(in_array($data_nama, $ar_kdrol)){} else {
                $ar_kdrol[] = '"'.$val->jenis_mesin.'"'; }
            }
            $im_kons = implode(',', $ar_kdrol);
        } else {
            $im_kons = '"Belum ada data"';
        }
    ?>
        const autoCompleteJS = new autoComplete({
            placeHolder: "Masukan Jenis Mesin",
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
                    }
                }
            }
        });
        function proses(){
            var kode_proses = document.getElementById('kode_proses').value;
            var tgl = document.getElementById('tgl_prod').value;
            if(tgl=="0000-00-00"){
                var today = new Date();
                var year = today.getFullYear();
                var month = (today.getMonth() + 1).toString().padStart(2, '0');
                var day = today.getDate().toString().padStart(2, '0');
                var tgl = year + '-' + month + '-' + day;
            } 
            $.ajax({
                url:"<?=base_url('proses/prosesProduksiWarping');?>",
                type: "POST",
                data: {"kode_proses" : kode_proses, "tgl" : tgl},
                cache: false,
                success: function(dataResult){ }
            });
        }
        function createBoxes() {
            var jumlahInput = document.getElementById('jumlah');
            var jumlahValue = parseInt(jumlahInput.value, 10);
            var meterPerBeamDiv = document.getElementById('meter_perbeam');
            meterPerBeamDiv.innerHTML = '';
            for (var i = 1; i <= jumlahValue; i++) {
                var boxKarung = document.createElement('div');
                boxKarung.className = 'box-karung';
                var spanBeam = document.createElement('span');
                spanBeam.textContent = 'Beam ' + i;
                boxKarung.appendChild(spanBeam);
                var labelInput = document.createElement('label');
                labelInput.setAttribute('for', 'idbeam' + i);
                labelInput.textContent = 'Meter per Beam';
                boxKarung.appendChild(labelInput);
                var inputBeam = document.createElement('input');
                inputBeam.setAttribute('type', 'text');
                inputBeam.setAttribute('id', 'idbeam' + i);
                inputBeam.setAttribute('placeholder', 'Masukkan ukuran');
                boxKarung.appendChild(inputBeam);
                meterPerBeamDiv.appendChild(boxKarung);
            }
        }
        proses();
        function saveDate(tgl){
            var kode_proses = document.getElementById('kode_proses').value;
            $.ajax({
                url:"<?=base_url('proses/updateTanggal');?>",
                type: "POST",
                data: {"kode_proses" : kode_proses, "tgl" : tgl},
                cache: false,
                success: function(dataResult){ }
            });
        }
        function saveMc(nm){
            var kode_proses = document.getElementById('kode_proses').value;
            $.ajax({
                url:"<?=base_url('proses/updateMesin');?>",
                type: "POST",
                data: {"kode_proses" : kode_proses, "nm" : nm},
                cache: false,
                success: function(dataResult){ }
            });
        }
        function saveCreel(nm){
            var kode_proses = document.getElementById('kode_proses').value;
            $.ajax({
                url:"<?=base_url('proses/updateCreel');?>",
                type: "POST",
                data: {"kode_proses" : kode_proses, "nm" : nm},
                cache: false,
                success: function(dataResult){ }
            });
        }
        function generateForms_2(dt){
            var kode_proses = document.getElementById('kode_proses').value;
            $.ajax({
                url:"<?=base_url('proses/updateJmlBeam2');?>",
                type: "POST",
                data: {"kode_proses" : kode_proses, "dt" : dt},
                cache: false,
                success: function(dataResult){ }
            });
        }
        function generateForms() {
            // Menghapus semua form yang sudah ada
            document.getElementById("meter_perbeam").innerHTML = "";

            // Mendapatkan nilai jumlah_beam
            var jumlahBeam = document.getElementById("jumlah").value;

            // Membuat form sesuai dengan nilai jumlah_beam
            for (var i = 0; i < jumlahBeam; i++) {
                var jo = i +1;
                // Membuat div container untuk setiap form
                var formContainer = document.createElement("div");
                formContainer.className = 'box-karung';

                // Membuat form
                var form = document.createElement("form");
                var spanBeam = document.createElement('span');
                spanBeam.textContent = 'Beam ' + jo;
                formContainer.appendChild(spanBeam);
                // Membuat input untuk nama_beam
                
                var inputNamaBeam = document.createElement("input");
                inputNamaBeam.type = "text";
                inputNamaBeam.name = "nama_beam";
                inputNamaBeam.setAttribute('id', 'kodewarp' + i);
                inputNamaBeam.placeholder = "Kode Beam Warping";
                inputNamaBeam.setAttribute("list", "suggestions" + i); // Unique list id for each input

                // Membuat datalist untuk suggest pada inputNamaBeam
                var datalist = document.createElement("datalist");
                datalist.id = "suggestions" + i; // Unique list id for each input
                var option1 = document.createElement("option");
                option1.value = "Suggest1";
                var option2 = document.createElement("option");
                option2.value = "Suggest2";
                var option3 = document.createElement("option");
                option3.value = "data2";
                // Tambahkan opsi-opsi lain sesuai kebutuhan

                datalist.appendChild(option1);
                datalist.appendChild(option2);
                datalist.appendChild(option3);

                // Membuat input untuk ukuran_beam
                var inputUkuranBeam = document.createElement("input");
                inputUkuranBeam.type = "text";
                inputUkuranBeam.name = "ukuran_beam";
                inputUkuranBeam.id = "ukuran_beam"+i+"";
                inputUkuranBeam.placeholder = "Panjang Beam Warping";

                // Menambahkan input ke dalam form
                form.appendChild(inputNamaBeam);
                form.appendChild(datalist);
                form.appendChild(inputUkuranBeam);

                // Menambahkan form ke dalam div container
                formContainer.appendChild(form);

                // Menambahkan div container ke dalam meter_perbeam
                document.getElementById("meter_perbeam").appendChild(formContainer);

                // Menambahkan fungsi autocomplete
                autocomplete(inputNamaBeam, datalist);
            }
        }

        // Fungsi autocomplete
        function autocomplete(input, datalist) {
            input.addEventListener("input", function() {
                var inputValue = this.value;
                var options = datalist.getElementsByTagName("option");
                for (var i = 0; i < options.length; i++) {
                    var optionValue = options[i].value;
                    if (optionValue.toLowerCase().indexOf(inputValue.toLowerCase()) !== -1) {
                        options[i].style.display = "";
                    } else {
                        options[i].style.display = "none";
                    }
                }
            });
        }
        for (let index = 0; index < 20; index++) {
            console.log(''+index);
            var kode_proses = document.getElementById('kode_proses').value;
            $('#kodewarp'+index+'').on('change', function() {
                console.log( 'tes'+this.value ); 
            });            
        }
        $( "#btnSimpanWarping" ).on( "click", function() {
            var kode_proses = document.getElementById('kode_proses').value;
            var tgl = document.getElementById('tgl_prod').value;
            var mc = document.getElementById('autoComplete').value;
            var creel = document.getElementById('creel').value;
            var jumlahss = document.getElementById('jumlahss').value;
            if(tgl!="" && mc!="" && creel!="" && jumlahss!=""){
                window.location.href = "<?=base_url('beam/warping/');?>"+kode_proses+"";
            } else {
                Swal.fire("Anda belum mengisi semua");
            }
        });
</script>
</body>
</html>