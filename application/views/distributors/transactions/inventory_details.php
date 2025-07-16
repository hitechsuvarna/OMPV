<style type="text/css">
	.transactions {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
		border-collapse: collapse;
		display: block;
		overflow: auto;
	}

	.transactions > thead > tr {
		box-shadow: 1px 5px 5px #ccc;
	}

	.transactions > thead > tr > th {
		padding: 10px;
	}

	.transactions > tbody > tr {
		width: 100%;
	}

	.transactions > tbody > tr > td {
		border-bottom: 1px solid #999;
		padding: 15px;
		width: 100%;
	}

	.cart_table_details {
		width: 100%;
	}
	.amount {
		text-align: right;
	}

	.order_number {
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

    .stock_display > h4	 {
    	text-align: center;
    }

    .stock_display > table {
    	width: 100%;
    }

</style>

<main class="mdl-layout__content">
	<div class="mdl-grid stock_display">
	    <div class="mdl-cell mdl-cell--6-col">
	        <h4>Available Stock: <?php echo $bal;?></h4>
	    </div>
	    <div class="mdl-cell mdl-cell--3-col">
	        Reconcile Stock
	        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="reconcile"><i class="material-icons">arrow_forward</i></button>
	    </div>
	    <div class="mdl-cell mdl-cell--3-col" style="display:flex;">
	        Add qty to place an order
	        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="order_qty" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="order_qty">Enter Order Qty</label>
			</div>
		    <button class="mdl-button mdl-js-button mdl-button--colored" id="order_list"><i class="material-icons">arrow_forward</i></button>
	    </div>
	</div>
	<div class="mdl-grid stock_transaction">
		<table class="transactions">
			<thead>
				<tr>
					<th>Particulars</th>
					<th>Txn Number</th>
					<th>Txn Date</th>
					<th>Credit</th>
					<th>Debit</th>
					<th>Rate</th>
				</tr>
			</thead>
			<tbody>
				<?php for ($i=0; $i < count($txn); $i++) { 
					echo '<tr> <td>';
					if($txn[$i]->ii_type == "return") {
					    echo 'Return - '.$txn[$i]->ic_name;
					}
					echo '</td> <td>';
					if($txn[$i]->it_txn_no != "") { 
					    echo $txn[$i]->ii_order_id.'</td><td>'.$txn[$i]->it_date.'</td>'; 
					} else { 
					    echo $txn[$i]->ii_txn_num.'</td> <td>'.$txn[$i]->ii_txn_date.'</td>'; 
					}
					
					if ($txn[$i]->ii_type == "credit" || $txn[$i]->ii_type == "return") {
						echo '<td>'.$txn[$i]->ii_inward.'</td><td></td>';
					} else {
						echo '<td></td><td>'.$txn[$i]->ii_outward.'</td>';
					}
					echo '<td>'.$txn[$i]->itp_rate.'</td> </tr>';
				} ?>
			</tbody>
		</table>
	</div>
    <div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</main>
</div>
</div>
</body>
<script>
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
	$(document).ready(function() {
		$('.stock_display').on('click', '.stock_item', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/inventory_details/'; ?>" + $(this).prop('id');
		});
		
		$('#reconcile').click(function(e) {
		    e.preventDefault();
		   
		    $.post('<?php echo base_url().$type."/Transactions/reconcile_inventory/".$pid; ?>', {}, function(d,s,x) {
		        var ert = {message: 'Stocks Reconciled.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                location.reload();
		    }) 
		});
		
		$('#order_list').click(function(e) {
		    e.preventDefault();
		   
		    $.post('<?php echo base_url().$type."/Transactions/add_item_to_list/".$pid; ?>', { 'q' : $('#order_qty').val() }, function(d,s,x) {
		        var ert = {message: 'Added to List.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                location.reload();
		    }) 
		});
	});
</script>
</html>