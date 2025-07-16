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
		<div class="mdl-cell mdl-cell--12-col">
			<table class="order_table">
				<tbody>
				<?php 
					for ($i=0; $i < count($orders) ; $i++) { 
						echo '<tr> <td> <b class="order_number" id="'.$orders[$i]->it_id.'">#'.$orders[$i]->it_txn_no.'</b><br> <i>'.$orders[$i]->it_date.'</i> </td> <td class="amount"> Rs.'.$orders[$i]->it_amount.'/- <i>'.$orders[$i]->it_status.'</i> </td> </tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('.order_table').on('click', '.order_number', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/invoice_details/'; ?>" + $(this).prop('id');
		})
	});
</script>
</html>