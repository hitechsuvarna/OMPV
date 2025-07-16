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
		color: #000;
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
					<h2 class="mdl-card__title-text">Order Details</h2>
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
						<input type="text" id="i_txn_no" class="mdl-textfield__input" value="<?php if(isset($orders)) { echo $orders; } ?>">
						<label class="mdl-textfield__label" for="i_txn_no">Enter Order Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<textarea id="i_txn_note" class="mdl-textfield__input"><?php if(isset($txn)) { echo $txn[0]->it_inv_note; } ?></textarea>
						<label class="mdl-textfield__label" for="i_txn_note">Order Note</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					    <b>Enter Mode of Order</b>
						<ul id="i_txn_mode" class="mdl-textfield__input">
							<?php if (isset($txn)) {
									for ($j=0; $j < count($vendors) ; $j++) { 
										if ($vendors[$j]->ic_id == $txn[0]->it_c_id) {
											echo "<li>".$vendors[$j]->ic_name."</li>";
											break;
										}
									}
								}
							?>
						</ul>
					</div>
					<button class="mdl-button" id="product_add"><i class="material-icons">add</i> Products</button>
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
					<h2 class="mdl-card__title-text">Order Items</h2>
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
        						<input type="text" id="prod_inventory" class="mdl-textfield__input" value="0" readonly disabled>
        						<label class="mdl-textfield__label" for="prod_inventory">Available</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_qty" class="mdl-textfield__input" value="<?php if(isset($txn)) echo $txn[0]->ic_name; ?>">
        						<label class="mdl-textfield__label" for="prod_qty">Qty</label>
        					</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col">
						    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
        						<input type="text" id="prod_rate" class="mdl-textfield__input" value="0">
        						<label class="mdl-textfield__label" for="prod_rate">Rate</label>
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
									<th>Rate</th>
									<th>Amount</th>
									<!--<th>Taxes</th>-->
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
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
<dialog class="mdl-dialog">
    <h4 class="mdl-dialog__title">Add Product</h4>
    <div class="mdl-dialog__content">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="p_m_name">
            <label class="mdl-textfield__label" for="p_m_name">Product Name</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="p_m_hsn">
            <label class="mdl-textfield__label" for="p_m_hsn">HSN Code</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="p_m_alias">
            <label class="mdl-textfield__label" for="p_m_alias">Alias</label>
        </div>
    </div>
    <div class="mdl-dialog__actions">
        <button class="mdl-button close">Close</button>
        <button class="mdl-button mdl-button--raised mdl-button--colored" id="p_m_submit"><i class="material-icons">done</i> Save Product</button>
    </div>
</dialog>
</body>

