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
</style>

<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">From Vendor/Godown</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;text-align:left;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="from-togg" style="text-align:left;">
                            <input type="checkbox" id="from-togg" class="mdl-switch__input" checked>
                            <span class="mdl-switch__label">Turn On for Contact,<br> Off for Godown</span>
                        </label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:auto;" id="from_contact_div">
    					<b>Type Contact Name</b>
    					<ul id="from_contact" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
    					</ul>
    				</div>
    				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:auto;" id="from_godown_div">
    					<b>Type Godown Name</b>
    					<ul id="from_godown" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
    					</ul>
    				</div>
                </div>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">To Vendor/Godown</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;text-align:left;">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="to-togg" style="text-align:left;">
                            <input type="checkbox" id="to-togg" class="mdl-switch__input" checked>
                            <span class="mdl-switch__label">Turn On for Contact,<br> Off for Godown</span>
                        </label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:auto;" id="to_contact_div">
    					<b>Type Contact Name</b>
    					<ul id="to_contact" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
    					</ul>
    				</div>
    				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:auto;" id="to_godown_div">
    					<b>Type Godown Name</b>
    					<ul id="to_godown" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
    					</ul>
    				</div>
				</div>
			</div>
		</div>
		
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--4dp">
				<div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Purchase Items</h2>
				</div>
				<div class="mdl-card__supporting-text">
					<div id="info_repea" class="mdl-grid">
						<div class="mdl-cel mdl-cell--4-col" style="margin:0px;padding:0px;">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:auto;">
								<b>Product Name</b>
								<ul id="products" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
								</ul>
							</div>
						</div>
						<div class="mdl-cel mdl-cell--2-col" style="margin-top:0%;margin-left:0%;padding:0px;">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:90%;">
								<b>Qty</b>
								<input type="text" id="prod_qty" class="" value="" style="text-align: center;width: 100%; padding-top: 11px; padding-bottom: 11px; border-radius: 5px 5px; margin: 6px 0px 16px 0px; border: 1px solid #999;">
							</div>
						</div>
						<div class="mdl-cel mdl-cell--4-col" style="margin-top:0%;margin-left:0%;padding:0px;">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="padding:0px;text-align:left;width:90%;">
								<b>Storage Ref</b>
								<input type="text" id="prod_box" class="" value="" style="text-align: center;width: 100%; padding-top: 11px; padding-bottom: 11px; border-radius: 5px 5px; margin: 6px 0px 16px 0px; border: 1px solid #999;">
							</div>
						</div>
						
						<div class="mdl-cel mdl-cell--2-col" style="margin-top:24px;margin-left:0%;padding:0px;">
							<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item"><i class="material-icons">add</i></button>
						</div>
					</div>
					<div class="mdl-grid">
						<table class="purchase_table" style="width:100%;">
							<thead>
								<tr>
									<th>Action</th>
									<th>Product</th>
									<th>Qty</th>
									<th>Storage</th>
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
	var product_box = [];
	var edit_index = 0;
	var edit_flag = false;

	$(document).ready( function() {
	    $('#from_godown_div').css('display', 'none');
	    $('#to_godown_div').css('display', 'none');
	    
    	var contact_data = [];
    	<?php
    		for ($i=0; $i < count($contacts) ; $i++) { 
    			echo "contact_data.push('".$contacts[$i]->ic_name."');";
    		}
    	?>
    	
    	$('#from_contact').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : contact_data,
    		tagLimit : 1,
    		singleField : true
    	});

        $('#to_contact').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : contact_data,
    		tagLimit : 1,
    		singleField : true
    	});

        var godown_data = [];
    	<?php
    		for ($i=0; $i < count($godowns) ; $i++) { 
    			echo "godown_data.push('".$godowns[$i]->ig_godown_name."');";
    		}
    	?>
    	
    	$('#from_godown').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : godown_data,
    		tagLimit : 1,
    		singleField : true
    	});

        $('#to_godown').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : godown_data,
    		tagLimit : 1,
    		singleField : true
    	});

        
        var product_data = [];
    	<?php
    		for ($i=0; $i < count($products) ; $i++) { 
    			echo "product_data.push('".$products[$i]->ip_name."');";
    		}
    	?>
    	
    	$('#products').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : product_data,
    		allowDuplicates: true,
    		tagLimit : 1,
    		singleField : true
    	});
    });
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
	    
	    $('#from-togg').change(function(e) {
	        if($(this)[0].checked == true) {
	            $('#from_contact_div').css('display','block');
	            $('#from_godown_div').css('display','none');
	        } else {
	            $('#from_contact_div').css('display','none');
	            $('#from_godown_div').css('display','block');
	        }
	    });
	    
	    $('#to-togg').change(function(e) {
	        if($(this)[0].checked == true) {
	            $('#to_contact_div').css('display','block');
	            $('#to_godown_div').css('display','none');
	        } else {
	            $('#to_contact_div').css('display','none');
	            $('#to_godown_div').css('display','block');
	        }
	    });
	    
	    
	    
	    
	   
		<?php if(isset($edit_txn_details)) for ($i=0; $i < count($edit_txn_details) ; $i++) { 
// 			echo "product_name.push('".$edit_txn_details[$i]->ip_name."');";
// 			echo "product_qty.push('".$edit_txn_details[$i]->itp_qty."');";
		} echo "update_list();"; ?>
		

		function add_product() {
			product_name.push($('#products')[0].innerText);
    		product_qty.push($('#prod_qty').val());
    		product_box.push($('#prod_box').val());
		}

		function remove_product(id) {
			product_name.splice(id, 1);
			product_qty.splice(id, 1);
			product_box.splice(id, 1);
		}

		function edit_product(id) {

			$('#products').append('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">' + product_name[id] + '</span><a class="tagit-close"><span class="text-icon">×</span><span class="ui-icon ui-icon-close"></span></a></li>');
    		$('#prod_qty').val(product_qty[id]);
    		$('#prod_box').val(product_box[id]);
    		edit_index = id;
    		edit_flag = true;
		}

		function update_product(id) {
			product_name[id] = $('#products')[0].innerText;
    		product_qty[id] = $('#prod_qty').val();
    		product_box[id] = $('#prod_box').val();
		}

		function update_list() {
			$('.purchase_table > tbody').empty();

			var out = "";
			for (var i = 0; i < product_name.length; i++) {
				out+='<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored edit" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + i + '"><i class="material-icons">delete</i></button></td><td>' + product_name[i] + "</td><td>" + product_qty[i] + "</td><td>" + product_box[i] + "</td></tr>";
			}

			$('.purchase_table > tbody').append(out);
		}

		function reset_fields() {
			$('#products > .tagit-choice').remove();
    		$('#prod_qty').val("");
    		$('#prod_box').val("");
    		$('#products').data("ui-tagit").tagInput.focus();
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
			var from = "", from_type="", to="", to_type="";
			
			if($('#from-togg')[0].checked == true) {
			    from = $('#from_contact')[0].innerText;
			    from_type = "contact";
			} else {
			    from = $('#from_godown')[0].innerText;
			    from_type = "godown";
			}
			
			if($('#to-togg')[0].checked == true) {
			    to = $('#to_contact')[0].innerText;
			    to_type = "contact";
			} else {
			    to = $('#to_godown')[0].innerText;
			    to_type = "godown";
			}
			
			
			$.post('<?php if(isset($edit_txn)) { echo base_url().$type."/Transactions/update_godown_transaction/".$tid; } else { echo base_url().$type."/Transactions/save_godown_transaction/"; } ?>', { 'from': from, 'from_type' : from_type, 'to' : to, 'to_type' : to_type, 'date' : $('#i_txn_date').val(), 'txn' : $('#i_txn_no').val(), 'name' : product_name, 'qty' : product_qty, 'box' : product_box }, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Transactions/godowns'; ?>";}, "text");

		});


	});
</script>
</html>