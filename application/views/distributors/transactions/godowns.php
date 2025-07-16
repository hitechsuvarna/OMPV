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
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--6-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="manage_godown" style="width:100%;">Manage Godown</button>
        </div>
        <div class="mdl-cell mdl-cell--6-col">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="manage_material" style="width:100%;">Manage Materials</button>
        </div>
    </div>
	<div class="mdl-grid" >
		<div class="mdl-cell mdl-cell--12-col">
			<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;" id="godown_table">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Godown</th>
                        <th class="mdl-data-table__cell--non-numeric">Product</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="mdl-data-table__cell--non-numeric">Godown 1</td>
                        <td class="mdl-data-table__cell--non-numeric">Curtain Heavy</td>
                        <td>25</td>
                    </tr>
                </tbody>
            </table>
		</div>
		
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
	    $('#manage_godown').click(function(e) {
	       e.preventDefault();
	       
	       window.location = "<?php echo base_url().$type.'/Transactions/manage_godowns'; ?>";
	    });
	    
	    $('#manage_material').click(function(e) {
	       e.preventDefault();
	       
	       window.location = "<?php echo base_url().$type.'/Transactions/manage_materials'; ?>";
	    });
	    
	    
	    
	    
		$('.order_table').on('click', 'tr', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/purchase_edit/'; ?>" + $(this).prop('id');
		});

		$('#status').change(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/filter_purchase"; ?>', { 'status' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">' + a[i].it_txn_no + '</b><br> <i>' + a[i].it_date + '</i> </td> <td class="amount">' + a[i].it_amount + '<i>' + a[i].it_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
		});

		$('#submit').click(function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/purchase_add'; ?>";
		});
	});
</script>
</html>