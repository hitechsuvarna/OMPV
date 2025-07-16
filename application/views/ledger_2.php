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
			<div class="mdl-textfield mdl-js-textfield" >
				<input type="text" id="ledger_name" class="mdl-textfield__input" style="font-size:2em;">
				<label class="mdl-textfield__label" for="ledger_name">Enter Ledger Name</label>
			</div>
		</div>
		
		
		<div class="mdl-cell mdl-cell--12-col">
			<table class="purchase_table">
			    <thead>
			        <tr>
			            <th>
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				                <input type="text" id="tb_date" class="mdl-textfield__input table_filter">
				                <label class="mdl-textfield__label" for="tb_date">Filter Date</label>
			                </div>
			            </th>
			            <th>
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				                <input type="text" id="tb_desc" class="mdl-textfield__input table_filter">
				                <label class="mdl-textfield__label" for="tb_desc">Filter Description</label>
			                </div>
			            </th>
			            <th>
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				                <input type="text" id="tb_credit" class="mdl-textfield__input table_filter">
				                <label class="mdl-textfield__label" for="tb_credit">Filter Credit</label>
			                </div>
			            </th>
			            <th>
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				                <input type="text" id="tb_debit" class="mdl-textfield__input table_filter">
				                <label class="mdl-textfield__label" for="tb_debit">Filter Debit</label>
			                </div>
			            </th>
			            <th>
			                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--primary" id="search">
                    			<i class="material-icons">search</i>
                    		</button>
			            </th>
			        </tr>
			    </thead>
				<tbody>
					<?php 
						for ($i=0; $i < count($ledger) ; $i++) { 
							echo '<tr id="'.$ledger[$i]->iaj_id.'"> <td>'.$ledger[$i]->iaj_date.'</td><td>'.$ledger[$i]->iaj_description.'</td><td>'.$ledger[$i]->iaj_credit.'</td><td class="amount">'.$ledger[$i]->iaj_debit.'</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--primary" id="submit">
			<i class="material-icons">done</i>
		</button>
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
    var filters = [];
	$(document).ready(function() {
	    
	    $('#submit').click(function(e) {
	        e.preventDefault();
	        filters = [];
	        filters.push({'date':$('#tb_date').val(), 'desc':$('#tb_desc').val(), 'credit':$('#tb_credit').val(), 'debit':$('#tb_debit').val()});
	        
	        $.post('<?php echo base_url().$type."/Test/ledger_create"; ?>', {
	            'filter' : filters,
	            'ledger' : $('#ledger_name').val()
	        }, function(d,s,x) {
	            var a = JSON.parse(d), t="";
	            for(var i=0;i<a.length;i++) {
	                t+='<tr id="' + a[i].iaj_id + '"> <td>' + a[i].iaj_date + '</td><td>' + a[i].iaj_description + '</td><td>' + a[i].iaj_credit + '</td><td class="amount">' + a[i].iaj_debit + '</td></tr>';
	            }
	            $('.purchase_table > tbody').empty();
	            $('.purchase_table > tbody').append(t);
	        })
	       // if($(this).prop('id') == "tb_date") {
	       //     filters.push({'date':$(this).val()});
	       // } else if($(this).prop('id') == "tb_desc") {
	       //     filters.push({'desc':$(this).val()});
	       // } else if($(this).prop('id') == "tb_credit") {
	       //     filters.push({'credit':$(this).val()});
	       // } else if($(this).prop('id') == "tb_debit") {
	       //     filters.push({'debit':$(this).val()});
	       // }
	    });
	    
	    $('#search').click(function(e) {
	        e.preventDefault();
	        filters = [];
	        filters.push({'date':$('#tb_date').val(), 'desc':$('#tb_desc').val(), 'credit':$('#tb_credit').val(), 'debit':$('#tb_debit').val()});
	        
	        $.post('<?php echo base_url().$type."/Test/filter_ledger_data"; ?>', {
	            'filter' : filters,
	        }, function(d,s,x) {
	            var a = JSON.parse(d), t="";
	            for(var i=0;i<a.length;i++) {
	                t+='<tr id="' + a[i].iaj_id + '"> <td>' + a[i].iaj_date + '</td><td>' + a[i].iaj_description + '</td><td>' + a[i].iaj_credit + '</td><td class="amount">' + a[i].iaj_debit + '</td><td></td></tr>';
	            }
	            $('.purchase_table > tbody').empty();
	            $('.purchase_table > tbody').append(t);
	        })
	       // if($(this).prop('id') == "tb_date") {
	       //     filters.push({'date':$(this).val()});
	       // } else if($(this).prop('id') == "tb_desc") {
	       //     filters.push({'desc':$(this).val()});
	       // } else if($(this).prop('id') == "tb_credit") {
	       //     filters.push({'credit':$(this).val()});
	       // } else if($(this).prop('id') == "tb_debit") {
	       //     filters.push({'debit':$(this).val()});
	       // }
	    });
	    
		$('#status').val('pending');
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

		/*$('.order_table').on('click', 'tr', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/order_details/'; ?>" + $(this).prop('id');
		});*/
		
// 		$('.purchase_table').on('click', 'tr', function(e) {
// 			e.preventDefault();
// 			window.location = "<?php echo base_url().$type.'/Transactions/order_details/'; ?>" + $(this).prop('id');
// 		});

		$('#print').click(function(e) {
		    e.preventDefault();
		    print_reciept();
		})
		
		$('#fixed-header-drawer-exp').keyup(function(e) {
			e.preventDefault();
			$.post('<?php echo base_url().$type."/Transactions/search_orders"; ?>', { 'keyword' : $(this).val() }, function(d,s,x) { $('.order_table').empty(); var a=JSON.parse(d), b=""; for(var i=0;i<a.length; i++) { console.log(i); b+='<tr id="' + a[i].it_id + '"> <td> <b class="order_number">#' + a[i].it_txn_no + '</b> - ' + a[i].ic_name + '<br> <i>' + a[i].it_date + '</i> </td> <td class="amount"><i>' + a[i].it_status + '</i> </td> </tr>'; } $('.order_table').append(b); });
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