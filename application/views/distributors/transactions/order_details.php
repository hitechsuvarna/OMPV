<style type="text/css">
	.cart_table {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
	}

	.cart_table > tr {
		width: 100%;
	}

	.cart_table > tr > td {
		border-bottom: 1px solid #999;
		border-top: 1px solid #999;
		display: flex;
		padding: 10px;
		width: 100%;
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

	.product_rate, .app_qty, .req_qty {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }
    
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
</style>

<main class="mdl-layout__content">

	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--12-col" style="background-color: #ccc; border-radius: 5px; padding: 10px; box-shadow: 1px 3px 5px #999;">
			<div class="mdl-grid">
				<div class="mdl-cell--12-col" style="background-color: #fff; box-shadow: 1px 3px 5px #999 inset;padding: 10px;">
					<table>
						<tr>
							<td style="width: 100%;">
								<h4><?php echo $txn[0]->ic_name; ?></h4>
								<h5>Total Amount: <span id="total_amount">Rs.<?php echo $txn[0]->it_amount; ?>/-</span></h5>
								<i>Order status: <?php echo $txn[0]->it_status; ?></i><br>
								<b><?php echo $txn[0]->it_mode; ?></b>
								<?php if(isset($subuser)) echo '<i>Order placed by sub user:'.$subuser[0]->idu_name.'</i>'; ?>
							</td>
							<td>
								<h6>Outstanding Payment: Rs.<?php print_r($outstanding[0]->amount); ?>/-</h6>
								<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
									<input type="text" data-type="date" id="i_txn_date" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->it_date; } ?>">
									<label class="mdl-textfield__label" for="i_txn_date">Estimated Delivery Date for pending items</label>
								</div>
								<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="txn_delete">
								    Delete Order
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="mdl-grid" >
		<h5 style="font-weight: bold; width: 100%; margin: 10px;">Order Summary</h5>
		<table class="purchase_table">
		    <thead>
		        <tr>
		            <th>Product</th>
		            <th>Available</th>
		            <th>Requested</th>
		            <th>Approve</th>
		            <th>Rate</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		    <tbody>
		       <?php $c=""; for ($i=0; $i < count($txn_details) ; $i++) { 
		            $c.= '<tr><td>'.$txn_details[$i]->ip_name.'</td><td>'.$txn_product_balance[$i].'</td>';
		            if($txn[0]->it_status == "approved") {
		                $c.= '<td><input type="text" id="req'.$txn_details[$i]->itd_id.'" class="req_qty" name="p_request_qty" value=""></td><td><input type="text" id="txt'.$txn_details[$i]->itd_id.'" class="app_qty" name="p_approved_qty" value="'.$txn_details[$i]->itp_qty.'"></td><td><input type="text" id="rate'.$txn_details[$i]->itd_id.'" class="product_rate" name="p_rate" value="'.$txn_details[$i]->itp_rate.'"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_add" id="'.$txn_details[$i]->itd_id.'"><i class="material-icons">update</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="'.$txn_details[$i]->itd_id.'"><i class="material-icons">delete</i></button></td></tr>';
		            } else {
		                $c.= '<td><input type="text" id="req'.$txn_details[$i]->itd_id.'" class="req_qty" name="p_request_qty" value="'.$txn_details[$i]->itp_qty.'"></td><td><input type="text" id="txt'.$txn_details[$i]->itd_id.'" class="app_qty" name="p_approved_qty" value=""></td><td><input type="text" id="rate'.$txn_details[$i]->itd_id.'" class="product_rate" name="p_rate" value="'.$txn_details[$i]->itp_rate.'"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="'.$txn_details[$i]->itd_id.'"><i class="material-icons">delete</i></button></td></tr>';
		            }
		            $c.= ''; 
		            } echo $c; ?> 
		    </tbody>
		</table>
		
		
		<?php 
		  //  for ($i=0; $i < count($txn_details) ; $i++) {
    // 			if ($txn[0]->it_status !== "approved") {
    // 				echo '<div class="mdl-cell mdl-cell--4-col"><div class="mdl-card mdl-shadow--4dp"><div class="mdl-card__title" style="background:  linear-gradient(rgba(20,20,20,.5), rgba(20,20,20, .5)), url('.base_url()."assets/uploads/".$oid."/".$txn_details[$i]->ip_id."/".$txn_details[$i]->ip_image.'); background-size:cover;height: 150px;"><h2 class="mdl-card__title-text">'.$txn_details[$i]->ip_name.'</h2></div> <div class="mdl-card__supporting-text" style="text-align: left;"> <div class="mdl-grid"> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_rate" name="p_rate" class="mdl-textfield__input" value="'.$txn_details[$i]->itp_rate.'"> <label class="mdl-textfield__label" for="p_rate">Rate</label> </div> </div> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_request_qty" name="p_request_qty" class="mdl-textfield__input" value="'.$txn_details[$i]->itp_qty.'"> <label class="mdl-textfield__label" for="p_request_qty">Requested Qty</label> </div> </div> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_available_qty" name="p_available_qty" class="mdl-textfield__input" value="'.$txn_product_balance[$i].'"> <label class="mdl-textfield__label" for="p_available_qty">Available Qty</label> </div> </div> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="'.$txn_details[$i]->itd_id.'" name="p_approved_qty" class="mdl-textfield__input" value=""> <label class="mdl-textfield__label" for="'.$txn_details[$i]->itd_id.'">Approved Qty</label> </div> </div> </div> </div> </div> </div>'; 			
    // 			} else {
    // 				echo '<div class="mdl-cell mdl-cell--4-col"><div class="mdl-card mdl-shadow--4dp"><div class="mdl-card__title" style="background:  linear-gradient(rgba(20,20,20,.5), rgba(20,20,20, .5)), url('.base_url()."assets/uploads/".$oid."/".$txn_details[$i]->ip_id."/".$txn_details[$i]->ip_image.'); background-size:cover;height: 150px;"><h2 class="mdl-card__title-text">'.$txn_details[$i]->ip_name.'</h2></div> <div class="mdl-card__supporting-text" style="text-align: left;"> <div class="mdl-grid"> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_rate" name="p_rate" class="mdl-textfield__input" value="'.$txn_details[$i]->itp_rate.'"> <label class="mdl-textfield__label" for="p_rate">Rate</label> </div> </div> <div class="mdl-cell mdl-cell--6-col">  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="'.$txn_details[$i]->itd_id.'" name="p_approved_qty" class="mdl-textfield__input" value="'.$txn_details[$i]->itp_qty.'"> <label class="mdl-textfield__label" for="'.$txn_details[$i]->itd_id.'">Approved Qty</label> </div> </div> <div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_available_qty" name="p_available_qty" class="mdl-textfield__input" value="'.$txn_product_balance[$i].'"> <label class="mdl-textfield__label" for="p_available_qty">Available Qty</label> </div> </div> <div class="mdl-cell mdl-cell--6-col"> </div> </div> </div> </div> </div>'; 
    // 			}
    // 		} 
    	?>
		<?php if($txn[0]->it_status !== "approved") echo '<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit"><i class="material-icons">done</i></button>'; ?>
		
	</div>
	<div class="mdl-grid">
	    <div class="mdl-cell mdl-cell--12-col">
	        <h4>Add Products</h4>
	    </div>
	    <div class="mdl-cell mdl-cell--4-col">
	        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
				<input type="text" id="products" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="products">Search Products</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--2-col">
	        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
				<input type="text" id="prod_qty" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="prod_qty">Qty</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--2-col">
	        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
				<input type="text" id="prod_rate" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="prod_rate">Rate</label>
			</div>
	    </div>
	    <div class="mdl-cell mdl-cell--2-col">
	        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item"><i class="material-icons">add</i></button>
	    </div>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
