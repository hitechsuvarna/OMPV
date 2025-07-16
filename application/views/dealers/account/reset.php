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
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.red-deep_orange.min.css" />


	<script src="<?php echo base_url().'assets/js/jquery-ui.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url().'assets/js/tag-it.js'; ?>" type="text/javascript" charset="utf-8"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery-ui.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery.tagit.css'; ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<style type="text/css">

		body {
			/*background-image: url('<?php echo base_url().'assets/images/pattern_reset.svg'; ?>');*/
			/*background-color: #e91818;*/
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
			/*background-image: url('<?php echo base_url().'assets/images/pattern.svg'; ?>');*/
			background-color: #ff0000;
			color: #fff;
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
			background-color: #ff0000;
			color: #fff;
			width: 100%;
		}

		.mdl-textfield__input, .mdl-textfield__label, .is-upgraded, .is-focused, .is-dirty, .mdl-textfield__label::after {
			color: #000 !important;
		}
		
		
	</style>

</head>
<body>
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<main class="mdl-layout__content">
			<div class="mdl-grid">
				<div class="mdl-cell mdl-cell--4-col"></div>
				<div class="mdl-cell mdl-cell--4-col" style="text-align: right;">
					<img src="<?php echo base_url().'assets/images/Logo_ompv.png'; ?>" style="width: 100%;">
				</div>
				<div class="mdl-cell mdl-cell--4-col"></div>
				
			'
				<!-- GENERAL DETAILS -->
				<div class="mdl-cell mdl-cell--4-col"></div>
				<div class="mdl-cell mdl-cell--4-col">
					<div class="mdl-card mdl-shadow--4dp">
						<div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Reset Password</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" id="u_pass" name="u_pass" class="mdl-textfield__input">
								<label class="mdl-textfield__label" for="u_pass">Password</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
								<input type="password" id="u_confirm_pass" name="u_confirm_pass" class="mdl-textfield__input">
								<label class="mdl-textfield__label" for="u_confirm_pass">Confirm Password</label>
							</div>
							<button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" id="submit">Update</button>
						</div>
					</div>
				</div>
				<div class="mdl-cell mdl-cell--4-col"></div>
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
		$('#submit').click(function(e) {
			e.preventDefault();

			var upass = $('#u_pass').val();
			var ucpass = $('#u_confirm_pass').val();

			if (upass==ucpass) {
				    $.post('<?php if(isset($sub)) { echo base_url().$type."/Account/reset_update/".$oid."/".$uid."/".$sub; } else { echo base_url().$type."/Account/reset_update/".$oid."/".$uid; } ?>',{
				        'upass' : upass
				    }, function(data, status, xhr) {
				        console.log(data);
				        if(data == 'true') { window.location = '<?php echo base_url().$type."/Account/index/".$oid; ?>'  } 
				    },
				    'text');
			} else {
				var snackbarContainer = document.querySelector('#demo-snackbar-example');
				var ert = { message: 'Both Passwords must match.',timeout: 2000,}; snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			
			}


		});
	})
</script>
</html>