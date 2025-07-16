<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<!--<link href="<?php echo base_url().'assets/css/tableexport.css'; ?>" rel="stylesheet">-->
<!--<script src="<?php echo base_url().'assets/js/FileSaver.js'; ?>"></script>-->
<!--<script src="<?php echo base_url().'assets/js/tableexport.js'; ?>"></script>-->
<!--<script src="<?php echo base_url().'assets/js/Blob.js'; ?>"></script>-->
<!--<script src="<?php echo base_url().'assets/js/xlsx.core.min.js'; ?>"></script>-->

<script src="<?php echo base_url().'assets/js/tableHTMLExport.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.10/jspdf.plugin.autotable.min.js"></script>

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
        display:block;
        overflow:auto;
        
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
    
    ::placeholder {
        color: #aaa;
    }
</style>

<main class="mdl-layout__content">
    <div class="mdl-grid">
	    <div class="mdl-cell mdl-cell--12-col">
	        <button class="mdl-button mdl-js--button" id="add_inventory"><i class="material-icons">add</i> Material Inward</button>
	        <button class="mdl-button mdl-js--button" id="transfer_inventory"><i class="material-icons">compare_arrows</i> Transfer Inventory Records</button>
	        <button class="mdl-button mdl-js--button" id="add_accounts"><i class="material-icons">receipt</i> Manage Accounts</button>
	        <button class="mdl-button mdl-js--button" id="view_order_list"><i class="material-icons">shopping_cart</i> Order List</button>
	        <?php if(isset($_SESSION['user_details'][0])) {
	            if($_SESSION['user_details'][0]->iua_u_inventory_reset == 'true') {
	                echo '<button class="mdl-button mdl-js--button" id="reset_inventory"><i class="material-icons">clear_all</i> Reset Inventory</button>';
	            }
	        } ?>
	    </div>
	</div>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col">
            <button class="mdl-button mdl-js--button mdl-button--colored home" category="0"><i class="material-icons">home</i></button>
            <button class="mdl-button mdl-js--button mdl-button--colored back" category="0"><i class="material-icons">arrow_back_ios</i></button>
            <label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="location_toggle">
                <input type="checkbox" id="location_toggle" class="mdl-icon-toggle__input">
                <i class="mdl-icon-toggle__label material-icons">room</i>
            </label>
        </div>
        <div class="mdl-cell mdl-cell--12-col">
            <div class="dhr_card" style="height:550px; overflow:auto;">
                <table class="purchase_table" id="main_category">
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
    						<label class="mdl-textfield__label" for="from_inv_account_trf">From Account</label>
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
    						<label class="mdl-textfield__label" for="to_inv_account_trf">To Account</label>
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
								<th class="ignore">Actions</th>
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

<div id="view_inventory_details" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inventory Details</h4>
                <div id="cat_search_text" style="display:none;">
                    <p>Search Category</p>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width:90%;">
    					<input type="text" id="view_inventory_search_category" class="mdl-textfield__input">
    					<label class="mdl-textfield__label" for="view_inventory_search_category">Category Name</label>
    				</div>
    				<button class="mdl-button mdl-button--colored" id="view_inventory_search_button"><i class="material-icons">search</i> Search</button>
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div id="single_action">
                    
                </div>
                <table class="purchase_table" id="inventory_details_modal_table">
                    
                </table>
            </div>
        </div>
    </div>
</div>


