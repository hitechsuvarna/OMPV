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
	    color: #000;
		padding: 15px;
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
    
    #print_data {
        display : block;
    }
    
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }
</style>

<main class="mdl-layout__content" style="display: non;">
	<div class="mdl-grid">
	    <?php if(isset($txn)) {
		    echo '<div class="mdl-cell mdl-cell--6-col">';
			echo '<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="print" style="width: 100%;">Print Invoice</button>';
			echo '</div>';
			echo '<div class="mdl-cell mdl-cell--6-col">';
			echo '<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="width:100%;" id="payment">RECORD PAYMENT</button>';
			echo '</div>';
		} ?>
	</div>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Invoice Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($txn)) echo $txn[0]->ic_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Vendor</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="i_txn_date" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="i_txn_date">Select Transaction Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_no" class="mdl-textfield__input" value="<?php if(isset($invoice_number)) { echo $invoice_number; } ?>">
						<label class="mdl-textfield__label" for="i_txn_no">Enter Invoice Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_discount" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_discount; } ?>">
						<label class="mdl-textfield__label" for="i_txn_discount">Enter Discount</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_freight" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_freight; } ?>">
						<label class="mdl-textfield__label" for="i_txn_freight">Enter Freight/ Insuarance/ Packg cost</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_credit" class="mdl-textfield__input" value="<?php if(isset($txn)) { if($txn[0]->it_credit == "" )  { echo $txn[0]->ic_credit; } else { echo $txn[0]->it_credit; } } ?>">
						<label class="mdl-textfield__label" for="i_txn_credit">Invoice Credit</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<textarea id="i_txn_note" class="mdl-textfield__input"><?php if(isset($txn)) { echo $txn[0]->it_note; } ?></textarea>
						<label class="mdl-textfield__label" for="i_txn_note">Invoice Note</label>
					</div>
					<div style="height:100px;overflow:auto;">
					    <label>Additional Challans to Merge</label>
					</div>
					<?php if(isset($txn)) { ?>
					<div style="margin:10px;">
    					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="txn_delete" style="width:100%;">
    					    Delete Invoice
    					</button>
    				</div>
    				<div style="margin:10px;">
    					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" id="txn_cancel" style="width:100%;">
    					    Cancel Invoice
    					</button>
    				</div>
    				<?php } ?>
				</div>

			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Invoice Items</h2>
				</div>
				<div class="mdl-card__supporting-text">
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
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_hold" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="prod_hold">Hold</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_rate" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="prod_rate">Rate</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col" style="margin-top:0%;margin-left:0%;padding:0px;">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<select id="prod_tax"  class="mdl-textfield__input">
								    <option value="0">Select</option>
								    <?php 
								        for($i=0; $i<count($tax_groups); $i++) {
								            echo '<option value="'.$tax_groups[$i]->ittxg_id.'">'.$tax_groups[$i]->ittxg_group_name.'</option>';
								        }
								    ?>
								</select>
        						<label class="mdl-textfield__label" for="prod_tax">Select Tax</label>
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
									<th>Hold</th>
									<th>Rate</th>
									<th>Amount</th>
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
					
					<!--<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">-->
					<!--	<input type="text" id="s_transport" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_account; ?>">-->
					<!--	<label class="mdl-textfield__label" for="s_account">Expense Account</label>-->
					<!--</div>-->
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_transport" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter; ?>">
						<label class="mdl-textfield__label" for="s_transport">Transport Through</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_lrno" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_lrno; ?>">
						<label class="mdl-textfield__label" for="s_lrno">L/R No</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="s_txn_date" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) if($txn_transport[0]->ittd_date != NULL) echo $txn_transport[0]->ittd_date; ?>">
						<label class="mdl-textfield__label" for="s_txn_date">Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_gstno" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter_gstno; ?>">
						<label class="mdl-textfield__label" for="s_gstno">Transporter GST No/</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_state" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_state; ?>">
						<label class="mdl-textfield__label" for="s_state">State</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_expense" class="mdl-textfield__input" value="<?php if(isset($txn_transport)) if(count($txn_transport) > 0 ) echo $txn_transport[0]->ie_amount; ?>">
						<label class="mdl-textfield__label" for="s_expense">Transport Expense</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mdl-grid">
	    <div id="print_data"></div>
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
	var product_alias = [];
	var product_qty = [];
	var product_hold = [];
	var product_rate = [];
	var product_amount = [];
	var product_tax = [];
	
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
	
	var edit_index = 0;
	var edit_flag = false;

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
            })
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
                url='<?php if(isset($tid)) echo base_url().$type."/Transactions/records_transaction_payment/invoice/".$tid."/e/"; ?>' + pay_edit_index;
            } else {
                url = '<?php if(isset($tid)) echo base_url().$type."/Transactions/records_transaction_payment/invoice/".$tid; ?>';
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
            $.post('<?php if(isset($tid)) echo base_url().$type."/Transactions/records_transaction_payment/invoice/".$tid."/d/"; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                reset_payments();
                load_payments();
            });
        });
        
        load_payments();
    });
    
    function load_payments() {
        
        $.post('<?php if(isset($tid)) echo base_url().$type."/Transactions/load_transaction_payment_records/".$tid; ?>', {}, function(d,s,x) {
            $('#pay_table > tbody').empty();
            var a=JSON.parse(d), b="";
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
		if(!isset($txn)) {
			echo "var dt = new Date();";
			echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
			
			echo "$('#i_txn_date').val(s_dt);";
		}
	?>
</script>
<script>
	$(document).ready(function() {

		<?php
		    function getIndianCurrency($number)
            {
                $decimal = round($number - ($no = floor($number)), 2) * 100;
                $hundred = null;
                $digits_length = strlen($no);
                $i = 0;
                $str = array();
                $words = array(0 => '', 1 => 'one', 2 => 'two',
                    3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                    7 => 'seven', 8 => 'eight', 9 => 'nine',
                    10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                    13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                    16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                    19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                    40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                    70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
                $digits = array('', 'hundred','thousand','lakh', 'crore');
                while( $i < $digits_length ) {
                    $divider = ($i == 2) ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += $divider == 10 ? 1 : 2;
                    if ($number) {
                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                    } else $str[] = null;
                }
                $Rupees = implode('', array_reverse($str));
                $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
                return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise.' only';
            }
            
            setlocale(LC_MONETARY, 'en_IN');
		
		  //  echo $logo;
			$typearr = ["Customer Copy","Office Copy", "Duplicate"]; 
			$typeidarr = ["original", "duplicate", "#triplicate"];
			$content="";
			if(isset($txn)) {
			    $content = "<style> .txt_center { text-align:center; } .txt_right { text-align:right; }  .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height:20px; font-size: 0.8em;} @page {size: A4; size:potrait; } @media print { #original, #duplicate, #triplicate { /*page-break-before: always;*/ } .main_haead { display:table-header-group !important; } .main_body tr:nth-of-type(10n) { page-break-after: always; } .main_foot { page-break-after:before !important;}} </style>";
    			for ($j=0; $j < count($typearr); $j++) { 
    			    $initial_value=0;
    			    
    			    // ***************************** INVOICE HEADER START *************************************
    				// $content.= '<td>';
    				$content.= '<div style="border: 0px #999 solid;padding: 0px 10px 0px 10px; page-break-before:always;" id="'.$typeidarr[$j].'">';
    				$line_limit = 14;
    				$line_cnt = count($txn_details);
    				if(count($txn_transport) > 0 ) { $line_cnt_total = $line_limit - $line_cnt; } else { $line_cnt_total = $line_limit - $line_cnt; }
    				
    				$total_repeat = ceil($line_cnt / $line_limit);
    				$full_repeat = floor($line_cnt / $line_limit);
    				$loop_start = 0;
    				$subtot = 0;
    					
    				for($uio=0; $uio<count($txn_details);$uio++) {
    				    $subtot = $subtot + $txn_details[$uio]->itp_value;
    				}
    				
    				for($abc=0; $abc<$total_repeat; $abc++) {
    				    $taxtot = 0.0;
    				    $taxes_arr = [];
        				$taxes_flg = false;
        				$taxes_amt_arr = [];
        				
    				    $loop_start++;
    				    $content.= '<div style="border: 0px #000 solid;">';
        				$content.= '<img src="'.$logo.'" style="width: 85%; margin: 0px auto; height: auto;margin-bottom:2px;padding:2px; display:flex; justify-content: center;" alt="Logo" />';
        				$content.= '<table border="0" style="width: 100%;">';
        				$content.= '<tr>';
        				$content.= '<td style="width:100%;">';
        				$content.= '<table style="width:100%;"><tr><td style="width:33%;">';
        				$content.= '<b>Invoice No: </b><u>'.$txn[0]->it_txn_no.'</u>';
        				$content.= '</td>';
        				$content.= '<td style="width:33%;font-size:1.3em;text-decoration:underline;">';
        				$content.= '<div style="text-align: center">TAX INVOICE - '.$typearr[$j].'</div>';
        				$content.= '</td>';
        				$content.= '<td style="width:33%;text-align:right;">';
        				$content.= '<b>Date:</b><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u>';
        				$content.= '</td></tr></table>';
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '<tr>';
        				$content.= '<td colspan="3">';
        				$content.= '<table style="width:100%;">';
        				$content.= '<tbody>';
        				$content.= '<tr>';
        				$content.= '<td style="width:80%;" rowspan="3">';
        				$content.= 'Client Name:';
        				$content.= '<b>'.$txn[0]->ic_name.'</b><br>';
        				$content.= $txn[0]->ic_address.'<br>';
        				if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
        				$content.= '</td>';
        				// $content.= '<td>';
        				// if(isset($txn_order)) $content.= 'Order No: '.$txn_order;
        				// $content.= '</td>';
        				$content.= '</tr>';
        				$content.= '<tr>';
        				$content.= '<td>';
        				if(isset($txn_delivery)) $content.= 'Challan No: '.$txn_delivery;
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '<tr>';
        				$content.= '<td>';
        				$content.= 'Mode: '.$txn[0]->it_mode;
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '</tbody>';
        				$content.= '</table>';
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '<tr>';
        				$content.= '<td colspan="3">';
        				if(count($txn_transport) > 0 ) $content.= 'Transporter: <u>'.$txn_transport[0]->ittd_transporter.'</u> ';
        				if(count($txn_transport) > 0 ) $content.= 'L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> ';
        				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_date.'</u> ';
        				if(count($txn_transport) > 0 ) $content.= 'Transporter GSTIN: <u>'.$txn_transport[0]->ittd_transporter_gstno.'</u> ';
        				if(count($txn_transport) > 0 ) $content.= 'Date: <u>'.$txn_transport[0]->ittd_state.'</u>';
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '<tr>';
        				$content.= '<td colspan="3">';
        				if(count($txn_challans) > 0 ) {
        				    $content.= 'Challans: ';
        				    $content.=implode(", ", $txn_challans);
        				}
        				$content.= '</td>';
        				$content.= '</tr>';
        				
        				$content.= '</table>';
        
        				$content.= '</div>';
        				$content.= '<div style="text-align: left;">';
        				$content.= '<hr>';
        				// $content.= '<b style="font-size:1.1em;">Order Details</b>';
        				$content.= '</div>';
        				$content.= '<div style="border: 0px #000 solid;">';
        				
        				// ***************************** INVOICE HEADER END *************************************
        				// ***************************** INVOICE BODY START *************************************
    				    
    				    $content.= '<table border="0" cellspacing=0 style="width: 100%;border:1px solid; border-radius:5px; display:table; page-break-after:always; ">';
        				$content.= '<thead class="main_head" style="display:table-header-group; ">';
        				$content.= '<tr class="item_header">';
        				$content.= '<th>Sr. No.</th>';
        				$content.= '<th>Particulars</th>';
        				$content.= '<th>HSN Code</th>';
        				$content.= '<th>Unit</th>';
        				$content.= '<th>Tax</th>';
        				$content.= '<th>Qty</th>';
        				$content.= '<th>Rate</th>';
        				$content.= '<th>Amount</th>';
        				$content.= '</tr>';
        				$content.= '</thead>';
        				
        				$tbody_content='<tbody style="page-break-inside:auto;" class="main_body">';
                        
                        $xcvz = $line_limit * $loop_start;
                        if($abc >= $full_repeat) {
                            $xcvz = $line_cnt;
                        }
        				for ($i= $initial_value; $i < ($xcvz); $i++) { 
        				    if($i==($xcvz - 1)) {
        				        $initial_value = $i + 1;
        				    }
        				 	$tbody_content .= '<tr class="item_name" style="page-break-inside:avoid; page-break-after:auto; "><td class="txt_center" style="page-break-inside:avoid; page-break-after:auto; ">'.($i+1).'</td><td>';
        					if($txn_details[$i]->itp_alias == 1) { $tbody_content.= $txn_details[$i]->ip_alias; } else { $tbody_content.= $txn_details[$i]->ip_name; }
        					$tbody_content.= '</td><td class="txt_center">'.$txn_details[$i]->ip_hsn_code.'</td><td class="txt_center">'.$txn_details[$i]->ip_unit.'</td><td class="txt_center">'.$txn_details[$i]->ittxg_group_name.'</td><td class="txt_center">'.$txn_details[$i]->itp_qty.'</td><td class="txt_right">'.money_format('%!i', $txn_details[$i]->itp_rate).'</td><td class="txt_right">'.money_format('%!i', $txn_details[$i]->itp_value).'</td></tr>';
        				}
        				
        				// echo "INTIAL VALUE: ".$initial_value;
        				// echo "XCVZ: ".$xcvz;
        				// echo "ABC: ".$abc;
        				// echo "FULL REPEAT: ".$full_repeat;
        				
        				// INCASE OF LESS ITEMS IN INVOICE, LEAVE BLANK ROWS
        				if($abc >= $full_repeat) {
        				    $additional_lines_inserted = $xcvz - $line_limit;
        				    // echo "XCVZ: ".$xcvz;
        				    // echo "ADDITIONAL LINES: ".$additional_lines_inserted;
        				    if($additional_lines_inserted < 0) {
        				        $additional_lines_inserted = $additional_lines_inserted*-1;
        				    }
        				    for ($qwer=0; $qwer < $additional_lines_inserted; $qwer++) { 
            				    $tbody_content.= '<tr class="item_name" style="page-break-inside:avoid; page-break-after:auto; "><td></td><td></td><td></td><td class="detail_center"></td><td class="detail_center"></td><td class="detail_right"></td><td class="detail_right"></td><td class="detail_right"></td></tr>';
            				}   
        				}
                        for ($i=0; $i < count($taxes); $i++) { 
        					$amt = 0.0;
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
        				$transamt = 0;
        				if(count($txn_transport) > 0) $transamt=$txn_transport[0]->ie_amount;
        				$totamt = $subtot - $disamt + $transamt;
        				$freight = $txn[0]->it_freight;
        				$creamt = $txn[0]->it_credit;
        				$grandtot = $totamt + $freight;
        
        				$tbody_content.= '</tbody>';
        				
        				$tfoot_content='';
        				$tfoot_content.= '<tfoot class="main_foot" style="display:table-footer-group;">';
        				$tfoot_content.= '<tr class="item_name"><td colspan="8">Payment within '.$creamt.' days</td></tr>';
        				$tfoot_content.= '<tr class="item_name"><td colspan="4" rowspan="';
        				if(count($txn_transport) > 0 ) { $tfoot_content.= 5; } else { $tfoot_content.= 4; }
        				$tfoot_content.= '" style="vertical-align: text-top;">Receivers Signature</td><td colspan="3">Subtotal</td><td class="txt_right">'.money_format('%!i', $subtot).'</td></tr><tr class="item_name"><td colspan="3">Discount</td><td class="txt_right">'.money_format('%!i', $disamt).'</td></tr>';
        				if(count($txn_transport) > 0 ) { 
        				    $tfoot_content.= '<tr class="item_name"><td colspan="3">Transport</td><td class="txt_right">'.money_format('%!i', $txn_transport[0]->ie_amount).'</td></tr>'; 
        				}
        				$tfoot_content.= '<tr class="item_name"><td colspan="3">Total</td><td class="txt_right">'.money_format('%!i', $totamt).'</td></tr><tr class="item_name"><td colspan="3">Freight/Insuarance/Packg</td><td class="txt_right">'.money_format('%!i', $freight).'</td></tr>';
        				$tfoot_content.= '<tr class="item_name"><td colspan="4" rowspan="'.(count($taxes_arr) + 2).'" style="vertical-align: text-top;">Note: '.$txn[0]->it_note.'</td>';
        				if(count($taxes_arr) <= 0) {
        				    $tfoot_content.= '</tr>';
        				}
        				for ($i=0; $i < count($taxes_arr) ; $i++) { 
        					$taxtot += $taxes_amt_arr[$i];
        					if($i==0) {
        					    $tfoot_content.= '<td colspan="3">'.$taxes_arr[$i].'</td><td class="txt_right">'.money_format('%!i', $taxes_amt_arr[$i]).'</td>';
        					    
        					} else {
        					    $tfoot_content.= '<tr class="item_name"><td colspan="3">'.$taxes_arr[$i].'</td><td class="txt_right">'.money_format('%!i', $taxes_amt_arr[$i]).'</td></tr>';   
        					}
        				}
        				$grandtot += $taxtot;
        				$t_flot=$grandtot - round($grandtot);
        				
        				
        				$tfoot_content.= '<tr class="item_name"><td colspan="3">Round Off</td><td class="txt_right">'.money_format('%!i', abs(number_format((float)$t_flot, 2, ".",""))).'</td></tr>';
        				$tfoot_content.= '<tr class="item_name"><td colspan="3">Grand Total</td><td class="txt_right">'.money_format('%!i', round($grandtot)).'</td></tr>';
        				
        				$tfoot_content.= '<tr class="item_name"><td colspan="8"><b>In words:</b> '.getIndianCurrency(round($grandtot)).'</td></tr>';
        				// NEW INSERTED 
        				$tfoot_content.= '<tr>';
        				$tfoot_content.= '<td colspan="8">';
        				$tfoot_content.= '<table style="width:100%;">';
        				$tfoot_content.= '<tr><td colspan="3" style="font-size:0.8em;"><b>CHEQUE BOUNCING CHARGES Rs. 500/-</b></td></tr>';
        				$tfoot_content.= '<tr>';
        				$tfoot_content.= '<td style="width:25%; font-size: 0.7em;">GSTIN: '.$gst_num.'<br>Bank Details:<br>Bank Name: '.$bank.'<br>Branch: '.$branch.'<br>Account No: '.$acc.'<br>IFSC Code: '.$ifsc.'</td>';
        				$tfoot_content.= '<td style="width:41%;"><div style="font-size:0.5em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.6em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition. 8. We declare this invoice shows the actual goods described and that the all particulars and correct to the best of our knowledge and belief. 9. Our responsibility ceases absolutely as soon as goods are handed over to the carrier.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
        				$tfoot_content.= '<td style="width:33%;">';
        				$tfoot_content.= '<div style="text-align: right;margin-top: 4em;white-space:nowrap;">';
        				$tfoot_content.= '<b style="border-top: 1px #000 solid;padding-top: 10px; font-size:0.9em;">Proprietor/ Authorized Signature</b>';
        				$tfoot_content.= '</div>';
        				$tfoot_content.= '</td>';
        				$tfoot_content.= '</tr>';
        				$tfoot_content.= '</table>';
        				$tfoot_content.= '</td></tr>';
        				$tfoot_content.= '<tr><td colspan="8" style="text-align:right;"> Page '.($abc + 1).' of '.$total_repeat.'.</td></tr>';
        				// END NEW INSERTED 
        				$tfoot_content.= '</tfoot>';
        				
        				
        				$content.=$tbody_content;
        				$content.=$tfoot_content;
        				$content.= '</table>'; 
        				
        				// ***************************** INVOICE BODY END *************************************
        				
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
        				// NEW INSERTED FROM HERE
        				$content.= '</table>';
        				
        				$content.= '</td>';
        				$content.= '</tr>';
        				$content.= '</table>';
        				$content.= '</div>';
    				
    				}
    			}
    			// $content.= "</tr></table>";
    			echo '$("#print_data").append(\''.$content.'\')';    
			}
				
		?>

		<?php 
		    if(isset($txn_details)) for ($i=0; $i < count($txn_details) ; $i++) { 
    			echo "product_name.push('".$txn_details[$i]->ip_name."');";
    			echo "product_alias.push('".$txn_details[$i]->itp_alias."');";
    			echo "product_qty.push('".$txn_details[$i]->itp_qty."');";
    			echo "product_hold.push('');";
    			echo "product_rate.push('".$txn_details[$i]->itp_rate."');";
    			echo "product_amount.push('".$txn_details[$i]->itp_value."');";
    			echo "product_tax.push('".$txn_details[$i]->itp_tax_group_id."');";
    		}
		
		    if(isset($txn_detail_merge)) for ($i=0; $i < count($txn_detail_merge) ; $i++) { 
    			for($j=0;$j < count($txn_detail_merge[$i]); $j++) {
    			    echo "product_name.push('".$txn_detail_merge[$i][$j]->ip_name."');";
        			echo "product_alias.push('".$txn_detail_merge[$i][$j]->itp_alias."');";
        			echo "product_qty.push('".$txn_detail_merge[$i][$j]->itp_qty."');";
        			echo "product_hold.push('');";
        			echo "product_rate.push('".$txn_detail_merge[$i][$j]->itp_rate."');";
        			echo "product_amount.push('".$txn_detail_merge[$i][$j]->itp_value."');";
        			echo "product_tax.push('".$txn_detail_merge[$i][$j]->itp_tax_group_id."');";   
    			}
    		}
		
		
		echo "update_list();"; ?>
		
		
		

		function add_product() {
		    if(product_exist=='true') {
    			product_name.push($('#products').val());
        		var tmp_rt = $('#prod_rate').val();
        		var tmp_qt = $('#prod_qty').val();
        		var tmp_tx = $('#prod_tax').val();
        		var tmp_amt = tmp_qt * tmp_rt;
        		product_rate.push(tmp_rt);
        		product_qty.push(tmp_qt);
        		product_hold.push($('#prod_hold').val());
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
			product_hold.splice(id, 1);
			product_rate.splice(id, 1);
			product_amount.splice(id, 1);
			product_tax.splice(id, 1);
		}

		function edit_product(id) {
            reset_fields();
			$('#products').val(product_name[id]);
    		$('#prod_qty').val(product_qty[id]);
    		$('#prod_hold').val(product_hold[id]);
    		$('#prod_rate').val(product_rate[id]);
    		$('#prod_tax').val(product_tax[id]);
    		edit_index = id;
    		edit_flag = true;
    		product_exist = 'true';
		}

		function update_product(id) {
		    if(product_exist=='true') {
    			product_name[id] = $('#products').val();
        		var tmp_rt = $('#prod_rate').val();
        		var tmp_qt = $('#prod_qty').val();
        		var tmp_amt = tmp_qt * tmp_rt;
                
        		product_rate[id] = tmp_rt;
        		product_qty[id] = tmp_qt;
        		product_hold[id] = $('#prod_hold').val();
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
				out+='</td></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_hold[i] + "</td><td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td><td>";
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
    		$('#prod_hold').val("");
    		$('#prod_rate').val("");
            $('#prod_tax').val("");
    		$('#products').focus();
    		edit_flag=false;
    		edit_index=0;
    		product_exist='false';
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
			$(this).attr('disabled','disabled');
			
			var alias_arr = [];
			$("input[name^='alias']").each(function(){
			    if($(this)[0].checked == true) {
			        alias_arr.push(1);
			    } else {
			        alias_arr.push(0);
			    }
				
			});
			
			$.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/update_invoice/".$tid; } else { echo base_url().$type."/Transactions/update_invoice/"; } ?>', {
			 'vendor': $('#vendors').val(), 'default_ledger' : $('#s_ledger').val(), 'default_ledger_to' : $('#s_paying_ledger').val(), 'transport' : $('#s_transport').val(), 'lrno' : $('#s_lrno').val(), 'transport_date' : $('#s_txn_date').val(), 'transport_gst' : $('#s_gst').val(), 'transport_state' : $('#s_state').val(), 'transport_expense' : $('#s_expense').val(), 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'note' : $('#i_txn_note').val(), 'name' : product_name, 'qty' : product_qty, 'hold': product_hold, 'rate' : product_rate, 'amt' : product_amount, 'tax_group' : product_tax, 'alias' : alias_arr, 'discount' : $('#i_txn_discount').val(), 'freight' : $('#i_txn_freight').val(), 'credit' : $('#i_txn_credit').val(), 'tid' : '<?php if(isset($tid)) echo $tid; ?>'
			 }, function(d,s,x) {
			    var ert = {message: 'Invoice Created. Please wait.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
                setTimeout(function() {
                    window.location = "<?php echo base_url().$type.'/Transactions/invoice_edit/'; ?>" + d;    
                }, 2000);
			 }, "text");

		});

		$('#print').click(function(e) {
			e.preventDefault();

			print_reciept();
		});

        $('#txn_delete').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/delete_invoice/"; ?>', { 'txnid' : <?php if(isset($tid)) { echo $tid; } else { echo '""'; }  ?> }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/invoice"; ?>'; }, "text");
		    
		});
		
		$('#txn_cancel').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/delivery_update/cancelled"; ?>', { 'txnid' : <?php if(isset($tid)) { echo $tid; } else { echo '""'; } ?> }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/invoice"; ?>'; }, "text");
		    
		});
		
		
	});

	function print_reciept() {
		var mywindow = window.open('', '<?php if(isset($txn)) { echo $title_doc; } else { echo '""'; } ?>', fullscreen=1);
		<?php echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; ?>
	}
</script>
</html>