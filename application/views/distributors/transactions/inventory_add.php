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
</style>


<main class="mdl-layout__content" style="display: non;">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Inventory Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="vendors" class="mdl-textfield__input" value="<?php if(isset($txn)) echo $txn[0]->ic_name; ?>">
						<label class="mdl-textfield__label" for="vendors">Vendor</label>
					</div>
					
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="i_txn_date" class="mdl-textfield__input" value="<?php if(isset($txn)) { echo $txn[0]->it_date; } ?>">
						<label class="mdl-textfield__label" for="i_txn_date">Select Order Date</label>
					</div>
					
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_txn_no" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="i_txn_no">Enter Transaction Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<textarea id="i_txn_note" class="mdl-textfield__input"><?php if(isset($txn)) { echo $txn[0]->it_inv_note; } ?></textarea>
						<label class="mdl-textfield__label" for="i_txn_note">Order Note</label>
					</div>
					<!--<div>
    					<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="txn_delete">
    					    Delete Invoice
    					</button>
    				</div>-->
				</div>

			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--8-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Inventory Items</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div id="info_repea" class="mdl-grid">
						<div class="mdl-cel mdl-cell--6-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="products" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="products">Search Products</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_qty" class="mdl-textfield__input" value="<?php if(isset($txn)) echo $txn[0]->ic_name; ?>">
        						<label class="mdl-textfield__label" for="prod_qty">Qty</label>
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
									<th>Product</th>
									<th>Qty</th>
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

</body>
<script type="text/javascript">
	var product_name = [];
	var product_qty = [];
// 	var product_tax = [];
	
// 	var taxes = [];
	<?php 
	   // for($i=0; $i<count($taxes); $i++){
	   //     echo 'taxes.push({ id: "'.$taxes[$i]->itx_id.'", name: "'.$taxes[$i]->itx_name.'", percent: "'.$taxes[$i]->itx_percent.'" });';
	   // }
	?>
