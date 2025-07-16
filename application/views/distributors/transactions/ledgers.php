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
        display: block;
        overflow-x:auto;
    }

    #dealers > thead > tr {
        /*box-shadow: 0px 5px 5px #ccc;*/
        border:1px solid #ccc;
    }

    #dealers > thead > tr > th {
        padding: 10px;
    }

    #dealers > tfoot {
        /*box-shadow: 0px 5px 5px #ccc;*/
        border: 1px solid #ccc;
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
    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
        <div class="mdl-tabs__tab-bar">
            <a href="#contact-ledger" class="mdl-tabs__tab is-active">Contact Ledger</a>
            <a href="#tax-ledger" class="mdl-tabs__tab">Tax Ledger</a>
            <a href="#account-ledger" class="mdl-tabs__tab">Account Ledger</a>
        </div>
        <div class="mdl-tabs__panel is-active" id="contact-ledger">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--4-col">
                    <div class="mdl-card mdl-shadow--2dp" style="min-height: 0px;">
                        <div class="mdl-card__supporting_text" style="min-height:0px;padding:10px 20px 0px 20px; ">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                				<input type="text" id="i_contact_auto" class="mdl-textfield__input" value="">
                				<label class="mdl-textfield__label" for="i_contact_auto">Search Contacts</label>
                			</div>
                		</div>
                	</div>  
                </div>
                <div class="mdl-cell mdl-cell--8-col">
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored filter" style="width:100%;">Filter</button>
                            
                    <div class="mdl-card mdl-shadow--2dp filter-primary" style="min-height: 0px;">
                        <div class="mdl-card__supporting_text filter-secondary" style="min-height:0px; height:0px;">
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="i_from_date" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="i_from_date">From Date</label>
                        			</div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="i_to_date" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="i_to_date">To Date</label>
                        			</div>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_sale">
                                        <input type="checkbox" id="i_sale" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Sale</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_purchase">
                                        <input type="checkbox" id="i_purchase" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Purchase</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_delivery">
                                        <input type="checkbox" id="i_delivery" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Delivery</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_delivery">
                                        <input type="checkbox" id="i_credit_note" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Credit Note</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_delivery">
                                        <input type="checkbox" id="i_debit_note" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Debit Note</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_entry">
                                        <input type="checkbox" id="i_entry" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Manual Entries</span>
                                    </label>
                                </div>
                            </div>
        			        <div>
        			            <b>Mode of Payment</b>
            					<ul id="i_pay_mode" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
            					</ul>
        			        </div>
        					<button class="mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--fab" id="apply_filter">
                        		<i class="material-icons">filter_list</i>
                        	</button>
                        </div>
                    </div>
                            
        		</div>
        		<div class="mdl-cell mdl-cell--12-col">
        		    <table id="dealers">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Txn No</th>
                                <th>Details</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php //for ($i=0; $i < count($vendors) ; $i++) { 
                                // echo '<tr id="'.$vendors[$i]->ic_id.'"> <td>'.$vendors[$i]->ic_name.'</td> </tr>';
                                //}
                            ?>
                        </tbody>
                        <tfoot >
                            <tr>
                                <th colspan="4">Total</th><th></th><th></th>
                            </tr>
                            <tr>
                                <th colspan="4">Balance</th><th></th><th></th>
                            </tr>
                        </tfoot>
                    </table>
        		</div>
        		<div class="mdl-cell mdl-cell--12-col">
        		    <div class="mdl-card mdl-shadow--2dp">
        		        <div class="mdl-card__title" style="height:70px;">
        		            <h4>Add a manual entry</h4>
        		        </div>
        		        <div class="mdl-card__supporting-text" style="width:auto;">
            		        
            		        <div class="mdl-grid">
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_date" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_date">Select Date</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--4-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_details" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_details">Details</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<select id="l_type" class="mdl-textfield__input">
                        				    <option value="credit">Credit</option>
                        				    <option value="debit">Debit</option>
                        				</select>
                        				<label class="mdl-textfield__label" for="l_type">Transaction Type</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_amt" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_amt">Amount</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <button class="mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--colored" id="l_save">
                                		<i class="material-icons">done</i>
                                	</button>
            		            </div>
            		        </div>
                        </div>
                    </div>
        		</div>
        		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit">
            		<i class="material-icons">print</i>
            	</button>
            </div>
        </div>
        <div class="mdl-tabs__panel" id="tax-ledger">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--4-col">
                    <div class="mdl-card mdl-shadow--2dp" style="min-height: 0px;">
                        <div class="mdl-card__supporting_text" style="min-height:0px;padding:10px 20px 0px 20px; ">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                				<input type="text" id="i_tax_auto" class="mdl-textfield__input" value="">
                				<label class="mdl-textfield__label" for="i_tax_auto">Search Tax Groups</label>
                			</div>
                		</div>
                	</div>  
                </div>
                <div class="mdl-cell mdl-cell--8-col">
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored filter-tax" style="width:100%;">Filter</button>
                            
                    <div class="mdl-card mdl-shadow--2dp filter-primary-tax" style="min-height: 0px;">
                        <div class="mdl-card__supporting_text filter-secondary-tax" style="min-height:0px; height:0px;">
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="i_from_date_tax" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="i_from_date_tax">From Date</label>
                        			</div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="i_to_date_tax" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="i_to_date_tax">To Date</label>
                        			</div>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_sale">
                                        <input type="checkbox" id="i_sale_tax" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Sale</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_purchase">
                                        <input type="checkbox" id="i_purchase_tax" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Purchase</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_delivery">
                                        <input type="checkbox" id="i_credit_note_tax" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Credit Note</span>
                                    </label>
                                </div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_delivery">
                                        <input type="checkbox" id="i_debit_note_tax" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Debit Note</span>
                                    </label>
                                </div>
                                <!--<div class="mdl-cell mdl-cell--4-col">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="i_entry">
                                        <input type="checkbox" id="i_entry_tax" class="mdl-checkbox__input" checked>
                                        <span class="mdl-checkbox__label">Manual Entries</span>
                                    </label>
                                </div>-->
                            </div>
        			        <!--<div>
        			            <b>Mode of Payment</b>
            					<ul id="i_pay_mode_tax" class="mdl-textfield__input" style="margin-top: 6px;width: auto;">
            					</ul>
        			        </div>-->
        					<button class="mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--fab" id="apply_filter_tax">
                        		<i class="material-icons">filter_list</i>
                        	</button>
                        </div>
                    </div>
                            
        		</div>
        		<div class="mdl-cell mdl-cell--12-col">
        		    <table id="tax">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Txn No</th>
                                <th>Basic Amt</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
        		</div>
        		<!--<div class="mdl-cell mdl-cell--12-col">
        		    <div class="mdl-card mdl-shadow--2dp">
        		        <div class="mdl-card__title" style="height:70px;">
        		            <h4>Add a manual entry</h4>
        		        </div>
        		        <div class="mdl-card__supporting-text" style="width:auto;">
            		        
            		        <div class="mdl-grid">
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_date" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_date">Select Date</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--4-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_details" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_details">Details</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<select id="l_type" class="mdl-textfield__input">
                        				    <option value="credit">Credit</option>
                        				    <option value="debit">Debit</option>
                        				</select>
                        				<label class="mdl-textfield__label" for="l_type">Transaction Type</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        				<input type="text" id="l_amt" class="mdl-textfield__input" value="">
                        				<label class="mdl-textfield__label" for="l_amt">Amount</label>
                        			</div>
            		            </div>
            		            <div class="mdl-cell mdl-cell--2-col">
            		                <button class="mdl-button mdl-button-done mdl-js-button mdl-js-ripple-effect mdl-button--colored" id="l_save">
                                		<i class="material-icons">done</i>
                                	</button>
            		            </div>
            		        </div>
                        </div>
                    </div>
        		</div>-->
        		<button class="lower-button mdl-button mdl-button-done mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--accent" id="submit_tax">
            		<i class="material-icons">print</i>
            	</button>
            </div>
        </div>
        <div class="mdl-tabs__panel" id="account-ledger">
            
        </div>
        </div>
    
	
