<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<link href="<?php echo base_url().'assets/css/tableexport.css'; ?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/js/FileSaver.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/tableexport.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/Blob.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/xlsx.core.min.js'; ?>"></script>

<style type="text/css">
	a {
        color: #fff;
        text-decoration: none;
    }

    a:hover {
        color: #fff;
        text-decoration: none;
    }

    .ui-front {
        z-index: 2000;
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

	.purchase_table > tfoot > tr {
		border-bottom: 1px solid #ccc;
	}

	.purchase_table > tfoot > tr > td {
		padding: 15px;
		font-weight: bold;
	}

    .dhr_card {
        box-shadow: 0px 2px 5px #aaa;
        border-radius: 5px;
        padding:20px;
    }
    
    .dhr_card_title {
        text-align: left;
        padding-left: 15px;
        color: #aaa;
        font-weight: bold;
        font-size: 20px;
    }
    
    .dhr_card_content {
        font-size: 3em;
        padding: 30px;
        text-align: center;
        color: #666;
    }
    
    .product_input {
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
	    <div class="mdl-cell mdl-cell--12-col">
            <h5>Actions</h5><hr>
        </div>
	    <div class="mdl-cell mdl-cell--12-col">
	        <button class="mdl-button mdl-js--button" id="add_inventory"><i class="material-icons">add</i> Material Inward</button>
	        <button class="mdl-button mdl-js--button" id="transfer_inventory"><i class="material-icons">compare_arrows</i> Transfer Inventory Records</button>
	        <button class="mdl-button mdl-js--button" id="add_accounts"><i class="material-icons">receipt</i> Manage Accounts</button>
	        <button class="mdl-button mdl-js--button" id="view_order_list"><i class="material-icons">shopping_cart</i> Order List</button>
	    </div>
	</div>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <h5>Product List</h5><hr>
        </div>
	    <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js--button mdl-button--colored home" category="0"><i class="material-icons">home</i></button>
            <button class="mdl-button mdl-js--button mdl-button--colored back" category="0"><i class="material-icons">arrow_back_ios</i></button>
            
            <div class="dhr_card" style="height:550px; overflow:auto;">
                <table class="purchase_table" id="main_category">
                    <?php for($i=0;$i<count($categories);$i++) {
                        echo '<tr id="'.$categories[$i]->ica_id.'" type="category"><td><i class="material-icons">category</i> '.$categories[$i]->ica_category_name.'</td></tr>';
                    } ?>
                </table>    
            </div>
	    </div>
	</div>
	<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
        <div class="mdl-snackbar__text"></div>
        <button class="mdl-snackbar__action" type="button"></button>
    </div>
</div>
</div>
<div id="add_inventory_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Inventory</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cell mdl-cell--6-col" style="display:none;">
					    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="from_switch">
                            <input type="checkbox" id="from_switch" class="mdl-switch__input" checked>
                            <span class="mdl-switch__label">On: Vendors/Dealers</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="from_inv_account" class="mdl-textfield__input">
    						<input type="text" id="from_inv_account_sec" style="display:none;">
    						<label class="mdl-textfield__label" for="from_inv_account">Search Vendor</label>
    					</div>
                    </div>
					<div class="mdl-cell mdl-cell--6-col" style="display:none;">
					    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="to_switch">
                            <input type="checkbox" id="to_switch" class="mdl-switch__input">
                            <span class="mdl-switch__label">On: Vendors/Dealers</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="to_inv_account" class="mdl-textfield__input" value="<?php echo $default_account; ?>">
    						<input type="text" id="to_inv_account_sec" style="display:none;" value="<?php echo $default_account_id; ?>">
    						<label class="mdl-textfield__label" for="to_inv_account">Search To</label>
    					</div>
                    </div>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="i_txn_date" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="i_txn_date">Select Date</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--12-col">
					    <label>Add Items to Inventory</label>
					</div>
					<hr>
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="prod">Search Products</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod_qty" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="prod_qty">Qty</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod_ref" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="prod_ref">Reference</label>
    					</div>
					</div>
					
					<div class="mdl-cel mdl-cell--2-col" style="margin-top:12px;margin-left:0%;padding:0px;">
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item"><i class="material-icons">add</i></button>
					</div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="order_table">
						<thead>
							<tr>
								<th>Action</th>
								<th>Product</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
							</tr>
						</tbody>
					</table>
				</div>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-dismiss="modal" id="save_order_list">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="transfer_inventory_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Transfer Inventory</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cell mdl-cell--6-col" style="display:none;">
					    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="from_switch_trf">
                            <input type="checkbox" id="from_switch_trf" class="mdl-switch__input">
                            <span class="mdl-switch__label">On: Vendors/Dealers</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="from_inv_account_trf" class="mdl-textfield__input">
    						<input type="text" id="from_inv_account_sec_trf" style="display:none;">
    						<label class="mdl-textfield__label" for="from_inv_account_trf">Search From</label>
    					</div>
                    </div>
					<div class="mdl-cell mdl-cell--6-col" style="display:none;">
					    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="to_switch_trf">
                            <input type="checkbox" id="to_switch_trf" class="mdl-switch__input">
                            <span class="mdl-switch__label">On: Vendors/Dealers</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="to_inv_account_trf" class="mdl-textfield__input">
    						<input type="text" id="to_inv_account_sec_trf" style="display:none;">
    						<label class="mdl-textfield__label" for="to_inv_account_trf">Search To</label>
    					</div>
                    </div>
					<div class="mdl-cel mdl-cell--12-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="i_txn_date_trf" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="i_txn_date_trf">Select Date</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--12-col">
					    <label>Add Items to Inventory</label>
					</div>
					<hr>
					<div class="mdl-cel mdl-cell--6-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod_trf" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="prod_trf">Search Products</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--4-col">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="prod_qty_trf" class="mdl-textfield__input" value="">
    						<label class="mdl-textfield__label" for="prod_qty_trf">Qty</label>
    					</div>
					</div>
					<div class="mdl-cel mdl-cell--2-col" style="margin-top:12px;margin-left:0%;padding:0px;">
						<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="add_item_trf"><i class="material-icons">add</i></button>
					</div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="order_table_trf">
						<thead>
							<tr>
								<th>Action</th>
								<th>Product</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
							</tr>
						</tbody>
					</table>
				</div>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal">Close</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-dismiss="modal" id="save_order_list_trf">Save</button>
            </div>
        </div>
    </div>
</div>
<div id="add_account_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Inventory Account</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="add_account" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="add_account">Name</label>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="add_account_parent" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="add_account_parent">Parent Account</label>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised" id="account_save" state="0"><i class="material-icons">done</i> Save</button>
                    </div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="accounts_table">
						<thead>
							<tr>
								<th>Accounts</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
				
            </div>
        </div>
    </div>
</div>
<div id="view_txn_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Inventory Transactions</h4>
            </div>
            <div class="modal-body">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--6-col">
					    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="search_switch">
                            <input type="checkbox" id="search_switch" class="mdl-switch__input" checked>
                            <span class="mdl-switch__label">On: Vendors/Delaers<br>Off: Inventory Accounts</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="search_inv_account" class="mdl-textfield__input">
    						<input type="text" id="search_inv_account_sec" style="display:none;">
    						<label class="mdl-textfield__label" for="search_inv_account">Search Account/ Contact</label>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="search_inv_product" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="search_inv_product">Product Name</label>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="search_inv_from" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="search_inv_from">From Date</label>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    						<input type="text" id="search_inv_to" class="mdl-textfield__input">
    						<label class="mdl-textfield__label" for="from_inv_to">To Date</label>
    					</div>
                    </div>
                    
                    <div class="mdl-cell mdl-cell--6-col">
                        <button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised" id="search_inv_txns"><i class="material-icons">search</i> Search Records</button>
                    </div>
				</div>
				<div class="mdl-grid">
					<table class="purchase_table" id="txns_table">
						<thead>
							<tr>
								<th>From</th>
								<th>To</th>
								<th>Date</th>
								<th>Product</th>
								<th>Qty</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
						    <tr>
						        <th colspan="4">Balance</th>
						        <th id="txn_search_total"></th>
						    </tr>
						</tfoot>
					</table>
				</div>
				
            </div>
        </div>
    </div>
</div>
<div id="view_order_list_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">View Order List</h4>
            </div>
            <div class="modal-body">
                <div class="mdl-grid">
					<table class="purchase_table" id="order_item_table">
						<thead>
							<tr>
								<th>Products</th>
								<th>Qty</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button" data-dismiss="modal" id="clear_order_list">Clear Order List</button>
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" data-dismiss="modal" id="download">Download</button>
            </div>
        </div>
    </div>
</div>
<div id="demo-snackbar-example" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
</div>
</body>
<script>
    var product_exist = 'false';
    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	
    var inv_new_list=[]; var inv_sel_rec=0; var inv_sel_flg=false;
    var inv_order_list=[];
	$(document).ready(function() {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	    var inv_accounts=[]; var sel_inv_account=0;
	    
	    
	    $('#i_txn_date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    var dt = new Date();
    	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
    	$('#i_txn_date').val(s_dt);
	    
	    $('#i_txn_date_trf').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    var dt = new Date();
    	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
    	$('#i_txn_date_trf').val(s_dt);
	    
	    $('#search_inv_from').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    var dt = new Date();
    	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
    	$('#search_inv_from').val(s_dt);
	    
	    $('#search_inv_to').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    var dt = new Date();
    	var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
    	$('#search_inv_to').val(s_dt);
	    
	    <?php for($i=0;$i<count($accounts);$i++) { 
	        echo 'inv_accounts.push({"iia_id" : "'.$accounts[$i]->iia_id.'","iia_name" : "'.$accounts[$i]->iia_name.'", "iia_star" : "'.$accounts[$i]->iia_star.'" });';
	        echo 'load_inv_accounts(inv_accounts);';
	    }?>
	    
	    var product_data = [];
    	
    	<?php for ($i=0; $i < count($products) ; $i++) { 
    		echo "product_data.push('".$products[$i]->ip_name."');";
    	}?>
    	
    	$( "#add_account_parent" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Transactions/search_inventory_accounts"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                            label: item.iia_name,
                            value: item.iia_name
                        }
                    }));
                })
            }
        }); 
    	
    	
    	$( "#prod" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Products/search_products_by_code/0"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                            label: item.ip_name,
                            value: item.ip_name
                        }
                    }));
                })
            }
        }); 
    	
    	
        $('#prod').change(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                product_exist=d;
            })
        });
        
        $( "#search_inv_product" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Products/search_products_by_code/0"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                            label: item.ip_name,
                            value: item.ip_name
                        }
                    }));
                })
            }
        }); 
    	
	    
        $("#from_inv_account").autocomplete({
            source: function( request, response ) {
                var url="";
                if($('#from_switch')[0].checked == true) {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/c'; ?>";
                } else {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/i'; ?>";
                }
                $.post(url, {
                    'term' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response(a);
                });
            },
            minLength: 2,
            focus: function(e,u) {
                $(this).val(u.item.label);
                $('#from_inv_account_sec').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#from_inv_account_sec').val(u.item.value);
                return false;
            }
        });
        
        $("#from_inv_account_trf").autocomplete({
            source: function( request, response ) {
                var url="";
                if($('#from_switch_trf')[0].checked == true) {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/c'; ?>";
                } else {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/i'; ?>";
                }
                $.post(url, {
                    'term' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response(a);
                });
            },
            minLength: 2,
            focus: function(e,u) {
                $(this).val(u.item.label);
                $('#from_inv_account_sec_trf').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#from_inv_account_sec_trf').val(u.item.value);
                return false;
            }
        });
        
        $("#to_inv_account").autocomplete({
            source: function( request, response ) {
                var url="";
                if($('#to_switch')[0].checked == true) {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/c'; ?>";
                } else {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/i'; ?>";
                }
                $.post(url, {
                    'term' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response(a);
                });
            },
            minLength: 2,
            focus: function(e,u) {
                $(this).val(u.item.label);
                $('#to_inv_account_sec').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#to_inv_account_sec').val(u.item.value);
                return false;
            }
        });
        
        $("#to_inv_account_trf").autocomplete({
            source: function( request, response ) {
                var url="";
                if($('#to_switch_trf')[0].checked == true) {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/c'; ?>";
                } else {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/i'; ?>";
                }
                $.post(url, {
                    'term' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response(a);
                });
            },
            minLength: 2,
            focus: function(e,u) {
                $(this).val(u.item.label);
                $('#to_inv_account_sec_trf').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#to_inv_account_sec_trf').val(u.item.value);
                return false;
            }
        });
        
        $("#search_inv_account").autocomplete({
            source: function( request, response ) {
                var url="";
                if($('#search_switch')[0].checked == true) {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/c'; ?>";
                } else {
                    url="<?php echo base_url().$type.'/Transactions/inventory_new_get_list/i'; ?>";
                }
                $.post(url, {
                    'term' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response(a);
                });
            },
            minLength: 2,
            focus: function(e,u) {
                $(this).val(u.item.label);
                $('#search_inv_account_sec').val(u.item.value);
                return false;
            },select: function(e,u) {
                $(this).val(u.item.label);
                $('#search_inv_account_sec').val(u.item.value);
                return false;
            }
        });
        
        
        
        $('#add_inventory').click(function(e) {
            e.preventDefault();
            $('#add_inventory_modal').modal('toggle');
            reset_order_fields();
        })
        
        $('#transfer_inventory').click(function(e) {
            e.preventDefault();
            $('#transfer_inventory_modal').modal('toggle');
            reset_order_fields();
        })
        
        $('#add_accounts').click(function(e) {
            e.preventDefault();
            $('#add_account_modal').modal('toggle');
        })
        
        $('#view_txns').click(function(e) {
            e.preventDefault();
            $('#view_txn_modal').modal('toggle');
        })
        
        $('#account_save').click(function(e) {
            e.preventDefault();
            var url="";
            
            if($(this).attr('state') == '0') {
                url='<?php echo base_url().$type."/Transactions/save_inventory_new_account"; ?>';
            } else {
                url='<?php echo base_url().$type."/Transactions/save_inventory_new_account/"; ?>' + sel_inv_account;
            }
            console.log(url);
            $.post(url, {
                'n' : $('#add_account').val(),
                'n_p' : $('#add_account_parent').val()
            }, function(d,s,x) {
                var a=JSON.parse(d), b="";
                inv_accounts=[];
                $("#add_account").val('');
                $(this).attr('state','0');
                inv_accounts=a;
                load_inv_accounts(a);
            })
        })
		
		$('#accounts_table').on('click','.account_edit', function(e) {
		    e.preventDefault();
		    sel_inv_account = $(this).prop('id');
		    $('#add_account').val(inv_accounts[$(this).attr('index')].iia_name);
		    $('#account_save').attr('state','1');
		}).on('click','.account_delete', function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/delete_inventory_new_account/"; ?>' + $(this).prop('id'), function(d,s,x) {
		        var a=JSON.parse(d), b="";
                inv_accounts=[];
                $("#add_account").val('');
                $(this).attr('state','0');
                inv_accounts=a;
                load_inv_accounts(a);
		    })
		}).on('click','.account_star', function(e) {
		    e.preventDefault();
		    $.post('<?php echo base_url().$type."/Transactions/star_inventory_new_account/"; ?>' + $(this).prop('id'), function(d,s,x) {
		        var a=JSON.parse(d), b="";
                inv_accounts=[];
                $("#add_account").val('');
                inv_accounts=a;
                load_inv_accounts(a);
		    })
		});
		
		$('#main_category').on('click','tr', function(e) {
            e.preventDefault();
            if($(this).attr('type') == "category") {
                load_inv_list($(this).prop('id'));
            }
            
        })
        
        $('.back').click(function(e) {
            e.preventDefault();
            load_inv_list($(this).attr('category'));
        })
        
        $('.home').click(function(e) {
            e.preventDefault();
            load_inv_list($(this).attr('category'));
        })
        
        $('#add_item').click(function(e) {
            e.preventDefault();
            if(product_exist=='true') {
                add_to_order_list($('#prod').val(), $('#prod_qty').val(), $('#prod_ref').val());
            } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
        })
        
        $('#prod_qty').keydown(function(e) {
            if(e.keyCode == 13) {
                add_to_order_list($('#prod').val(), $(this).val(), $('#prof_ref').val());
            }
        })
        
        $('#order_table').on('click','.edit_order_list', function(e) {
            e.preventDefault();
            inv_sel_rec=$(this).prop('id');
            inv_sel_flg=true
            $('#prod').val(inv_new_list[inv_sel_rec].p);
            $('#prod_qty').val(inv_new_list[inv_sel_rec].q);
            $('#prod_qty').val(inv_new_list[inv_sel_rec].r);
            
            $('#prod').focus();
        })
        
        $('#order_table').on('click','.delete_order_list', function(e) {
            e.preventDefault();
            var x=$(this).prop('id');
            inv_new_list.splice(x,1);
            load_order_list(inv_new_list);
            reset_order_fields()
        })
        
        $('#save_order_list').click(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/save_inventory_new_records"; ?>', {
                'f' : $('#from_inv_account_sec').val(),
                'f_t' : $('#from_switch')[0].checked,
                't' : $('#to_inv_account_sec').val(),
                't_t' : $('#to_switch')[0].checked,
                'd' : $('#i_txn_date').val(),
                'l' : inv_new_list
            }, function(d,s,x) {
                if(d=="true") {
                    $('#order_table > tbody').empty();
                }
            })
        })
        
        $('#save_order_list_trf').click(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/save_inventory_new_records"; ?>', {
                'f' : $('#from_inv_account_sec_trf').val(),
                'f_t' : $('#from_switch_trf')[0].checked,
                't' : $('#to_inv_account_sec_trf').val(),
                't_t' : $('#to_switch_trf')[0].checked,
                'd' : $('#i_txn_date_trf').val(),
                'l' : inv_new_list
            }, function(d,s,x) {
                if(d=="true") {
                    $('#order_table_trf > tbody').empty();
                }
            })
        })
        
        
        
        $('#search_inv_txns').click(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/fetch_inventory_new_records"; ?>', {
                'a_t' : $('#search_switch')[0].checked,
                'a' : $('#search_inv_account_sec').val(),
                'p' : $('#search_inv_product').val(),
                'f' : $('#search_inv_from').val(),
                't' : $('#search_inv_to').val()
            }, function(d,s,x) {
                var b="", a=JSON.parse(d), bal=0;
                $('#txns_table > tbody').empty();
                for(var i=0;i<a.length;i++) {
                    b+='<tr><td>';
                    if(a[i].from_type == "contact") {
                        b+=a[i].from_name;
                    } else {
                        b+=a[i].from_acc;
                    }
                    b+='</td><td>';
                    if(a[i].to_type == "contact") {
                        b+=a[i].to_name;
                    } else {
                        b+=a[i].to_acc;
                    }
                    b+='</td><td>' + a[i].dt + '</td><td>' + a[i].product + '</td><td>';
                    if($('#search_inv_account_sec').val() == a[i].frm) {
                        b+= '-('+a[i].qty+')';
                        bal-=parseInt(a[i].qty);
                    } else {
                        bal+=parseInt(a[i].qty);
                        b+=a[i].qty;
                    }
                    b+='</td></tr>';
                }
                $('#txns_table > tbody').append(b);
                $('#txn_search_total').empty(); $('#txn_search_total').append(bal);
            });
        })
        
        $('#main_category').on('click','.product_qty_accept', function(e) {
            e.preventDefault();
            var pid = $(this).prop('id');
            var qty = '[product="' + pid + '"]';
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_order_list_update"; ?>', {
                'p' : pid,
                'q' : $(qty).val()
            }, function(d,s,x) {
                var ert = {message: "Qty Added.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
            }).fail(function(r) {
                var ert = {message: "Please try again.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            })
        })
        
        $('#order_item_table').on('click','.order_item_table_delete', function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_delete_order_item"; ?>', {
                'i' : $(this).prop('id')
            }, function(d,s,x) {
                var a=JSON.parse(d);
                var ert = {message: "Please wait.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
                var b="";
                $('#order_item_table > tbody').empty();
                for(var i=0;i<a.length;i++) {
                    b+='<tr><td>' + a[i].ip_name + '</td><td>' + a[i].iino_qty + '</td><td>' + a[i].iino_date + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored order_item_table_delete" id="' + a[i].iino_id + '"><i class="material-icons">delete</i></button></td></tr>';
                }
                $('#order_item_table > tbody').append(b);
            })
        })
        
        $('#view_order_list').click(function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_fetch_order_list"; ?>', function(d,s,x) {
                var a=JSON.parse(d);
                var ert = {message: "Please wait.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
                var b="";
                $('#order_item_table > tbody').empty();
                for(var i=0;i<a.length;i++) {
                    b+='<tr><td>' + a[i].ip_name + '</td><td>' + a[i].iino_qty + '</td><td>' + a[i].iino_date + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored order_item_table_delete" id="' + a[i].iino_id + '"><i class="material-icons">delete</i></button></td></tr>';
                }
                $('#order_item_table > tbody').append(b);
                
                setTimeout(function() {
                    $('#view_order_list_modal').modal('toggle');    
                }, 2000);
            })
        })
        
        $('#clear_order_list').click(function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_clear_order_list"; ?>', function(d,s,x) {
                var ert = {message: "Please wait.",timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                
                $('#order_item_table > tbody').empty();
                setTimeout(function() {
                    $('#view_order_list_modal').modal('toggle');    
                }, 2000);
            })
        })
        
        $('#fixed-header-drawer-exp').keyup(function(e) {
            if(e.keyCode == 13) {
                $.post('<?php echo base_url().$type."/Transactions/inventory_new_search_product_category_child/"; ?>', {
                    'pn' : $(this).val()
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    display_inv_list_data(a);
                });
            }
        });
        
        function add_to_order_list(p, q, r) {
            if(inv_sel_flg==false) {
                inv_new_list.push({'p' : p, 'q' : q, 'r' : r});    
            } else {
                inv_new_list[inv_sel_rec].p = p;
                inv_new_list[inv_sel_rec].q = q;
                inv_new_list[inv_sel_rec].r = r;
                inv_sel_rec=0;
                inv_sel_flg=false;
            }
            load_order_list(inv_new_list);
            reset_order_fields()
        }
        
        function load_order_list(a) {
            $('#order_table > tbody').empty();
            var b="";
            for(var i=0;i<a.length;i++) {
                b+='<tr><td>' + a[i].p + '</td><td>' + a[i].q + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored edit_order_list" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js--button mdl-button--colored delete_order_list" id="' + i + '"><i class="material-icons">delete</i></button></td></tr>';
            }
            $('#order_table > tbody').append(b);
        }
        
        function reset_order_fields() {
            $('#prod').val(null);
            $('#prod_qty').val(null);
            $('#prod').focus();
        }
        
        function load_inv_list(catid) {
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_get_product_category_child/"; ?>' + catid, {}, function(d,s,x) {
                var a=JSON.parse(d);
                display_inv_list_data(a);
            });
        }
        
        function display_inv_list_data(a) {
            var b="";
            $('#main_category > tbody').empty();
            for(var i=0;i<a.category.length;i++) {
                b+='<tr id="' + a.category[i].ica_id + '" type="category"><td><i class="material-icons">category</i> ' + a.category[i].ica_category_name + '</td></tr>';
            }
            
            
            for(var i=0;i<a.product.length;i++) {
                b+='<tr id="' + a.product[i].id + '" type="product"><td>' + a.product[i].name + '</td><td>Limit: ' + a.product[i].limit + '</td>';
                var m=a.product[i].stock;
                for(var j=0;j<m.length;j++) {
                    b+='<td>' + m[j].account + ': ' + m[j].bal + '</td>';
                }
                b+='<td><input type="text" id="" class="product_input" value="" product="' + a.product[i].id + '"><button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised product_qty_accept" prod_name="' + a.product[i].name + '" id="' + a.product[i].id + '"><i class="material-icons">add</i> Qty</button></td></tr>';
            }
            
            $('#main_category > tbody').append(b);
            $('.back').attr("category", a.parent);
        }
        
        function load_inv_accounts(a) {
            $('#accounts_table > tbody').empty();var b="";
            for(var i=0;i<a.length;i++) {
                b+='<tr><td>' + a[i].iia_name + '</td><td><button class="mdl-button mdl-js-button mdl-button--colored account_edit" id="' + a[i].iia_id +'" index="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js-button mdl-button--colored account_delete" id="' + a[i].iia_id +'" index="' + i + '"><i class="material-icons">delete</i></button><button class="mdl-button mdl-js-button mdl-button--colored account_star" id="' + a[i].iia_id +'" state="' + a[i].iia_star + '"><i class="material-icons">';
                if(a[i].iia_star=="1") {
                    console.log("Here");
                    b+='star';
                } else {
                    b+='star_border';
                }
                b+='</i></button></td></tr>';
            }
            $('#accounts_table > tbody').append(b);
        }
        
        $('#download').click(function(e) {
		    e.preventDefault();
		    var dt1 = new Date();
		    
		    $('#order_item_table').tableExport({
                // Displays table headings (th or td elements) in the <thead>
                headings: true,                    
                // Displays table footers (th or td elements) in the <tfoot>    
                footers: true, 
                // Filetype(s) for the export
                formats: ["xls"],           
                // Filename for the downloaded file
                filename: 'Order List as on ' + dt.getDate() + '-' + (dt.getMonth() + 1) + '-' + dt.getFullYear(), 
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
	
	
	
</script>
</html>