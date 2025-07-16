<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style type="text/css">
	.purchase_table {
		width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
        
	}

	@media only screen and (max-width: 760px) {
		.purchase_table {
			display: block;
        	overflow: auto;
		}
	}

	.purchase_table > thead > tr {
		box-shadow: 0px 5px 5px #ccc;
	}

	.purchase_table > thead > tr > th {
		padding: 10px;
		font-size: 1.3em;
	}

	.purchase_table > tbody > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tbody > tr > td {
		padding: 15px;
		color: #000;
		font-size: 1.3em;
	}

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	#order_number {
		color: #ff0000;
	}

	#total_amount {
		color: #ff0000;
	}

	.product_qty {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }
</style>

<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--6-col">
			<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="print" <?php if(isset($price_display)) { if($price_display == 'false') { echo ' style="display:none;"'; } } ?> style="width: 100%;">Print Purchase</button>
		</div>
		<?php if(isset($edit_txn)) {
		    echo '<div class="mdl-cell mdl-cell--6-col">';
		    echo '<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="width:100%;" id="payment">RECORD PAYMENT</button>';
		    echo '</div>';
		} ?>
	</div>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Purchase Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->ic_name; } ?>">
						<label class="mdl-textfield__label" for="vendors">Enter Vendor Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					    <select id="txn_type" class="mdl-textfield__input">
					        <option value="Delivery">Delivery</option>
					        <option value="Purchase">Purchase</option>
					    </select>
						<label class="mdl-textfield__label" for="txn_type">Select Purchase Type</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="i_txn_date" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="i_txn_date">Select Transaction Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_no" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->it_txn_no; } ?>">
						<label class="mdl-textfield__label" for="i_txn_no">Enter Transaction Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					    <p style="margin: 10px;text-align: left;">Upload Purchae Image</p>
					    <br>
					    <input type="file" name="attach_file" class="upload">
					    <img src="<?php if(isset($edit_txn)) if($edit_txn[0]->it_file != "") echo base_url().'assets/uploads/'.$oid.'/purchase/'.$tid.'/'.$edit_txn[0]->it_file; ?>" id="p_image" style="width: 100%;">
			        </div>
			        <div>
						<?php if(isset($edit_txn)) echo '<button style="width:100%;" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="pur_delete"><i class="material-icons">delete</i> Delete Purchase</button>'; ?>
					</div>
				</div>

			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Purchase Items</h2>
				</div>
				<div class="mdl-card__supporting-text">
				    <!--<div class="mdl-grid">-->
				    <!--    <div class="mdl-cell mdl-cell--12-col">-->
				    <!--        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">-->
        <!--						<select id="inventory_txn" class="mdl-textfield__input">-->
        <!--						    <option value="0">Select</option>-->
        						    <?php
        						        for($i=0;$i<count($inventory); $i++) {
        						            if($inventory[$i]->ii_txn_num != null) {
        						                #echo '<option value="'.$inventory[$i]->ii_id.'">'.$inventory[$i]->ii_txn_num.' - '.$inventory[$i]->ii_txn_date.' - '.$inventory[$i]->ic_name.'</option>';
        						            }
        						        }
        						    ?>
        <!--						</select>-->
        <!--						<label class="mdl-textfield__label" for="inventory_txn">Select Inventory Details</label>-->
        <!--					</div>-->
				    <!--    </div>-->
				    <!--</div>-->
					<div id="info_repea" class="mdl-grid">
						<div class="mdl-cel mdl-cell--4-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="products" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="products">Search Products</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_qty" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="prod_qty">Qty</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col"<?php if(isset($price_display)) { if($price_display == 'false') { echo ' style="display:none;"'; } } ?>>
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_rate" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="prod_rate">Rate</label>
        					</div>
						</div>'
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
						        <select id="prod_tax" class="mdl-textfield__input">
								    <option value="0">Select</option>
								    <?php 
								        for($i=0; $i<count($tax_groups); $i++) {
								            echo '<option value="'.$tax_groups[$i]->ittxg_id.'">'.$tax_groups[$i]->ittxg_group_name.'</option>';
								        }
								    ?>
								</select>
        						<label class="mdl-textfield__label" for="prod_rate">Taxes</label>
        					</div>
						</div>
						
						<div class="mdl-cel mdl-cell--2-col" style="margin-top:12px;margin-left:0%;padding:0px;">
							<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item"><i class="material-icons">add</i></button>
						</div>
					</div>
					<div class="mdl-grid">
						<table class="purchase_table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Alias</th>
									<th>Product</th>
									<th>Qty</th>
									<th<?php if(isset($price_display)) { if($price_display == 'false') { echo ' style="display:none;"'; } } ?>>Rate</th>
									<th<?php if(isset($price_display)) { if($price_display == 'false') { echo ' style="display:none;"'; } } ?>>Amount</th>
									<th>Taxes</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									
								</tr>
							</tbody>
						</table>
					</div>
					
				</div>

			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Transport Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_ledger" class="mdl-textfield__input" value="<?php if(count($default_ledger)>0) echo $default_ledger[0]->im_secondary_ledger; ?>">
						<label class="mdl-textfield__label" for="s_ledger">Expense Ledger</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_paying_ledger" class="mdl-textfield__input" value="<?php if(count($default_ledger)>0) echo $default_ledger[0]->im_secondary_ledger_to; ?>">
						<label class="mdl-textfield__label" for="s_paying_ledger">Paying Ledger</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_transport" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter; ?>">
						<label class="mdl-textfield__label" for="s_transport">Transport Through</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_lrno" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_lrno; ?>">
						<label class="mdl-textfield__label" for="s_lrno">L/R No</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="s_txn_date" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_date; ?>">
						<label class="mdl-textfield__label" for="s_txn_date">Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_gstno" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter_gstno; ?>">
						<label class="mdl-textfield__label" for="s_gstno">Transporter GST No/</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_state" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_state; ?>">
						<label class="mdl-textfield__label" for="s_state">State</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_amt" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ie_amount; ?>">
						<label class="mdl-textfield__label" for="s_amt">Amount</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