</body>
<script>
    var product_data = [];
    var product_exist = 'false';
	
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
        
    $(document).ready(function() {
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
        
        $('#add_item').click(function(e) {
            e.preventDefault();
            
            if(product_exist=='true') {
                $.post('<?php echo base_url().$type."/Transactions/add_delivery_qty/order"; ?>', {
                    'tx' : <?php echo $txnid; ?>, 'p' : $('#products').val(), 'q' : $('#prod_qty').val(), 'r' : $('#prod_rate').val() 
                }, function(d,s,x) { 
                    var a=JSON.parse(d); 
                    load_products(a.t); 
                    var ert = {message: 'Product Added.',timeout: 2000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);  
                    $('#total_amount').empty(); 
                    $('#total_amount').append("Rs."+a.tot+"/-");
                    $('#products').val('');
                    $('#prod_qty').val('');
                    $('#prod_rate').val('');
                },"text");
            } else {
                var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            }
        });
        
        $('.purchase_table').on('click','.qty_add', function(e) {
            e.preventDefault();
            
            var tid=$(this).prop('id');
            var a = "#txt" + tid;
            var qty = $(a).val();
            a="#rate" + tid;
            var rate = $(a).val();
            
            var snackbarContainer = document.querySelector('#demo-snackbar-example');
            $.post('<?php echo base_url().$type."/Transactions/update_delivery_qty/order"; ?>', {
                'tx' : <?php echo $txnid; ?>, 't' : tid, 'q' : qty, 'r' : rate 
            }, function(d,s,x) { 
                var ert = {message: 'Qty Updated.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
                $('#total_amount').empty(); 
                $('#total_amount').append("Rs."+d+"/-"); 
            }, "text");
        });
	    
	    $('.purchase_table').on('click','.qty_delete', function(e) {
            e.preventDefault();
            
            var tid=$(this).prop('id');
            
            $.post('<?php echo base_url().$type."/Transactions/delete_delivery_qty/order"; ?>', { 
                'tx': <?php echo $txnid; ?>, 't' : tid 
            }, function(d,s,x) { 
                var a=JSON.parse(d); 
                load_products(a.t); 
                var ert = {message: 'Qty Updated.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
                $('#total_amount').empty(); 
                $('#total_amount').append("Rs."+a.tot+"/-");
            }, "text");
        });
	    
        
    }); 
    
    function load_products(a) {
        var b="";
        for(var i=0;i<a.length;i++) { 
            b+='<tr><td>' + a[i].ip_name + '</td><td></td>';
            <?php if($txn[0]->it_status == "approved") {
                echo 'b+=\'<td><input type="text" id="req\' + a[i].itd_id + \'" class="req_qty" value=""></td><td><input type="text" id="txt\' + a[i].itd_id + \'" class="app_qty" value="\' + a[i].itp_qty + \'"></td><td><input type="text" id="rate\' + a[i].itd_id + \'" class="product_rate" value="\' + a[i].itp_rate + \'"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_add" id="\' + a[i].itd_id + \'"><i class="material-icons">update</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="\' + a[i].itd_id + \'"><i class="material-icons">delete</i></button></td></tr>\';';
            } else {
                echo 'b+=\'<td><input type="text" id="req\' + a[i].itd_id + \'" class="req_qty" value="\' + a[i].itp_qty + \'"></td><td><input type="text" id="txt\' + a[i].itd_id + \'" class="app_qty" value=""></td><td><input type="text" id="rate\' + a[i].itd_id + \'" class="product_rate" value="\' + a[i].itp_rate + \'"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="\' + a[i].itd_id + \'"><i class="material-icons">delete</i></button></td></tr>\';';
            } ?>
        }
        $('.purchase_table > tbody').empty(); $('.purchase_table > tbody').append(b);
    }
    
    // OLD SECTION
    
	var txnid = <?php echo $txnid; ?>;
	var txnrow = [];
	var txnapprove = []; var txnrate=[]; var txnrequest = []; var txnprod = [];
	<?php for ($i=0; $i < count($txn_details) ; $i++) { echo "txnprod.push('".$txn_details[$i]->ip_id."');"; } ?>
	$(document).ready(function() {
		$('#i_txn_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
		
		<?php 
			if(!isset($edit_txn)) {
				echo "var dt = new Date();";
				echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				
				echo "$('#i_txn_date').val(s_dt);";
			}
		?>
		$('#submit').click(function(e) {
			e.preventDefault();
			$(this).attr('disabled','disabled');

			$('input[name="p_approved_qty"]').each(function(i) {
			    var abc=$(this).prop('id');
				txnrow[i] = abc.substring(3, abc.length);
				txnapprove[i] = $(this).val();
			});

			$('input[name="p_rate"]').each(function(i) {
				txnrate[i] = $(this).val();
			});

			$('input[name="p_request_qty"]').each(function(i) {
				txnrequest[i] = $(this).val();
			});

			$.post('<?php echo base_url().$type."/Transactions/approve_order"; ?>', { 'cid' : '<?php echo $cid; ?>', 'txnid' : txnid, 'txnrow' : txnrow, 'txnapprove' : txnapprove, 'txnrate' : txnrate, 'txnrequest' : txnrequest, 'txnprod' : txnprod, 'est_pending' : $('#i_txn_date').val() }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/orders"; ?>'; }, "text");
			
		});
		
		$('#txn_delete').click(function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/delete_order/"; ?>', { 'txnid' : <?php echo $txnid; ?> }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/orders"; ?>'; }, "text");
		    
		});

	});
</script>
</html>