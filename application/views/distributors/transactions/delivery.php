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
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="status" class="mdl-textfield__input">
					<option value="approved">Approved</option>
					<option value="shipping">Shipping</option>
					<option value="delivered">Delivered</option>
					<option value="invoiced">Invoiced</option>
				</select>
				<label class="mdl-textfield__label" for="status">Delivery Status</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="amount_filter" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="amount_filter">Amount Filter Less Than</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="from_date" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="from_date">From Date</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="to_date" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="to_date">To Date</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
		    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="search"><i class="material-icons">search</i></button>
		    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="print"><i class="material-icons">print</i></button>
		</div>
		
		<div class="mdl-cell mdl-cell--2-col" style="text-align:right;">
		    <?php 
		        $tmt = 0;
				for ($i=0; $i < count($txn) ; $i++) { 
		            $tmt+=$txn[$i]->it_amount;
				}
			?>
			<h3 id="total_amount" style="margin:0px;">Total: Rs.<?php echo $tmt; ?>/-</h3>
		</div>
		<div class="mdl-cell mdl-cell--12-col">
			<table class="purchase_table">
			    <thead>
			        <tr>
			            <th>Txn Id</th>
			            <th>Txn Date</th>
			            <th>Client</th>
			            <th>Status</th>
			        </tr>
			    </thead>
				<tbody>
					<?php $b="";
						for ($i=0; $i < count($txn) ; $i++) { 
							$b.='<tr id="'.$txn[$i]->it_id.'"> <td> <b class="order_number">#'.$txn[$i]->it_txn_no.'</b></td><td>'.$txn[$i]->it_date.'</td><td>'.$txn[$i]->ic_name.'</td><td>';
							if($txn[$i]->it_status == "payment_pending") {
							    $b.= "delivered"; 
							} else {
							    $b.= $txn[$i]->it_status;
							}
							$b.= '</td><td class="amount">'.$txn[$i]->it_amount.'</td></tr>';
						}
						echo $b;
						
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
</body>
<script>
    var p_rint_style= '<style>.purchase_table {width: 100%; text-align: left; border: 0px solid #ccc; border-collapse: collapse; } @media only screen and (max-width: 760px) {.purchase_table {display: block; overflow: auto; } } .purchase_table > thead > tr {box-shadow: 0px 5px 5px #ccc; } .purchase_table > thead > tr > th {padding: 10px; } .purchase_table > tbody > tr {border-bottom: 1px solid #ccc; } .purchase_table > tbody > tr > td {padding: 15px; }</style>';
    var p_rint_head = '<table class="purchase_table"> <thead> <tr> <th>Txn Id</th> <th>Txn Date</th> <th>Client</th> <th>Status</th> <th>Amount</th> </tr> </thead> <tbody>';
    var p_rint = "";
    var p_rint_foot1 = '</tbody><tfoot><tr><td colspan="4">Total</td><td>';
    var p_rint_total = "";
    var p_rint_foot2 = '</td></tr></tfoot></table>';
	
	$(document).ready(function() {
	    $('#from_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        $('#to_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        <?php 
			if(!isset($edit_txn)) {
				echo "var dt = new Date();";
				echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
				
				echo "$('#from_date').val(s_dt);";
				echo "$('#to_date').val(s_dt);";
				
			}
		?>
		
		$('.purchase_table').on('click', 'tr', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/delivery_details/'; ?>" + $(this).prop('id');
		});

		$('#status').change(function(e) {
			e.preventDefault();
            var t=0;
			$.post('<?php echo base_url().$type."/Transactions/filter_orders"; ?>', { 'status' : $(this).val(), 'type' : 'Delivery' }, function(d,s,x) { $('.purchase_table > tbody').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { t+=parseInt(a[i].it_amount); b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">#' + a[i].it_txn_no + '</b></td><td>' + a[i].it_date + '</td><td>' + a[i].ic_name + '</td><td>' + a[i].it_status + '</td><td class="amount">' + a[i].it_amount + '</td> </tr>'; if(a[i].it_status == "payment_pending") { b+= "delivered"; } else { b+=a[i].it_status; } + '</i> </td> </tr>'; } $('.purchase_table > tbody').append(b); p_rint=b; load_total(t); p_rint_total=t;  });
		});
        
        $('#search').click(function(e) {
			e.preventDefault();
			var t=0;
			$.post('<?php echo base_url().$type."/Transactions/filter_orders_by_date"; ?>', {'type' : 'Delivery', 'status': $('#status').val(), 'from' : $('#from_date').val(), 'to' : $('#to_date').val(), 'amount' : $('#amount_filter').val() }, function(d,s,x) { $('.purchase_table > tbody').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) {  t+=parseInt(a[i].it_amount);b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">#' + a[i].it_txn_no + '</b></td><td>' + a[i].it_date + '</td><td>' + a[i].ic_name + '</td><td>' + a[i].it_status + '</td><td class="amount">' + a[i].it_amount + '</td> </tr>'; } $('.purchase_table > tbody').append(b); p_rint=b; load_total(t); p_rint_total=t; });
		});
		
		$('#fixed-header-drawer-exp').keyup(function(e) {
			e.preventDefault();
			var t=0;
			$.post('<?php echo base_url().$type."/Transactions/filter_orders_by_name"; ?>', { 'keyword' : $(this).val(), 'type' : 'Delivery' }, function(d,s,x) { $('.purchase_table > tbody').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { t+=parseInt(a[i].it_amount);b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">#' + a[i].it_txn_no + '</b></td><td>' + a[i].it_date + '</td><td>' + a[i].ic_name + '</td><td>' + a[i].it_status + '</td><td class="amount">' + a[i].it_amount + '</td> </tr>'; } $('.purchase_table > tbody').append(b); p_rint=b; load_total(t); p_rint_total=t; });
		});
		
		
		$('#print').click(function(e) {
		    e.preventDefault();
		    
		    print_reciept();
		});
		
		function load_total(amt) {
		    $('#total_amount').empty();
            $('#total_amount').append('Total: Rs.'+amt+'/-');
        }
        
        function print_reciept() {
    		var mywindow = window.open('', 'Orders', fullscreen=1);
    		mywindow.document.write(p_rint_style + p_rint_head + p_rint + p_rint_foot1 + p_rint_total + p_rint_foot2); 
    		mywindow.document.close(); 
    		mywindow.focus(); 
    		mywindow.print(); 
    		mywindow.close();
    	}
	});
</script>
</html>