</div>
</div>
</body>
<script>
	$('#i_from_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#i_to_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#l_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	
	
</script>
<script type="text/javascript">
	$(document).ready( function() {
    	var contact_data = [];
    	
    	<?php
    		for ($i=0; $i < count($contacts) ; $i++) { 
    			echo "contact_data.push('".$contacts[$i]->ic_name."');";
    		}
    	?>

    	$('#i_contact_auto').autocomplete({
            source: contact_data
        });
        
        var mode_data = [];
    	
    	<?php
    		for ($i=0; $i < count($modes) ; $i++) { 
    			echo "mode_data.push('".$modes[$i]->itp_mode."');";
    		}
    	?>
    	
    	$('#i_pay_mode').tagit({
    		autocomplete : { delay: 0, minLenght: 5},
    		allowSpaces : true,
    		availableTags : mode_data,
    	});
    	
    });
</script>

<script>
    var disp_flg = false;
    var print_content_head = "<table><thead> <tr> <th>Date</th> <th>Type</th> <th>Txn No</th> <th>Details</th> <th>Credit</th> <th>Debit</th> </tr> </thead> <tbody>";
    $(document).ready(function(){
        $('.filter').click(function(e) {
            e.preventDefault();
            if(disp_flg == false) {
                $('.filter-primary').css('padding', '20px 40px 20px 40px');
                $('.filter-container').animate({ height: '300px' });
                $('.filter-primary').animate({ height: '550px' });
                $('.filter-secondary').animate({ height: '370px'});
                disp_flg = true;
            } else {
                $('.filter-container').animate({ height: '0px' });
                $('.filter-primary').animate({ height: '0px' }, function() { $('.filter-primary').css('padding', '0px'); });
                $('.filter-secondary').animate({ height: '0px', padding: '0px'});
                
                disp_flg = false;
            }
                
            
        })
        
        $('#i_contact_auto').change(function(e) {
            e.preventDefault(); 
            get_txns($(this).val());
        });
        
        $('#l_save').click(function (e) {
            e.preventDefault();
            save_entry($('#i_contact_auto').val(), null, null, null, null, false);
        });
        
        $('#apply_filter').click(function(e){
            e.preventDefault();
            var type = [];
            
            if($('#i_sale')[0].checked == true){
                type.push("Sale");
            }
            if($('#i_delivery')[0].checked == true){
                type.push("Delivery");
            }
            if($('#i_purchase')[0].checked == true){
                type.push("Purchase");
            }
            if($('#i_credit_note')[0].checked == true){
                type.push("Credit Note");
            }
            if($('#i_debit_note')[0].checked == true){
                type.push("Debit Note");
            }
            
            var from=null, to=null
            if($('#i_from_date').val() != "") {
                from = $('#i_from_date').val();
            }
            if($('#i_to_date').val() != "") {
                to = $('#i_to_date').val();
            }
            
            var pays = [];
            $('#i_pay_mode > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					pays.push(tmpstr);
				}
			});
			console.log($('#i_contact').val());
			get_txns($('#i_contact_auto').val(), from, to, type, pays, $('#i_entry')[0].checked, true);
        });
        
        $('#submit').click(function(e) {
            e.preventDefault();
            
            print_reciept();
        });
        
        $('#dealers').on('click','.del', function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/delete_ledger_entry"; ?>', { 'txid' : $(this).prop('id') }, function(d,s,x) { window.location = "<?php echo base_url().$type.'/Transactions/ledgers'; ?>"; }, "text");
        })
    });
    
    function save_entry(contact) {
        $.post('<?php echo base_url().$type.'/Transactions/save_ledger_entry/'; ?>', {
                'c' : contact,
                'date' : $('#l_date').val(),
                'details' : $('#l_details').val(),
                'type' :  $('#l_type').val(),
                'amt' : $('#l_amt').val()
            } , function(d,s,x) { 
                get_txns(contact);
            }, "text");
        
    }
    
    function get_txns(contact, from_date, to_date, type, payment, entry, filter) {
        $.post('<?php echo base_url().$type.'/Transactions/get_ledger_data/'; ?>', {
                'c' : contact,
                'from' : from_date,
                'to' : to_date,
                'type' : type,
                'payment' : payment,
                'filter' : filter,
                'entry' : entry
            } , function(d,s,x) {
                $('#dealers > tbody').empty();
                var a=JSON.parse(d), b="";
                var credit=0,debit=0, balance=0;
                for(var i=0;i<a.length;i++) {
                    b+='<tr>';                    
                    b+='<td>' + a[i].txdate + '</td>';
                    b+='<td>' + a[i].type + '</td>';
                    b+='<td>' + a[i].txno + '</td>';
                    b+='<td>' + a[i].txdetails + '</td>';
                    b+='<td>' + a[i].credit + '</td>';
                    b+='<td>' + a[i].debit + '</td>';
                    if(a[i].type == "Entry") {
                        b+='<td class="del" id="' + a[i].txid + '"><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored"><i class="material-icons">delete</i></button></td>'
                    }
                    b+='</tr>';
                    credit+=parseInt(a[i].credit);
                    debit+=parseInt(a[i].debit);
                }
                balance = credit-debit;
                
                console.log(debit);
                $('#dealers > tbody').append(b);
                $('#dealers > tfoot').empty();
                $('#dealers > tfoot').append('<tr><th colspan="4">Total</th><th>' + credit + '</th><th>' + debit + '</th></tr>');
                $('#dealers > tfoot').append('<tr><th colspan="5">Balance</th><th>' + balance + '</th></tr>');
                
                
            }, "text");
    }
    
    function print_reciept() {
		var mywindow = window.open('', 'Ledger', fullscreen=1);
		mywindow.document.write('<h3>Ledger for ' + $('#i_contact_auto').val() + '</h3><table style="width:100%;border:1px solid;" border="1">' + $('#dealers')[0].innerHTML + '</table>');
		mywindow.document.close();
		mywindow.focus();
		mywindow.print();
		mywindow.close();
	}
</script>
</html>