// 	var tax_groups = [];
	<?php 
	   // for($i=0; $i<count($tax_groups); $i++){
	   //     echo 'tax_groups.push({ id: "'.$tax_groups[$i]->ittxg_id.'", name: "'.$tax_groups[$i]->ittxg_group_name.'" });';
	   // }
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
            source: product_data
        });
        
        
    // 	$('#products').tagit({
    // 		autocomplete : { delay: 0, minLenght: 5},
    // 		allowSpaces : true,
    // 		availableTags : product_data,
    // 		allowDuplicates: true,
    // 		tagLimit : 1,
    // 		singleField : true,
    // 		afterTagAdded : (function(event, ui) {
    // 			getrates(ui.tag[0].innerText, $('#vendors').val());
    // 		})
    // 	});
    });
    
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
		
		    /*echo $logo;
			$typearr = ["Original","Duplicate"]; 
			$typeidarr = ["original", "duplicate"];
			$content = "<style> .col_right {text-align: right; padding-right: 10px; } .col_left {text-align: left; width: 30%; } .item_header > th {font-weight: bold; border:0.5px solid; } .item_name > td { border: 0.5px solid;} @page {size: A4; size:potrait; } @media print { #duplicate { page-break-before: always; } } </style> <!--<table style=\"width:100%;\"><tr> -->";
			for ($j=0; $j < count($typearr); $j++) { 
				// $content.= '<td>';
				$content.= '<div style="border: 0px #999 solid;padding: 20px;" id="'.$typeidarr[$j].'">';
				$content.= '<div style="border: 1px #000 solid;">';
				$content.= '<img src="'.$logo.'" style="width: 98%; height: auto;margin-bottom:5px;padding:5px" alt="Logo" />';
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
				for ($i=0; $i < count($txn_details) ; $i++) { 
				    $subtot = $subtot + $txn_details[$i]->itp_value;
					$content.= '<tr class="item_name"><td>'.($i+1).'</td><td>'.$txn_details[$i]->ip_name.'</td><td>'.$txn_details[$i]->ip_hsn_code.'</td><td class="detail_center">'.$txn_details[$i]->ip_unit.'</td><td class="detail_center">'.$txn_details[$i]->itp_qty.'</td><td class="detail_right">'.$txn_details[$i]->itp_rate.'</td><td class="detail_right">'.$txn_details[$i]->itp_value.'</td></tr>';
				}

				for ($i=0; $i < count($taxes); $i++) { 
					$amt = 0;
					for ($ik=0; $ik < count($txn_prod_tax); $ik++) { 
						if ($taxes[$i]->itx_id == $txn_prod_tax[$ik]->itpt_tx_id ) {
							if ($taxes_flg == false) {
								array_push($taxes_arr, $txn_prod_tax[$ik]->itpt_t_name);
								$taxes_flg = true;
							}
							$amt+=$txn_prod_tax[$ik]->itpt_t_amount;
						}
					}
					array_push($taxes_amt_arr, $amt);
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
			}*/
			// $content.= "</tr></table>";
			/*echo '$("#print_data").append(\''.$content.'\')';	*/
		?>

		<?php if(isset($txn_details)) for ($i=0; $i < count($txn_details) ; $i++) { 
			echo "product_name.push('".$txn_details[$i]->ip_name."');";
			echo "product_qty.push('".$txn_details[$i]->itp_qty."');";
// 			echo "product_rate.push('".$txn_details[$i]->itp_rate."');";
// 			echo "product_amount.push('".$txn_details[$i]->itp_value."');";
// 			echo "product_tax.push('".$txn_details[$i]->itp_tax_group_id."');";
		} echo "update_list();"; ?>
		

		function add_product() {
			product_name.push($('#products').val());
    // 		var tmp_rt = $('#prod_rate').val();
    		var tmp_qt = $('#prod_qty').val();
    // 		var tmp_tx = $('#prod_tax').val();
    // 		var tmp_amt = tmp_qt * tmp_rt;
    // 		product_rate.push(tmp_rt);
    		product_qty.push(tmp_qt);
    // 		product_amount.push(tmp_amt);
    // 		product_tax.push($('#prod_tax').val());
		}

		function remove_product(id) {
			product_name.splice(id, 1);
			product_qty.splice(id, 1);
// 			product_rate.splice(id, 1);
// 			product_amount.splice(id, 1);
 	  //  	product_tax.splice(id, 1);
		}

		function edit_product(id) {

			$('#products').val(product_name[id]);
    		$('#prod_qty').val(product_qty[id]);
    // 		$('#prod_rate').val(product_rate[id]);
    // 		$('#prod_tax').val(product_tax[id]);
    		edit_index = id;
    		edit_flag = true;
		}

		function update_product(id) {
			product_name[id] = $('#products').val();
    // 		var tmp_rt = $('#prod_rate').val();
    		var tmp_qt = $('#prod_qty').val();
    // 		var tmp_amt = tmp_qt * tmp_rt;
            
    // 		product_rate[id] = tmp_rt;
    		product_qty[id] = tmp_qt;
    // 		product_amount[id] = tmp_amt;
    // 		product_tax[id] = $('#prod_tax').val();
    		
		}

		function update_list() {
			$('.purchase_table > tbody').empty();

			var out = "";
			for (var i = 0; i < product_name.length; i++) {
				// out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td><td>";
				out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td>"; //<td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td>";
				// for(var j=0; j<tax_groups.length; j++) {
				//     if(product_tax[i] == tax_groups[j].id) {
				//         out += tax_groups[j].name;
				//     }
				// }
				// out+= "</td></tr>";
				out+= "</tr>";
			}

			$('.purchase_table > tbody').append(out);
		}

		function reset_fields() {
			$('#products').val("");
    		$('#prod_qty').val("");
    // 		$('#prod_rate').val("");
            // $('#prod_tax').val("");
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


		$('#submit').click(function(e) {
			e.preventDefault();
			$(this).attr('disabled','disabled');
			$.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/update_inventory/".$tid; } else { echo base_url().$type."/Transactions/save_inventory/"; } ?>', {
			 'vendor': $('#vendors').val(), 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'note' : $('#i_txn_note').val(), /*'mode' : $('#i_txn_mode')[0].innerText,*/ 'name' : product_name, 'qty' : product_qty, /* 'rate' : product_rate, 'amt' : product_amount, 'tax_group' : product_tax, 'discount' : $('#i_txn_discount').val(), 'freight' : $('#i_txn_freight').val(), 'credit' : $('#i_txn_credit').val()*/
			 }, function(d,s,x) {
			 	window.location = "<?php echo base_url().$type.'/Transactions/inventory'; ?>";
			 }, "text");

		});

		$('#print').click(function(e) {
			e.preventDefault();

			print_reciept();
		});

        $('#txn_delete').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/delete_order/"; ?>', { 'txnid' : <?php echo 0; ?> }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/orders"; ?>'; }, "text");
		    
		});
	});

	function print_reciept() {
// 		var mywindow = window.open('', '<?php #echo $title_doc; ?>', fullscreen=1);
		<?php #echo 'mywindow.document.write(\''.$content.'\'); mywindow.document.close(); mywindow.focus(); mywindow.print(); mywindow.close();'; ?>
	}
</script>
<div>
<?php //echo $content; ?>
</div>
</html>