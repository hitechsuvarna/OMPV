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
		<div class="mdl-cell mdl-cell--12-col" style="background-color: #ccc; border-radius: 5px; padding: 10px;">
			<h4>Total Amount: <span id="total_amount">Rs.15,000/-</span></h4>
		</div>
	</div>
	<div class="mdl-grid" >
		<h5 style="font-weight: bold; width: 100%; margin: 10px;">Order Summary</h5>
		<table class="cart_table">
			<tr>
				<td class="cart_table_details">
					<h5>Car Matress</h5>
		            <i>Car Matress Description</i><br>
		            Rate: <b>Rs.3,000/-</b><br>
		            Qty: <input type="text" class="product_qty"><br>
		        </td>
		        <td class="cart_table_image">
		        	<div style="background-image: url('<?php echo base_url()."assets/images/pattern_nav.svg"; ?>');width:100px;height: 100px;"></div>
		        </td>
	    	</tr>
	    	<tr>
				<td class="cart_table_details">
					<h5>Car Matress</h5>
		            <i>Car Matress Description</i><br>
		            Rate: <b>Rs.3,000/-</b><br>
		            Qty: <input type="text" class="product_qty"><br>
		        </td>
		        <td class="cart_table_image">
		        	<div style="background-image: url('<?php echo base_url()."assets/images/pattern_nav.svg"; ?>');width:100px;height: 100px;"></div>
		        </td>
	    	</tr>
		</table>
		
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
			<i class="material-icons">done</i>
		</button>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('#submit').click(function(e) {
			e.preventDefault();
			
			window.location = "<?php echo base_url().$type.'/Transactions/confirm'; ?>";

		});

	});
</script>
</html>