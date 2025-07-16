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
					<h2 class="mdl-card__title-text">Expense Details</h2>
				</div>
				<div class="mdl-card__supporting-text" style="width: auto;">
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="i_expense_date" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->ie_date; } ?>">
						<label class="mdl-textfield__label" for="i_expense_date">Select Transaction Date</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select class="mdl-textfield__input" id="i_orders">
							<option value="0">None</option>
							<?php
								for ($i=0; $i < count($orders) ; $i++) { 	
									echo '<option value="'.$orders[$i]->it_id.'"';
									if(isset($edit_txn)) if($edit_txn[0]->ie_order_id == $orders[$i]->it_id) echo "selected";
									echo '>#'.$orders[$i]->it_txn_no.' - '.$orders[$i]->ic_name.'</option>';
								}
							?>
						</select>
						<label class="mdl-textfield__label" for="i_orders">Enter Transaction Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<textarea id="i_expense_desc" class="mdl-textfield__input"><?php if(isset($edit_txn)) { echo $edit_txn[0]->ie_description; } ?></textarea>
						<label class="mdl-textfield__label" for="i_expense_desc">Expense Description</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="i_expense_amount" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->ie_amount; } ?>">
						<label class="mdl-textfield__label" for="i_expense_amount">Expense Amount</label>
					</div>	
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					    <p style="margin: 10px;text-align: left;">Upload Expense Ticket</p>
					    <br>
					    <input type="file" name="attach_file" class="upload">
					    <img src="<?php if(isset($edit_txn)) echo base_url().'assets/uploads/'.$oid.'/expenses/'.$uid.'/'.$eid.'/'.$edit_txn[0]->ie_tickets; ?>" id="p_image" style="width: 100%;">
			        </div>';
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
	
	$(document).ready( function() {
    	$('#i_expense_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	
		<?php 
			if(!isset($edit_txn)) {
				echo "var dt = new Date();";
				echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				
				echo "$('#i_expense_date').val(s_dt);";
			}
		?>

		var order = [];

		<?php for ($i=0; $i < count($orders); $i++) { 
			echo "order[".$orders[$i]->it_id."] = { 'id': '".$orders[$i]->it_txn_no."', 'name': '".$orders[$i]->ic_name."' };";
		} ?>

		$('#i_orders').change(function(e) {
			e.preventDefault();

			$('#i_expense_desc').val("Delivery of order number " + order[$(this).val()].id + " for " + order[$(this).val()].name);
			$('#i_expense_desc').focus();
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
			
			$.post('<?php if(isset($edit_txn)) { echo base_url().$type."/Transactions/update_expense/".$eid; } else { echo base_url().$type."/Transactions/save_expense/"; } ?>', { 'order': $('#i_orders').val(), 'date' : $('#i_expense_date').val(), 'description' : $('#i_expense_desc').val(), 'amt' : $('#i_expense_amount').val() }, function(d,s,x) { upload_image(d); }, "text");

		});


	});
	
	function upload_image(eid) {
		var datat = new FormData();
		if($('.upload')[0].files[0]) {
			datat.append("use", $('.upload')[0].files[0]);
			
			flnm = "";
			$.ajax({
				url: "<?php echo base_url().$type.'/Transactions/expense_ticket_upload/'; ?>" + eid, // Url to which the request is send
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
	    window.location = "<?php echo base_url().$type.'/Transactions/expenses'; ?>";
	}
	
</script>
</html>