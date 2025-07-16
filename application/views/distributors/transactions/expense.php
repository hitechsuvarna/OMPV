<style type="text/css">
	.order_table {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
	}

	.order_table > tbody > tr {
		width: 100%;
	}

	.order_table > tbody > tr > td {
		border-bottom: 1px solid #999;
		padding: 10px;
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
</style>

<main class="mdl-layout__content">

	<div class="mdl-grid" >
		<!-- <div class="mdl-cell mdl-cell--12-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="status" class="mdl-textfield__input">
					<option value="active">Active</option>
					<option value="done">Done</option>
				</select>
				<label class="mdl-textfield__label" for="status">Select status of expense to filter</label>
			</div>
		</div> -->
		<div class="mdl-cell mdl-cell--12-col">
			<table class="order_table">
				<tbody>
				<?php for ($i=0; $i < count($expense) ; $i++) { 
					echo '<tr id="'.$expense[$i]->ie_id.'"> <td> <b class="order_number">'.$expense[$i]->ie_description.'</b><br> <i>'.$expense[$i]->ie_date.'</i> </td> <td class="amount">'.$expense[$i]->ie_amount.'<i>'.$expense[$i]->ie_status.'</i> </td> </tr>';	
				} ?>
				</tbody>
			</table>
		</div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">add</i>
		</button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('.order_table').on('click', 'tr', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/expense_edit/'; ?>" + $(this).prop('id');
		});

		$('#status').change(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/filter_expense"; ?>', { 'status' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].ie_id + '"> <td> <b class="order_number">' + a[i].ie_description + '</b><br> <i>' + a[i].ie_date + '</i> </td> <td class="amount">' + a[i].ie_amount + '<i>' + a[i].ie_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/expense_add'; ?>";
		});
	});
</script>
</html>