<style>
    .reset_notes {
        text-align: left;
        border: 1px solid #ccc;
        box-shadow: 0px 5px 10px #ccc inset;
        padding: 1em;
        border-radius: 10px;
    }
    
    .reset_notes p {
        margin: 0px;
        border-bottom: 1px solid #ccc;
        color: #3f51b5;
        font-weight: bold;
    }
    
    .reset_notes ol {
        padding-left: 1em;
        margin-bottom: auto;
    }
    
    .reset_notes ol li {
        padding-left: 1em;
        padding-bottom: 0.8em;
    }
</style>
<main class="mdl-layout__content">
	<div class="mdl-grid">
		<div class="mdl-cell mdl-cell--4-col">
			<div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Initialize Transactions</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_orders">
                                <input type="checkbox" id="del_orders" class="mdl-switch__input">
                                <span class="mdl-switch__label">Orders</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_delivery">
                                <input type="checkbox" id="del_delivery" class="mdl-switch__input">
                                <span class="mdl-switch__label">Delivery</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_purchase">
                                <input type="checkbox" id="del_purchase" class="mdl-switch__input">
                                <span class="mdl-switch__label">Purchase</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_invoice">
                                <input type="checkbox" id="del_invoice" class="mdl-switch__input">
                                <span class="mdl-switch__label">Invoices</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_credit_delivery">
                                <input type="checkbox" id="del_credit_delivery" class="mdl-switch__input">
                                <span class="mdl-switch__label">Credit Note (Delivery)</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_credit">
                                <input type="checkbox" id="del_credit" class="mdl-switch__input">
                                <span class="mdl-switch__label">Credit Note</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_debit_delivery">
                                <input type="checkbox" id="del_debit_delivery" class="mdl-switch__input">
                                <span class="mdl-switch__label">Debit Note (Delivery)</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_debit">
                                <input type="checkbox" id="del_debit" class="mdl-switch__input">
                                <span class="mdl-switch__label">Debit Note</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_inventory">
                                <input type="checkbox" id="del_inventory" class="mdl-switch__input">
                                <span class="mdl-switch__label">Inventory</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_godown">
                                <input type="checkbox" id="del_godown" class="mdl-switch__input">
                                <span class="mdl-switch__label">Inventory Accounts (Deletes all records)</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_expenses">
                                <input type="checkbox" id="del_expenses" class="mdl-switch__input">
                                <span class="mdl-switch__label">Expenses</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_accounting">
                                <input type="checkbox" id="del_accounting" class="mdl-switch__input">
                                <span class="mdl-switch__label">Accounting</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col" style="text-align:left;">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="del_payments">
                                <input type="checkbox" id="del_payments" class="mdl-switch__input">
                                <span class="mdl-switch__label">Payments</span>
                            </label>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="text" data-type="date" id="del_from" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="del_from">Select From Date</label>
        					</div>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="text" data-type="date" id="del_to" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="del_to">Select To Date</label>
        					</div>
                        </div>
                        <button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--primary" style="width: 100%;" id="update">Proceed</button>
                        <div id="pass-disp">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        						<input type="password" id="switch_pass" name="switch_pass" class="mdl-textfield__input">
        						<label class="mdl-textfield__label" for="switch_pass">Enter your Password</label>
        					</div>
        					<button class="mdl-button mdl-js-button mdl-button--primary" id="switch-pass-btn">Update Settings</button>
    					</div>
                    </div>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Password Reset</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_o_pass" name="u_o_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_o_pass">Old Password</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_pass" name="u_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_pass">Password</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="password" id="u_confirm_pass" name="u_confirm_pass" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="u_confirm_pass">Confirm Password</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--colored" style="width: 100%;" id="update_password">Update</button>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Financial Year</h2>
                </div>
                <div class="mdl-card__supporting-text">
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect" style="" id="manage_financial">Manage <i class="material-icons">arrow_right</i></button>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Reset Order & Challan Number</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="reset_notes">
                        <p>Note:</p>
                        <ol>
                            <li>If you want number to start from 1, enter 0.</li>
                            <li>If left blank, the number wont reset.</li>
                        </ol>    
                    </div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_challan" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_challan">Order Start Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_delivery" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_delivery">Delivery Start Number</label>
					</div>
					
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" style="" id="reset_challan_button"><i class="material-icons">info</i> Reset Order & Delivery</button>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Reset Invoice Number</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="reset_notes">
                        <p>Note:</p>
                        <ol>
                            <li>If you want number to start from 1, enter 0.</li>
                            <li>If left blank, the number wont reset.</li>
                        </ol>    
                    </div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_invoice" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_invoice">Invoice Start Number</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" style="" id="reset_invoice_button"><i class="material-icons">info</i> Reset Invoice</button>
                </div>
                
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" style="">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Reset Credit & Debit Note</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="reset_notes">
                        <p>Note:</p>
                        <ol>
                            <li>If you want number to start from 1, enter 0.</li>
                            <li>If left blank, the number wont reset.</li>
                        </ol>    
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_credit_note_delivery" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_credit_note_delivery">Credit Note Delivery Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_credit_note_invoice" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_credit_note_invoice">Credit Note Invoice Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_debit_note_delivery" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_debit_note_delivery">Debit Note Delivery Number</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="reset_debit_note_invoice" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="reset_debit_note_invoice">Debit Note Delivery Number</label>
					</div>
					
					<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" style="" id="reset_credit_debit_button"><i class="material-icons">info</i> Reset Credit & Debit Note</button>
                </div>
            </div>
		</div>
		<div class="mdl-cell mdl-cell--4-col" id="user-create-div" style="display:none;">
		    <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <h2 class="mdl-card__title-text">Create Users</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="cu_name" name="cu_name" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="cu_name">Name</label>
					</div>
					<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input type="text" id="cu_email" name="cu_email" class="mdl-textfield__input">
						<label class="mdl-textfield__label" for="cu_email">Email</label>
					</div>
					<button class="mdl-button mdl-js-button mdl-button-done mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" id="cu_submit">Add User</button>
					<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width:100%;" id="cu_table">
                        <thead>
                            <tr>
                                <th class="mdl-data-table__cell--non-numeric">Name</th>
                                <th class="mdl-data-table__cell--non-numeric">Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i=0;$i<count($usrs); $i++) {
                               echo '<tr><td class="mdl-data-table__cell--non-numeric">'.$usrs[$i]->idu_name.'</td><td class="mdl-data-table__cell--non-numeric">'.$usrs[$i]->idu_username.'</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="'.$usrs[$i]->idu_id.'"><i class="material-icons">delete</i></button></td></tr>'; 
                            }?>
                        </tbody>
                    </table>
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
</body>
<script>
	var fromdy, frommn, fromyr, tody, tomn, toyr;
	$(document).ready(function() {
	    $('#del_from').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    $('#del_to').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	    $('#yr_from_dy').bootstrapMaterialDatePicker({ weekStart : 0, time: false }).on('change', function(e, date) {
	    	var rt = new Date(date._d);
	    	fromdy = rt.getDate();
	    	frommn = rt.getMonth();
	    	fromyr = rt.getFullYear();
	    });
	    $('#yr_to_dy').bootstrapMaterialDatePicker({ weekStart : 0, time: false }).on('change', function(e, date) {
	    	var rt = new Date(date._d);
	    	tody = rt.getDate();
	    	tomn = rt.getMonth();
	    	toyr = rt.getFullYear();
	    });
	    
	    <?php 
    		if(!isset($txn)) {
    			echo "var dt = new Date();";
    			echo "var s_dt = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();";
    			
    			echo "$('#del_from').val(s_dt);";
    			echo "$('#del_to').val(s_dt);";
    		}
    	?>
	    
	    <?php 
	        $sess_data = $this->session->userdata();
	        if(isset($sess_data['sub'])) {
	            echo '$("#user-create-div").css("display","none");';
	        }
	    ?>
	    
	    $('#pass-disp').css('display','none');
	    
	    $('#update').click(function(e) {
	        e.preventDefault();
	        $('#pass-disp').css('display','block');
	    });
	    
	    $('#switch-pass-btn').click(function(e) {
	        e.preventDefault();
	        
	        var ps = $('#switch_pass').val();
	        $.post('<?php echo base_url().$type."/Account/delete_txns"; ?>', { 
	            'ps' : ps,
	            'or' : $('#del_orders')[0].checked,
	            'de' : $('#del_delivery')[0].checked,
	            'pu' : $('#del_purchase')[0].checked,
	            'in' : $('#del_invoice')[0].checked,
	            'crd' : $('#del_credit_delivery')[0].checked,
	            'cr' : $('#del_credit')[0].checked,
	            'drd' : $('#del_debit_delivery')[0].checked,
	            'dr' : $('#del_debit')[0].checked,
	            'i' : $('#del_inventory')[0].checked,
	            'g' : $('#del_godown')[0].checked,
	            'e' : $('#del_expenses')[0].checked,
	            'a' : $('#del_accounting')[0].checked,
	            'py' : $('#del_payments')[0].checked,
	            'from' : $('#del_from').val(), 
	            'to': $('#del_to').val()
	        }, function(d, s, x) { 
	            if(d=="true") {
	                display_message("Done.");
	            } else if(d=="false") { 
	                display_message("Failed."); 
	            } else if(d=="password") { 
	                display_message("Incorrect Password.");
	            } 
	            $('#pass-disp').css('display','none'); 
	            $('#switch_pass').val('');
	        }, 'text');
	    });
	    
	    $('#update_password').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php $sess_data = $this->session->userdata(); if(isset($sess_data['sub'])) { echo base_url().$type."/Account/reset_account_pass/sub"; } else { echo base_url().$type."/Account/reset_account_pass"; } ?>', { 'upass' : $('#u_pass').val(), 'uopass' : $('#u_o_pass').val() }, function(d, s, x) {  if(d=="true") {display_message("Password Updated. Please Login Again"); window.location = "<?php echo base_url().$type.'/Account/logout/1'; ?>" } else if(d=="false") { display_message("Error."); } else if(d=="password") { display_message("Incorrect Password."); } }, 'text');
	    });
	    
	    $('#cu_submit').click(function(e) {
	        e.preventDefault();
	        
	        $.post('<?php echo base_url().$type."/Account/save_user"; ?>', { 'name' : $('#cu_name').val(), 'email' : $('#cu_email').val() }, function(d, s, x) { $('#cu_table > tbody').empty(); var a=JSON.parse(d), o=""; for(var i=0;i<a.length; i++) { o+='<tr><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_name + '</td><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_username + '</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + a[i].idu_id + '"><i class="material-icons">delete</i></button></td></tr>';  } $('#cu_table > tbody').append(o); reset_users()  }, 'text');
	    });
	    
	    $('#cu_table').on('click','.delete', function(e) {
	        e.preventDefault();
	        
	        $.post('<?php echo base_url().$type."/Account/delete_user"; ?>', { 'did' : $(this).prop('id') }, function(d, s, x) { $('#cu_table > tbody').empty(); var a=JSON.parse(d), o=""; for(var i=0;i<a.length; i++) { o+='<tr><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_name + '</td><td class="mdl-data-table__cell--non-numeric">' + a[i].idu_username + '</td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored delete" id="' + a[i].idu_id + '"><i class="material-icons">delete</i></button></td></tr>';  } $('#cu_table > tbody').append(o); reset_users(); }, 'text');
	    });
	    
	    $('#manage_financial').click(function(e) {
	    	e.preventDefault();
	    	window.location = "<?php echo base_url().$type.'/Account/manage_financial'; ?>";
	    })
	    
	    
	    $('#reset_challan_button').click(function(e) {
	        e.preventDefault();
	        $.post('<?php echo base_url().$type."/Account/reset_challan_numbers/"; ?>', {
	            'order' : $('#reset_challan').val(),
	            'delivery' : $('#reset_delivery').val(),
            }, function(d, s, x) {  
                if(d=="true") {
                    display_message("Order & Challan Number Reset");
                }
            }, 'text');
	    })
	    
	    $('#reset_invoice_button').click(function(e) {
	        e.preventDefault();
	        $.post('<?php echo base_url().$type."/Account/reset_invoice_numbers/"; ?>', {
	            'invoice' : $('#reset_invoice').val()
            }, function(d, s, x) {  
                if(d=="true") {
                    display_message("Invoice Number Reset");
                }
            }, 'text');
	    })
	    
	    $('#reset_credit_debit_button').click(function(e) {
	        e.preventDefault();
	        $.post('<?php echo base_url().$type."/Account/reset_credit_debit_numbers/"; ?>', {
	            'cn_d' : $('#reset_credit_note_delivery').val(),
	            'cn_i' : $('#reset_credit_note_invoice').val(),
	            'dn_d' : $('#reset_debit_note_delivery').val(),
	            'dn_i' : $('#reset_debit_note_invoice').val(),
            }, function(d, s, x) {  
                if(d=="true") {
                    display_message("Credit & Debit Number Reset");
                }
            }, 'text');
	    })
	});
	
	function reset_users() {
	
	    $('#cu_name').val('');
	    $('#cu_email').val('');
	}
	function switchon(){
	    document.getElementById('switch-1').checked = true;
	   // $("#switch-1").prop( "checked", true );
	    console.log("Ahoy");
	}
	
	function switchoff(){
	    $("#switch-1").prop( "checked", false );
	}
	
	
	function display_message(message) {
	    var snackbarContainer = document.querySelector('#demo-snackbar-example');
        var ert = {message: message,timeout: 2000, }; 
        
        snackbarContainer.MaterialSnackbar.showSnackbar(ert);

	}
	function redirect() {
		window.location = '<?php echo base_url().$this->config->item('dealer_login_red'); ?>';
	}
</script>
</html>