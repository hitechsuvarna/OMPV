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
			            <th>Txn No</th>
			            <th>Date</th>
			            <th>Type</th>
			            <th>Party Name</th>
			            <th>Amount</th>
			        </tr>
			    </thead>
				<tbody>
				<?php for ($i=0; $i < count($txn) ; $i++) { 
					echo '<tr id="'.$txn[$i]->it_id.'"> <td> <b class="order_number">'.$txn[$i]->it_txn_no.'</b></td><td>'.$txn[$i]->it_date.'</td><td>'.$txn[$i]->it_type.'</td><td>'.$txn[$i]->ic_name.'</td> <td class="amount">'.$txn[$i]->it_amount.'</td></tr>';	
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
		$('.purchase_table').on('click', 'tr', function(e) {
			e.preventDefault();
			window.location = "<?php echo base_url().$type.'/Transactions/cd_note_edit/'; ?>" + $(this).prop('id');
		});
		
		$('#fixed-header-drawer-exp').keyup(function(e) {
		    e.preventDefault();
		    
		    $.post('<?php echo base_url().$type."/Transactions/search_cd_note"; ?>', { 'keyword' : $(this).val() }, function(d,s,x) { 
		        $('.purchase_table > tbody').empty(); 
		        var a=JSON.parse(d), b=""; 
		        for(var i=0;i<a.length; i++) { 
		            b+='<tr id="' +a[i].it_id + '"> <td> <b class="order_number">' + a[i].it_txn_no + '</b></td><td>' + a[i].it_date + '</td><td>'+a[i].it_type + '</td><td>' + a[i].ic_name + '</td> <td class="amount">' + a[i].it_amount + '</td></tr>';
		        } $('.purchase_table > tbody').append(b); });
		})

		$('#status').change(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/filter_invoice"; ?>', { 'status' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">' + a[i].it_txn_no + '</b> - ' + a[i].ic_name + '<br> <i>' + a[i].it_date + '</i> </td> <td class="amount">' + a[i].it_amount + '<i>' + a[i].it_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/cd_note_add'; ?>";
		});
	});
</script>
</html>