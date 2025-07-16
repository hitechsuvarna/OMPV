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
		<div class="mdl-cell mdl-cell--12-col" style="border: 3px solid #000; border-radius: 5px; padding: 10px; text-align: center;padding: 20px;">
			<h4>Your order has been placed.</h4><h1><br><span id="order_id"><i>Order No:</i><?php echo $orderid; ?></span>.</h1><h4>It shall be processed shortly.</h4>
		</div>
		<button id="go_home" class="mdl-button mdl-js-button mdl-js-ripple-effect  mdl-button--accent" style="width: 100%;">Go Home.</button>
	</div>
</div>
</div>
</body>

<script>
	$(document).ready(function() {	
		$('#go_home').click(function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Home/index/1'; ?>";
		});
	});
</script>
</html>