<div id="add_payment_dialog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Payment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cell mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<select id="pay_mode" class="mdl-textfield__input">
    						    <option value="0">Select Account</option>
    						    <?php for($i=0;$i<count($cash);$i++) {
    						        echo '<option value="'.$cash[$i]->icm_id.'">'.$cash[$i]->icm_name.'</option>';
    						    } ?>
    					    </select>
    						<label class="mdl-textfield__label" for="pay_mode">Select Cash Account</label>
    					</div>
                    </div>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="pay_date" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="pay_date">Select Date</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="pay_amount" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="pay_amount">Payment Amount</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--12-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<textarea id="pay_narration" class="mdl-textfield__input"></textarea>
    						<label class="mdl-textfield__label" for="pay_narration">Enter narration</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--12-col" style="margin-top:12px;margin-left:0%;padding:0px;">
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_pay" edit_val="0"><i class="material-icons">add</i></button>
					</div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="pay_table">
						<thead>
							<tr>
								<th>Action</th>
								<th>Date</th>
								<th>Mode</th>
								<th>Amount</th>
								<th>Narration</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
							</tr>
						</tbody>
					</table>
				</div>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
	var product_name = [];
	var product_qty = [];
	var product_rate = [];
	var product_amount = [];
	var product_tax = [];
	var product_alias = [];
	var edit_index = 0;
	var edit_flag = false;
	
	var product_exist = 'false';
	var snackbarContainer = document.querySelector('#demo-snackbar-example');
	
	var pay_edit_flag=false, pay_edit_index=0;
    $('#pay_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	$('#pay_date').val(s_dt);
    
	
	var taxes = [];
	<?php 
	    for($i=0; $i<count($taxes); $i++){
	        echo 'taxes.push({ id: "'.$taxes[$i]->itx_id.'", name: "'.$taxes[$i]->itx_name.'", percent: "'.$taxes[$i]->itx_percent.'" });';
	    }
	?>
	var tax_groups = [];
	<?php 
	    for($i=0; $i<count($tax_groups); $i++){
	        echo 'tax_groups.push({ id: "'.$tax_groups[$i]->ittxg_id.'", name: "'.$tax_groups[$i]->ittxg_group_name.'" });';
	    }
	?>
	var dealer_exist = 'false';
	

	$(document).ready( function() {
    	var vendor_data = [];
    	
    	<?php
    		for ($i=0; $i < count($vendors) ; $i++) { 
    			echo "vendor_data.push('".$vendors[$i]->ic_name."');";
    		}
    	?>
    	
    	$( "#vendors" ).autocomplete({
            source: vendor_data
        });
        
        $('#vendors').change(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Dealers/dealer_exists/"; ?>',{ 'd': $(this).val() }, function(d,s,x) {
                dealer_exist=d;
            })   
        });
        
    	var product_data = [];
    	
    	<?php
    		for ($i=0; $i < count($products) ; $i++) { 
    			echo "product_data.push('".$products[$i]->ip_name."');";
    		}
    	?>
    	
    	$( "#products" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Products/search_products_by_code/0"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                                label: item.ip_name,
                                value: item.ip_name
                        }
                    }));
                })
            }
        }); 
    	
    	$('#products').change(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                product_exist=d;
            });
            getinventory($(this).val());
            getrates($(this).val(), $('#vendors').val());
        });
        
        function getinventory(p) {
            $.post("<?php echo base_url().$type.'/Transactions/order_get_inventory'; ?>", { 'p' : p }, function(d,s,x) { $('#prod_inventory').val(d); }, "text");
        }
        
        function getrates(p, de) {
            $.post("<?php echo base_url().$type.'/Transactions/purchase_get_rates'; ?>", { 'p' : p, 'd' : de}, function(d,s,x) { $('#prod_rate').val(d); }, "text");
        }
        
        
        $('#pur_delete').click(function(e) {
            e.preventDefault();
            window.location = "<?php if(isset($edit_txn)) echo base_url().$type.'/Transactions/delete_purchase/'.$tid; ?>";
        });
        
        var ledger_arr = [];
    	<?php 
    	    for($i=0; $i<count($ledgers); $i++){
    	        echo 'ledger_arr.push("'.$ledgers[$i]->iacl_name.'");';
    	    }
    	?>
    	$( "#s_ledger" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(ledger_arr, request.term);
                response(results.slice(0, 10));
            }
        });
        $( "#s_paying_ledger" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(ledger_arr, request.term);
                response(results.slice(0, 10));
            }
        });
        
        $('#payment').click(function(e) {
            e.preventDefault();
            $('#add_payment_dialog').modal('toggle');
        })
        
        $('#add_pay').click(function(e) {
            e.preventDefault();
            var url="";
            if(pay_edit_flag==true) {
                url='<?php if(isset($tid)) { echo base_url().$type."/Transactions/records_transaction_payment/purchase/".$tid."/e/"; } ?>' + pay_edit_index;
            } else {
                url = '<?php if(isset($tid)) { echo base_url().$type."/Transactions/records_transaction_payment/purchase/".$tid; } ?>';
            }
            $.post(url, {
                'm' : $('#pay_mode').val(),
                'd' : $('#pay_date').val(),
                'a' : $('#pay_amount').val(),
                'n' : $('#pay_narration').val()
            }, function(d,s,x) {
                reset_payments();
                load_payments();
            })
            
        });
        
        $('#pay_table').on('click','.edit_pay', function(e) {
            e.preventDefault();
            var ix=$(this).prop('id')
            $.post('<?php echo base_url().$type."/Transactions/fetch_transaction_payment/"; ?>' + ix, {}, function(d,s,x) {
                var a=JSON.parse(d);
                $('#pay_mode').val(a[0].itpn_mode_id);
                $('#pay_date').val(a[0].itpn_date);
                $('#pay_amount').val(a[0].itpn_amount);
                $('#pay_narration').val(a[0].itpn_narration);
                
                pay_edit_index=ix;
                pay_edit_flag=true;
            });
        }).on('click','.delete_pay', function(e) {
            e.preventDefault();
            $.post('<?php if(isset($tid)) {  echo base_url().$type."/Transactions/records_transaction_payment/purchase/".$tid."/d/"; } ?>' + $(this).prop('id'), {}, function(d,s,x) {
                reset_payments();
                load_payments();
            });
        });
        
        <?php if(isset($tid)) {
            echo 'load_payments();';
        } ?>
        
    });
    
    function load_payments() {
        $.post('<?php  if(isset($tid)) { echo base_url().$type."/Transactions/load_transaction_payment_records/".$tid; } ?>', {}, function(d,s,x) {
            $('#pay_table > tbody').empty();
            var a=JSON.parse(d);
            var b="";
            for(var i=0;i<a.length;i++) {
                b+='<tr><td><button class="mdl-button mdl-button--colored edit_pay mdl-button--icon" id="' + a[i].itpn_id + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-button--colored delete_pay mdl-button--icon" id="' + a[i].itpn_id + '"><i class="material-icons">delete</i></button></td><td>' + a[i].itpn_date + '</td><td>' + a[i].icm_name + '</td><td>' + a[i].itpn_amount + '</td><td>' + a[i].itpn_narration + '</td></tr>';
            }
            $('#pay_table > tbody').append(b);    
        });
    }
    
    
    function reset_payments() {
        $('#pay_mode').val('');
        $('#pay_date').val('');
        $('#pay_amount').val('');
        $('#pay_narration').val('');
        
        $('#add_payment_dialog').modal('toggle');
        
        pay_edit_index=0;
        pay_edit_flag=false;
    }
    