<script type="text/javascript">
	var product_name = [];
	var product_qty = [];
	var product_rate = [];
	var product_amount = [];
	var snackbarContainer = document.querySelector('#demo-snackbar-example');
	var dialog = document.querySelector('dialog');
    var showDialogButton = document.querySelector('#product_add');
    
    if (! dialog.showModal) {
      dialogPolyfill.registerDialog(dialog);
    }
    showDialogButton.addEventListener('click', function() {
      dialog.showModal();
    });
    dialog.querySelector('.close').addEventListener('click', function() {
      dialog.close();
    });
    
    
    
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
	var product_exist = 'false';
	var dealer_exist = 'false';
	var edit_index = 0;
	var edit_flag = false;
    var product_data = [];
    
	$(document).ready( function() {
	   // setTimeout(function() {
	   //     var ert = {message: 'Please Wait...', timeout: '2000'};
    //         snackbarContainer.MaterialSnackbar.showSnackbar(ert);    
	   // }, 2000)
	    
        
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
        
    	var mode_data = [];
    	
    	<?php
    		for ($i=0; $i < count($modes); $i++) { 
    			echo "mode_data.push('".$modes[$i]->it_mode."');";
    		}
    	?>
    	$('#i_txn_mode').tagit({
    		autocomplete : { delay: 0, minLenght: 10},
    		allowSpaces : true,
    		availableTags : mode_data,
    		tagLimit : 1,
    		singleField : true
    	});

    	
    // 	load_products()
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
    	
    	$('#p_m_submit').click(function(e) {
    	    e.preventDefault();
    	    $.post('<?php echo base_url().$type."/Products/save_product"; ?>', {
    			'product' : $('#p_m_name').val(),
    			'alias' : $('#p_m_alias').val(),
    			'hsn' : $('#p_m_hsn').val(),
    		}, function(data, status, xhr) {
    		    dialog.close();
                var data = {message: 'Product Added. Please Wait', timeout: '2000'};
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
                
    			load_products();
    		}, 'text');
    	})
    	
        $('#products').change(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                product_exist=d;
            })
            getinventory($(this).val());
            getrates($(this).val(), $('#vendors').val());
        });
        
    });
    
    
    function getinventory(p) {
        $.post("<?php echo base_url().$type.'/Transactions/order_get_inventory'; ?>", { 'p' : p }, function(d,s,x) { $('#prod_inventory').val(d); }, "text");
    }
    
    function getrates(p, de) {
        $.post("<?php echo base_url().$type.'/Transactions/order_get_rates'; ?>", { 'p' : p, 'd' : de}, function(d,s,x) { $('#prod_rate').val(d); }, "text");
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
		<?php if(isset($txn_details)) for ($i=0; $i < count($txn_details) ; $i++) { 
			echo "product_name.push('".$txn_details[$i]->ip_name."');";
			echo "product_qty.push('".$txn_details[$i]->itp_qty."');";
			echo "product_rate.push('".$txn_details[$i]->itp_rate."');";
			echo "product_amount.push('".$txn_details[$i]->itp_value."');";
// 			echo "product_tax.push('".$txn_details[$i]->itp_tax_group_id."');";
		} echo "update_list();"; ?>
		

		function add_product() {
		    if(product_exist=='true') {
    	        product_name.push($('#products').val());
        		var tmp_rt = $('#prod_rate').val();
        		var tmp_qt = $('#prod_qty').val();
        // 		var tmp_tx = $('#prod_tax').val();
        		var tmp_amt = tmp_qt * tmp_rt;
        		product_rate.push(tmp_rt);
        		product_qty.push(tmp_qt);
        		product_amount.push(tmp_amt);
        // 		product_tax.push($('#prod_tax').val());
		    } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
		}

		function remove_product(id) {
			product_name.splice(id, 1);
			product_qty.splice(id, 1);
			product_rate.splice(id, 1);
			product_amount.splice(id, 1);
 	  //  	product_tax.splice(id, 1);
		}

		function edit_product(id) {
			$('#products').val(product_name[id]);
    		$('#prod_qty').val(product_qty[id]);
    		$('#prod_rate').val(product_rate[id]);
    // 		$('#prod_tax').val(product_tax[id]);
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
        // 		product_tax[id] = $('#prod_tax').val();
                edit_flag=false;
		    } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
		}

		function update_list() {
			$('.purchase_table > tbody').empty();

			var out = "";
			for (var i = 0; i < product_name.length; i++) {
				// out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td><td>";
				out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_rate[i] + "</td><td>" + product_amount[i] + "</td>";
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
    		$('#prod_rate').val("");
            // $('#prod_tax').val("");
    		$('#products').focus();
		} 


		$('#prod_qty').keypress(function(e) {
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
			if(dealer_exist=='true') {
			    $(this).attr('disabled','disabled');
    			
    			$.post('<?php if(isset($txn)) { echo base_url().$type."/Transactions/update_order/".$tid; } else { echo base_url().$type."/Transactions/save_order/"; } ?>', {
    			 'vendor': $('#vendors').val(), 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'note' : $('#i_txn_note').val(), 'mode' : $('#i_txn_mode')[0].innerText, 'name' : product_name, 'qty' : product_qty, 'rate' : product_rate, 'amt' : product_amount, /*'tax_group' : product_tax, 'discount' : $('#i_txn_discount').val(), 'freight' : $('#i_txn_freight').val(), 'credit' : $('#i_txn_credit').val()*/
    			 }, function(d,s,x) {
    			 	window.location = "<?php echo base_url().$type.'/Transactions/orders'; ?>";
    			 }, "text");   
			} else {
			    var data = {message: 'Dealer doesnot Exist. Kindly check.', timeout: '2000'};
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
			}
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