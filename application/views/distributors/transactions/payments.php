<style type="text/css">
	.order_table, .invoice_table {
		width: 100%;
		border: none;
		padding: 10px;
		border: 1px solid #999;
	}

	.order_table > tbody > tr, .invoice_table > tbody > tr {
		width: 100%;
	}

	.order_table > tbody > tr > td, .invoice_table > tbody > tr > td {
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
    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
        <div class="mdl-tabs__tab-bar">
            <a href="#invoice" class="mdl-tabs__tab is-active">Payments by Invoice</a>
            <a href="#individual" class="mdl-tabs__tab">Individual Payments</a>
        </div>
    
        <div class="mdl-tabs__panel is-active" id="invoice">
            <div class="mdl-cell mdl-cell--12-col">
    			<table class="invoice_table">
    				<tbody>
    				<?php for ($i=0; $i < count($txn) ; $i++) { 
    					echo '<tr id="r'.$txn[$i]->it_id.'"> <td> <b class="order_number">'.$txn[$i]->it_txn_no.'</b> - '.$txn[$i]->ic_name.'<br> <i>'.$txn[$i]->it_date.'</i> </td> <td class="amount">';
    					$amt = 0;
    					for($j=0;$j<count($payments); $j++) {
    					    if($txn[$i]->it_id == $payments[$j]->itp_t_id) {
    					        $amt+= $payments[$j]->itp_amt;    
    					    }
    					}
    					echo $amt.'/'.$txn[$i]->it_amount;
    					echo '</td> </tr>';	
    				} ?>
    				</tbody>
    			</table>
    		</div>
        </div>
        <div class="mdl-tabs__panel" id="individual">
            <div class="mdl-cell mdl-cell--12-col">
    			<table class="order_table">
    				<tbody>
    				<?php for ($i=0; $i < count($payments) ; $i++) { 
    					echo '<tr id="'.$payments[$i]->itp_id.'"> <td> <b class="order_number">'.$payments[$i]->it_txn_no.'</b> - '.$payments[$i]->ic_name.'<br> <i>'.$payments[$i]->it_date.'</i> </td> <td class="amount">'.$payments[$i]->itp_amt.'</td> </tr>';	
    				} ?>
    				</tbody>
    			</table>
    		</div>
        </div>
    </div>

	<div class="mdl-grid" >
		
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

			window.location = "<?php echo base_url().$type.'/Transactions/payment_edit/'; ?>" + $(this).prop('id');
		});
		
		$('#fixed-header-drawer-exp').keyup(function(e) {
		    e.preventDefault();
		    
		    $.post('<?php echo base_url().$type."/Transactions/search_invoice"; ?>', { 'keyword' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">' + a[i].it_txn_no + '</b> - ' + a[i].ic_name + '<br> <i>' + a[i].it_date + '</i> </td> <td class="amount">' + a[i].it_amount + '<i>' + a[i].it_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
		})

		$('#status').change(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/filter_invoice"; ?>', { 'status' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">' + a[i].it_txn_no + '</b> - ' + a[i].ic_name + '<br> <i>' + a[i].it_date + '</i> </td> <td class="amount">' + a[i].it_amount + '<i>' + a[i].it_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/payment_add'; ?>";
		});
	});
</script>
</html>