</script>
<script>
	$('#i_txn_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	
	<?php 
		if(!isset($edit_txn)) {
			echo "var dt = new Date();";
			echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
			
			echo "$('#i_txn_date').val(s_dt);";
		}
	?>
</script>
<script>
	$(document).ready(function() {
	    
	    <?php
		
		  //  echo $logo;
			$typearr = ["Original"]; 
			$typeidarr = ["original"];
			$content = "<style> .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height:20px;} @page {size: A4; size:potrait; } @media print { #duplicate { page-break-before: always; } } </style> <!--<table style=\"width:100%;\"><tr> -->";
			if(isset($txn))
			for ($j=0; $j < count($typearr); $j++) { 
				// $content.= '<td>';
				$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
				$content.= '<div style="border: 1px #000 solid;">';
				$content.= '<table border="0" style="width: 100%;">';
				$content.= '<tr>';
				$content.= '<td style="width:100%;">';
				$content.= '<table style="width:100%;"><tr><td style="width:33%;">';
				$content.= '<b>Invoice No: </b><u>'.$txn[0]->it_txn_no.'</u>';
				$content.= '</td>';
				$content.= '<td style="width:33%;font-size:1.3em;text-decoration:underline;">';
				$content.= '<div style="text-align: center">PURCHASE - '.$typearr[$j].'</div>';
				$content.= '</td>';
				$content.= '<td style="width:33%;text-align:right;">';
				$content.= '<b>Date:</b><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u>';
				$content.= '</td></tr></table>';
				$content.= '</td>';
				$content.= '</tr>';
				$content.= '<tr>';

				$content.= '<td style="width: 50%;">';
				$content.= 'Client Name:<br/>';
				$content.= '<p>';
				$content.= '<b>'.$txn[0]->ic_name.'</b><br>';
				$content.= $txn[0]->ic_address.'<br>';
				if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
				$content.= '</p>';
				$content.= '</td>';
				$content.= '<td>';
				$content.= '<div style="text-align: right;">';
				$content.= '</div>';
				$content.= '<div style="text-align: right;">';
				
				$content.= '<table style="width: 100%;padding: 10px;">';
				$content.= '<tr>';
				$content.= '<td class="col_right">';
				$content.= '</td>';
				$content.= '<td class="col_left">';
				
				$content.= '</td>';
				$content.= '</tr>';
				$content.= '<tr>';
				$content.= '<td class="col_right">';
				$content.= '</td>';
				$content.= '<td class="col_left">';
				
				$content.= '</td>';
				$content.= '</tr>';
				$content.= '</table>';
				
				$content.= '</div>';
				$content.= '</td>';
				$content.= '</tr>';
				$content.= '<tr>';
				$content.= '<td colspan="2">';
				if(count($txn_transport) > 0 ) $content.= 'Transporter: <u>'.$txn_transport[0]->ittd_transporter.'</u> ';
				if(count($txn_transport) > 0 ) $content.= 'L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> ';
				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_date.'</u> ';
				if(count($txn_transport) > 0 ) $content.= 'Transporter GSTIN: <u>'.$txn_transport[0]->ittd_transporter_gstno.'</u> ';
				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_state.'</u> ';
				

				$content.= '</td>';
				$content.= '</tr>';
				$content.= '</table>';

				$content.= '</div>';
				$content.= '<div style="text-align: left;">';
				$content.= '<hr>';
				// $content.= '<b style="font-size:1.1em;">Order Details</b>';
				$content.= '</div>';
				$content.= '<div style="border: 0px #000 solid;">';
				$content.= '<table border="0" cellspacing=0 style="width: 100%;border:1px solid; border-radius:5px;">';
				$content.= '<thead>';
				$content.= '<tr class="item_header">';
				$content.= '<th>Sr. No.</th>';
				$content.= '<th>Particulars</th>';
				$content.= '<th>HSN Code</th>';
				$content.= '<th>Unit</th>';
				$content.= '<th>Qty</th>';
				$content.= '<th>Rate</th>';
				$content.= '<th>Amount</th>';
				$content.= '</tr>';
				$content.= '</thead>';
				$content.= '<tbody>';

				$taxes_arr = [];
				$taxes_flg = false;
				$taxes_amt_arr = [];
				$subtot = 0;
				$line_cnt = count($txn_details);
				$line_cnt_total = 15 - $line_cnt;
				for ($i=0; $i < count($txn_details) ; $i++) { 
				    $subtot = $subtot + $txn_details[$i]->itp_value;
					$content.= '<tr class="item_name"><td>'.($i+1).'</td><td>';
					if($txn_details[$i]->itp_alias == 1) { $content.= $txn_details[$i]->ip_alias; } else { $content.= $txn_details[$i]->ip_name; }
					$content.= '</td><td>'.$txn_details[$i]->ip_hsn_code.'</td><td class="detail_center">'.$txn_details[$i]->ip_unit.'</td><td class="detail_center">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right">'.$txn_details[$i]->itp_value.'</td></tr>';
				}
                for ($i=0; $i < $line_cnt_total; $i++) { 
				    $content.= '<tr class="item_name"><td></td><td></td><td></td><td class="detail_center"></td><td class="detail_center"></td><td class="detail_right"></td><td class="detail_right"></td></tr>';
				}
                for ($i=0; $i < count($taxes); $i++) { 
					$amt = 0;
					$tmp_flg = false;
					for ($ik=0; $ik < count($txn_prod_tax); $ik++) { 
						
						if ($taxes[$i]->itx_id == $txn_prod_tax[$ik]->itpt_tx_id ) {
						    if ($taxes_flg == false) {
								array_push($taxes_arr, $txn_prod_tax[$ik]->itpt_t_name);
								$taxes_flg = true;
							}
							$tmp_flg = true;
							$amt+=$txn_prod_tax[$ik]->itpt_t_amount;
						}
					}
					if($tmp_flg == true) { array_push($taxes_amt_arr, $amt); }
					$taxes_flg = false;
				}
				// $subtot = $txn[0]->it_amount;
				
				$disamt = $txn[0]->it_discount;
				$totamt = $subtot - $disamt;
				$freight = $txn[0]->it_freight;
				$creamt = $txn[0]->it_credit;
				$grandtot = $totamt + $freight;

				$content.= '</tbody>';
				$content.= '<tfoot>';
				$content.= '<tr class="item_name"><td colspan="7">Receivers Signature</td></tr>';
				$content.= '<tr class="item_name"><td colspan="7">Payment within '.$creamt.' days</td></tr>';
				$content.= '<tr class="item_name"><td colspan="5" rowspan="9">Note: '.$txn[0]->it_inv_note.'</td><td>Subtotal</td><td>'.$subtot.'</td></tr><tr class="item_name"><td>Discount</td><td>'.$disamt.'</td></tr><tr class="item_name"><td>Total</td><td>'.$totamt.'</td></tr><tr class="item_name"><td>Freight / Insuarance / Packg</td><td>'.$freight.'</td></tr>';
				$taxtot = 0;
				for ($i=0; $i < count($taxes_arr) ; $i++) { 
					$taxtot += $taxes_amt_arr[$i];
					$content.= '<tr class="item_name"><td>'.$taxes_arr[$i].'</td><td>'.$taxes_amt_arr[$i].'</td></tr>';
				}
				$grandtot += $taxtot;
				$content.= '<tr class="item_name"><td>Grand Total</td><td>'.$grandtot.'</td></tr>';
				$content.= '</tfoot>';
				$content.= '</table>';
				$content.= '</div>';
				# $content.= '<div style="border: 0px #000 solid;">';
				#$content.= '<table border="0" style="width: 100%;text-align: left;padding-top: 10px;">';
				#$content.= '<tr>';
				#$content.= '<th colspan="2">Terms:</th>';
				#$content.= '</tr>';
				#for ($i=0; $i < count($terms) ; $i++) {$content.= "<tr>"; $content.= "<td>".($i+1)."</td>"; $content.= "<td>".$terms[$i]->iextdt_term."</td>"; $content.= "</tr>"; }
				#$content.= '</table>';
				// $content.= '<b>GST No:'.$gst_num.'</b>';
				// $content.= '</div>';
				$content.= '<table>';
				$content.= '<tr>';
				$content.= '<td style="width:100%;">';
				$content.= '<table style="width:100%;"><tr><td colspan="3" style="font-size:0.8em;"><b>CHEQUE BOUNCING CHARGES Rs. 500/-</b></td></tr><tr>';
				$content.= '<td style="width:25%; font-size: 0.7em;">GSTIN: '.$gst_num.'<br>Bank Details:<br>Bank Name: '.$bank.'<br>Branch: '.$branch.'<br>Account No: '.$acc.'<br>IFSC Code: '.$ifsc.'</td>';
				$content.= '<td style="width:41%;"><div style="font-size:0.6em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.6em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition. 8. We declare this invoice shows the actual goods described and that the all particulars and correct to the best of our knowledge and belief. 9. Our responsibility ceases absolutely as soon as goods are handed over to the carrier.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
				$content.= '<td style="width:33%;">';
				$content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
				$content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
				$content.= '</div>';
				$content.= '</td>';
				$content.= '</tr></table>';
				
				$content.= '</td>';
				$content.= '</tr>';
				$content.= '</table>';
				$content.= '<hr>';
				$content.= '</div>';

				// $content.= "</td>";
			}
			// $content.= "</tr></table>";
			echo '$("#print_data").append(\''.$content.'\')';	
		?>
		
		
		$('#inventory_txn').change(function(e) {
		    e.preventDefault();
		    
		    $.post('<?php echo base_url().$type.'/Transactions/purchase_load_inventory'; ?>', {
		        'txnid' : $(this).val()
		    }, function(d,s,x) {
		        var a = JSON.parse(d);
		        product_name = [];
            	product_qty = [];
            	product_rate = [];
            	product_amount = [];
            	product_tax = [];
            	product_alias = [];
            	
		        
		        for(var i=0;i<a.length;i++) {
		            product_name.push(a[i].ip_name);
		            product_alias.push('');
        			product_qty.push(a[i].ii_inward);
        			product_rate.push('');
        			product_amount.push('');
        			product_tax.push('');
		        }
		        update_list();
		        
		    }, "text");
		});

		<?php if(isset($edit_txn_details)) for ($i=0; $i < count($edit_txn_details) ; $i++) { 
			echo "product_name.push('".$edit_txn_details[$i]->ip_name."');";
			echo "product_alias.push('".$txn_details[$i]->itp_alias."');";
			echo "product_qty.push('".$edit_txn_details[$i]->itp_qty."');";
			echo "product_rate.push('".$edit_txn_details[$i]->itp_rate."');";
			echo "product_amount.push('".$edit_txn_details[$i]->itp_value."');";
			echo "product_tax.push('".$txn_details[$i]->itp_tax_group_id."');";
		} echo "update_list();"; ?>
		

		function add_product() {
		    if(product_exist=='true') {
    		    product_name.push($('#products').val());
        		var tmp_rt = $('#prod_rate').val();
        		var tmp_qt = $('#prod_qty').val();
        		var tmp_tx = $('#prod_tax').val();
        		var tmp_amt = tmp_qt * tmp_rt;
        		
        		product_rate.push(tmp_rt);
        		product_qty.push(tmp_qt);
        		product_amount.push(tmp_amt);
        		product_tax.push($('#prod_tax').val());
		    } else {
		        var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
		    }
		}

		function remove_product(id) {
			product_name.splice(id, 1);
			product_alias.splice(id, 1);
			product_qty.splice(id, 1);
			product_rate.splice(id, 1);
			product_amount.splice(id, 1);
			product_tax.splice(id, 1);
		}

		function edit_product(id) {
            $('#products').val(product_name[id]);
    		$('#prod_qty').val(product_qty[id]);
    		$('#prod_rate').val(product_rate[id]);
    		$('#prod_tax').val(product_tax[id]);
    		edit_index = id;
    		edit_flag = true;
		}

		function update_product(id) {
		    if(product_exist=='true') {
    			product_name[id] = $('#products').val();
        		var tmp_rt = $('#prod_rate').val();
        		var tmp_qt = $('#prod_qty').val();
        		var tmp_amt = tmp_qt * tmp_rt;
                
        		product_rate[id] = tmp_rt;
        		product_qty[id] = tmp_qt;
        		product_amount[id] = tmp_amt;
        		product_tax[id] = $('#prod_tax').val();
		    } else {
		        var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
		    }
		}

		function update_list() {
			$('.purchase_table > tbody').empty();

			var out = "";
			for (var i = 0; i < product_name.length; i++) {
				out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>';
				if(product_alias[i] == 1) {
				    out+='<input type="checkbox" id="' + i + '" name="alias[]" checked>';
				} else {
				    out+='<input type="checkbox" id="' + i + '" name="alias[]">';
				}
				out+='</td></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td<?php if(isset($price_display)) { if($price_display == 'false') { echo ' style=\'display:none;\''; } } ?>>" + product_rate[i] + "</td><td<?php if(isset($price_display)) { if($price_display == 'false') { echo ' style=\'display:none;\''; } } ?>>" + product_amount[i] + "</td><td>";
				for(var j=0; j<tax_groups.length; j++) {
				    if(product_tax[i] == tax_groups[j].id) {
				        out += tax_groups[j].name;
				    }
				}
				out+= "</td></tr>";
			}

			$('.purchase_table > tbody').append(out);
		}

		function reset_fields() {
			$('#products').val("");
    		$('#prod_qty').val("");
    		$('#prod_rate').val("");
            $('#prod_tax').val("");
    		$('#products').focus();
		} 

		$('#prod_rate').keypress(function(e) {
			if(e.keyCode == 13) {
    			if(edit_flag == true) {
    				update_product(edit_index);
    			} else {
    				add_product();
    			}
    			update_list();
    			reset_fields();
			}
		});

		$('#add_item').click(function(e) {
			e.preventDefault();
			if(edit_flag == true) {
				update_product(edit_index);
			} else {
				add_product();
			}
			update_list();
			reset_fields();
		});

		$('.purchase_table').on('click', '.delete', function(e) {
			e.preventDefault();

			remove_product($(this).prop('id'));
			update_list();
			reset_fields();
		});

		$('.purchase_table').on('click','.edit', function(e) {
			e.preventDefault();
			edit_product($(this).prop('id'));
		});

		$('#submit').click(function(e) {
			e.preventDefault();
			var alias_arr = [];
			$("input[name^='alias']").each(function(){
			    if($(this)[0].checked == true) {
			        alias_arr.push(1);
			    } else {
			        alias_arr.push(0);
			    }
			});
            
            $.post('<?php if(isset($edit_txn)) { echo base_url().$type."/Transactions/update_purchase/".$tid; } else { echo base_url().$type."/Transactions/save_purchase/"; } ?>', { 
                'vendor': $('#vendors').val(), 'sec_type' : $('#txn_type').val(), 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'name' : product_name, 'qty' : product_qty, 'rate' : product_rate, 'amt' : product_amount, 'tax_group' : product_tax, 'alias' : alias_arr, 'inv_link' : $('#inventory_txn').val(), 'transporter' : $('#s_transport').val(), 'lrno' : $('#s_lrno').val(), 't_tdate' : $('#s_txn_date').val(), 't_gstno' : $('#s_gstno').val(), 't_state' : $('#s_state').val(), 't_tamt' : $('#s_amt').val(), 'default_ledger' : $('#s_ledger').val(), 'default_ledger_to': $('#s_paying_ledger').val(), <?php if(!isset($txn)) { echo "'upd' : 'true',"; } else { echo "'upd' : 'false',"; } ?> 
            }, function(d,s,x) { upload_image(d); }, "text");

		});

        $('#print').click(function(e) {
			e.preventDefault();
			print_reciept();
		});
	});
	
	function print_reciept() {
		var mywindow = window.open('', '<?php if(isset($txn)) { echo $title_doc;} else { echo 'Purchase'; } ?>', fullscreen=1);
		<?php echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; ?>
	}
	
	function upload_image(eid) {
		var datat = new FormData();
		if($('.upload')[0].files[0]) {
			datat.append("use", $('.upload')[0].files[0]);
			
			flnm = "";
			$.ajax({
				url: "<?php echo base_url().$type.'/Transactions/purchase_ticket_upload/'; ?>" + eid, // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: datat, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(data)   // A function to be called if request succeeds
				{
					console.log("Recd: " + data);
					flnm = data.toString();
					$('.upload').val('');
					redirect();
				}
			});
		} else {
			redirect();
		}
	}
	
	function redirect() {
	    window.location = "<?php echo base_url().$type.'/Transactions/purchase'; ?>";
	}
	
</script>
</html>