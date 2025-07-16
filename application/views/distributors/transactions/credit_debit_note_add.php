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
	}

	.purchase_table > tbody > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tbody > tr > td {
		padding: 15px;
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
        display : none;
    }
    
    .detail_center {
        text-align:center;
    }
    
    .detail_right {
        text-align:right;
    }
    
    .txt_right {
        text-align: right;
    }
</style>

<main class="mdl-layout__content" style="display: non;">
	<div class="mdl-grid" id="print_button">
		<div class="mdl-cell mdl-cell--12-col">
			<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="print" style="width: 100%;">Print Note</button>
		</div>
	</div>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Note Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="i_txn_type" class="mdl-textfield__input">
						    <option value="Credit Note" <?php if(isset($txn)) if($txn[0]->it_type == "Credit Note") echo "selected"; ?>>Credit Note</option>
						    <option value="Debit Note" <?php if(isset($txn)) if($txn[0]->it_type == "Debit Note") echo "selected"; ?>>Debit Note</option>
						</select>
						<label class="mdl-textfield__label" for="i_txn_type">Select Transaction Type</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="i_txn_type_sec" class="mdl-textfield__input">
						    <option value="0">None</option>
						    <option value="Delivery" <?php if(isset($txn)) if($txn[0]->it_type_sec == "Delivery") echo "selected"; ?>>Delivery</option>
						    <option value="Invoice" <?php if(isset($txn)) if($txn[0]->it_type_sec == "Invoice") echo "selected"; ?>>Invoice & Purchase</option>
						    <!--<option value="Purchase" <?php #if(isset($txn)) if($txn[0]->it_type_sec == "Purchase") echo "selected"; ?>>Purchase</option>-->
						</select>
						<label class="mdl-textfield__label" for="i_txn_type_sec">Select Transaction For</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($txn)) echo $txn[0]->ic_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Contact</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<label>Select Transactions</label>
						<ul id="i_txn_ref_no" style="height:150px; overflow: auto;">
						    
						</ul>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="i_txn_date" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="i_txn_date">Select Transaction Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_no" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_txn_no; } ?>">
						<label class="mdl-textfield__label" for="i_txn_no">Enter Transaction Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<textarea id="i_txn_note" class="mdl-textfield__input"><?php if(isset($txn)) { echo $txn[0]->it_note; } ?></textarea>
						<label class="mdl-textfield__label" for="i_txn_note">Note</label>
					</div>
					<div>
					    <?php if(isset($txn)) {
					        echo '<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="txn_delete">
    					    Delete Invoice
    					</button>';    
					    } ?>
    				</div>
				</div>

			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Note Items</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div id="info_repea" class="mdl-grid">
						<div class="mdl-cel mdl-cell--4-col" style="margin:0px;padding:0px;">
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
						<div class="mdl-cel mdl-cell--2-col" style="margin-top:24px;margin-left:0%;padding:0px;">
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
		<!--<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Transport Details</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_transport" class="mdl-textfield__input" value="<?php #if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter ?>">
						<label class="mdl-textfield__label" for="s_transport">Transport Through</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_lrno" class="mdl-textfield__input" value="<?php #if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_lrno ?>">
						<label class="mdl-textfield__label" for="s_lrno">L/R No</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="s_txn_date" class="mdl-textfield__input" value="<?php #if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_date ?>">
						<label class="mdl-textfield__label" for="s_txn_date">Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_gstno" class="mdl-textfield__input" value="<?php #if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_transporter_gstno ?>">
						<label class="mdl-textfield__label" for="s_gstno">Transporter GST No/</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="s_state" class="mdl-textfield__input" value="<?php #if(count($txn_transport) > 0 ) echo $txn_transport[0]->ittd_state ?>">
						<label class="mdl-textfield__label" for="s_state">State</label>
					</div>
					
					<div>
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="update" style="margin: 20px; width: auto;">Update Records</button>
					</div>
				</div>
			</div>
		</div>-->
	</div>
	<div class="mdl-grid">
	    <div id="print_data"></div>
	</div>
	
	<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
		<i class="material-icons">done</i>
	</button>
