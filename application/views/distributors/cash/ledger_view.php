<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<script src="<?php echo base_url().'assets/js/tableHTMLExport.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/jquery.table2excel.min.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.10/jspdf.plugin.autotable.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.js"></script>

<style>
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

	.purchase_table > tfoot > tr > td, th {
		padding: 15px;
	}
	
	.selected_account {
	    background-color:#eee;
	}
	
	
</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet">
            <div>
                <button class="mdl-button mdl-button--colored mdl-button--icon mdl-button--raised" id="acc_home"><i class="material-icons">home</i></button>
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:60%;">
					<input type="text" id="search_master" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="search_master">Search</label>
				</div>
				<button class="mdl-button mdl-button--colored" id="search_btn"><i class="material-icons">search</i></button>    
            </div>
            
            <div class="mdl-card mdl-shadow--2dp" style="height:75vh;overflow:auto;">
                <table class="purchase_table" id="account_table" style="width:auto; margin:0px 10px;">
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-cell mdl-cell--8-col mdl-cell--5-col-tablet">
            <div style="text-align:right;">
                
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:10em;margin: 0px 1vw;">
					<select id="txn_type" class="mdl-textfield__input">
					    <option value="all">All Transactions</option>
					    <option value="delivery">Delivery</option>
					    <option value="invoice">Invoice</option>
				    </select>
					<label class="mdl-textfield__label" for="txn_type">Transaction Type</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:10em;margin: 0px 1vw;">
					<input type="text" id="from_filter" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="from_filter">From Date</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:10em;margin: 0px 1vw;">
					<input type="text" id="to_filter" class="mdl-textfield__input">
					<label class="mdl-textfield__label" for="to_filter">To Date</label>
				</div>
                <button class="mdl-button mdl-button--colored" id="filter_records"><i class="material-icons">sort</i></button>
                <button id="demo-menu-lower-right" class="mdl-button mdl-js-button mdl-button--icon">
                    <i class="material-icons">more_vert</i>
                </button>
                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right">
                    <li class="mdl-menu__item"><button class="mdl-button mdl-button--colored" id="download_records"><i class="material-icons">cloud_download</i> Download List</button></li>
                    <li class="mdl-menu__item"><button class="mdl-button mdl-button--colored" id="display_current"><i class="material-icons">sync</i> Selection Records</button></li>
                    <li class="mdl-menu__item"><button class="mdl-button mdl-button--colored" id="opening_balance"><i class="material-icons">money</i> Add Opening Balance</button></li>
                    <li class="mdl-menu__item"><button class="mdl-button mdl-button--colored mdl-button--raised" id="add_entry"><i class="material-icons">add</i> Account Entry</button></li>
                </ul>
                
            </div>
            <div class="mdl-card">
                <table class="purchase_table" id="ledger_table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Narration</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th class="ignore">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
                <table class="purchase_table" id="ledger_table_print" style="display:none;">
                    <thead>
                        <tr>
                            <th style="width:250px;">Date</th>
                            <th style="width:250px;">Account</th>
                            <th style="width:250px;">Narration</th>
                            <th style="width:250px;">Debit</th>
                            <th style="width:250px;">Credit</th>
                            <th class="ignore">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
