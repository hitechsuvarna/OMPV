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
        <div class="mdl-cell mdl-cell--12-col" style="border-radius:5px; padding:15px; box-shadow:0px 5px 10px #ccc;">
            <b>Search</b><br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:40%;">
				<input type="text" id="f_date" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="f_date">From Date</label>
			</div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:40%;">
				<input type="text" id="t_date" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="t_date">To Date</label>
			</div>
			<button class="mdl-button mdl-button--icon mdl-button--raised ndl-button--colored" id="d_search">
			    <i class="material-icons">search</i>
			</button>				
        </div>
	    <div class="mdl-cell mdl-cell--12-col">
			<table class="purchase_table" id="order_list">
			    <thead>
			        <tr>
			            <th>Txn Num</th>
			            <th>Date</th>
			            <th>Type</th>
			            <th>Status</th>
			            <th>Amount</th>
			        </tr>
			    </thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
</body>
<script>
	$(document).ready(function() {
	    $('#f_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    $('#t_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    
	    var dt = new Date();
		var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
		$('#f_date').val(s_dt);
		$('#t_date').val(s_dt);
		
		load_orders();
		
		$('#d_search').click(function(e) {
		    e.preventDefault();
		    load_orders($('#f_date').val(), $('#t_date').val());
		})
	
		$('.purchase_table').on('click', '.or_nu', function(e) {
			e.preventDefault();
            window.location = "<?php echo base_url().$type.'/Home/order_details_new/'; ?>" + $(this).prop('id');
		});
		
		$.post('<?php echo base_url().$type."/Home/load_cart_values/count"; ?>', {}, function(d,s,x) {
            $('#disp_cart').attr('data-badge', d);
        })
        
        $('#disp_cart').click(function(e) {
            e.preventDefault();
            window.location = '<?php echo base_url().$type."/Home/load_cart"; ?>';
        })
	});
	
	function load_orders(from=null, to=null) {
	    $.post('<?php echo base_url().$type."/Home/fetch_order_list"; ?>', {
	        'f' : from, 't' : to
	    }, function(d,s,x) {
	        var a=JSON.parse(d), b="";
	        for(var i=0;i<a.orders.length;i++) {
	            b+='<tr class="or_nu" id="' + a.orders[i].id + '"> <td> <b class="order_number">#' + a.orders[i].txnno + '</b></td><td>' + a.orders[i].txndt + '</td><td>' + a.orders[i].txntype + '</td><td>' + a.orders[i].status + '</td><td class="amount"> Rs.' + a.orders[i].amount + '/-</td></tr>';
	        }
	        $('#order_list > tbody').empty().append(b);
	        
	    })
	}
</script>
</html>