</div>
</div>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
<script type="text/javascript">
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	    
	var product_name = [];
	var product_alias = [];
	var product_qty = [];
	var product_rate = [];
	var product_amount = [];
	var product_tax = [];
	var product_exist = 'false';
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
            source: vendor_data,
            select: function(event, ui) {
                get_txns($('#i_txn_type').val(), ui.item.value);
            }
        });
        

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
        
    });
</script>
<script>
	$('#i_txn_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	<?php if(!isset($txn)) echo "$('#print_button').css('display','none');" ; ?>
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
		    if(isset($txn)) {
		        echo "get_txns($('#i_txn_type').val(), $('#vendors').val());";
		    
		  //  echo $logo;
    		    $typearr = ["Original","Duplicate"]; 
    			$typeidarr = ["original", "duplicate"];
    			
    		    if($txn[0]->it_type_sec == "Invoice") {
    		        $content = "<style> .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid; height:20px;} @page {size: A4; size:potrait; } @media print { #duplicate { page-break-before: always; } } </style> <!--<table style=\"width:100%;\"><tr> -->";
        			for ($j=0; $j < count($typearr); $j++) { 
        				// $content.= '<td>';
        				$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
        				$content.= '<div style="border: 1px #000 solid;">';
        				$content.= '<img src="'.$logo.'" style="width: 98%; height: auto;margin-bottom:5px;padding:5px" alt="Logo" />';
        				$content.= '<table border="0" style="width: 100%;">';
        				$content.= '<tr>';
        				$content.= '<td style="width:100%;">';
        				$content.= '<table style="width:100%;"><tr><td style="width:33%;">';
        				$content.= '<b>'.$txn[0]->it_type.' No: </b><u>'.$txn[0]->it_txn_no.'</u>';
        				$content.= '</td>';
        				$content.= '<td style="width:33%;font-size:1.3em;text-decoration:underline;">';
        				$content.= '<div style="text-align: center">'.$txn[0]->it_type.' - '.$typearr[$j].'</div>';
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
        				$line_cnt_total = 14 - $line_cnt;
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
        				$content.= '<tr class="item_name"><td colspan="5" rowspan="9">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td class="txt_right">'.money_format('%!i',$subtot).'</td></tr><tr class="item_name"><td>Discount</td><td class="txt_right">'.money_format('%!i',$disamt).'</td></tr><tr class="item_name"><td>Total</td><td class="txt_right">'.money_format('%!i',$totamt).'</td></tr><tr class="item_name"><td>Freight / Insuarance / Packg</td><td class="txt_right">'.money_format('%!i',$freight).'</td></tr>';
        				$taxtot = 0;
        				for ($i=0; $i < count($taxes_arr) ; $i++) { 
        					$taxtot += $taxes_amt_arr[$i];
        					$content.= '<tr class="item_name"><td>'.$taxes_arr[$i].'</td><td class="txt_right">'.money_format('%!i',$taxes_amt_arr[$i]).'</td></tr>';
        				}
        				$grandtot += $taxtot;
        				$t_flot=$grandtot - round($grandtot);
    				
    				    $content.= '<tr class="item_name"><td>Round Off</td><td class="txt_right">'.money_format('%!i', abs(number_format((float)$t_flot, 2, ".",""))).'</td></tr>';
    				    $content.= '<tr class="item_name"><td>Grand Total</td><td class="txt_right">'.money_format('%!i', round($grandtot)).'</td></tr>';
    				
        				// $content.= '<tr class="item_name"><td>Grand Total</td><td>'.$grandtot.'</td></tr>';
        				
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
        // 			echo $content;
        			echo '$("#print_data").append(\''.$content.'\')';    
    		    } else {
    		        $content = "<style> @media print { .main_foot { display:table-footer-group; }  .main_head { display:table-header-group; }  } .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:1px solid;   } .item_name > td { border: 0.5px solid; height: 20px;} @page {size: A4; size:landscape; }  @media print { #duplicate {/*page-break-before: always;*/ } }  </style> <table style=\"width:100%;margin-bottom:5px;font-family: Calibri, sans-serif;\"><tr>";
    		        for($j=0;$j<count($typearr);$j++) {
    					    $content.= '<td id="'.$typeidarr[$j].'" style="padding-right:25px;padding-left:25px;">';
                			$content.= '<table style="border-collapse:seperate; border-spacing:0px;">';
                			$content.= '<thead class="main_head">';
                			$content.= '<tr>';
                			$content.= '<td colspan="5"><img src="'.$logo_sec.'" style="width: 100%; height: 100px;margin-bottom:5px;"></td>';
                			$content.= '</tr>';
                			$content.= '<tr><td colspan="5"><div style="text-align: center">'.$txn[0]->it_type.' - '.$typearr[$j].'</div></td></tr>';
                			$content.= '<tr>';
                			// CLIENT
                			$content.= '<td colspan="3" style="font-size:0.9em;">Client Name:<b>'.$txn[0]->ic_name.'</b><br>'.$txn[0]->ic_address.'<br>';
    						if($txn[0]->ic_gst_number !== "") $content.= 'GST: '.$txn[0]->ic_gst_number;
    						$content.= '</td>';
                			$content.= '<td colspan="2">';
                            // DATE
                			$content.= '<table style="width: 100%;padding: 10px;">';
                			$content.= '<tr>';
                			$content.= '<td class="col_right"><b>'.$txn[0]->it_type.' No:</b></td>';
    						$content.= '<td class="col_left"><u>#'.$txn[0]->it_txn_no.'</u></td>';
    						$content.= '</tr>';
    						$content.= '<tr>';
    						$content.= '<td class="col_right"><b>Date:</b></td>';
    						$content.= '<td class="col_left"><u>'.date_format(date_create($txn[0]->it_date), 'd/m/Y').'</u></td>';
    						$content.= '</tr>';
    						$content.= '</table>';
    					    $content.= '</td>';
    					    
    					    $content.= '</tr>';
                            if(count($txn_transport) > 0 ) $content.= '<tr><td colspan="5">Delivery through: <u>'.$txn_transport[0]->ittd_transporter.'</u> L/R No.: <u>'.$txn_transport[0]->ittd_lrno.'</u> Date: <u>'.$txn_transport[0]->ittd_date.'</u></td></tr>';
    						$content.= '<tr><td colspan="5">Order Details</td></tr>';
    						$content.= '<tr class="item_header"><th style="border-radius:5px 0px 0px 0px;">Sr. No.</th><th>Particulars</th><th>Qty</th><th>Rate</th><th style="border-radius:0px 5px 0px 0px;">Amount</th></tr>';
    						$content.='</thead>';
    						
    				        // BODY BEGINS
    						$content.='<tbody>';
    						$line_cnt = count($txn_details);
    						if(count($txn_transport) > 0 ) { $line_cnt_total = 13 - $line_cnt; } else { $line_cnt_total = 14 - $line_cnt; }
    				        $amt=0;
    				        for ($i=0; $i < count($txn_details) ; $i++) { 
    				            $amt+=$txn_details[$i]->itp_amount;
    							$content.= '<tr class="item_name"><td style="text-align:center;">'.($i+1).'</td>';
    							if(strlen($txn_details[$i]->ip_name) > 32) {
    							    $content.= '<td style="font-size:0.8em;">'.$txn_details[$i]->ip_name.'</td>';
    							} else {
    							    $content.= '<td>'.$txn_details[$i]->ip_name.'</td>';
    							}
    							$content.='<td class="detail_center" style="text-align:center;">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right" style="text-align:right;">'.$txn_details[$i]->itp_amount.'</td></tr>';
    						}
    						for ($i=0; $i < $line_cnt_total; $i++) {
    							$content.= '<tr class="item_name"><td> </td><td></td><td class="detail_center" style="text-align:center;"> </td><td class="detail_right" style="text-align:right;"></td><td class="detail_right" style="text-align:right;"></td></tr>';
    						}
    						$totamt=0;$transamt=0;
    						$content.= '</tbody>';
    						
    						// FOOTER BEGINS
    						$content.= '<tfoot class="main_foot">';
    						$content.= '<tr class="item_name"><td colspan="5">Receivers Signature</td></tr>';
    						$content.= '<tr class="item_name"><td colspan="5">Payment within: '.$txn[0]->ic_credit.' days</td></tr>';
    						$content.= '<tr class="item_name"><td colspan="3" rowspan="';
    						if(count($txn_transport) > 0 ) { $content.= '3'; } else { $content .= '2'; }
    						$content.= '" style="border-radius:0px 0px 0px 5px;">Note: '.$txn[0]->it_note.'</td><td>Subtotal</td><td>'.$amt.'</td></tr>';
    						if(count($txn_transport) > 0 ) { $content.= '<tr class="item_name"><td>Transport</td><td>'.$txn_transport[(count($txn_transport)-1)]->ie_amount.'</td></tr>' ; $transamt = $txn_transport[(count($txn_transport)-1)]->ie_amount; } 
    						$totamt = $transamt + $amt;
    						$content.= '<tr class="item_name"><td>Grand Total</td><td style="border-radius:0px 0px 5px 0px;">'.$totamt.'</td></tr>';
    						$content.= '<tr>';
    						$content.= '<td colspan="3"><div style="font-size:0.7em;font-weight:bold;">TERMS & CONDITIONS</div><div style="font-size:0.5em; line-height:1em;">1. Subject to mumbai Jurisdiction. 2. Goods supplied on order will not be accepted back. 3. Payment terms - Immidiate. Intrest @ 18% per annum will be charged on delayed payments. 4. Warranty/Service of purchased goods is the manufacturers/ importers responsibility under the warranty period. 5. I the undersigned have accepted the terms and conditions of the invoice. 6. O M Prime Ventures shall not be responsible for any expenses involving legal costs in case of a dispute 7. Received goods in order and good condition.</div><div style="font-size:0.6em;">E.&.O.E.</div></td>';
    						$content.= '<td colspan="2"><div style="padding-top: 3em;width:90%;border-bottom: 1px solid #000;margin:0px;"></div><div style="text-align: right; margin:0px;"><b style="padding-top: 0px; font-size:0.9em;">Proprietor/ Authorized Signature</b></div></td>';
    						$content.= '</tr>';
    						$content.= '</tfoot>';
    						$content.= '</table>';
                		    $content.= '</td>';
    					}
                	$content.= "</tr></table>";
                	echo '$("#print_data").append(\''.$content.'\')'; 
    		    }
			
		    }
		?>

        $('#i_txn_type_sec').change(function(e) {
            e.preventDefault();
            <?php if(!isset($txn_details)) echo "get_invoice_num($('#i_txn_type').val(), $(this).val());"; ?>
        })
        
        <?php if(isset($txn_details)) for ($i=0; $i < count($txn_details) ; $i++) { 
			echo "product_name.push('".$txn_details[$i]->ip_name."');";
			echo "product_alias.push('".$txn_details[$i]->itp_alias."');";
			echo "product_qty.push('".$txn_details[$i]->itp_qty."');";
			echo "product_rate.push('".$txn_details[$i]->itp_rate."');";
			echo "product_amount.push('".$txn_details[$i]->itp_value."');";
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

			$('#products').append(product_name[id]);
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
				out+='</td></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td><td>";
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


		$('#prod_qty').keypress(function(e) {
			if (e.keyCode == 13) {
				if(edit_flag == true) {
					update_product(edit_index);
				} else {
					add_product();
				}
				
				update_list();
				reset_fields();
			}
		});

		$('#prod_rate').keypress(function(e) {
			if (e.keyCode == 13) {
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

        $('#i_txn_ref_no').on('change','.txn_data', function(e) {
            get_txn_items($(this).val());
        })

		$('#submit').click(function(e) {
			e.preventDefault();
			
            var ert = {message: "Please wait.",timeout: 1000, }
            snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            
			var alias_arr = [];
			$("input[name^='alias']").each(function(){
			    if($(this)[0].checked == true) {
			        alias_arr.push(1);
			    } else {
			        alias_arr.push(0);
			    }
				
			});
			
			var txn_arr=[];
			$(".txn_data").each(function(){
			    if($(this)[0].checked == true) {
			        txn_arr.push($(this).val());
			    }
			});
			
			$.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/update_cd_note/".$tid; } else { echo base_url().$type."/Transactions/save_cd_note/"; } ?>', {
			 'type': $('#i_txn_type').val(),'type_sec': $('#i_txn_type_sec').val(), 'vendor': $('#vendors').val(), 'txn_link' : txn_arr, 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'note' : $('#i_txn_note').val(), 'name' : product_name, 'qty' : product_qty, 'rate' : product_rate, 'amt' : product_amount, 'tax_group' : product_tax, 'alias' : alias_arr, 'discount' : $('#i_txn_discount').val(), 'freight' : $('#i_txn_freight').val(), 'credit' : $('#i_txn_credit').val()
			 }, function(d,s,x) {
			     var ert = {message: "Note Saved.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                setTimeout(function() {
                    window.location = "<?php if(isset($txn)) { echo base_url().$type.'/Transactions/cd_note_edit/'; } else { echo base_url().$type.'/Transactions/cd_note_edit/'; } ?>" + d;    
                }, 1000);
			 }, "text").fail(function(r) {
                var ert = {message: "Please try again.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            });

		});
		
		$('#i_txn_type').change(function(e) {
		    e.preventDefault();
		    get_txns($(this).val(), $('#vendors').val());
		});

        $('#print').click(function(e) {
			e.preventDefault();

			print_reciept();
		});

        $('#txn_delete').click(function(e) {
		    e.preventDefault();
		    $.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/delete_order/"; } ?>', { 'txnid' : '<?php if(isset($txn)) echo $tid; ?>' }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/cd_note"; ?>'; }, "text");
		    
		});
		
		function get_txn_items(txnid) {
    	    $.post('<?php echo base_url().$type."/Transactions/get_txn_items/"; ?>', {
    	        't' : txnid
    	    }, function(d,s,x) {
    	        var a=JSON.parse(d);
    	        for(var i=0;i<a.length;i++) {
    	            product_name.push(a[i].ip_name);
        	        product_alias.push(a[i].itp_alias);
        	        product_qty.push(a[i].itp_qty);
        	        product_rate.push(a[i].itp_rate);
        	        product_amount.push(a[i].itp_value);
        	        product_tax.push(a[i].itp_tax_group_id);    
    	        }
    	        update_list();
    	    });
    	}

	});
	
    function get_txns(type, contact) {
        if(type == "Credit Note") {
	        var type = "cn";
	    } else {
	        var type = "dn";
	    }
	    
        $.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/cd_note_get_txns/".$tid; } else { echo base_url().$type."/Transactions/cd_note_get_txns/"; } ?>', { 'type' : type, 'for' : $('#i_txn_type_sec').val(), 'contact' : contact }, function(d,s,x) { 
            var a=JSON.parse(d); $('#i_contact_txn').empty(); var t=""; 
            $('#i_txn_ref_no').empty(); var x="";
            for(var i=0;i<a.main.length;i++) {
                x+='<li><input type="checkbox" class="txn_data" value="' + a.main[i].it_id + '"';
                for(var j=0;j<a.select.length;j++) {
                    if(a.select[j].itpl_txn_id == a.main[i].it_id) {
                        x+=' checked';
                        break;
                    }
                }
                x+='>' + a.main[i].it_txn_no + ' Date: ' + a.main[i].it_date + '</li>';
            }
            $('#i_txn_ref_no').append(x);
        })
            
            
    }
    
    function get_invoice_num(t, f) {
        if(t == "Credit Note") {
	        var type = "CN";
	    } else {
	        var type = "DN";
	    }
	    
        $.post('<?php echo base_url().$type."/Transactions/cd_note_get_txn_num"; ?>', { 't' : type, 'f' : f }, function(d,s,x) {
           $('#i_txn_no').empty(); $('#i_txn_no').val(d);
        }).fail(function(r) {
            var ert = {message: "Please wait.",timeout: 1000, }; 
            snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            get_invoice_num(t,f);
        });
    }
    
	function print_reciept() {
		var mywindow = window.open('', '<?php echo $title_doc; ?>', fullscreen=1);
		<?php if(isset($txn)) { echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; } ?>
	}
</script>
<div>
<?php //echo $content; ?>
</div>
</html>