<div id="reset_inventory_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reset Inventory</h4>
            </div>
            <div class="modal-body">
                <div id="info_repea" class="mdl-grid">
					<div class="mdl-cell mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					    <b>Enter categories to reset</b>
    						<ul id="mod_reset_category" class="mdl-textfield__input">
    						</ul>
    					</div>
    					<div style="text-align:center; font-weight:bold; font-size: 1.5em;">
    					    OR
    					</div>
    					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					    <b>Enter products to reset</b>
    						<ul id="mod_reset_products" class="mdl-textfield__input">
    						</ul>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					    <b>Enter locations</b>
    						<ul id="mod_reset_account" class="mdl-textfield__input">
    						</ul>
    					</div>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="mod_reset_in">
                            <input type="checkbox" id="mod_reset_in" class="mdl-switch__input">
                            <span class="mdl-switch__label">Inward</span>
                        </label>
                    </div>
                    <div class="mdl-cell mdl-cell--12-col">
                        <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="mod_reset_out">
                            <input type="checkbox" id="mod_reset_out" class="mdl-switch__input">
                            <span class="mdl-switch__label">Outward</span>
                        </label>
                    </div>
                    
                    
                    <div class="mdl-cell mdl-cell--12-col">
                        <button class="mdl-button mdl-js--button mdl-button--colored mdl-button--raised" id="mod_reset_button"><i class="material-icons">done</i> Reset Inventory</button>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>

