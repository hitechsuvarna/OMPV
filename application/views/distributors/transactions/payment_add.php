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
				<input type="text" data-type="date" id="contact" class="mdl-textfield__input" value="">
				<label class="mdl-textfield__label" for="contact">Search Contact</label>
			</div>
		</div>
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
				<select id="txn_type" class="mdl-textfield__input">
				    <option value="delivery">Delivery only</option>
				    <option value="delivery_purchase">Delivery Purchase only</option>
				    <option value="invoice">Invoice</option>
				    <option value="purchase">Purchases</option>
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
			<button class="mdl-button mdl-js--button mdl-button--colored" id="download">
			    <i class="material-icons">file_download</i> Download
			</button>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="text-align:center;">
		    <h4 style="background-color:#1433c1;color:#fff; font-weight: bold;padding:10px; border-radius:5px;" id="name_total"></h4>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="text-align:right;">
		    <p style="color:#000; font-weight: bold;" id="sel_total"></p>
		</div>
		
		
		<div class="mdl-cell mdl-cell--12-col" style="height:350px; overflow-y:auto;">
		    <table class="purchase_table">
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
		<div class="mdl-cell mdl-cell--12-col" style="border:1px solid #ccc; border-radius:10px; box-shadow: 0px 2px 10px #ccc;">
		    <div class="mdl-grid">
		        <div class="mdl-cell mdl-cell--4-col">
		            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" data-type="date" id="pay_date" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="pay_date">Payment Date</label>
					</div>
		        </div>
		        <div class="mdl-cell mdl-cell--4-col">
		            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<select id="pay_type" class="mdl-textfield__input">
						    <option value="credit">Credit</option>
						    <option value="debit">Debit</option>
						</select>
						<label class="mdl-textfield__label" for="pay_type">Payment Type</label>
					</div>
		        </div>
		        <div class="mdl-cell mdl-cell--4-col">
		            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="pay_mode" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="pay_mode">Mode of Payment</label>
					</div>
		        </div>
		        <div class="mdl-cell mdl-cell--4-col">
		            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="pay_desc" class="mdl-textfield__input" value="">
						<label class="mdl-textfield__label" for="pay_desc">Payment Description</label>
					</div>
		        </div>
		        <div class="mdl-cell mdl-cell--4-col">
		            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="pay_amt" class="mdl-textfield__input" value="<?php if(isset($edit_txn)) { echo $edit_txn[0]->itp_amt; } ?>">
						<label class="mdl-textfield__label" for="pay_amt">Payment Amount</label>
					</div>
		        </div>
		        <div class="mdl-cell mdl-cell--4-col" style="text-align:right;">
		            <button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised" id="update_pay">
		                <i class="material-icons">done</i> Save
		            </button>
		        </div>
		    </div>
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
	var sel_amt_calc = [];
	var sel_total_amt =0.0;
	var snackbarContainer = document.querySelector('#demo-snackbar-example');
    
	
	$(document).ready( function() {
    	<?php
    		for ($i=0; $i < count($modes) ; $i++) { 
    			echo "mode.push('".$modes[$i]->itp_mode."');";
    		}
    	?>
    	$( "#pay_mode" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(mode, request.term);
                response(results.slice(0, 10));
            }
        });
        
        <?php
    		for ($i=0; $i < count($contacts) ; $i++) { 
    			echo "contacts.push('".$contacts[$i]->ic_name."');";
    		}
    	?>
    	$( "#contact" ).autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(contacts, request.term);
                response(results.slice(0, 10));
            }
        });
        
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
	    $('#update_pay').click(function(e) {
	        e.preventDefault();
	        var url = "";
	        if(edit_flag == true) {
	            url = '<?php echo base_url().$type."/Transactions/update_payment_txn/"; ?>/' + edit_index;
	        } else {
	            url = '<?php echo base_url().$type."/Transactions/update_payment_txn/"; ?>/';
	        }
	        var sel_txns_amt = [];
	        
	        $('input[type=text]').each(function(){
                if($(this).hasClass('txn_pay_amt')) {
                    for(var i=0;i<sel_txns.length; i++) {
                        if($(this).prop('id') == sel_txns[i]) {
                            sel_txns_amt.push($(this).val());
                        }
                    }
                }
            })
            
            console.log(sel_txns_amt);
	        
	        $.post(url, {
	            'contact' : $('#contact').val(),
	            'date' : $('#pay_date').val(),
	            'mode' : $('#pay_mode').val(),
	            'type' : $('#pay_type').val(),
	            'detail' : $('#pay_desc').val(),
	            'amt' : $('#pay_amt').val(),
	            'type' : $('#txn_type').val(),
	            'agreement' : $('#pay_type').val(),
	            'sel_txns' : sel_txns,
	            'sel_txns_val' : sel_txns_amt,
	            'sel_amt_cal' : sel_amt_calc
	        }, function(d,s,x) {
	            var ert = {message: 'Payment Updated.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
                
	            var c = $('#contact').val();
	            get_transactions($('#contact').val(), $('#txn_type').val(), $('#from_date').val(), $('#to_date').val());
	        })
	    })
	    
	    $('#search_txn').click(function(e) {
	        e.preventDefault();
	        get_transactions($('#contact').val(), $('#txn_type').val(), $('#from_date').val(), $('#to_date').val());
	    })
        
        $('.purchase_table').on('click','.edit_pay', function(e) {
            e.preventDefault();
            var ert = {message: 'Fetching Please Wait...',timeout: 2000, }; 
            snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
            edit_flag=true;
            edit_index=$(this).prop('id');
            
            $.post('<?php echo base_url().$type."/Transactions/get_payment_txns/"; ?>' + $(this).prop('id'),{}, function(d,s,x) {
                var a=JSON.parse(d);
                
                $('#pay_date').val(a[0].itp_date);
                // $('#pay_type').val(a[0].itp_date);
                $('#pay_mode').val(a[0].itp_mode);
                $('#pay_desc').val(a[0].itp_details);
                if(a[0].itp_agreement == "credit") {
                    $('#pay_amt').val(a[0].itp_credit);
                    $('#pay_type').val(a[0].itp_agreement);
                } else {
                    $('#pay_amt').val(a[0].itp_debit);
                    $('#pay_type').val(a[0].itp_agreement);
                }
                
                
            })
        }).on('click','.delete_pay', function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/delete_payment_transaction"; ?>', { 'ptid' : $(this).prop('id') }, function(d,s,x) {
                    var ert = {message: 'Record Deleted',timeout: 2000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
            
                    get_transactions($('#contact').val(), $('#txn_type').val(), $('#from_date').val(), $('#to_date').val());
                }, "text");
        }).on('click', '.view_txn', function(e) {
            e.preventDefault();
            if($(this).attr('tmp') == "Delivery") {
                window.location = "<?php echo base_url().$type.'/Transactions/print_delivery/'; ?>"  + $(this).prop('id');    
            } else if($(this).attr('tmp') == "Invoice") {
                window.location = "<?php echo base_url().$type.'/Transactions/invoice_edit/'; ?>"  + $(this).prop('id');
            } else if($(this).attr('tmp') == "Purchase") {
                window.location = "<?php echo base_url().$type.'/Transactions/purchase_edit/'; ?>"  + $(this).prop('id');
            } else if($(this).attr('tmp') == "Credit Note" || $(this).attr('tmp') == "Debit Note") {
                window.location = "<?php echo base_url().$type.'/Transactions/cd_note_edit/'; ?>"  + $(this).prop('id');
            }
            
        }).on('click','.show_records', function(e) {
            e.preventDefault();
            getpaymenthistory($(this).prop('id'));
            setTimeout(function() {
                $('#myModal').modal('toggle');    
            }, 2000);
            
        })
        
        $('.purchase_table').on('click','.select_pay', function(e) {
            e.preventDefault();
            if($(this).attr('sel') == "false") {
                $(this).children(".material-icons").empty();
                $(this).children(".material-icons").append('check_circle');
                $(this).attr('sel','true');
                sel_txns.push($(this).prop('id'));
                sel_amt_calc.push({'c' : parseFloat($(this).attr('credit')), 'd' : parseFloat($(this).attr('debit'))});
                sel_total_amt+=parseFloat($(this).attr('credit')) - parseFloat($(this).attr('debit'));
                console.log("c: " + $(this).attr('credit') + ' d: ' + $(this).attr('debit'));
                
            } else {
                $(this).children(".material-icons").empty();
                $(this).children(".material-icons").append('check_circle_outline');
                $(this).attr('sel','false');
                var index = sel_txns.indexOf($(this).prop('id'));
                if(index!=-1){
                    sel_txns.splice(index, 1);
                    sel_amt_calc.splice(index, 1);
                }
                sel_total_amt-=parseFloat($(this).attr('credit')) - parseFloat($(this).attr('debit'));
            }
            
            $('#sel_total').empty();
            $('#sel_total').append("Selected Total: Rs." + sel_total_amt);
            $('#pay_amt').empty();
            if(sel_total_amt < 0) {
                $('#pay_amt').val((sel_total_amt) * -1);    
            } else {
                $('#pay_amt').val(sel_total_amt)
            }
            
        })
        
        $('#download').click(function(e) {
		    e.preventDefault();
		    $('.purchase_table').tableExport({
                // Displays table headings (th or td elements) in the <thead>
                headings: true,                    
                // Displays table footers (th or td elements) in the <tfoot>    
                footers: true, 
                // Filetype(s) for the export
                formats: ["xls"],           
                // Filename for the downloaded file
                filename: $('#contact').val() + '-' + $('#txn_type').val(),                         
                // Style buttons using bootstrap framework  
                bootstrap: true,                     
                // Position of the caption element relative to table
                position: "top",                   
                // (Number, Number[]), Row indices to exclude from the exported file(s)
                ignoreRows: null,       
                // (Number, Number[]), column indices to exclude from the exported file(s)              
                ignoreCols: null,                
                // Selector(s) to exclude cells from the exported file(s)       
                ignoreCSS: ".tableexport-ignore",  
                // Selector(s) to replace cells with an empty string in the exported file(s)       
                emptyCSS: ".tableexport-empty",   
                // Removes all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)     
                trimWhitespace: false         

            });
		})
    });
	
	function get_transactions(contact, type, from, to) {
	    $.post('<?php echo base_url().$type."/Transactions/get_payment_contact_txns/"; ?>', {
	        'c' : contact,
	        't' : type,
	        'fr' : from,
	        'to' : to
	    }, function(d,s,x) {
	        var a = $('.purchase_table > tbody');
	        a.empty();
	        var p=JSON.parse(d), x="";
	        var credit=0.0, debit=0.0, bal=0;
	        console.log(p.main);
	        var b=p.records;
	        $('#name_total').empty();
	        $('#name_total').append("Total Ledger Balance: " + p.main[0].results);
	        for(var i=0;i<b.length; i++) {
	            credit +=parseInt(b[i].credit);
	            debit +=parseInt(b[i].debit);
	            x+='<tr><td>' + b[i].descr + '</td><td>' + b[i].date + '</td><td style="text-align:right;">' + b[i].credit + '</td><td style="text-align:right;">' + b[i].debit + '</td><td>';
	            if(b[i].info == "payment") {
	                x+='<button class="mdl-button mdl-js--button mdl-button--colored edit_pay" id="' + b[i].id +'"><i class="material-icons">create</i></button><button class="mdl-button mdl-js--button mdl-button--colored delete_pay" id="' + b[i].id + '"><i class="material-icons">delete</i></button><button class="mdl-button mdl-js--button mdl-button--colored show_records" id="' + b[i].id + '"><i class="material-icons">launch</i></button>';
	            } else {
	                x+='<button class="mdl-button mdl-js--button view_txn" id="' + b[i].id + '" tmp="' + b[i].info + '"><i class="material-icons">visibility</i></button><button class="mdl-button mdl-js--button mdl-button--colored select_pay" id="' + b[i].id + '" sel="false" ';
	                
	                if(b[i].credit == 0) {
	                    bal=b[i].pay_amt - b[i].debit;
	                    x+='credit="' + b[i].pay_amt + '" debit="' + b[i].debit + '" ';
	                } else {
	                    bal=b[i].credit - b[i].pay_amt;
	                    x+='credit="' + b[i].credit + '" debit="' + b[i].pay_amt + '" ';
	                }
	                x+='><i class="material-icons">check_circle_outline</i></button><input style="width: 100px;outline: none;padding: 5px;text-align: center;border-radius: 5px;';
	                if(bal >= 0) {
	                    x+='border: 1px solid #8bc34a;';
	                } else {
	                    x+='border: 1px solid #ff5722;';
	                }
	                x+='" type="text" id="' + b[i].id + '" class=" txn_pay_amt" placeholder="Payment Amount" value="' + b[i].pay_amt + '"></div>';
	            }
	            x+='</td></tr>';
	        }
	        a.append(x);
	        $('.purchase_table > tfoot').empty();
	        var y="";
	        y+='<tr><td colspan="2">Total</td><td style="text-align:right;">' + credit + ' Cr</td><td style="text-align:right;">' + debit + ' Dr</td><td></td></tr>';
	        y+='<tr><td colspan="3">Balance</td><td style="text-align:right;">';
	        if((credit-debit) > 0) {
	            y+= (credit-debit) + " Cr";
	        } else {
	            y+=((credit-debit)*-1) + " Dr";
	        }
	        y+='</td><td></td></tr>';
	        $('.purchase_table > tfoot').append(y);
	        
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
        sel_amt_calc=[];
        sel_total_amt=0.0;
        $('#sel_total').empty();
        
	}
</script>
</html>