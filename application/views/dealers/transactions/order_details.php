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
			<h4>Total Amount: <span id="total_amount">Rs.<?php echo $order[0]->it_amount; ?>/-</span></h4>
			<i><?php echo $order[0]->it_status; ?></i>
			<?php if(count($payment) > 0) {
			    echo '<h5>Payments Made:</h5>';
			    echo '<p>'.$payment[0]->itp_details.' Amount:'.($payment[0]->itpl_amt).'</p>'; 
			 } ?>
		</div>
	</div>
	<div class="mdl-grid" >
		<h5 style="font-weight: bold; width: 100%; margin: 10px;">Order Summary</h5>
		<table class="cart_table">
			<?php for ($i=0; $i < count($order_details); $i++) { 
				echo '<tr> <td class="cart_table_details"> <h5>'.$order_details[$i]->ip_name.'</h5> <i>'.$order_details[$i]->ip_description.'</i><br> Rate: <b>Rs.'.$order_details[$i]->itp_rate.'/-</b><br> Qty:  <input type="text" class="product_qty" value="'.$order_details[$i]->itp_qty.'"><br> </td> <td class="cart_table_image"> <div style="background-image: url(\''.base_url().'assets/uploads/'.$oid.'/'.$order_details[$i]->ip_id.'/'.$order_details[$i]->ip_image.'\');width:100px;height: 100px; background-size:cover;"></div> </td> </tr>';
			} ?>
		</table>
	</div>
	
	<?php if($order[0]->it_type == "Orders") {
	  echo '<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit"><i class="material-icons">done</i></button>';
	}
	?>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
</body>
<script>
    var product_arr = [];
	var total = 0;
	$(document).ready(function() {
	    <?php
			for ($i=0; $i < count($order_details) ; $i++) { 
				echo 'product_arr.push({"id" : '.$order_details[$i]->ip_id.', "name" : "'.$order_details[$i]->ip_name.'", "description" : "'.$order_details[$i]->ip_description.'", "rate" : '.$order_details[$i]->itp_rate.', "qty" : '.$order_details[$i]->itp_qty.', "image" : "'.base_url().'assets/uploads/'.$oid.'/'.$order_details[$i]->ip_id.'/'.$order_details[$i]->ip_image.'" });';
			}
		 ?>
		
		$('#submit').click(function(e) {
			e.preventDefault();
			recalculate();
			$.post('<?php echo base_url().$type."/Transactions/order_update/".$id; ?>', { 'total' : total, 'products' : product_arr }, function(d,s,x) { if(d=="true") { window.location = "<?php echo base_url().$type.'/Transactions/order_details/'.$id; ?>"; } else { display_message("You cannot change order once approved.")  } }, "text");
		});

	});
	
	function recalculate() {
		$('.product_qty').each(function(i) {
			product_arr[i].qty = $(this).val();
			var a=product_arr[i].rate, b=product_arr[i].qty;
			total+=(a*b);
		});
	}
	
	function display_message(message) {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
        var ert = {message: message,timeout: 2000, }; 
        
        snackbarContainer.MaterialSnackbar.showSnackbar(ert);

	}
	
</script>
</html>