</main>
<div id="add_journal_entry" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Entry</h4>
            </div>
            <div class="modal-body">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--12-col">
                        <p>Type of Transaction</p>
                        <button class="mdl-button mdl-button--colored mdl-button--raised" id="issue_entry"><i class="material-icons">arrow_upward</i> Issue</button>
                        <button class="mdl-button mdl-button--colored" id="receive_entry"><i class="material-icons">arrow_downward</i> Receive</button>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<select id="entry_account" class="mdl-textfield__input">
        					    <option value="0">Select Account</option>
        					    <?php for($i=0;$i<count($cash);$i++) {
        					        echo '<option value="'.$cash[$i]->icm_id.'">'.$cash[$i]->icm_name.'</option>';
        					    } ?>
        					</select>
        					<label class="mdl-textfield__label" for="entry_account">Select Cash Account</label>
        				</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<input type="text" id="entry_date" class="mdl-textfield__input">
        					<label class="mdl-textfield__label" for="entry_date">Entry Date</label>
        				</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<input type="text" id="entry_amount" class="mdl-textfield__input">
        					<label class="mdl-textfield__label" for="entry_amount">Entry Amount</label>
        				</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:100%;">
        					<input type="text" id="entry_narration" class="mdl-textfield__input">
        					<label class="mdl-textfield__label" for="entry_narration">Entry Narration</label>
        				</div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="save_entry">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="add_opening_balance" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Opening Balance</h4>
            </div>
            <div class="modal-body">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<select id="bal_type" class="mdl-textfield__input">
        					    <option value="0">Select Type</option>
        					    <option value="credit">Receivable / Cr</option>
        					    <option value="debit">Payable / Dr</option>
        					</select>
        					<label class="mdl-textfield__label" for="bal_type">Select Type</label>
        				</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<input type="text" id="bal_date" class="mdl-textfield__input">
        					<label class="mdl-textfield__label" for="bal_date">Balance as on Date</label>
        				</div>
                    </div>
                    
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					<input type="text" id="bal_amount" class="mdl-textfield__input">
        					<label class="mdl-textfield__label" for="bal_amount">Amount</label>
        				</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="save_bal">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
