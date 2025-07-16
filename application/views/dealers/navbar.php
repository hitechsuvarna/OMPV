<!DOCTYPE html>
<html>
<head>
	<title>IRENE - Customers</title>
	<script src="<?php echo base_url().'assets/js/jquery.min.js'; ?>"></script>
	<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<script src="<?php echo base_url().'assets/js/material.min.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/material.min.css'; ?>">
	<link rel="stylesheet" href="<?php echo base_url().'assets/css/material_icon.css'; ?>">
	<!--<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.red-deep_orange.min.css" />-->
	<link rel="stylesheet" href="<?php echo base_url().'assets/css/material.indigo-light_green.min.css'; ?>" />

	<script src="<?php echo base_url().'assets/js/moment-with-locales.min.js'; ?>" type="text/javascript" charset="utf-8"></script>
	
	<script src="<?php echo base_url().'assets/js/jquery-ui.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo base_url().'assets/js/tag-it.js'; ?>" type="text/javascript" charset="utf-8"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery-ui.css'; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery.tagit.css'; ?>">
	

	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/material-calender.css'; ?>">
	<script src="<?php echo base_url().'assets/js/material-calender.js'; ?>" type="text/javascript" charset="utf-8"></script>
	

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<style type="text/css">
				.mdl-card {
			text-align: center;
		}

		.mdl-card {
			width: 100% !important;
		}

		.mdl-cell {
			/*border: 1px #000 solid;*/
		}

		.mdl-card__title {
			width: 100%!important;
			height: 175px;
			color: #fff;
			background-color: #404040;
		}

		.lower-button {
			right: 30px !important;
			bottom: 50px!important;
			position: fixed;
			z-index: 5;
			background-color: #404040;
			color: #fff;
			/*box-shadow: 2px 5px 10px #999999;*/
		}

		.mdl-button-upside {
			/*padding: 5px;*/
			margin-left: 10px!important;
			margin-right: 10px!important;
		}

        .mdl-layout__header {
            /*background-color:#ff0000;*/
        }
        
        .mdl-layout__drawer {
            background-color: #fff;
            color: #000;
        }
        
        .mdl-navigation__link {
            color: #000 !important;
        }
        
		#myTags {
			margin: 0px;
		}

		a:link {
		    color: #fff;
			text-decoration: none;
		}

		a:visited {
		    color: #fff;
			text-decoration: none;
		}

		a:hover {
		    color:#fff;
			text-decoration: underline;
		}

		a:active {
		    color:#fff;
			text-decoration: underline;
		}

		::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
			color: #fff;
			opacity: 1; /* Firefox */
		}

		:-ms-input-placeholder { /* Internet Explorer 10-11 */
			color: #fff;
		}

		::-ms-input-placeholder { /* Microsoft Edge */
			color: #fff;
		}
	</style>
</head>
<body id="">
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title"><?php echo $title; ?></span>
				<div class="mdl-layout-spacer"></div>
				<div class="material-icons mdl-badge mdl-badge--overlap " data-badge="0" id="disp_cart">shopping_cart</div>
			</div>
		</header>
		<div class="mdl-layout__drawer">
			<span class="mdl-layout-title" style="background-image: url('<?php echo base_url()."assets/images/pattern_blue_green.svg"; ?>'); height:300px; background-size:cover; color:#fff; ">
				<!-- <img src="<?php echo base_url().'assets/images/Logo_white.pn'; ?>" style="width: 80%;bottom: 20%;"> -->
				<div style="margin-top: 75px;">
					<a class="mdl-navigation__link" href="<?php $sess_data = $this->session->userdata(); echo base_url().$type.'/Account/index/'.$sess_data['user_details'][0]->iu_owner; ?>">
						<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" style="">
							<i class="material-icons">face</i>
						</button>
					</a>
					<div style="font-size: 1.2em;line-height: 100%;margin-bottom: 15px;"><?php echo "Hey, ".$name; ?></div>
					<div style="font-size: 0.6em;line-height: 150%;" id="quote"></div>
				</div>
			</span>
			<nav class="mdl-navigation animsition-overlay">
				<a class="mdl-navigation__link animsition-link" href="<?php  $sess_data = $this->session->userdata(); echo base_url().$type.'/Home/index/'.$sess_data['user_details'][0]->iu_owner; ?>"><i class="material-icons">home</i> Home</a>
				<hr>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Home/load_cart'; ?>"><i class="material-icons">shopping_cart</i> My Cart</a>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Home/orders_new'; ?>"><i class="material-icons">list_alt</i> My Orders</a>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Home/invoice_new'; ?>"><i class="material-icons">receipt</i> My Invoices</a>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Home/ledger_new'; ?>"><i class="material-icons">account_balance_wallet</i> My Ledger</a>
				<hr>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Account/settings'; ?>"><i class="material-icons">settings</i>Settings</a>
				<hr>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Account/company'; ?>"><i class="material-icons">star</i>About</a>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Account/terms'; ?>"><i class="material-icons">star</i>Terms & Conditions</a>
				<hr>
				<a class="mdl-navigation__link" href="<?php $sess_data = $this->session->userdata(); echo base_url().$type.'/Account/logout/'.$sess_data['user_details'][0]->iu_owner; ?>"><i class="material-icons">power_settings_new</i> Logout</a>				
			</nav>
		</div>