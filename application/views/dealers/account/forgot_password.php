<!DOCTYPE html>
<html>
<head>
	<title>IRENE - Portal Login</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<script src="<?php echo base_url().'assets/js/material.min.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/material.min.css'; ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<!--<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.brown-orange.min.css" />-->
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-light_green.min.css" />


	<script src="<?php echo base_url().'assets/js/jquery-ui.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url().'assets/js/tag-it.js'; ?>" type="text/javascript" charset="utf-8"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery-ui.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery.tagit.css'; ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<style type="text/css">

		body {
			/*background-image: url('<?php echo base_url().'assets/images/pattern.svg'; ?>');
			background-size: cover;*/
			/*background: linear-gradient(1200deg, #ff0000, #ff4d4d);*/
			/*color: #fff;*/
		}

		.mdl-card {
			text-align: center;
		}

		.mdl-card {
			width: 100% !important;
			color: #999;
			background-color: #fff;
		}

		.mdl-cell {
			/*border: 1px #000 solid;*/
		}

		.mdl-card__title {
			width: 100%!important;
			height: 175px;
			background-image: url('<?php echo base_url().'assets/images/pattern.svg'; ?>');
			background-size: cover;
		}

		.lower-button {
			right: 30px !important;
			bottom: 50px!important;
			position: fixed;
			z-index: 5;
			/*background-color: #330000;
			color: #fff;
			box-shadow: 2px 5px 10px #999999;*/
		}

		.mdl-button-upside {
			/*padding: 5px;*/
			margin-left: 10px!important;
			margin-right: 10px!important;
		}

		#myTags {
			margin: 0px;
		}

		a:link {
			text-decoration: none;
		}

		a:visited {
			text-decoration: none;
		}

		a:hover {
			text-decoration: underline;
		}

		a:active {
			text-decoration: underline;
		}

		#submit {
			/*background-color: #ff0000;*/
			/*color: #fff;*/
			width: 100%;
		}
		
		#login {
		    width: 100%;
		}

		.mdl-textfield__input, .mdl-textfield__label, .is-upgraded, .is-focused, .is-dirty, .mdl-textfield__label::after {
			/*color: #000 !important;*/
		}
		
		@media screen and (max-width: 1030px) and (min-width: 720px) {
            .ipad {
                width: 22%;
            }
        }
	</style>

</head>
<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<main class="mdl-layout__content">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col ipad"></div>
				<div class="mdl-cell mdl-cell--4-col" style="text-align: right;">
					<img src="<?php echo base_url().'assets/images/Logo_intro.png'; ?>" style="padding-left: : 10px;padding-right: 10px; width: 90%;">
					<!-- <h3>Dhristi - A distributors portal</h3> -->
				</div>
				<div class="mdl-cell mdl-cell--4-col ipad"></div>
				
			'
				<!-- GENERAL DETAILS -->
				<div class="mdl-cell mdl-cell--4-col ipad"></div>
				<div class="mdl-cell mdl-cell--4-col" style="text-align: center;">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="c_email" name="c_email" class="mdl-textfield__input" value="<?php if(isset($user_info)) { echo $user_info[0]->iud_email; } ?>">
						<label class="mdl-textfield__label" for="c_email">Email</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" id="submit">
						Reset Password
					</button>
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="login">
						Remember password? Login Here.
					</button>
				</div>
				<div class="mdl-cell mdl-cell--4-col ipad"></div>
			</div>
		</main>
	</div>

	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
		<div class="mdl-snackbar__text"></div>
		<button class="mdl-snackbar__action" type="button"></button>
	</div>
</body>

<script>
	$(document).ready(function() {
		$('#show_password').change(function(e) {
	        e.preventDefault();
	        
	        if($('#u_pass').attr('type') == "text") {
	            $('#u_pass').attr('type','password');    
	        } else {
	            $('#u_pass').attr('type','text');
	        }
	        
	    });
	    
		$('#submit').click(function(e) {
			e.preventDefault();

			var snackbarContainer = document.querySelector('#demo-snackbar-example');

		    $.post('<?php echo base_url().$type."/Account/submit_forgot_password/".$oid; ?>', {
				'email' : $('#c_email').val(),
			}, function(data, status, xhr) { 
				console.log(data); 
				if(data == 'true') { 
				    var ert = {
						message: 'Please check your Email.',timeout: 2000,
					};
					snackbarContainer.MaterialSnackbar.showSnackbar(ert);
					setTimeout(function(e) {
					    window.location = '<?php echo base_url().$type."/Account/login/".$oid; ?>';
					}, 2000);
				} else if(data == "false") { 
					var ert = {
						message: 'Please check your Email.',timeout: 2000,
					};
					snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
				} else if(data == "exists") { 
					var ert = {
						message: 'Email ID Already Exists.',timeout: 2000,
					};
					snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
				}  else {
					window.location = '<?php echo base_url().$type."/Account/reset_password/"; ?>' + data;
				}
			}, 'text');
		});
		
		$('#login').click(function(e) {
		    window.location = "<?php echo base_url().'dealers/Account/login/'.$oid; ?>";
		})
	})
</script>
</html>