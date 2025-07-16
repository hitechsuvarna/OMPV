<!DOCTYPE html>
<html>
<head>
	<title>IRENE - Customers</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<script src="<?php echo base_url().'assets/js/material.min.js'; ?>" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/material.min.css'; ?>">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.red-deep_orange.min.css" />

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
            background-color:#ff0000;
        }
        
        .mdl-layout-title {
        	color: #fff !important;
        }
        
        .mdl-layout__drawer {
            background-color: #fff;
            color: #999;
        }
        
        .mdl-navigation__link {
            color: #999 !important;
        }
        
		#myTags {
			margin: 0px;
		}

		a:link {
		    color: #999;
			text-decoration: none;
		}

		a:visited {
		    color: #999;
			text-decoration: none;
		}

		a:hover {
		    color:#999;
			text-decoration: none;
		}

		a:active {
		    color:#999;
			text-decoration: none;
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
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right">
					<?php if($search=="true") {
						echo '<label class="mdl-button mdl-js-button mdl-button--icon" for="fixed-header-drawer-exp"><i class="material-icons">search</i></label>'; 
						echo '<div class="mdl-textfield__expandable-holder">
						<input class="mdl-textfield__input" type="text" name="sample" id="fixed-header-drawer-exp" placeholder="'.$search_placeholder.'" style="color:;">
					</div>';
						}?>

					
				</div>
			</div>
		</header>
		<div class="mdl-layout__drawer">
			<span class="mdl-layout-title" style="background-image: url('<?php echo base_url()."assets/images/pattern_nav.svg"; ?>'); height:300px; ">
				<!-- <img src="<?php echo base_url().'assets/images/Logo_white.pn'; ?>" style="width: 80%;bottom: 20%;"> -->
				<div style="margin-top: 75px;">
					<a class="mdl-navigation__link" href="<?php #echo base_url().$type.'/Account/account'; ?>">
						<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" style="">
							<i class="material-icons">face</i>
						</button>
					</a>
					<div style="font-size: 1.2em;line-height: 100%;margin-bottom: 15px;"><?php echo "Hey, ".$name; ?></div>
					<div style="font-size: 0.6em;line-height: 150%;" id="quote"></div>
				</div>
			</span>
			<nav class="mdl-navigation animsition-overlay">
				<!-- <a class="mdl-navigation__link animsition-link" href="<?php echo base_url().$type.'/Home'; ?>"><i class="material-icons">dashboard</i> Home</a>
				 --><hr>
				
			</nav>
		</div>