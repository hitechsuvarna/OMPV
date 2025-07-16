<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

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

	.product_qty, .product_rate {
        border: 1px solid #999;
        border-radius: 3px;
        padding: 10px;
        text-align: center;
        margin-bottom: 10px;
        width: 70px;
    }

    button {
    	width: 100%;
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
		<div class="mdl-cell mdl-cell--12-col" style="background-color: #ccc; border-radius: 5px; padding: 10px;">
		    <h3>Customer Name: <?php echo $txn[0]->ic_name; ?></h3>
			<h4>Total Amount: <span id="total_amount">Rs.<?php echo $txn[0]->it_amount; ?>/-</span></h4>
			<i><?php echo $txn[0]->it_status; ?></i><br>
		</div>
	</div>
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--3-col">
			<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" id="ship">PROCEED TO SHIPPING</button>
		</div>
		<div class="mdl-cell mdl-cell--3-col">
			<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="delivered" > DELIVERED </button>
		</div>
		<div class="mdl-cell mdl-cell--3-col">
		    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="delete">DELETE TXN</button>
		</div>
		<div class="mdl-cell mdl-cell--3-col">
		    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="payment">RECORD PAYMENT</button>
		</div>
	</div>
	<div class="mdl-grid">
		<h5 style="font-weight: bold; width: 100%; margin: 10px;">Order Summary</h5>
		<table class="purchase_table">
		    <thead>
		        <tr>
		            <th>Product</th>
		            <th>Qty</th>
		            <th>Rate</th>
		            <th>Action</th>
		        </tr>
		    </thead>
		    <tbody>
		       <?php for ($i=0; $i < count($txn_details) ; $i++) { echo '<tr><td>'.$txn_details[$i]->ip_name.'</td><td><input type="text" id="txt'.$txn_details[$i]->itd_id.'" class="product_qty" value="'.$txn_details[$i]->itp_qty.'"></td><td><input type="text" id="rate'.$txn_details[$i]->itd_id.'" class="product_rate" value="'.$txn_details[$i]->itp_rate.'"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_add" id="'.$txn_details[$i]->itd_id.'"><i class="material-icons">update</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="'.$txn_details[$i]->itd_id.'"><i class="material-icons">delete</i></button></td></tr>'; } ?> 
		    </tbody>
		</table>
		<?php #for ($i=0; $i < count($txn_details) ; $i++) { echo '<div class="mdl-cell mdl-cell--4-col"><div class="mdl-card mdl-shadow--4dp"><div class="mdl-card__title" style="background:  linear-gradient(rgba(20,20,20,.5), rgba(20,20,20, .5)), url('.base_url()."assets/uploads/".$oid."/".$txn_details[$i]->ip_id."/".$txn_details[$i]->ip_image.'); background-size:cover;height: 150px;"><h2 class="mdl-card__title-text">'.$txn_details[$i]->ip_name.'</h2></div> <div class="mdl-card__supporting-text" style="text-align: left;"> <div class="mdl-grid"><div class="mdl-cell mdl-cell--6-col"> <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> <input type="text" id="p_request_qty" name="p_request_qty" class="mdl-textfield__input" value="'.$txn_details[$i]->itp_qty.'" readonly> <label class="mdl-textfield__label" for="p_request_qty">To Deliver</label> </div> </div>  </div> </div> </div> </div>'; } ?>
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
<script>
    var product_data = [];
    var product_exist = 'false';
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
    var pay_edit_flag=false, pay_edit_index=0;
    
    $('#pay_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	$('#pay_date').val(s_dt);
    
    $(document).ready(function() {
    	    
    	<?php
    // 		for ($i=0; $i < count($products) ; $i++) { 
    // 			echo "product_data.push('".$products[$i]->ip_name."');";
    // 		}
    	?>
    // 	$( "#products" ).autocomplete({
    //         source: function(request, response) {
    //             var results = $.ui.autocomplete.filter(product_data, request.term);
    //             response(results.slice(0, 10));
    //         }
    //     });
    
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
                $.post('<?php echo base_url().$type."/Transactions/add_delivery_qty"; ?>', {
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
        
        
        $('#payment').click(function(e) {
            e.preventDefault();
            $('#add_payment_dialog').modal('toggle');
        })
        
        $('#add_pay').click(function(e) {
            e.preventDefault();
            var url="";
            if(pay_edit_flag==true) {
                url='<?php echo base_url().$type."/Transactions/records_transaction_payment/delivery/".$txnid."/e/"; ?>' + pay_edit_index;
            } else {
                url = '<?php echo base_url().$type."/Transactions/records_transaction_payment/delivery/".$txnid; ?>';
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
            $.post('<?php echo base_url().$type."/Transactions/records_transaction_payment/delivery/".$txnid."/d/"; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                reset_payments();
                load_payments();
            });
        });
        
        load_payments();
    }); 
    
    function load_payments() {
        $.post('<?php echo base_url().$type."/Transactions/load_transaction_payment_records/".$txnid; ?>', {}, function(d,s,x) {
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
    
    function load_products(a) {
        var b="";
        for(var i=0;i<a.length;i++) { b+='<tr><td>' + a[i].ip_name + '</td><td><input type="text" id="txt' + a[i].itd_id + '" class="product_qty" value="' + a[i].itp_qty + '"></td><td><input type="text" id="txt' + a[i].itd_id + '" class="product_rate" value="' + a[i].itp_rate + '"></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_add" id="' + a[i].itd_id + '"><i class="material-icons">update</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored qty_delete" id="' + a[i].itd_id + '"><i class="material-icons">delete</i></button></td></tr>'; }
        $('.purchase_table > tbody').empty(); $('.purchase_table > tbody').append(b);
    }
    
	var txnid = <?php echo $txnid; ?>;
    <?php  if($txn[0]->it_status == "invoiced" || $txn[0]->it_status == "delivered") {
	    echo 'var flg=true;';
	} else {
	    echo 'var flg=false;';
	}?>
	

	$(document).ready(function() {
	    $('.purchase_table').on('click','.qty_add', function(e) {
            e.preventDefault();
            
            var tid=$(this).prop('id');
            var a = "#txt" + tid;
            var qty = $(a).val();
            a="#rate" + tid;
            var rate = $(a).val();
            
            var snackbarContainer = document.querySelector('#demo-snackbar-example');
            $.post('<?php echo base_url().$type."/Transactions/update_delivery_qty"; ?>', {
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
            
            $.post('<?php echo base_url().$type."/Transactions/delete_delivery_qty"; ?>', { 
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
	    
	    
	    if(flg == true) {
	        $('#ship').html('PRINT CHALLAN')
	        $('#delivered').css('display','none');
	    }
	    
		$('#ship').click(function(e) {
			e.preventDefault();
			
			if(flg==true) {
			    window.location = '<?php echo base_url().$type."/Transactions/print_delivery/".$txnid; ?>';
			} else {
			    $.post('<?php echo base_url().$type."/Transactions/delivery_update/shipping"; ?>', { 'txnid' : txnid }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/print_delivery/"; ?>' + d; }, "text");   
			}
		});

		$('#delivered').click(function(e) {
			e.preventDefault();
			
			$.post('<?php echo base_url().$type."/Transactions/delivery_update/delivered"; ?>', { 'txnid' : txnid }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/delivery"; ?>'; }, "text");
		});

        $('#delete').click(function(e) {
			e.preventDefault();
			$.post('<?php echo base_url().$type."/Transactions/delivery_delete/"; ?>', { 'txnid' : txnid }, function(d,s,x) { window.location = '<?php echo base_url().$type."/Transactions/delivery"; ?>'; }, "text");
		});

        


	});
</script>
</html>