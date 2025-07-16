<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

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
</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                <div class="mdl-tabs__tab-bar">
                    <a href="#starks-panel" class="mdl-tabs__tab is-active">Cash Master</a>
                    <a href="#lannisters-panel" class="mdl-tabs__tab">Ledger Master</a>
                </div>
                <div class="mdl-tabs__panel is-active" id="starks-panel">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:right;">
                            <button class="mdl-button mdl-button--colored" id="add_cash"><i class="material-icons">add</i> Cash Master</button>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col">
                            <table class="purchase_table" id="cash_table">
                                <thead>
                                    <tr>
                                        <th>Cash Account</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mdl-tabs__panel" id="lannisters-panel">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:right;">
                            <button class="mdl-button mdl-button--colored" id="add_ledger"><i class="material-icons">add</i> Ledger Master</button>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col">
                            <table class="purchase_table" id="ledger_table">
                                <thead>
                                    <tr>
                                        <th>Ledger Account</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div id="add_cash_dialog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cash Master Manage</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cel mdl-cell--12-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="cash_master" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="cash_master">Cash Master</label>
    					</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button class="mdl-button mdl-button--raised mdl-button--colored" id="add_cash_master"><i class="material-icons">done</i> Save</button>
            </div>
        </div>
    </div>
</div>
<div id="add_ledger_dialog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ledger Master Manage</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cel mdl-cell--12-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="ledger_master" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="ledger_master">Ledger Master</label>
    					</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button class="mdl-button mdl-button--raised mdl-button--colored" id="add_ledger_master"><i class="material-icons">done</i> Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    var edit_cash_index=0;
    var edit_ledger_index=0;
    $(document).ready(function() {
        $('#add_cash').click(function(e) {
            e.preventDefault();
            $('#add_cash_dialog').modal('toggle');
        });
        
        $('#add_ledger').click(function(e) {
            e.preventDefault();
            $('#add_ledger_dialog').modal('toggle');
        });
        
        $('#add_cash_master').click(function(e) {
            e.preventDefault();
            var url = "";
            if(edit_cash_index == 0) {
                url = '<?php echo base_url().$type."/Account_Master/save_cash_master/"; ?>';
            } else {
                url ='<?php echo base_url().$type."/Account_Master/save_cash_master/"; ?>' + edit_cash_index;
            }
            
            $.post(url, {
                'n' : $('#cash_master').val()
            }, function(d,s,x) {
                reset_cash_master();
                load_cash_master();
            })
        });
        
        $('#add_ledger_master').click(function(e) {
            e.preventDefault();
            var url = "";
            if(edit_ledger_index == 0) {
                url = '<?php echo base_url().$type."/Account_Master/save_ledger_master/"; ?>';
            } else {
                url ='<?php echo base_url().$type."/Account_Master/save_ledger_master/"; ?>' + edit_ledger_index;
            }
            
            $.post(url, {
                'l' : $('#ledger_master').val()
            }, function(d,s,x) {
                reset_ledger_master();
                load_ledger_master();
            })
        });
        
        $('#cash_table').on('click','.edit', function(e) {
            e.preventDefault();
            edit_cash_index=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Account_Master/load_cash_master/"; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                var a=JSON.parse(d);
                $('#cash_master').val(a[0].icm_name);
                $('#add_cash_dialog').modal('toggle');
                $('#cash_master').focus();
            });
        })
        
        $('#ledger_table').on('click','.edit', function(e) {
            e.preventDefault();
            edit_ledger_index=$(this).prop('id');
            $.post('<?php echo base_url().$type."/Account_Master/load_ledger_master/"; ?>' + $(this).prop('id'), {}, function(d,s,x) {
                var a=JSON.parse(d);
                $('#ledger_master').val(a[0].ilm_name);
                $('#add_ledger_dialog').modal('toggle');
                $('#ledger_master').focus();
            });
        })
        
        
        load_ledger_master();
        load_cash_master();
    });
    
    function load_cash_master() {
        $.post('<?php echo base_url().$type."/Account_Master/load_cash_master/"; ?>', {}, function(d,s,x) {
            var a=JSON.parse(d), b="";
            $('#cash_table > tbody').empty();
        
            for(var i=0;i<a.length;i++) {
                b+='<tr><td><button class="mdl-button mdl-button--icon mdl-button--colored edit" id="' + a[i].icm_id + '"><i class="material-icons">create</i></button></td><td>' + a[i].icm_name + '</td></tr>';
            }
            console.log(b);
            $('#cash_table > tbody').append(b);
        })
    }
    
    function reset_cash_master() {
        $('#cash_master').val('');
        edit_cash_index=0;
        $('#add_cash_dialog').modal('toggle');
    }
    
    function load_ledger_master() {
        $.post('<?php echo base_url().$type."/Account_Master/load_ledger_master/"; ?>', {}, function(d,s,x) {
            var a=JSON.parse(d), b="";
            $('#ledger_table > tbody').empty();
        
            for(var i=0;i<a.length;i++) {
                b+='<tr><td><button class="mdl-button mdl-button--icon mdl-button--colored edit" id="' + a[i].ilm_id + '"><i class="material-icons">create</i></button></td><td>' + a[i].ilm_name + '</td></tr>';
            }
            $('#ledger_table > tbody').append(b);
        })
    }
    
    function reset_ledger_master() {
        $('#ledger_master').val('');
        edit_ledger_index=0;
        $('#add_ledger_dialog').modal('toggle');
    }
</script>