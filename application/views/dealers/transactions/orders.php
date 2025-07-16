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

	<div class="mdl-grid" >
	    <div class="mdl-cell mdl-cell--12-col">
			<table class="purchase_table">
			    <thead>
			        <tr>
			            <th>Order Id</th>
			            <th>Date</th>
			            <th>Status</th>
			            <th>Amount</th>
			        </tr>
			    </thead>
				<tbody>
					<?php 
				        $a="";
    					for ($i=0; $i < count($orders) ; $i++) { 
    						$a.= '<tr class="or_nu" id="'.$orders[$i]['id'].'"> <td> <b class="order_number">#'.$orders[$i]['txnno'].'</b></td><td>'.$orders[$i]['txndt'].'</td><td>'.$orders[$i]['txntype'].' - '.$orders[$i]['status'].'</td><td class="amount"> Rs.'.$orders[$i]['amount'].'/-</td></tr>';
    					}
    					echo $a;
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
		$('.purchase_table').on('click', '.or_nu', function(e) {
			e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Transactions/order_details/'; ?>" + $(this).prop('id');
		})
	});
</script>
</html>