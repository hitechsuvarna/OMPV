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
		<div class="mdl-cell mdl-cell--3-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="status" class="mdl-textfield__input">
					<option value="payment_pending">Pending</option>
					<option value="invoiced">Invoiced</option>
					<option value="paid">Paid</option>
					<option value="cancelled">Cancelled</option>
					<option value="hold">On Hold</option>
				</select>
				<label class="mdl-textfield__label" for="status">Select status of purchase to filter</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--3-col">
		    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" id="name" class="mdl-textfield__input">
				<label class="mdl-textfield__label" for="name">Client Name</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
		    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="search"><i class="material-icons">search</i> Search</button>
		</div>
		<div class="mdl-cell mdl-cell--4-col"></div>
		<div class="mdl-cell mdl-cell--12-col">
			<table class="purchase_table">
			    <thead>
			        <tr>
			            <th>ID</th>
			            <th>Customer Name</th>
			            <th>Date</th>
			            <th>Status</th>
			            <th>Amount</th>
			            <th>Action</th>
			        </tr>
			    </thead>
				<tbody>
				<?php for ($i=0; $i < count($txn) ; $i++) { 
					echo '<tr> <td id="'.$txn[$i]->it_id.'" class="invoice_values"> <b class="order_number">'.$txn[$i]->it_txn_no.'</b></td><td>'.$txn[$i]->ic_name.'</td><td>'.$txn[$i]->it_date.'</td><td>'.$txn[$i]->it_status.'</td><td class="amount">'.$txn[$i]->it_amount.'</td><td><button class="mdl-button mdl-js--button mdl-button--colored invoice_select" id="'.$txn[$i]->it_id.'" state="0"><i class="material-icons">done</i></button></td></tr>';	
				} ?>
				</tbody>
			</table>
		</div>
		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--accent" id="merge">
			<i class="material-icons">star</i> Merge
		</button>
		<button class="lower-button mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent mdl-button--fab" id="add">
			<i class="material-icons">add</i>
		</button>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
</body>
<script>
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	$(document).ready(function() {
	    $('#merge').css('display','none');
	    
	    var sl=0;
		$('.purchase_table').on('click', '.invoice_values', function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/invoice_edit/'; ?>" + $(this).prop('id');
		}).on('click','.invoice_select', function(e) {
		    e.preventDefault();
		    if($(this).attr('state') == 0) {
		        sl++;
		        $(this).attr('state','1');
		        $(this).addClass('mdl-button--raised');
		        $(this).children().empty();
		        $(this).children().append('trending_flat');
		    } else {
		        sl--;
		        $(this).attr('state','0');
		        $(this).removeClass('mdl-button--raised');
		        $(this).children().empty();
		        $(this).children().append('done');
		        
		    }
		    
		    if(sl>0) {
		        $('#merge').css('display','block');
		        $('#add').css('display','none');
		    } else {
		        $('#merge').css('display','none');
		        $('#add').css('display', 'block');
		    }
		    
		})

        $('#add').click(function(e) {
            e.preventDefault();
            
            window.location = "<?php echo base_url().$type.'/Transactions/invoice_add'; ?>";
        })
		$('#search').click(function(e) {
			e.preventDefault();

			$.post('<?php echo base_url().$type."/Transactions/search_invoice_records"; ?>', { 's' : $('#status').val(), 'c' : $('#name').val() }, function(d,s,x) { 
			    $('.purchase_table > tbody').empty();
			    var a=JSON.parse(d), b="";
			    for(var i=0;i<a.length; i++) { 
			        b+='<tr> <td id="' + a[i].it_id + '" class="invoice_values"> <b class="order_number">' + a[i].it_txn_no + '</b></td><td>' + a[i].ic_name + '</td><td>' + a[i].it_date + '</td><td>' + a[i].it_status + '</td><td class="amount">' + a[i].it_amount + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored invoice_select" id="' + a[i].it_id + '" state="0"><i class="material-icons">done</i></button></td></tr>'; 
			    } $('.purchase_table > tbody').append(b); });
		});

        $('#merge').click(function(e) {
            e.preventDefault();
            var arr=[];
            $("[state='1']").each(function() {
                arr.push($(this).prop("id"));
            });
            
            var ert = {message: 'Invoice is being Created. Please wait.',timeout: 2000, }; 
            snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            
            $.post('<?php echo base_url().$type."/Transactions/merge_create_invoice"; ?>', { 'i' : arr }, function(d,s,x) {
                setTimeout(function() {
                    window.location = "<?php echo base_url().$type.'/Transactions/invoice_edit/'; ?>" + d;    
                }, 2000);    
            })
            
        })

		$('#submit').click(function(e) {
			e.preventDefault();

			window.location = "<?php echo base_url().$type.'/Transactions/invoice_add'; ?>";
		});
	});
</script>
</html>