<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RJS Weaving</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('assets/');?>style.css?v=2">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
</head>
<body>
    <div class="box">
        <div class="box-login">
            <div class="logo">
                <img src="<?=base_url('assets/');?>logo_rjs2.jpg" alt="Logo RJS">
            </div>
            <h1>RJS Weaving Login</h1>
            <span>PT. Rindang Jati Spinning</span>
            <div class="box-input mt-30">
                <label for="username">Username</label>
                <input type="text" placeholder="Masukan username anda..." id="user">
            </div>
            <div class="box-input mt-10">
                <label for="username">Password</label>
                <input type="password" placeholder="Masukan password anda..." id="pass">
            </div>
            <div class="notes">
                <small id="notes_id"></small>
            </div>
			<div class="box-center mt-20">
				<div id="data"></div>
                <button class="Btn">
                    <div class="sign"><svg viewBox="0 0 512 512"><path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"></path></svg></div>
                    <div class="text">Login</div>
                  </button>
            </div>
        </div>
    </div>
    

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			$(".Btn").click(function(){
				$('#data').html('<img src="<?=base_url('assets/oval.svg');?>">');
				var username = $('#user').val();
				var pass = $('#pass').val();
				if(username!="" && pass!=""){
					$.ajax({
                    url:"<?=base_url('proses/login');?>",
                    type: "POST",
					data: {"username" : username, "pass" : pass},
							cache: false,
							success: function(dataResult){
								var dataResult = JSON.parse(dataResult);
								if(dataResult.statusCode==200){
									if(dataResult.fix == "fix"){
										sessionStorage.setItem("userName", username);
										sessionStorage.setItem("userPass", pass);
										$('#notes_id').html('');
										$('#data').html('');
										Swal.fire({
											position: "top-end",
											icon: "success",
											title: "Login Berhasil",
											showConfirmButton: false,
											timer: 1500
										});
										setTimeout(() => {
											window.location.href = "<?=base_url('beranda');?>";
										}, "1500");
									} else {
										$('#notes_id').html(''+dataResult.psn+'');
										$('#data').html('');
										Swal.fire({
											position: "top-end",
											icon: "error",
											title: "Username dan Password tidak sesuai",
											showConfirmButton: false,
											timer: 1500
										});
									}
								} else {
									$('#notes_id').html('Anda tidak mengisi semua data');
								}
							}
					});
					
				} else {
					Swal.fire({
						title: 'Error!',
						text: 'Login Harus Mengisi Username & Password',
						icon: 'error',
						confirmButtonText: 'Oke'
					});
					$('#data').html('');
				}
			}); 
		});
	</script>
</body>
</html>