<div id="waiting_modal" style="opacity:0.5; position:absolute; width: 100em; height:100em; z-index:9999; background-color:#000; display:none;">
    <h4>Please Wait</h4>
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
    var inv_new_list_trf=[]; var inv_sel_rec_trf=0; var inv_sel_flg_trf=false;
    
    var inv_order_list=[];
	$(document).ready(function() {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
	    var inv_accounts=[]; var sel_inv_account=0;
	    
	    var cat_data = [];
    	<?php
    		for ($i=0; $i < count($category); $i++) { 
    			echo "cat_data.push('".$category[$i]->ica_category_name."');";
    		}
    	?>
    	$('#mod_reset_category').tagit({
    		autocomplete : { delay: 0, minLenght: 10},
    		allowSpaces : true,
    		availableTags : cat_data,
    		singleField : true
    	});

    	var pro_data = [];
    	<?php
    		for ($i=0; $i < count($products); $i++) { 
    			echo "pro_data.push('".$products[$i]->ip_name."');";
    		}
    	?>
    	$('#mod_reset_products').tagit({
    		autocomplete : { delay: 0, minLenght: 10},
    		allowSpaces : true,
    		availableTags : pro_data,
    		singleField : true
    	});

    	var acc_data = [];
    	<?php
    		for ($i=0; $i < count($accounts); $i++) { 
    			echo "acc_data.push('".$accounts[$i]->iia_name."');";
    		}
    	?>
    	$('#mod_reset_account').tagit({
    		autocomplete : { delay: 0, minLenght: 10},
    		allowSpaces : true,
    		availableTags : acc_data,
    		singleField : true
    	});

    	$('#reset_inventory').click(function(e) {
    	    e.preventDefault();
    	    $('#reset_inventory_modal').modal('toggle');
    	})
	    
	    $('#mod_reset_button').click(function(e) {
	        e.preventDefault();
	        var c_arr=[], p_arr=[], l_arr=[];
	        
	        $('#mod_reset_category > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					c_arr.push(tmpstr);
				}
			});

            $('#mod_reset_products > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					p_arr.push(tmpstr);
				}
			});

            $('#mod_reset_account > li').each(function(index) {
				var tmpstr = $(this).text();
				var len = tmpstr.length - 1;
				if(len > 0) {
					tmpstr = tmpstr.substring(0, len);
					l_arr.push(tmpstr);
				}
			});
            
            
            console.log($('#mod_reset_out')[0].checked);
            $.post('<?php echo base_url().$type."/Transactions/inventory_reset_records"; ?>', {
                'c' : c_arr,
                'p' : p_arr,
                'l' : l_arr,
                'in' : $('#mod_reset_in')[0].checked,
                'out' : $('#mod_reset_out')[0].checked
            }, function(d,s,x) {
                var ert = {message: 'Inventory Reset.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                setTimeout(function() {
                    window.location.reload();    
                }, 1000);
                
            })
	    })
	    
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
	    
        // AUTOCOMPLETE 
        
    	$( "#view_inventory_search_category" ).autocomplete({
            source: function(request, response) {
                $.post('<?php echo base_url().$type."/Transactions/search_inventory_by_category_name"; ?>', {
                    'keywords' : request.term
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    response( $.map(a, function( item ) {
                        return{
                            label: item.ica_category_name,
                            value: item.ica_category_name
                        }
                    }));
                })
            }, select: function(e,u) {
                
            }
        }); 
    	
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
            },
            select: function(e, u) {
                // $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                    var d='true';
                    product_exist=d;
                    if(d=="true") {
                        $('#prod_qty').focus()
                    } else {
                        reset_order_fields();
                    }
                // })
            }
        });
        
        $('#prod').change(function(e) {
            e.preventDefault();
            if(product_exist=="false") {
                reset_order_fields();
            }
        });
        
        $( "#prod_trf" ).autocomplete({
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
            },
            close: function(e, u) {
                $('#waiting_modal').show();
                $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                    product_exist=d;
                    if(d=="true") {
                        setTimeout(function() { 
                            $('#waiting_modal').hide("slow");
                            $('#prod_trf_qty').focus();
                        }, 1000);
                    }
                })
            },
            select: function(e, u) {
                $.post('<?php echo base_url().$type."/Products/product_exist/"; ?>', { 'p' : $(this).val() }, function(d,s,x) {
                    product_exist=d;
                    if(d=="true") {
                        $('#prod_trf_qty').focus()
                    }
                })
            }
        }); 
    	
        $('#prod_trf').change(function(e) {
            e.preventDefault();
            if(product_exist=="false") {
                reset_order_fields_trf();
            }
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
        
        // EVENTS ACTIONS
        
        $('#add_inventory').click(function(e) {
            e.preventDefault();
            $('#add_inventory_modal').modal('toggle');
            reset_order_fields();
            setTimeout(function() {
                $('#from_inv_account').focus();  
            }, 500);
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
        
        // SAVE & UPDATE ACCOUNTS
        
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
                $('#add_account').focus();
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
		
        // NAVIGATE & SEARCH PRODUCTS
		
		
        $('.back').click(function(e) {
            e.preventDefault();
            load_products($(this).attr('category'));
        })
        
        $('.home').click(function(e) {
            e.preventDefault();
            load_products($(this).attr('category'));
        })
        
        // ADD ITEM TO INWARDS LIST
        
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
            if(product_exist=='true') {
                if(e.keyCode == 13) {
                    add_to_order_list($('#prod').val(), $(this).val(), $('#prof_ref').val());
                }
            } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
        })
        
        $('#order_table').on('click','.edit_order_list', function(e) {
            e.preventDefault();
            inv_sel_rec=$(this).prop('id');
            inv_sel_flg=true
            $('#prod').val(inv_new_list[inv_sel_rec].p);
            $('#prod_qty').val(inv_new_list[inv_sel_rec].q);
            $('#prod_qty').val(inv_new_list[inv_sel_rec].r);
            product_exist = 'true';
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
                $('#order_table > tbody').empty();
                window.location.reload();
                
            })
        })
        
        // ADD ITEMS TO TRANSFER LIST
        
        $('#add_item_trf').click(function(e) {
            e.preventDefault();
            if(product_exist=='true') {
                add_to_order_list_trf($('#prod_trf').val(), $('#prod_qty_trf').val());
            } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
        })
        
        $('#prod_qty_trf').keydown(function(e) {
            if(product_exist=='true') {
                if(e.keyCode == 13) {
                    add_to_order_list_trf($('#prod_trf').val(), $(this).val());
                }
            } else {
			    var ert = {message: 'Product does not Exist.',timeout: 2000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
			}
        })
        
        $('#order_table_trf').on('click','.edit_order_list', function(e) {
            e.preventDefault();
            inv_sel_rec_trf=$(this).prop('id');
            inv_sel_flg_trf=true
            $('#prod_trf').val(inv_new_list_trf[inv_sel_rec].p);
            $('#prod_qty_trf').val(inv_new_list_trf[inv_sel_rec].q);
            
            $('#prod_trf').focus();
        })
        
        $('#order_table_trf').on('click','.delete_order_list', function(e) {
            e.preventDefault();
            var x=$(this).prop('id');
            inv_new_list_trf.splice(x,1);
            load_order_list_trf(inv_new_list_trf);
            reset_order_fields_trf()
        })
        
        $('#save_order_list_trf').click(function(e) {
            e.preventDefault();
            $.post('<?php echo base_url().$type."/Transactions/save_inventory_new_records"; ?>', {
                'f' : $('#from_inv_account_sec_trf').val(),
                'f_t' : $('#from_switch_trf')[0].checked,
                't' : $('#to_inv_account_sec_trf').val(),
                't_t' : $('#to_switch_trf')[0].checked,
                'd' : $('#i_txn_date_trf').val(),
                'l' : inv_new_list_trf
            }, function(d,s,x) {
                if(d=="true") {
                    $('#order_table_trf > tbody').empty();
                    window.location.reload();
                }
            })
        })
        
        // PURCHASE ORDER MANAGE
        
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
                    b+='<tr><td>' + a[i].ip_name + '</td><td>' + a[i].iino_qty + '</td><td class="ignore">' + a[i].iino_date + '</td><td class="ignore"><button class="mdl-button mdl-js--button mdl-button--colored order_item_table_delete" id="' + a[i].iino_id + '"><i class="material-icons">delete</i></button></td></tr>';
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
                window.location.reload();
            })
        })
        
        
        function add_to_order_list(p, q) {
            if(inv_sel_flg==false) {
                inv_new_list.push({'p' : p, 'q' : q});    
            } else {
                inv_new_list[inv_sel_rec].p = p;
                inv_new_list[inv_sel_rec].q = q;
                inv_sel_rec=0;
                inv_sel_flg=false;
            }
            load_order_list(inv_new_list);
            reset_order_fields()
        }
        
        function add_to_order_list_trf(p, q) {
            if(inv_sel_flg_trf==false) {
                inv_new_list_trf.push({'p' : p, 'q' : q});    
            } else {
                inv_new_list_trf[inv_sel_rec_trf].p = p;
                inv_new_list_trf[inv_sel_rec_trf].q = q;
                inv_sel_rec_trf=0;
                inv_sel_flg_trf=false;
            }
            load_order_list_trf(inv_new_list_trf);
            reset_order_fields_trf()
        }
        
        function load_order_list(a) {
            $('#order_table > tbody').empty();
            var b="";
            for(var i=0;i<a.length;i++) {
                b+='<tr><td>' + a[i].p + '</td><td>' + a[i].q + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored edit_order_list" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js--button mdl-button--colored delete_order_list" id="' + i + '"><i class="material-icons">delete</i></button></td></tr>';
            }
            $('#order_table > tbody').append(b);
        }
        
        function load_order_list_trf(a) {
            $('#order_table_trf > tbody').empty();
            var b="";
            for(var i=0;i<a.length;i++) {
                b+='<tr><td>' + a[i].p + '</td><td>' + a[i].q + '</td><td><button class="mdl-button mdl-js--button mdl-button--colored edit_order_list" id="' + i + '"><i class="material-icons">create</i></button><button class="mdl-button mdl-js--button mdl-button--colored delete_order_list" id="' + i + '"><i class="material-icons">delete</i></button></td></tr>';
            }
            $('#order_table_trf > tbody').append(b);
        }
        
        
        function reset_order_fields() {
            $('#prod').val('');
            $('#prod_qty').val('');
            $('#prod').focus();
            product_exist = 'false';
        }
        
        function reset_order_fields_trf() {
            $('#prod_trf').val(null);
            $('#prod_qty_trf').val(null);
            $('#prod_trf').focus();

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
                b+='<tr id="' + a.product[i].id + '" type="product"><td>' + a.product[i].name + '</td><td>Limit: ' + a.product[i].ip_lower_limit + '</td>';
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
		    
		  //  $('#order_item_table').tableExport({
    //             // Displays table headings (th or td elements) in the <thead>
    //             headings: true,                    
    //             // Displays table footers (th or td elements) in the <tfoot>    
    //             footers: true, 
    //             // Filetype(s) for the export
    //             formats: ["xls"],           
    //             // Filename for the downloaded file
    //             filename: 'Order List as on ' + dt.getDate() + '-' + (dt.getMonth() + 1) + '-' + dt.getFullYear(), 
    //             // Style buttons using bootstrap framework  
    //             bootstrap: true,                     
    //             // Position of the caption element relative to table
    //             position: "top",                   
    //             // (Number, Number[]), Row indices to exclude from the exported file(s)
    //             ignoreRows: null,       
    //             // (Number, Number[]), column indices to exclude from the exported file(s)              
    //             ignoreCols: null,                
    //             // Selector(s) to exclude cells from the exported file(s)       
    //             ignoreCSS: ".tableexport-ignore",  
    //             // Selector(s) to replace cells with an empty string in the exported file(s)       
    //             emptyCSS: ".tableexport-empty",   
    //             // Removes all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)     
    //             trimWhitespace: false         

    //         });
            $("#order_item_table").tableHTMLExport({
                // csv, txt, json, pdf
                type:'pdf',
                // file name
                filename:'Order List ' + dt1,
                orientation : 'p',
                ignoreColumns: '.ignore',
                ignoreRows: '.ignore'
            
            });
            
		})
        
	});
	
	
	