<script>
    var sel_category = "", sel_index=0, sel_account_name="";
    
    var disp_current_flg=false, disp_current_filter_state='search';
    
    var edit_journal_flg=0;
    
    $('#from_filter').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#to_filter').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#entry_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#bal_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    
    var dt = new Date();
	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
	
	$('#from_filter').val(s_dt);
    $('#to_filter').val(s_dt);
    $('#entry_date').val(s_dt);
    $('#bal_date').val(s_dt);
    
    var txn_mode='issue';
    
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	
    $(document).ready(function() {
        load_accounts(null);
        
        $('#display_current').click(function(e) {
            e.preventDefault();
            disp_current_flg=!disp_current_flg;
            
            if(disp_current_filter_state=='search') {
                load_accounts(sel_category, sel_index); 
            } else {
                load_accounts(sel_category, sel_index, null, $('#from_filter').val(), $('#to_filter').val(), $('#txn_type').val());
            }
            
            if(disp_current_flg) {
                $(this).addClass('mdl-button--raised');
                $(this).html('<i class="material-icons">sync_alt</i> All Records');
                
            } else {
                $(this).removeClass('mdl-button--raised');
                $(this).html('<i class="material-icons">sync_alt</i> Selection Records');
            }
        })
        
        $('#issue_entry').click(function(e) {
            e.preventDefault();
            txn_mode='issue';
            $(this).addClass('mdl-button--raised');
            $('#receive_entry').removeClass('mdl-button--raised');
        })
        
        $('#receive_entry').click(function(e) {
            e.preventDefault();
            txn_mode='receive';
            $(this).addClass('mdl-button--raised');
            $('#issue_entry').removeClass('mdl-button--raised');
        })
        
        $('#add_entry').click(function(e) {
            e.preventDefault();
        
            if(sel_index==0) {
                var ert = {message: 'Choose Account Ledger',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            } else {
                $('#add_journal_entry').modal('toggle');
            }
        });
        
        $('#opening_balance').click(function(e) {
            e.preventDefault();
        
            if(sel_index==0) {
                var ert = {message: 'Choose Account Ledger',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            } else {
                $('#add_opening_balance').modal('toggle');
            }
        });
        
        $('#account_table').on('click','td', function(e) {
            e.preventDefault();
            $('td').removeClass('selected_account');
            $(this).addClass('selected_account');
            sel_category=$(this).attr('category');
            sel_account_name=$(this).attr('account_name');
            sel_index=$(this).prop('id');
            disp_current_filter_state='search';
            load_accounts($(this).attr('category'), $(this).prop('id'));    
        })
        
        $('#acc_home').click(function(e) {
            e.preventDefault();
            sel_category=null;
            sel_account_name="";
            sel_index=0;
            load_accounts(null);
        });
        
        $('#search_btn').click(function(e) {
            e.preventDefault();
            load_accounts(sel_category, 0, $('#search_master').val());
        });
        
        $('#search_master').keydown(function(e) {
            if(e.keyCode == 13) {
                load_accounts(sel_category, 0, $(this).val());   
            }
        });
        
        $('#filter_records').click(function(e) {
            e.preventDefault();
            disp_current_filter_state='filter';
            load_accounts(sel_category, sel_index, null, $('#from_filter').val(), $('#to_filter').val(), $('#txn_type').val());
        });
        
        $('#download_records').click(function(e) {
            e.preventDefault();
            
            // NEW EXCEL DOWNLOAD
            $("#ledger_table").table2excel({
                // exclude CSS class
                exclude:".ignore",
                name:"Ledger Details",
                filename: sel_account_name,//do not include extension
                fileext:".xls" // file extension
            });

            
            // OLD PDF DOWNLOAD WITH COLUMN ERRORS
            
            var doc = new jsPDF();
            doc.autoTable({ html: '#ledger_table_print', theme: 'grid', useCSS: true, columnStyles: {cellWidth:'wrap', overflow: 'linebreak' }});
            doc.save(sel_account_name + '.pdf');
            
            // if(sel_index==0) {
            //     var ert = {message: 'Choose Account Ledger',timeout: 2000, }; 
            //     snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            // } else {
            //     $("#ledger_table_print").tableHTMLExport({
            //         // csv, txt, json, pdf
            //         type:'pdf',
            //         footers: false,
            //         // file name
            //         htmlContent: true,
            //         filename:sel_account_name,
            //         orientation : 'p',
            //         ignoreColumns: '.ignore',
            //         ignoreRows: '.ignore'
                
            //     });
            // }
        });
        
        $('#save_entry').click(function(e) {
            e.preventDefault();
            if($('#entry_account').val() == 0 || ($('#entry_account').val() == sel_index && sel_category=='account')) {
                var ert = {message: 'No Transaction to Same Account.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            } else {
                if(edit_journal_flg==0) {
                    var url = '<?php echo base_url().$type."/Account_Master/save_account_entry"; ?>';    
                } else {
                    var url = '<?php echo base_url().$type."/Account_Master/save_account_entry/"; ?>' + edit_journal_flg;
                }
                $.post(url, {
                    'sel_type' : sel_category,
                    'sel_id' : sel_index,
                    'type' : txn_mode,
                    'cash' : $('#entry_account').val(),
                    'date' : $('#entry_date').val(),
                    'amount' : $('#entry_amount').val(),
                    'narration' : $('#entry_narration').val(),
                }, function(d,s,x) {
                    $('#add_journal_entry').modal('toggle');
                    edit_journal_flg=0;
                    var ert = {message: 'Record Added.',timeout: 2000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    load_accounts(sel_category, sel_index);
                })   
            }
        });
        
        $('#save_bal').click(function(e) {
            e.preventDefault();
            if($('#bal_type').val() == 0) {
                var ert = {message: 'Select Type of Transaction.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            } else {
                $.post('<?php echo base_url().$type."/Account_Master/update_account_balance"; ?>', {
                    'sel_type' : sel_category,
                    'sel_id' : sel_index,
                    'type' : $('#bal_type').val(),
                    'date' : $('#bal_date').val(),
                    'amount' : $('#bal_amount').val()
                }, function(d,s,x) {
                    $('#add_opening_balance').modal('toggle');
                    var ert = {message: 'Record Added.',timeout: 2000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    load_accounts(sel_category, sel_index);
                })   
            }
        });
        
        $('#ledger_table').on('click','.edit_journal', function(e) {
            e.preventDefault();
            edit_journal_flg=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Account_Master/get_journal_entry/"; ?>' + edit_journal_flg, {}, function(d,s,x) {
                var a=JSON.parse(d);
                if(a[0].ict_from_type == sel_category && a[0].ict_from == sel_index) {
                    txn_mode='receive';
                    $('#receive_entry').addClass('mdl-button--raised');
                    $('#issue_entry').removeClass('mdl-button--raised');
                    
                    $('#entry_account').val(a[0].ict_to);
                    $('#entry_date').val(a[0].ict_date);
                    $('#entry_amount').val(a[0].ict_amount);
                    $('#entry_narration').val(a[0].ict_narration);
                } else {
                    txn_mode='issue';
                    $('#receive_entry').removeClass('mdl-button--raised');
                    $('#issue_entry').addClass('mdl-button--raised');
                    
                    $('#entry_account').val(a[0].ict_to);
                    $('#entry_date').val(a[0].ict_date);
                    $('#entry_amount').val(a[0].ict_amount);
                    $('#entry_narration').val(a[0].ict_narration);
                }
                
                $('#add_journal_entry').modal('toggle');
            })
        }).on('click','.delete_journal', function(e) {
           e.preventDefault();
            $.post('<?php echo base_url().$type."/Account_Master/del_journal_entry/"; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                var ert = {message: 'Record Deleted.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                load_accounts(sel_category, sel_index);
            })
        }).on('click','.info_journal', function(e) {
            e.preventDefault();
            var ert = {message: 'Go to Txn > Record Payment to update.',timeout: 2000, }; 
            snackbarContainer.MaterialSnackbar.showSnackbar(ert);
        });
    });
    
    function load_accounts(type, pid=0, filter='', from_date=null, to_date=null, txn=null) {
        var b="", c="";
        if(type==null){
            $('#account_table > tbody').empty();
            b+='<tr><td id="0" category="account" ty="main"><i class="material-icons">category</i> Cash Accounts</td></tr>';
            b+='<tr><td id="0" category="ledger" ty="main"><i class="material-icons">category</i> Ledgers</td></tr>';
            b+='<tr><td id="0" category="contact" ty="main"><i class="material-icons">category</i> Vendors/Dealers</td></tr>';    
            $('#account_table > tbody').append(b);
        } else if(type=='account') {
            if(pid==0) {
                
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : filter
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#account_table > tbody').empty();
                    for(var i=0;i<a.account.length;i++) {
                        b+='<tr><td id="' + a.account[i].icm_id + '" account_name="' + a.contact[i].ic_name + '"  category="account">' + a.account[i].icm_name + '</td></tr>';
                    }
                    $('#account_table > tbody').append(b);
                });
            } else {
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : from_date,
                    't' : to_date,
                    'tx' : txn
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#ledger_table > tbody').empty();
                    $('#ledger_table_print > tbody').empty().append('<tr><td colspan="5">Ledger Details: ' + sel_account_name + '</td></tr>');
                    var cr=0, dr=0;
                    
                    var txnList = a.transaction;
                    txnList.sort(function(a,b){
                      return new Date(b.date) - new Date(a.date);
                    });
                    for(var i=0;i<txnList.length;i++) {
                        b+='<tr><td>' + txnList[i].date +'</td>';
                        if(txnList[i].from_id == pid && txnList[i].from_type == 'account') {
                            b+='<td>' + txnList[i].to_name + '</td><td>' + txnList[i].narration +'</td><td></td><td>' + txnList[i].amount + '</td>';
                            cr+=parseInt(txnList[i].amount);
                        } else {
                            b+='<td>' + txnList[i].from_name + '</td><td>' + txnList[i].narration +'</td><td>' + txnList[i].amount + '</td><td></td>';
                            dr+=parseInt(txnList[i].amount);
                        }
                        b+='<td class="ignore"><button class="mdl-button mdl-button--icon mdl-button--colored edit_journal" id="' + txnList[i].id +'"><i class="material-icons">edit</i></button><button class="mdl-button mdl-button--icon mdl-button--colored delete_journal" id="' + txnList[i].id +'"><i class="material-icons">delete</i></button></td></tr>';
                    }
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append(b);
                    if(a.opening.type && disp_current_flg==true) {
                        if(a.opening.type == 'credit') {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td></td><td>' + a.opening.amt + '</td><td></td></tr>');
                            cr+=parseInt(a.opening.amt);
                        } else {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td>' + a.opening.amt + '</td><td></td><td></td></tr>');
                            dr+=parseInt(a.opening.amt);
                        }
                    }
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Current Total</td><td></td><td></td><td>' + dr + '</td><td>' + cr + '</td><td></td></tr>');
                    if((cr-dr) >= 0) {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td></td><td>' + (cr-dr) + '</td><td></td></tr>');    
                    } else {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td>' + ((cr-dr)*-1) + '</td><td></td><td></td></tr>');
                    }
                    
                });
            }
                
        } else if(type=='ledger') {
            if(pid==0) {
                
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : filter
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#account_table > tbody').empty();
                    for(var i=0;i<a.ledger.length;i++) {
                        b+='<tr><td id="' + a.ledger[i].ilm_id + '" account_name="' + a.contact[i].ic_name + '"  category="ledger">' + a.ledger[i].ilm_name + '</td></tr>';
                    }
                    $('#account_table > tbody').append(b);
                });
            } else {
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : from_date,
                    't' : to_date,
                    'tx' : txn
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#ledger_table > tbody').empty();
                    $('#ledger_table_print > tbody').empty().append('<tr><td colspan="5">Ledger Details: ' + sel_account_name + '</td></tr>');
                    var cr=0,dr=0;
                    var txnList = a.transaction;
                    txnList.sort(function(a,b){
                      return new Date(b.date) - new Date(a.date);
                    });
                    for(var i=0;i<txnList.length;i++) {
                        b+='<tr><td>' + txnList[i].date +'</td>';
                        if(txnList[i].from_id == pid && txnList[i].from_type == 'ledger') {
                            b+='<td>' + txnList[i].to_name +'</td><td>' + txnList[i].narration +'</td><td></td><td>' + txnList[i].amount + '</td>';
                            cr+=parseInt(txnList[i].amount);
                        } else {
                            b+='<td>' + txnList[i].from_name +'</td><td>' + txnList[i].narration +'</td><td>' + txnList[i].amount + '</td><td></td>';
                            dr+=parseInt(txnList[i].amount);
                        }
                        b+='<td class="ignore"><button class="mdl-button mdl-button--icon mdl-button--colored edit_journal" id="' + txnList[i].id +'"><i class="material-icons">edit</i></button><button class="mdl-button mdl-button--icon mdl-button--colored delete_journal" id="' + txnList[i].id +'"><i class="material-icons">delete</i></button></td></tr>';
                    }
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append(b);
                    if(a.opening.type && disp_current_flg==true) {
                        if(a.opening.type == 'credit') {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td></td><td>' + a.opening.amt + '</td><td></td></tr>');
                            cr+=parseInt(a.opening.amt);
                        } else {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td>' + a.opening.amt + '</td><td></td><td></td></tr>');
                            dr+=parseInt(a.opening.amt);
                        }
                    }
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Current Total</td><td></td><td></td><td>' + dr + '</td><td>' + cr + '</td><td></td></tr>');
                    if((cr-dr) >= 0) {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td></td><td>' + (cr-dr) + '</td><td></td></tr>');    
                    } else {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td>' + ((cr-dr)*-1) + '</td><td></td><td></td></tr>');
                    }
                    
                });
            }
            
        } else if(type=='contact') {
            if(pid==0) {
                
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : filter
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#account_table > tbody').empty();
                    for(var i=0;i<a.contact.length;i++) {
                        b+='<tr><td id="' + a.contact[i].ic_id + '" account_name="' + a.contact[i].ic_name + '" category="contact">' + a.contact[i].ic_name + '</td></tr>';
                    }
                    $('#account_table > tbody').append(b);
                });
            } else {
                $.post('<?php echo base_url().$type."/Account_Master/fetch_account_ledger_details/"; ?>' + type + '/' + pid, {
                    'f' : from_date,
                    't' : to_date,
                    'tx' : txn
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    $('#ledger_table > tbody').empty();
                    $('#ledger_table_print > tbody').empty(); //.append('<tr><td colspan="5">Ledger Details: ' + sel_account_name + '</td></tr>');
                    var cr=0,dr=0;
                    if(a.txn) {
                        for(var i=0;i<a.txn.txn.length;i++) {
                            var transport_charges=0;
                            for(var j=0;j<a.txn.transporter.length;j++) {
                                if(a.txn.transporter[j].transaction_no == a.txn.txn[i].it_txn_no) {
                                    transport_charges = parseInt(a.txn.transporter[j].amount);
                                    break;
                                }
                            }
                            console.log("INVOICE AMOUNT: " + a.txn.txn[i].it_amount);
                            console.log("TRANSPORT AMOUNT: " + transport_charges);
                            
                            b+='<tr><td>' + a.txn.txn[i].it_date +'</td><td>' + a.txn.txn[i].it_type + '</td><td>Txn No: ' + a.txn.txn[i].it_txn_no + '</td>';
                            
                            if(a.txn.txn[i].it_type=='Purchase' || a.txn.txn[i].it_type =='Credit Note') {
                                b+='<td style="width:250px;"></td><td style="width:250px;">' + (parseInt(a.txn.txn[i].it_amount) + parseInt(transport_charges)) + '</td>';
                                cr+=(parseInt(a.txn.txn[i].it_amount) + parseInt(transport_charges));
                            } else {
                                b+='<td style="width:250px;">' + (parseInt(a.txn.txn[i].it_amount) + parseInt(transport_charges)) + '</td><td style="width:250px;"></td>';
                                dr+=((parseInt(a.txn.txn[i].it_amount) + parseInt(transport_charges)));
                            }
                            b+='<td></td></tr>';
                        }
                        
                    }
                    
                    if(a.transaction) {
                        var txnList = a.transaction;
                        txnList.sort(function(a,b){
                          return new Date(b.date) - new Date(a.date);
                        });
                        for(var i=0;i<txnList.length;i++) {
                            b+='<tr><td>' + txnList[i].date +'</td>';
                            if(txnList[i].from_id == pid && txnList[i].from_type == 'contact') {
                                b+='<td>' + txnList[i].to_name +'</td><td style="width:150px;">' + txnList[i].narration +'</td><td style="width:250px;"></td><td style="width:250px;">' + txnList[i].amount + '</td>';
                                cr+=parseInt(txnList[i].amount);
                            } else {
                                b+='<td>' + txnList[i].from_name +'</td><td style="width:150px;">' + txnList[i].narration +'</td><td style="width:250px;">' + txnList[i].amount + '</td><td style="width:250px;"></td>';
                                dr+=parseInt(txnList[i].amount);
                            }
                            if(txnList[i].typx == 'false') {
                                b+='<td class="ignore"><button class="mdl-button mdl-button--icon mdl-button--colored edit_journal" id="' + txnList[i].id +'"><i class="material-icons">edit</i></button><button class="mdl-button mdl-button--icon mdl-button--colored delete_journal" id="' + txnList[i].id +'"><i class="material-icons">delete</i></button></td></tr>';    
                            } else {
                                b+='<td class="ignore"><button class="mdl-button mdl-button--icon mdl-button--colored info_journal"><i class="material-icons">info</i></button></td></tr>';
                            }
                            
                        }
                    }
                    
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append(b);
                    if(a.opening.type && disp_current_flg==true) {
                        if(a.opening.type == 'credit') {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td></td><td>' + a.opening.amt + '</td><td></td></tr>');
                            cr+=parseInt(a.opening.amt);
                        } else {
                            $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Opening Balance</td><td></td><td></td><td>' + a.opening.amt + '</td><td></td><td></td></tr>');
                            dr+=parseInt(a.opening.amt);
                        }
                    }
                    $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Current Total</td><td></td><td></td><td>' + dr + '</td><td>' + cr + '</td><td></td></tr>');
                    if((dr-cr) >= 0) {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td>' + (dr-cr) + '</td><td></td><td></td></tr>');
                    } else {
                        $('#ledger_table > tbody, #ledger_table_print > tbody').append('<tr><td>Closing Balance</td><td></td><td></td><td></td><td>' + ((dr-cr)*-1) + '</td><td></td></tr>');
                    }
                    
                    
                });
            }
            
        }
        // $('#ledger_table_print #print_ledger_name').html("LEDGER DETAILS: " + sel_account_name.replace(/&amp;/g, 'AND'));
    }
</script>