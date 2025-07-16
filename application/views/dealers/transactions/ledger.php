<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<link href="<?php echo base_url().'assets/css/tableexport.css'; ?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/js/FileSaver.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/tableexport.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/Blob.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/xlsx.core.min.js'; ?>"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<style type="text/css">
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

	.purchase_table > tfoot > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tfoot > tr > td {
		padding: 15px;
		font-weight: bold;
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
    
    #dealers {
        width: 100%;
        text-align: left;
        border: 0px solid #ccc;
        border-collapse: collapse;
    }

    #dealers > thead > tr {
        box-shadow: 0px 5px 5px #ccc;
    }

    #dealers > thead > tr > th {
        padding: 10px;
    }

    #dealers > tfoot {
        box-shadow: 0px 5px 5px #ccc;
    }

    #dealers > tfoot > tr > th {
        padding: 10px;
    }

    #dealers > tbody > tr > td {
        padding: 15px;
    }

    #dealers > tbody > tr {
        border-bottom: 1px solid #ccc;
    }

    #dealers > tbody > tr > td > a {
        color: #ff0000;
        text-decoration: none;
    }
</style>

<main class="mdl-layout__content">
    <div class="mdl-grid" style="display:non;">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="txn_type" class="mdl-textfield__input">
				    <option value="delivery">Delivery only</option>
				    <option value="invoice">Invoice</option>
				</select>
				<label class="mdl-textfield__label" for="txn_type">Transaction Type</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--2-col">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" data-type="date" id="from_date" class="mdl-textfield__input" value="">
				<label class="mdl-textfield__label" for="from_date">From</label>
			</div>
        </div>
        <div class="mdl-cell mdl-cell--2-col">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<input type="text" data-type="date" id="to_date" class="mdl-textfield__input" value="">
				<label class="mdl-textfield__label" for="to_date">To</label>
			</div>
        </div>
		<div class="mdl-cell mdl-cell--4-col">
			<button class="mdl-button mdl-js--button mdl-button--colored" id="search_txn">
			    <i class="material-icons">search</i> Search
			</button>
			<!--<button class="mdl-button mdl-js--button mdl-button--colored" id="download">-->
			<!--    <i class="material-icons">file_download</i> Download-->
			<!--</button>-->
		</div>
		<div class="mdl-cell mdl-cell--8-col" style="text-align:right;">
		    <p style="color:#ff0000;" id="sel_total"></p>
		</div>
		
		
		<div class="mdl-cell mdl-cell--12-col">
		    <table class="purchase_table" id="ledger_records">
		        <thead>
		            <tr>
		                <th>Description</th>
		                <th>Date</th>
		                <th>Credit</th>
		                <th>Debit</th>
		                <th>Action</th>
		            </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                    
                </tfoot>
            </table>
		</div>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
    
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">List of Records against payment</h4>
                </div>
                <div class="modal-body">
                    <table id="record_history" class="purchase_table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Details</th>
                                <th>Amount</th>
                                <td>Paid</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
	var mode = [];
	var contacts = [];
    var edit_flag = false;
	var edit_index = 0;
	var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	
	var sel_txns = [];
	var sel_total_amt =0.0;
	var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
	
	$(document).ready( function() {
    	
    });
</script>
<script>
	$('#pay_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#from_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#to_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	<?php 
		if(!isset($edit_txn)) {
		    echo "$('#pay_date').val(s_dt);"; 
		    echo "$('#from_date').val(s_dt);"; 
		    echo "$('#to_date').val(s_dt);"; 
		    
		}
	?>
</script>
<script>
    $(document).ready(function() {
	    
	    $('#search_txn').click(function(e) {
	        e.preventDefault();
	        get_transactions($('#txn_type').val(), $('#from_date').val(), $('#to_date').val());
	    })
        
        $('#ledger_records').on('click','.show_records', function(e) {
            e.preventDefault();
            getpaymenthistory($(this).prop('id'));
            $('#myModal').modal('toggle');
        })
    });
	
	function get_transactions(type, from, to) {
	    $.post('<?php echo base_url().$type."/Transactions/get_payment_contact_txns/"; ?>', {
	        't' : type,
	        'fr' : from,
	        'to' : to
	    }, function(d,s,x) {
	        var a = $('#ledger_records > tbody');
	        a.empty();
	        var b=JSON.parse(d), x="";
	        var credit=0.0, debit=0.0;
	        for(var i=0;i<b.length; i++) {
	            credit +=parseInt(b[i].credit);
	            debit +=parseInt(b[i].debit);
	            x+='<tr><td>' + b[i].descr + '</td><td>' + b[i].date + '</td><td style="text-align:right;">' + b[i].credit + '</td><td style="text-align:right;">' + b[i].debit + '</td>';
	            if(b[i].info == "payment") {
	                x+='<td><button class="mdl-button mdl-js--button mdl-button--colored show_records" id="' + b[i].id + '"><i class="material-icons">launch</i></button></td>';
	            } else {
	                x+='<td></td>';
	            }
	            x+='</tr>';
	        }
	        a.append(x);
	        $('#ledger_records > tfoot').empty();
	        $('#ledger_records > tfoot').append('<tr><td colspan="2">Total</td><td style="text-align:right;">' + credit + '</td><td style="text-align:right;">' + debit + '</td><td></td></tr>');
	        var bal=credit-debit;
	        if(bal < 0) { bal=bal*-1; }
	        $('#ledger_records > tfoot').append('<tr><td colspan="3">Balance</td><td style="text-align:right;">' + bal + '</td><td></td></tr>');
	        reset_fields();
	    })
	}
	
	function getpaymenthistory(id) {
	    $.post('<?php echo base_url().$type."/Transactions/get_payment_history/"; ?>', {
	        'i' : id
	    }, function(d,s,x) {
	        var a=JSON.parse(d), x="";
	        $('#record_history > tbody').empty();
	        for(var i=0;i<a.length;i++) {
	            x+='<tr><td>' + a[i].date + '</td><td>' + a[i].info + '</td><td>' + a[i].amount + '</td><td>' + a[i].paid_amount + '</td></tr>';
	        }
	        $('#record_history > tbody').append(x);
	        
	    });
	}
	
	function reset_fields() {
	    $('#pay_date').val(s_dt);
        $('#pay_type').val('');
        $('#pay_mode').val('');
        $('#pay_desc').val('');
        $('#pay_amt').val('');
        sel_txns = [];
        sel_total_amt=0.0;
        $('#sel_total').empty();
        
	}
</script>
</html>