</script>

<script>
    $(document).ready(function() {
        var sel_location_mod =0;
        load_products(0);
        
        $('#main_category').on('click','.table_data', function(e) {
            e.preventDefault();
            $('.back').attr('category',$(this).attr('category'));
            if($(this).attr('state') == "category") {
                load_products($(this).prop('id'));
            } else if($(this).attr('state') == "product") {
                load_product_stock($(this).prop('id'), 'p',null);
            } else if($(this).attr('state') == "location") {
                sel_location_mod=$(this).prop('id');
                load_product_stock($(this).prop('id'), 'l', null);
            }
        }).on('click','.order_add', function(e) {
            e.preventDefault();
            var pid = $(this).prop('id');
            var qty = '[pid="' + pid + '"]';
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
        }).on('click','.reset_stock', function(e) {
            e.preventDefault();
            var xc = confirm("Are you sure you want to reset. Action cannot be undone!");
            if(xc==true) {
                var pid = $(this).prop('id');
                var cid = $(this).attr('category');
                $.post('<?php echo base_url().$type."/Transactions/inventory_clear_stock/p"; ?>', {
                    'p' : $(this).prop('id')
                }, function(d,s,x) {
                    var ert = {message: "Stock Reset.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    load_products(cid);
                }).fail(function(r) {
                    var ert = {message: "Please try again.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                })
            }
        }).on('click','.reset_category', function(e) {
            e.preventDefault();
            var xc = confirm("Are you sure you want to reset. Action cannot be undone!");
            if(xc==true) {
                var pid = $(this).prop('id');
                var cid = $(this).attr('category');
                $.post('<?php echo base_url().$type."/Transactions/inventory_clear_stock/c"; ?>', {
                    'p' : $(this).prop('id')
                }, function(d,s,x) {
                    if(d=='true') {
                        var ert = {message: "Stock Reset.",timeout: 1000, }; 
                        snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                        load_products(cid);    
                    } else if(d=='false') {
                        var ert = {message: "No Product in Category.",timeout: 1000, }; 
                        snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    }
                    
                }).fail(function(r) {
                    var ert = {message: "Please try again.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                })
            }
        }).on('click','.reset_location', function(e) {
            e.preventDefault();
            var xc = confirm("Are you sure you want to reset. Action cannot be undone!");
            if(xc==true) {
                var pid = $(this).prop('id');
                var cid = $(this).attr('category');
                $.post('<?php echo base_url().$type."/Transactions/inventory_clear_stock/l"; ?>', {
                    'p' : $(this).prop('id')
                }, function(d,s,x) {
                    var ert = {message: "Stock Reset.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                    load_products(cid);
                }).fail(function(r) {
                    var ert = {message: "Please try again.",timeout: 1000, }; 
                    snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                })
            }
        }).on('click', '.order_add_text', function(e) {
            e.preventDefault();
        }).on('click', '.reset_stock_individual', function(e) {
            e.preventDefault();
            var xc=$(this);
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_clear_single_product"; ?>', {
                'p' : xc.attr('id')
            }, function(d,s,x) {
                var ert = {message: "Single Product Stock Reset.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
                load_products(xc.attr('category'));
            })
            
        });
        
        $('#view_inventory_search_button').click(function(e) {
            e.preventDefault();
            load_product_stock(sel_location_mod, 'l', $('#view_inventory_search_category').val());
        })
        
        $('#fixed-header-drawer-exp').keyup(function(e) {
            if(e.keyCode == 13) {
                $.post('<?php echo base_url().$type."/Transactions/search_inventory_by_name/"; ?>', {
                    'p' : $(this).val()
                }, function(d,s,x) {
                    var a=JSON.parse(d);
                    load_table_data(a);
                });
            }
        });
        
        $('#location_toggle').change(function(e) {
            e.preventDefault();
            if($(this)[0].checked) {
                load_locations(null)
            } else {
                load_products(0);
            }
        })
        
        $('#inventory_details_modal_table').on('click','.order_add', function(e) {
            e.preventDefault();
            
            var pid = $(this).prop('id');
            var qty = '[pid="' + pid + '"]';
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
        }).on('click','.log_button', function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Transactions/get_inventory_log"; ?>', {
                'p' : $(this).attr('pid'),
                'a' : $(this).attr('aid')
            }, function(d,s,x) {
                var a=JSON.parse(d), b="<thead><tr><th>Date</th><th>From</th><th>To</th><th>Qty</th></tr></thead><tbody>";
                for(var i=0;i<a.length;i++) {
                    b+='<tr><td>' + a[i].date + '</td><td>';
                    if(a[i].frm_typ == "account") {
                        b+=a[i].frm_acc;
                    } else {
                        b+=a[i].frm_con;
                    }
                    b+='</td><td>';
                    if(a[i].t_typ == "account") {
                        b+=a[i].t_acc;
                    } else {
                        b+=a[i].t_con;
                    }
                    b+='</td><td>' + a[i].qty + '</td></tr>';
                }
                b+='</tbody>';
                
                $('#single_action').empty();
                $('#inventory_details_modal_table').empty();
                $('#inventory_details_modal_table').append(b);
                $('.progress').css('display','none');
            })
        }).on('click','.transfer_add', function(e) {
            e.preventDefault();
            
            var pid = $(this).prop('id');
            var pname = $(this).attr('product_name');
            var loc = '[lid="' + pid + '"]';
            var qty = '[qid="' + pid + '"]';
            
            inv_new_list.push({'p' : pname , 'q' : $(qty).val() });    
            var dt=new Date();
            
            // alert('Location: ' + $(loc).attr('location_id') + ', Qty: ' + $(qty).val() + ' Product: ' + pname + ' Date: ' +  dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate());
            
            $.post('<?php echo base_url().$type."/Transactions/save_inventory_new_records"; ?>', {
                'f' : sel_location_mod,
                'f_t' : 'account',
                't' : $(loc).attr('location_id'),
                't_t' : 'account',
                'd' : dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate() ,
                'l' : inv_new_list
            }, function(d,s,x) {
                sel_location_mod=0;
                inv_new_list=[];
                // $('#order_table > tbody').empty();
                // window.location.reload();
                $('#view_inventory_details').modal('toggle');
            })
        })
        
        $('#single_action').on('click', '.order_add_single', function(e) {
            e.preventDefault();
            
            $.post('<?php echo base_url().$type."/Transactions/inventory_new_order_list_update"; ?>', {
                'p' : $(this).prop('id'),
                'q' : $('.order_add_single_text').val()
            }, function(d,s,x) {
                var ert = {message: "Qty Added.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert); 
            }).fail(function(r) {
                var ert = {message: "Please try again.",timeout: 1000, }; 
                snackbarContainer.MaterialSnackbar.showSnackbar(ert);
            })
        })
    })
    
    function load_products(c) {
        $('#cat_search_text').css('display','none');
        $.post('<?php echo base_url().$type."/Transactions/search_inventory_by_category/"; ?>' + c, {}, function(d,s,x) {
            var a=JSON.parse(d);
            $('.back').attr('category', a.parent);
            load_table_data(a);
        })
    }
    
    function load_locations(c) {
        $('#cat_search_text').css('display','block');
        $.post('<?php echo base_url().$type."/Transactions/search_inventory_locations/"; ?>', {}, function(d,s,x) {
            var a=JSON.parse(d);
            load_table_data(a);
        })
    }
    
    function load_table_data(a) {
        $('#main_category').empty(); var b="";
        if(a.category) {
            for(var i=0;i<a.category.length;i++) {
                b+='<tr><td class="table_data" id="' + a.category[i].ica_id + '" state="category" category="' + a.category[i].ica_parent_category + '"><i class="material-icons">category</i> ' + a.category[i].ica_category_name + '</td><td></td><td></td><td></td><!-- <td><button class="mdl-button mdl-button--colored reset_category" id="' + a.category[i].ica_id + '" category="' + a.category[i].ica_parent_category +'"><i class="material-icons">clear_all</i></button></td> --></tr>';
            }
        }
        
        if(a.product) {
            for(var i=0;i<a.product.length;i++) {
                b+='<tr><td class="table_data" id="' + a.product[i].ip_id + '" state="product" category="' + a.product[i].ip_category +'">' + a.product[i].ip_name + '</td><td>' + a.product[i].ip_description + '</td><td>Limit: ' + a.product[i].ip_lower_limit +'</td><td>Total Available: ' + a.product[i].bal + '</td><td><input class="order_add_text" style="width: 4.2em;outline: none;padding: 5px;border-radius: 5px;" type="text" pid="' + a.product[i].ip_id + '"></td><td><button class="mdl-button mdl-button--colored mdl-button--icon order_add" id="' + a.product[i].ip_id + '"><i class="material-icons">add_shopping_cart</i></button><button class="mdl-button mdl-button--colored mdl-button--icon reset_stock_individual" id="' + a.product[i].ip_id + '" category="' + a.product[i].ip_category +'"><i class="material-icons">refresh</i></button></td></tr>';
            }
        }
        
        if(a.location) {
            for(var i=0;i<a.location.length;i++) {
                b+='<tr><td class="table_data" id="' + a.location[i].iia_id + '" state="location"><i class="material-icons">room</i> ' + a.location[i].iia_name + '</td><td></td><td></td><!-- <td><button class="mdl-button mdl-button--colored reset_location" id="' + a.location[i].iia_id + '" location="' + a.location[i].iia_id +'"><i class="material-icons">clear_all</i></button></td> --></tr>';
            }
        }
        
        $('#main_category').append(b);
    }
    
    function load_product_stock(p, type, cat) {
        $('#inventory_details_modal_table').empty();
        $('.progress').css('display','block');
        if(cat == null) {
            $('#view_inventory_details').modal('toggle');
        }
        
        $.post('<?php echo base_url().$type."/Transactions/search_inventory_details"; ?>', {
            'p' : p,
            't' : type,
            'c' : cat
        }, function(d,s,x) {
            var a = JSON.parse(d), b="", c="", tot=0;;
            
            if(type == "p" ) {
                b+='<thead><tr><th>Location</th><th>Available Qty</th><th>Log</th></tr></thead><tbody>';
                for(var i=0;i<a.stock.length;i++) {
                    tot+=parseInt(a.stock[i].bal);
                    if(a.stock[i].bal != 0) {
                        b+='<tr><td>' + a.stock[i].account + '</td><td>' + a.stock[i].bal + '</td><td><button class="mdl-button mdl-button--colored log_button" type="account" pid="' + a.id + '" aid="' + a.stock[i].id + '"><i class="material-icons">history</i></button></td></tr>';
                    }
                }
                b+='</tbody>';
                
                c+='<h5>Lower Limit: ' + a.low + '</h5><h4>Total Available: ' + tot + '</h4><input style="width: 4.2em;outline: none;padding: 5px;border-radius: 5px;" class="order_add_single_text" type="text" id="' + a.id + '" placeholder="Order Qty"><button class="mdl-button mdl-button--colored order_add_single" id="' + a.id + '"><i class="material-icons">add_shopping_cart</i></button>';
            } else if(type=="l") {
                b+='<thead><tr><th>Product</th><th>Code</th><th>Lower Limit</th><th>Available Qty</th><th>Order Qty</th><th>Transfer</th><th>Log</th></tr></thead><tbody>';
                for(var i=0;i<a.stock.length;i++) {
                
                    b+='<tr><td>' + a.stock[i].product + '</td><td>' + a.stock[i].code + '</td><td>' + a.stock[i].low + '</td><td>' + a.stock[i].bal + '</td><td><input class="order_add_text" style="width: 4.2em;outline: none;padding: 5px;border-radius: 5px;" type="text" pid="' + a.stock[i].id + '"><button class="mdl-button mdl-button--colored order_add" id="' + a.stock[i].id + '"><i class="material-icons">add_shopping_cart</i></button></td><td><input class="transfer_text" style="width: 4.2em;outline: none;padding: 5px;border-radius: 5px;" placeholder="Location" type="text" lid="' + a.stock[i].id + '"><input class="transfer_qty" style="width: 4.2em;outline: none;padding: 5px;border-radius: 5px;" placeholder="Qty" type="text" qid="' + a.stock[i].id + '"><button class="mdl-button mdl-button--colored transfer_add" product_name="' + a.stock[i].product + '" id="' + a.stock[i].id + '"><i class="material-icons">send</i></button></td><td><button class="mdl-button mdl-button--colored log_button" type="product" pid="' + a.stock[i].id + '"><i class="material-icons">history</i></button></td></tr>';
                }
                b+='</tbody>';
            }
            
            $('#single_action').empty();
            $('#single_action').append(c);
            $('#inventory_details_modal_table').append(b);
            $('.progress').css('display','none');
            $(".transfer_text").autocomplete({
                    source: function(request, response) {
                        $.post('<?php echo base_url().$type."/Transactions/search_inventory_accounts"; ?>', {
                            'keywords' : request.term
                        }, function(d,s,x) {
                            var a=JSON.parse(d);
                            response( $.map(a, function( item ) {
                                return{
                                    label: item.iia_name,
                                    value: item.iia_name,
                                    loc_id: item.iia_id
                                }
                            }));
                        })
                    }, select: function(u,i) {
                        $(this).attr('location_id',i.item.loc_id);
                    }
                });
            
        })
    }
    
    function load_product_log(p) {
        
    }
    
</script>
</html>