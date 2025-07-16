<!DOCTYPE html>
<html>
<head>
	<title>Dhristi-B2B Ecommerce | OMPV </title>
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
	<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
	

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
        
        .mdl-layout-title {
        	color: #fff !important;
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
<script>
    $(document).ready(function() {
        $.post('<?php echo base_url().$type."/Home/new_order_notify"; ?>', {}, function(d,s,x) { if(d=="0") { $('#plain--menu').css('display','inline-block'); $('#order--menu').css('display','none'); } else { $('#plain--menu').css('display','none'); $('#order--menu').css('display','inline-block'); $('#order--menu').attr("data-badge",d); } }, "text");
        $.post('<?php echo base_url().$type."/Home/new_dealer_notify"; ?>', {}, function(d,s,x) { if(d=="0") { $('#plain--dealer').css('display','inline-block'); $('#dealer--menu').css('display','none'); } else { $('#plain--dealer').css('display','none'); $('#dealer--menu').css('display','inline-block'); $('#dealer--menu').attr("data-badge",d); } }, "text");
        
    })
</script>
<body id="">
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title"><?php echo $title; ?></span>
				<div class="mdl-layout-spacer"></div>
				<?php if($search=="true") {
					echo '<div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right">';
					echo '<label class="mdl-button mdl-js-button mdl-button--icon" for="fixed-header-drawer-exp"><i class="material-icons">search</i></label>'; 
					echo '<div class="mdl-textfield__expandable-holder">
					<input class="mdl-textfield__input" type="text" name="sample" id="fixed-header-drawer-exp" placeholder="'.$search_placeholder.'" style="color:;">
						</div>';
					echo '</div>';
					} else if ($search=="no_text") {
						echo '<button class="mdl-button mdl-js-button mdl-button--icon" id="fixed_header_search"><i class="material-icons">search</i></button>';
					}?>
			</div>
		</header>
		<div class="mdl-layout__drawer">
			<span class="mdl-layout-title" style="background-image: url('<?php echo base_url()."assets/images/pattern_blue_green.svg"; ?>'); height:300px;background-size:cover; ">
				<!-- <img src="<?php echo base_url().'assets/images/Logo_white.pn'; ?>" style="width: 80%;bottom: 20%;"> -->
				<div style="margin-top: 75px;">
					<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Account/account'; ?>">
						<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" style="">
							<i class="material-icons">face</i>
						</button>
					</a>
					<div style="font-size: 1.2em;line-height: 100%;margin-bottom: 15px;"><?php echo "Hey, ".$name; ?></div>
					<div style="font-size: 0.6em;line-height: 150%;" id="quote"></div>
				</div>
			</span>
			<nav class="mdl-navigation animsition-overlay">
				<a class="mdl-navigation__link animsition-link" href="<?php echo base_url().$type.'/Home'; ?>"><i class="material-icons">dashboard</i> Home</a>
				<hr>
				<?php 
					$sess_data = $this->session->userdata();
					
					if ($sess_data['user_details'][0]->iua_u_products == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Products'.'"><i class="material-icons">label_outline</i>Products</a>';

					if ($sess_data['user_details'][0]->iua_u_pricing == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Products/product_pricing'.'"><i class="material-icons">label_outline</i>Product Pricing</a>';

					if ($sess_data['user_details'][0]->iua_u_dealers == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Dealers'.'"><i class="material-icons" id="plain--dealer">label_outline</i><span class="mdl-badge" id="dealer--menu" data-badge=""></span>Dealers</a>';
					if ($sess_data['user_details'][0]->iua_u_vendors == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Vendors'.'"><i class="material-icons">label_outline</i>Vendors</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_orders == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/orders'.'"><i class="material-icons" id="plain--menu">label_outline</i><span class="mdl-badge" id="order--menu" data-badge=""></span>Orders</a>';
					if ($sess_data['user_details'][0]->iua_u_delivery == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/delivery'.'"><i class="material-icons">label_outline</i>Delivery</a>';
                    if ($sess_data['user_details'][0]->iua_u_inventory == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/inventory_new'.'"><i class="material-icons">label_outline</i>Inventory</a>';
                    // if ($sess_data['user_details'][0]->iua_u_godown == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/godowns'.'"><i class="material-icons">label_outline</i>Godowns</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_purchase == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/purchase'.'"><i class="material-icons">label_outline</i>Purchase</a>';
                    if ($sess_data['user_details'][0]->iua_u_invoice == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/invoice'.'"><i class="material-icons">label_outline</i>Invoice</a>';
                    if ($sess_data['user_details'][0]->iua_u_credit_note == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/cd_note'.'"><i class="material-icons">label_outline</i>Credit & Debit Note</a><hr>';
					
					if ($sess_data['user_details'][0]->iua_u_ledgers == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Account_Master/view_account_ledgers'.'"><i class="material-icons">label_outline</i>Account Ledgers</a>';
					if ($sess_data['user_details'][0]->iua_u_ledgers == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Account_Master'.'"><i class="material-icons">label_outline</i>Account Masters</a><hr>';
					
					#if ($sess_data['user_details'][0]->iua_u_expenses == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/expenses'.'"><i class="material-icons">label_outline</i>Expenses</a>';
					#if ($sess_data['user_details'][0]->iua_bank_accounts == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/bank_accounts'.'"><i class="material-icons">label_outline</i>Bank Accounts</a>';
					#if ($sess_data['user_details'][0]->iua_u_payments == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/payments'.'"><i class="material-icons">label_outline</i>Payments</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_analyze == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Transactions/analyze'.'"><i class="material-icons">label_outline</i>Analyze</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_tax == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Products/tax'.'"><i class="material-icons">label_outline</i>Taxes</a><a class="mdl-navigation__link" href="'.base_url().$type.'/Products/tax_group'.'"><i class="material-icons">label_outline</i>Tax Groups</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_users == "true") echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Account/users'.'"><i class="material-icons">label_outline</i>User and Access</a><hr>';
					if ($sess_data['user_details'][0]->iua_u_settings == "true")  echo '<a class="mdl-navigation__link" href="'.base_url().$type.'/Account/settings'.'"><i class="material-icons">settings</i>Settings</a>';
				?>
				<hr>
				<a class="mdl-navigation__link" href="<?php echo base_url().$type.'/Account/logout'; ?>"><i class="material-icons">power_settings_new</i> Logout</a>				
			</nav>
		</div>